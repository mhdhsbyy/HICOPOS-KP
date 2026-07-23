<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('username', 'daniel')->first();
        $kasir = User::where('username', 'mhdhsbyy')->first();
        $menus = Menu::where('status', 'active')->get();

        if (! $owner || ! $kasir || $menus->isEmpty()) {
            return;
        }

        $users = [$owner, $kasir];

        for ($i = 0; $i < 15; $i++) {
            $user = $users[array_rand($users)];
            $itemCount = rand(1, 4);
            $selectedMenus = $menus->random($itemCount);

            $subtotal = 0;
            $items = [];

            foreach ($selectedMenus as $menu) {
                $qty = rand(1, 3);
                $total = $menu->price * $qty;
                $subtotal += $total;
                $items[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $qty,
                    'price' => $menu->price,
                    'total' => $total,
                ];
            }

            $discount = rand(0, 1) ? rand(5000, 20000) : 0;
            $total = $subtotal - $discount;
            $amountPaid = $total + rand(0, 50000);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'change' => $amountPaid - $total,
                'payment_method' => 'cash',
                'status' => 'completed',
                'notes' => rand(0, 1) ? 'Tanpa seduh' : null,
                'created_at' => now()->subDays(rand(0, 7))->subHours(rand(0, 23)),
            ]);

            foreach ($items as $item) {
                $transaction->items()->create($item);
            }
        }
    }
}
