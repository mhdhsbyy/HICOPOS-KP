<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $kopiCategoryId = Category::where('name', 'Kopi')->value('id');
        $nonKopiCategoryId = Category::where('name', 'Non-Kopi')->value('id');
        $matchaId = Menu::where('name', 'Matcha')->value('id');
        $takoyakiId = Menu::where('name', 'Takoyaki')->value('id');

        Promo::create([
            'name' => 'Promo Spesial Kopi',
            'description' => 'Potongan Rp 5.000 untuk setiap pembelian menu kopi',
            'type' => 'flat',
            'value' => 5000,
            'scope' => 'category',
            'target_id' => $kopiCategoryId,
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

        Promo::create([
            'name' => 'Promo Hemat Non-Kopi',
            'description' => 'Diskon 15% untuk semua menu kategori Non-Kopi',
            'type' => 'percentage',
            'value' => 15,
            'scope' => 'category',
            'target_id' => $nonKopiCategoryId,
            'is_active' => true,
        ]);

        $matchaPromo = Promo::create([
            'name' => 'Diskon Spesial Matcha',
            'description' => 'Diskon 20% untuk menu Matcha, minimal pembelian 2 item',
            'type' => 'percentage',
            'value' => 20,
            'scope' => 'item',
            'min_quantity' => 2,
            'start_date' => now()->subDays(3),
            'end_date' => now()->addDays(14),
            'is_active' => true,
        ]);
        $matchaPromo->menus()->attach($matchaId);

        $takoyakiPromo = Promo::create([
            'name' => 'Takoyaki Berlimpah',
            'description' => 'Potongan Rp 3.000 untuk setiap pembelian Takoyaki',
            'type' => 'flat',
            'value' => 3000,
            'scope' => 'item',
            'is_active' => true,
        ]);
        $takoyakiPromo->menus()->attach($takoyakiId);
    }
}
