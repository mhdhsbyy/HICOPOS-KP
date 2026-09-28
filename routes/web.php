<?php

use App\Http\Controllers\Cashier\DashboardController as KasirDashboardController;
use App\Http\Controllers\Cashier\TransactionController;
use App\Http\Controllers\Owner\CashierController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\MenuController;
use App\Http\Controllers\Owner\PromoController;
use App\Http\Controllers\Owner\TransactionController as OwnerTransactionController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    /** @var User $user */
    $user = Auth::user();

    if ($user->hasRole('owner')) {
        return redirect()->route('owner.dashboard');
    }

    if ($user->hasRole('kasir')) {
        return redirect()->route('kasir.dashboard');
    }

    Auth::logout();

    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::get('/kasir-dashboard', KasirDashboardController::class)->name('kasir.dashboard');

        // Route::get('/dashboard', ...)->name('dashboard');

        Route::resource('categories', CategoryController::class);

        Route::resource('menus', MenuController::class);

        Route::get('/menus/load-more', [MenuController::class, 'loadMore'])->name('menus.load-more');

        Route::patch('/menus/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menus.toggle-status');

        Route::resource('cashiers', CashierController::class);

        Route::patch('/cashiers/{cashier}/toggle-status', [CashierController::class, 'toggleStatus'])->name('cashiers.toggle-status');

        Route::get('/transactions-page', [OwnerTransactionController::class, 'page'])->name('transactions.page');

        Route::get('/transactions/create', [OwnerTransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [OwnerTransactionController::class, 'store'])->name('transactions.store');
        Route::patch('/transactions/{transaction}/cancel', [OwnerTransactionController::class, 'cancel'])->name('transactions.cancel');
        Route::get('/transactions-history', [OwnerTransactionController::class, 'history'])->name('transactions.history');
        Route::get('/transactions-history/filter', [OwnerTransactionController::class, 'historyFilter'])->name('transactions.history-filter');
        Route::get('/transactions/filter', [OwnerTransactionController::class, 'indexFilter'])->name('transactions.index-filter');
        Route::get('/transactions/chart-filter', [OwnerTransactionController::class, 'chartFilter'])->name('transactions.chart-filter');
        Route::get('/transactions/menus/load-more', [OwnerTransactionController::class, 'loadMoreMenus'])->name('transactions.load-more-menus');
        Route::get('/transactions', [OwnerTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}/receipt-pdf', [OwnerTransactionController::class, 'receiptPdf'])->name('transactions.receipt-pdf');
        Route::get('/transactions/print', [OwnerTransactionController::class, 'printIndex'])->name('transactions.print-index');
        Route::get('/transactions/{transaction}/kasir-show', [OwnerTransactionController::class, 'kasirShow'])->name('transactions.kasir-show');
        Route::get('/transactions/{transaction}', [OwnerTransactionController::class, 'show'])->name('transactions.show');

        Route::resource('promos', PromoController::class);
        Route::patch('/promos/{promo}/toggle-status', [PromoController::class, 'toggleStatus'])->name('promos.toggle-status');

        // Route::get('/reports', ...)->name('reports.index');
    });

Route::middleware(['auth', 'role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        Route::get('/dashboard', KasirDashboardController::class)->name('dashboard');

        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::patch('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
        Route::get('/transactions-history', [TransactionController::class, 'history'])->name('transactions.history');
        Route::get('/transactions-history/filter', [TransactionController::class, 'historyFilter'])->name('transactions.history-filter');
        Route::get('/transactions/menus/load-more', [TransactionController::class, 'loadMoreMenus'])->name('transactions.load-more-menus');
        Route::get('/transactions/{transaction}/receipt-pdf', [TransactionController::class, 'receiptPdf'])->name('transactions.receipt-pdf');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    });

require __DIR__.'/auth.php';
