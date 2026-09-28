<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class MenuStoreProbeTest extends TestCase
{
    public function test_probe_create_html(): void
    {
        $owner = User::where('username', 'daniel')->firstOrFail();

        $create = $this->actingAs($owner)->get(route('owner.menus.create'));

        $html = $create->getContent();

        fwrite(STDERR, "\nFORM ACTION: ".($create->getContent() && preg_match('/<form[^>]*>/', $html, $m) ? $m[0] : 'no form')."\n");
        fwrite(STDERR, 'HAS ENCTYPE: '.(str_contains($html, 'multipart/form-data') ? 'yes' : 'no')."\n");
        fwrite(STDERR, 'HAS CSRF: '.(str_contains($html, '_token') ? 'yes' : 'no')."\n");
        fwrite(STDERR, 'HAS price hidden: '.(str_contains($html, 'name="price"') ? 'yes' : 'no')."\n");
        fwrite(STDERR, 'HAS category select: '.(str_contains($html, 'name="category_id"') ? 'yes' : 'no')."\n");
        fwrite(STDERR, 'CATEGORY OPTIONS COUNT: '.substr_count($html, 'Pilih kategori')."\n");
        fwrite(STDERR, 'MENUS STORE ROUTE: '.route('owner.menus.store')."\n");

        $fragment = substr($html, strpos($html, '<form'), 1200);
        fwrite(STDERR, "\n--- FORM FRAGMENT ---\n".$fragment."\n--- END ---\n");
    }
}
