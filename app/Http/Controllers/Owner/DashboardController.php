<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = today()->toDateString();
        $yesterday = today()->subDay()->toDateString();

        $todayRevenue = (int) Transaction::where('status', 'completed')->whereDate('created_at', $today)->sum('total');
        $yesterdayRevenue = (int) Transaction::where('status', 'completed')->whereDate('created_at', $yesterday)->sum('total');
        $todayTransactions = Transaction::where('status', 'completed')->whereDate('created_at', $today)->count();
        $yesterdayTransactions = Transaction::where('status', 'completed')->whereDate('created_at', $yesterday)->count();

        $revenueGrowth = $yesterdayRevenue > 0
            ? (int) round(($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue * 100)
            : ($todayRevenue > 0 ? 100 : 0);

        $ordersGrowth = $yesterdayTransactions > 0
            ? (int) round(($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions * 100)
            : ($todayTransactions > 0 ? 100 : 0);

        $menuActive = Menu::where('status', 'active')->count();
        $categoryCount = Category::count();
        $cashierActive = User::role('kasir')->where('status', 'active')->count();

        $chartLabels = [];
        $chartValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = today()->subDays($i);
            $chartLabels[] = $day->format('j M y');
            $chartValues[] = (int) Transaction::where('status', 'completed')
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
            ->limit(4)
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

        $recentTransactions = Transaction::with('user', 'items.menu.category')
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'todayRevenue',
            'todayTransactions',
            'revenueGrowth',
            'ordersGrowth',
            'menuActive',
            'categoryCount',
            'cashierActive',
            'chartLabels',
            'chartValues',
            'topMenus',
            'recentTransactions'
        ));
    }
}