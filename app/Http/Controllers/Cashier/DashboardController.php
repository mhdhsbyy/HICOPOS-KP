<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $userId = Auth::id();

        $today = today()->toDateString();

        $todayTransactions = Transaction::where('user_id', $userId)->whereDate('created_at', $today)->count();
        $todayRevenue = (int) Transaction::where('user_id', $userId)->where('status', 'completed')->whereDate('created_at', $today)->sum('total');

        $chartLabels = [];
        $chartValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = today()->subDays($i);
            $chartLabels[] = $day->format('j M y');
            $chartValues[] = (int) Transaction::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereDate('created_at', $day->toDateString())
                ->sum('total');
        }

        $topMenuRows = TransactionItem::query()
            ->selectRaw('menu_id, SUM(quantity) as qty')
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'completed');
            })
            ->groupBy('menu_id')
            ->orderByDesc('qty')
            ->limit(5)
            ->with('menu:id,name')
            ->get();

        $maxQty = $topMenuRows->max('qty') ?: 1;

        $topMenus = $topMenuRows
            ->filter(fn ($row) => $row->menu !== null)
            ->map(fn ($row) => [
                'name' => $row->menu->name,
                'qty' => (int) $row->qty,
                'percent' => max(1, (int) round(((int) $row->qty) / $maxQty * 100)),
            ])
            ->values();

        $recentTransactions = Transaction::with('items.menu.category')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $menuActive = Menu::where('status', 'active')->count();

        return view('cashier.dashboard', compact(
            'todayTransactions',
            'todayRevenue',
            'chartLabels',
            'chartValues',
            'topMenus',
            'recentTransactions',
            'menuActive'
        ));
    }
}
