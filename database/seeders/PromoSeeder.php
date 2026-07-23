<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        Promo::create([
            'name' => 'Promo Spesial Kopi',
            'description' => 'Potongan Rp 5.000 untuk setiap pembelian menu kopi',
            'type' => 'flat',
            'value' => 5000,
            'scope' => 'category',
            'target_id' => 1,
            'is_active' => true,
        ]);

        Promo::create([
            'name' => 'Diskon Besar Belanja',
            'description' => 'Potongan 10% untuk transaksi minimal Rp 100.000',
            'type' => 'percentage',
            'value' => 10,
            'scope' => 'transaction',
            'min_amount' => 100000,
            'is_active' => true,
        ]);

        Promo::create([
            'name' => 'Promo Akhir Bulan',
            'description' => 'Diskon Rp 25.000 untuk transaksi minimal Rp 200.000',
            'type' => 'flat',
            'value' => 25000,
            'scope' => 'transaction',
            'min_amount' => 200000,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(25),
            'is_active' => true,
        ]);
    }
}
