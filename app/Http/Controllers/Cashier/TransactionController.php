<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Transaction;
use App\Support\ReceiptBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function create()
    {
        $menus = Menu::where('status', 'active')
            ->with('category')
            ->orderBy('category_id')
            ->paginate(8);

        $promos = Promo::active()->with('menus')->get();
        $categories = Category::has('menus')->get();

        return view('cashier.transactions.create', compact('menus', 'promos', 'categories'));
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
            'html' => view('cashier.transactions._menu_cards', ['menus' => $menus])->render(),
            'hasMore' => $menus->hasMorePages(),
        ]);
    }

    public function store(Request $request)
    {
        if (is_string($request->items)) {
            $request->merge(['items' => json_decode($request->items, true)]);
        }
        if (is_string($request->applied_promos)) {
            $request->merge(['applied_promos' => json_decode($request->applied_promos, true)]);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,debit,kredit,transfer,qris',
            'applied_promos' => 'nullable|array',
            'applied_promos.*.promo_id' => 'required_with:applied_promos|exists:promos,id',
            'applied_promos.*.discount_amount' => 'required_with:applied_promos|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ], [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $itemDiscountsTotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $lineTotal = $menu->price * $item['quantity'];
                $itemDiscount = min((float) ($item['discount'] ?? 0), $menu->price * $item['quantity']);
                $itemDiscountTotal = $itemDiscount;
                $subtotal += $lineTotal;
                $itemDiscountsTotal += $itemDiscountTotal;

                $itemsData[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'discount' => $itemDiscount,
                    'price' => $menu->price,
                    'total' => $lineTotal - $itemDiscountTotal,
                ];
            }

            $promoDiscount = 0;
            $appliedPromosData = [];

            if ($request->applied_promos) {
                $promoIds = collect($request->applied_promos)->pluck('promo_id');
                $activePromos = Promo::active()->with('menus')->whereIn('id', $promoIds)->get();

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

            if ($amountPaid < $total) {
                DB::rollBack();

                return back()->withErrors(['amount_paid' => 'Jumlah bayar kurang dari total.'])->withInput();
            }

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
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

            $transaction->refresh()->load('items.menu', 'user');

            if ($request->boolean('print_receipt')) {
                return ReceiptBuilder::toResponse($transaction);
            }

            return redirect()->route('kasir.transactions.create')
                ->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Terjadi kesalahan: '.$e->getMessage()])->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load(['items.menu.category', 'promos']);

        $itemDiscountsTotal = $transaction->items->sum(fn ($item) => $item->discount);

        return view('cashier.transactions.show', compact('transaction', 'itemDiscountsTotal'));
    }

    public function receiptPdf(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return ReceiptBuilder::toResponse($transaction);
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->status === 'completed') {
            $transaction->update(['status' => 'cancelled']);

            return back()->with('success', 'Transaksi berhasil dibatalkan.');
        }

        return back()->withErrors(['error' => 'Transaksi tidak dapat dibatalkan.']);
    }

    public function history(Request $request)
    {
        $transactions = $this->historyQuery($request)->paginate(10)->withQueryString()->withPath(route('kasir.transactions.history'));

        $stats = $this->historyStats($request);

        return view('cashier.transactions.history', array_merge(compact('transactions'), $stats));
    }

    public function historyFilter(Request $request)
    {
        $transactions = $this->historyQuery($request)->paginate(10)->withQueryString()->withPath(route('kasir.transactions.history'));

        return response()->json([
            'html' => view('cashier.transactions._history_table', ['transactions' => $transactions])->render(),
            'stats' => $this->historyStats($request),
        ]);
    }

    private function historyStats(Request $request)
    {
        $query = $this->historyQuery($request);

        $completedTransactions = (clone $query)->where('status', 'completed')->count();
        $totalRevenue = (int) (clone $query)->where('status', 'completed')->sum('total');
        $cancelledTransactions = (clone $query)->where('status', 'cancelled')->count();

        return compact('completedTransactions', 'totalRevenue', 'cancelledTransactions');
    }

    private function historyQuery(Request $request)
    {
        $query = Transaction::with(['items.menu.category'])
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

        return $query->latest();
    }

    private function calculatePromoDiscount(Promo $promo, array $itemsData, float $subtotal): float
    {
        if (! $promo->is_active) {
            return 0;
        }

        $qualifyingItems = [];

        if ($promo->scope === 'item') {
            $targetMenuIds = $promo->menus->pluck('id')->all();
            $qualifyingItems = array_filter($itemsData, fn ($item) => in_array((int) $item['menu_id'], $targetMenuIds, true));
        } elseif ($promo->scope === 'category') {
            $menuIds = Menu::where('category_id', $promo->target_id)->pluck('id')->toArray();
            $qualifyingItems = array_filter($itemsData, fn ($item) => in_array($item['menu_id'], $menuIds));
        }

        $itemDiscountsTotal = array_sum(array_map(fn ($item) => $item['discount'], $itemsData));

        if ($promo->scope === 'item' || $promo->scope === 'category') {
            if (empty($qualifyingItems)) {
                return 0;
            }

            $qualifyingQty = array_sum(array_map(fn ($item) => $item['quantity'], $qualifyingItems));
            $qualifyingAmount = array_sum(array_map(fn ($item) => ($item['price'] * $item['quantity']) - $item['discount'], $qualifyingItems));

            if ($promo->min_quantity && $qualifyingQty < $promo->min_quantity) {
                return 0;
            }

            if ($promo->min_amount && $qualifyingAmount < $promo->min_amount) {
                return 0;
            }

            if ($promo->type === 'percentage') {
                return round($qualifyingAmount * ($promo->value / 100));
            }

            return $promo->value * $qualifyingQty;
        }

        if ($promo->scope === 'transaction') {
            $afterItemDiscounts = $subtotal - $itemDiscountsTotal;

            if ($promo->min_amount && $afterItemDiscounts < $promo->min_amount) {
                return 0;
            }

            if ($promo->min_quantity) {
                $totalQty = array_sum(array_map(fn ($item) => $item['quantity'], $itemsData));
                if ($totalQty < $promo->min_quantity) {
                    return 0;
                }
            }

            if ($promo->type === 'percentage') {
                return round($afterItemDiscounts * ($promo->value / 100));
            }

            return $promo->value;
        }

        return 0;
    }
}
