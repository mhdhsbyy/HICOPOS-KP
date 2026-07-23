<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function page(Request $request)
    {
        // Tab: Buat Transaksi
        $menus = Menu::where('status', 'active')
            ->with('category')
            ->orderBy('category_id')
            ->paginate(8);

        $promos = Promo::active()->get();

        // Tab: Riwayat Transaksi
        $query = Transaction::with(['items.menu'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        $totalTransactions = Transaction::where('user_id', Auth::id())->count();
        $totalRevenue = Transaction::where('user_id', Auth::id())->where('status', 'completed')->sum('total');
        $todayTransactions = Transaction::where('user_id', Auth::id())->whereDate('created_at', today())->count();
        $todayRevenue = Transaction::where('user_id', Auth::id())->whereDate('created_at', today())->where('status', 'completed')->sum('total');

        $activeTab = $request->query('tab', 'create');

        return view('owner.transactions.page', compact(
            'menus', 'promos',
            'transactions', 'totalTransactions', 'totalRevenue', 'todayTransactions', 'todayRevenue',
            'activeTab'
        ));
    }

    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'items.menu']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('username', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::where('status', 'completed')->sum('total');
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $todayRevenue = Transaction::whereDate('created_at', today())->where('status', 'completed')->sum('total');

        return view('owner.transactions.index', compact(
            'transactions',
            'totalTransactions',
            'totalRevenue',
            'todayTransactions',
            'todayRevenue'
        ));
    }

    public function create()
    {
        $menus = Menu::where('status', 'active')
            ->with('category')
            ->orderBy('category_id')
            ->paginate(8);

        $promos = Promo::active()->get();
        $categories = Category::has('menus')->get();

        return view('owner.transactions.create', compact('menus', 'promos', 'categories'));
    }

    public function loadMoreMenus(Request $request)
    {
        $page = max(1, (int) $request->input('page', 1));
        $query = Menu::where('status', 'active')
            ->with('category')
            ->orderBy('category_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $menus = $query->paginate(8, ['*'], 'page', $page);

        return response()->json([
            'html' => view('owner.transactions._menu_cards', ['menus' => $menus])->render(),
            'hasMore' => $menus->hasMorePages(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,debit,card,qris',
            'applied_promos' => 'nullable|array',
            'applied_promos.*.promo_id' => 'required_with:applied_promos|exists:promos,id',
            'applied_promos.*.discount_amount' => 'required_with:applied_promos|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $itemDiscountsTotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $lineTotal = $menu->price * $item['quantity'];
                $itemDiscount = min((float) ($item['discount'] ?? 0), $lineTotal);
                $subtotal += $lineTotal;
                $itemDiscountsTotal += $itemDiscount;

                $itemsData[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'discount' => $itemDiscount,
                    'price' => $menu->price,
                    'total' => $lineTotal - $itemDiscount,
                ];
            }

            $promoDiscount = 0;
            $appliedPromosData = [];

            if ($request->applied_promos) {
                $promoIds = collect($request->applied_promos)->pluck('promo_id');
                $activePromos = Promo::active()->whereIn('id', $promoIds)->get();

                foreach ($request->applied_promos as $applied) {
                    $promo = $activePromos->firstWhere('id', $applied['promo_id']);
                    if (! $promo) {
                        continue;
                    }

                    $serverAmount = $this->calculatePromoDiscount($promo, $itemsData, $subtotal);

                    if ($serverAmount > 0) {
                        $promoDiscount += $serverAmount;
                        $appliedPromosData[$promo->id] = $serverAmount;
                    }
                }
            }

            $manualDiscount = (float) ($request->discount ?? 0);
            $total = max(0, $subtotal - $itemDiscountsTotal - $promoDiscount - $manualDiscount);
            $amountPaid = (float) $request->amount_paid;
            $change = $amountPaid - $total;

            if ($change < 0) {
                DB::rollBack();

                return back()->withErrors(['amount_paid' => 'Jumlah bayar kurang dari total.'])->withInput();
            }

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'discount' => $manualDiscount,
                'promo_discount' => $promoDiscount,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'change' => max(0, $change),
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            foreach ($itemsData as $itemData) {
                $transaction->items()->create($itemData);
            }

            foreach ($appliedPromosData as $promoId => $amount) {
                $transaction->promos()->attach($promoId, ['discount_amount' => $amount]);
            }

            DB::commit();

            return redirect()->route('owner.transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Terjadi kesalahan: '.$e->getMessage()])->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.menu.category', 'promos']);

        return view('owner.transactions.show', compact('transaction'));
    }

    public function history(Request $request)
    {
        $query = Transaction::with(['items.menu'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        $totalTransactions = Transaction::where('user_id', Auth::id())->count();
        $totalRevenue = Transaction::where('user_id', Auth::id())->where('status', 'completed')->sum('total');
        $todayTransactions = Transaction::where('user_id', Auth::id())->whereDate('created_at', today())->count();
        $todayRevenue = Transaction::where('user_id', Auth::id())->whereDate('created_at', today())->where('status', 'completed')->sum('total');

        return view('owner.transactions.history', compact(
            'transactions',
            'totalTransactions',
            'totalRevenue',
            'todayTransactions',
            'todayRevenue'
        ));
    }

    private function calculatePromoDiscount(Promo $promo, array $itemsData, float $subtotal): float
    {
        if (! $promo->is_active) {
            return 0;
        }

        $qualifyingItems = [];

        if ($promo->scope === 'item') {
            $qualifyingItems = array_filter($itemsData, fn ($item) => $item['menu_id'] === $promo->target_id);
        } elseif ($promo->scope === 'category') {
            $menuIds = Menu::where('category_id', $promo->target_id)->pluck('id')->toArray();
            $qualifyingItems = array_filter($itemsData, fn ($item) => in_array($item['menu_id'], $menuIds));
        }

        if ($promo->scope === 'item' || $promo->scope === 'category') {
            if (empty($qualifyingItems)) {
                return 0;
            }

            $qualifyingQty = array_sum(array_map(fn ($item) => $item['quantity'], $qualifyingItems));
            $qualifyingAmount = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $qualifyingItems));

            if ($promo->min_quantity && $qualifyingQty < $promo->min_quantity) {
                return 0;
            }

            if ($promo->min_amount && $qualifyingAmount < $promo->min_amount) {
                return 0;
            }

            if ($promo->type === 'percentage') {
                return round($qualifyingAmount * ($promo->value / 100), 2);
            }

            return $promo->value * $qualifyingQty;
        }

        if ($promo->scope === 'transaction') {
            if ($promo->min_amount && $subtotal < $promo->min_amount) {
                return 0;
            }

            if ($promo->min_quantity) {
                $totalQty = array_sum(array_map(fn ($item) => $item['quantity'], $itemsData));
                if ($totalQty < $promo->min_quantity) {
                    return 0;
                }
            }

            if ($promo->type === 'percentage') {
                return round($subtotal * ($promo->value / 100), 2);
            }

            return $promo->value;
        }

        return 0;
    }
}
