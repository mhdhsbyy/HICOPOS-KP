<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Promo;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('username', 'daniel')->first();
        $kasirHasby = User::where('username', 'mhdhsbyy')->first();
        $kasirZein = User::where('username', 'zein')->first();
        $menus = Menu::where('status', 'active')->get();
        $promos = Promo::active()->get();

        if (! $owner || ! $kasirHasby || ! $kasirZein || $menus->isEmpty()) {
            return;
        }

        Transaction::query()->delete();

        $paymentMethods = ['cash', 'cash', 'cash', 'debit', 'kredit', 'qris'];

        foreach ([$owner, $kasirHasby, $kasirZein] as $user) {
            for ($i = 0; $i < 5; $i++) {
                $itemCount = rand(1, 4);
                $selectedMenus = $menus->random(min($itemCount, $menus->count()));

                $subtotal = 0;
                $items = [];
                $itemDiscountsTotal = 0;

                foreach ($selectedMenus as $menu) {
                    $qty = rand(1, 3);
                    $total = $menu->price * $qty;

                    $itemDiscount = 0;
                    if (rand(0, 5) === 0) {
                        $itemDiscount = min(rand(1000, 3000), $total);
                        $itemDiscountsTotal += $itemDiscount * $qty;
                    }

                    $subtotal += $total;
                    $items[] = [
                        'menu_id' => $menu->id,
                        'quantity' => $qty,
                        'discount' => $itemDiscount,
                        'price' => $menu->price,
                        'total' => $total,
                    ];
                }

                // Manual transaction discount
                $manualDiscount = rand(0, 3) === 0 ? rand(5000, 20000) : 0;
                $manualDiscount = min($manualDiscount, $subtotal);

                // Apply active promos (transaction-scope: percentage/flat gated by min_amount)
                $promoDiscount = 0;
                $appliedPromos = [];
                foreach ($promos as $promo) {
                    if ($promo->scope !== 'transaction') {
                        continue;
                    }

                    $base = $subtotal - $itemDiscountsTotal - $manualDiscount;
                    if ($base < $promo->min_amount) {
                        continue;
                    }

                    $amount = $promo->type === 'percentage'
                        ? round($base * $promo->value / 100)
                        : $promo->value;

                    // Hanya terapkan satu promo transaksi
                    $appliedPromos[$promo->id] = min($amount, $base);
                    $promoDiscount = $amount;
                    break;
                }

                $total = $subtotal - $itemDiscountsTotal - $manualDiscount - $promoDiscount;
                $total = max(0, $total);

                $status = rand(0, 9) === 0 ? 'cancelled' : 'completed';
                $paymentMethod = $status === 'cancelled' ? 'cash' : $paymentMethods[array_rand($paymentMethods)];
                $amountPaid = $status === 'cancelled' ? $total : $total + rand(0, 50000);

                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'subtotal' => $subtotal,
                    'discount' => $manualDiscount,
                    'promo_discount' => $promoDiscount,
                    'total' => $total,
                    'amount_paid' => $amountPaid,
                    'change' => max(0, $amountPaid - $total),
                    'payment_method' => $paymentMethod,
                    'status' => $status,
                    'notes' => rand(0, 3) === 0 ? ['Tanpa seduh', 'Extra es', 'Take away'][array_rand(['Tanpa seduh', 'Extra es', 'Take away'])] : null,
                    'created_at' => now()->subDays(rand(0, 6))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                ]);

                foreach ($items as $item) {
                    $transaction->items()->create($item);
                }

                foreach ($appliedPromos as $promoId => $amount) {
                    $transaction->promos()->attach($promoId, ['discount_amount' => $amount]);
                }
            }
        }
    }
}
