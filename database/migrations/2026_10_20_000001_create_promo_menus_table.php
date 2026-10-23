<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->unique(['promo_id', 'menu_id']);
        });

        $itemPromos = DB::table('promos')
            ->where('scope', 'item')
            ->whereNotNull('target_id')
            ->get(['id', 'target_id']);

        foreach ($itemPromos as $promo) {
            DB::table('promo_menus')->insertOrIgnore([
                'promo_id' => $promo->id,
                'menu_id' => $promo->target_id,
            ]);
        }

        DB::table('promos')->where('scope', 'item')->update(['target_id' => null]);
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_menus');
    }
};
