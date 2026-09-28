<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Transaction;
use App\Models\User;
use App\Support\ReceiptBuilder;
use Carbon\Carbon;
use Dompdf\Dompdf;
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
        $transactions = $this->transactionsQuery($request)->paginate(10)->withQueryString()->withPath(route('owner.transactions.index'));

        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::where('status', 'completed')->sum('total');
        $completedTransactions = Transaction::where('status', 'completed')->count();
        $cancelledTransactions = Transaction::where('status', 'cancelled')->count();

        [$chartLabels, $chartValues] = $this->revenueChartData('week');

        $oldest = Transaction::min('created_at');
        $minYear = $oldest ? Carbon::parse($oldest)->year : now()->year;

        $cashiers = User::role(['kasir', 'owner'])->orderBy('first_name')->get();

        return view('owner.transactions.index', compact(
            'transactions',
            'totalTransactions',
            'totalRevenue',
            'completedTransactions',
            'cancelledTransactions',
            'chartLabels',
            'chartValues',
            'minYear',
            'cashiers'
        ));
    }

    public function indexFilter(Request $request)
    {
        $transactions = $this->transactionsQuery($request)->paginate(10)->withQueryString()->withPath(route('owner.transactions.index'));

        return response()->json([
            'html' => view('owner.transactions._data_table', ['transactions' => $transactions])->render(),
            'stats' => $this->transactionsStats($request),
        ]);
    }

    public function printIndex(Request $request)
    {
        $transactions = $this->transactionsQuery($request)->get();

        $totalRevenue = (int) $transactions->where('status', 'completed')->sum('total');
        $completedTransactions = $transactions->where('status', 'completed')->count();
        $cancelledTransactions = $transactions->where('status', 'cancelled')->count();

        $filterSummary = $this->transactionFilterSummary($request);

        $html = view('owner.transactions.print', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'completedTransactions' => $completedTransactions,
            'cancelledTransactions' => $cancelledTransactions,
            'filterSummary' => $filterSummary,
            'printedBy' => Auth::user(),
            'printedAt' => now()->translatedFormat('d F Y H:i'),
        ])->render();

        $dompdf = new Dompdf;
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();

        $filename = 'laporan-transaksi-'.now()->format('Y-m-d-His').'.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
        ]);
    }

    private function transactionFilterSummary(Request $request): array
    {
        $filterSummary = [];
        if ($request->filled('search')) {
            $filterSummary[] = 'Cari: '.$request->search;
        }
        if ($request->filled('cashier_id')) {
            $user = User::find($request->cashier_id);
            if ($user) {
                $filterSummary[] = 'Kasir: '.(trim($user->first_name.' '.$user->last_name) ?: $user->username);
            }
        }
        if ($request->filled('date_from')) {
            $filterSummary[] = 'Dari: '.Carbon::parse($request->date_from)->translatedFormat('d M Y');
        }
        if ($request->filled('date_to')) {
            $filterSummary[] = 'Sampai: '.Carbon::parse($request->date_to)->translatedFormat('d M Y');
        }
        if ($request->filled('status')) {
            $filterSummary[] = 'Status: '.($request->status === 'completed' ? 'Selesai' : 'Dibatalkan');
        }
        if ($request->filled('payment_method')) {
            $paymentLabels = ['cash' => 'Cash', 'debit' => 'Debit', 'kredit' => 'Kredit', 'qris' => 'QRIS'];
            $filterSummary[] = 'Metode: '.($paymentLabels[$request->payment_method] ?? $request->payment_method);
        }

        return $filterSummary;
    }

    private function transactionsStats(Request $request)
    {
        $query = $this->transactionsQuery($request, false);

        $revenueStatus = $request->filled('status') ? $request->status : 'completed';

        return [
            'totalRevenue' => (int) (clone $query)->where('status', $revenueStatus)->sum('total'),
            'totalTransactions' => (clone $query)->count(),
            'completedTransactions' => (clone $query)->where('status', 'completed')->count(),
            'cancelledTransactions' => (clone $query)->where('status', 'cancelled')->count(),
        ];
    }

    public function chartFilter(Request $request)
    {
        [$labels, $values] = $this->revenueChartData($request->input('period', 'week'), $request);

        return response()->json(compact('labels', 'values'));
    }

    private function revenueChartData(string $period, ?Request $request = null): array
    {
        $labels = [];
        $values = [];

        $query = Transaction::where('status', $request?->filled('status') ? $request->status : 'completed');

        if ($request) {
            if ($request->filled('cashier_id')) {
                $query->where('user_id', $request->cashier_id);
            }
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }
        }

        $dateFrom = $request?->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
        $dateTo = $request?->filled('date_to') ? Carbon::parse($request->date_to)->startOfDay() : null;

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom->toDateString());
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo->toDateString());
        }

        // Jika filter tanggal dipakai, sumbu grafik mengikuti rentang tanggal tersebut;
        // selain itu mengikuti tombol periode (Minggu / Bulan / Tahun).
        if ($dateFrom || $dateTo) {
            $start = $dateFrom ?? match ($period) {
                'month' => today()->subDays(29)->startOfDay(),
                'year' => today()->startOfMonth()->subMonths(11)->startOfDay(),
                default => today()->subDays(6)->startOfDay(),
            };
            $end = $dateTo ?? today()->startOfDay();

            if ($end->lt($start)) {
                [$start, $end] = [$end, $start];
            }

            $days = abs($end->diffInDays($start)) + 1;

            if ($days <= 31) {
                for ($i = 0; $i < $days; $i++) {
                    $day = $start->copy()->addDays($i);
                    $labels[] = $day->translatedFormat('j M');
                    $values[] = (int) (clone $query)->whereDate('created_at', $day->toDateString())->sum('total');
                }
            } else {
                $cursor = $start->copy()->startOfMonth();
                $limit = $end->copy()->startOfMonth();

                do {
                    $labels[] = $cursor->translatedFormat('M Y');
                    $values[] = (int) (clone $query)
                        ->whereDate('created_at', '>=', $cursor->copy()->startOfMonth()->toDateString())
                        ->whereDate('created_at', '<=', $cursor->copy()->endOfMonth()->toDateString())
                        ->sum('total');
                    $cursor->addMonth();
                } while ($cursor->lte($limit));
            }

            return [$labels, $values];
        }

        $year = $request?->filled('year') ? (int) $request->year : now()->year;
        $month = $request?->filled('month') ? (int) $request->month : now()->month;

        if ($period === 'today') {
            for ($i = 6; $i >= 0; $i--) {
                $day = now()->subDays($i)->startOfDay();
                $labels[] = $day->translatedFormat('j M');
                $values[] = (int) (clone $query)->whereDate('created_at', $day->toDateString())->sum('total');
            }
        } elseif ($period === 'week') {
            $monthStart = now()->setDate($year, $month, 1)->startOfDay();

            if ($request?->filled('week')) {
                $week = (int) $request->week;
            } else {
                $nowWeekMonday = now()->copy()->startOfWeek(Carbon::MONDAY);
                $week = (int) floor(abs($monthStart->diffInDays($nowWeekMonday)) / 7) + 1;
            }

            $start = $monthStart->copy()->startOfWeek(Carbon::MONDAY)->addWeeks($week - 1);

            for ($i = 0; $i < 7; $i++) {
                $day = $start->copy()->addDays($i);
                $labels[] = $day->translatedFormat('j M');
                $values[] = (int) (clone $query)->whereDate('created_at', $day->toDateString())->sum('total');
            }
        } elseif ($period === 'month') {
            $start = now()->setDate($year, $month, 1)->startOfDay();

            for ($i = 0; $i < $start->daysInMonth; $i++) {
                $day = $start->copy()->addDays($i);
                $labels[] = $day->translatedFormat('j M');
                $values[] = (int) (clone $query)->whereDate('created_at', $day->toDateString())->sum('total');
            }
        } elseif ($period === 'custom') {
            $start = today()->subDays(6)->startOfDay();

            for ($i = 0; $i < 7; $i++) {
                $day = $start->copy()->addDays($i);
                $labels[] = $day->translatedFormat('j M');
                $values[] = (int) (clone $query)->whereDate('created_at', $day->toDateString())->sum('total');
            }
        } else {
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = now()->month($m)->translatedFormat('M');
                $values[] = (int) (clone $query)
                    ->whereMonth('created_at', $m)
                    ->whereYear('created_at', $year)
                    ->sum('total');
            }
        }

        return [$labels, $values];
    }

    private function transactionsQuery(Request $request, bool $withSearch = true)
    {
        $query = Transaction::with(['user', 'items.menu.category']);

        if ($withSearch && $request->filled('search')) {
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

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->cashier_id);
        }

        return $query->latest();
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
            'payment_method' => 'required|in:cash,debit,kredit,qris',
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

            if ($amountPaid < $total) {
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

            $transaction->refresh()->load('items.menu', 'user');

            if ($request->boolean('print_receipt')) {
                return ReceiptBuilder::toResponse($transaction);
            }

            return redirect()->route('owner.transactions.create')
                ->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Terjadi kesalahan: '.$e->getMessage()])->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.menu.category', 'promos']);

        $itemDiscountsTotal = $transaction->items->sum(fn ($item) => $item->discount);

        return view('owner.transactions.show', compact('transaction', 'itemDiscountsTotal'));
    }

    public function kasirShow(Transaction $transaction)
    {
        $transaction->load(['user', 'items.menu.category', 'promos']);

        $itemDiscountsTotal = $transaction->items->sum(fn ($item) => $item->discount);

        return view('cashier.transactions.show', compact('transaction', 'itemDiscountsTotal'));
    }

    public function receiptPdf(Transaction $transaction)
    {
        return ReceiptBuilder::toResponse($transaction);
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->status === 'completed') {
            $transaction->update(['status' => 'cancelled']);

            return back()->with('success', 'Transaksi berhasil dibatalkan.');
        }

        return back()->withErrors(['error' => 'Transaksi tidak dapat dibatalkan.']);
    }

    public function history(Request $request)
    {
        $transactions = $this->historyQuery($request)->paginate(10)->withQueryString()->withPath(route('owner.transactions.history'));

        $stats = $this->historyStats($request);

        return view('cashier.transactions.history', array_merge(compact('transactions'), $stats));
    }

    public function historyFilter(Request $request)
    {
        $transactions = $this->historyQuery($request)->paginate(10)->withQueryString()->withPath(route('owner.transactions.history'));

        return response()->json([
            'html' => view('cashier.transactions._history_table', ['transactions' => $transactions])->render(),
            'stats' => $this->historyStats($request),
        ]);
    }

    private function historyStats(Request $request)
    {
        $query = $this->historyQuery($request);

        $revenueStatus = $request->filled('status') ? $request->status : 'completed';

        $completedTransactions = (clone $query)->where('status', 'completed')->count();
        $totalRevenue = (int) (clone $query)->where('status', $revenueStatus)->sum('total');
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
            $qualifyingItems = array_filter($itemsData, fn ($item) => $item['menu_id'] === $promo->target_id);
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
