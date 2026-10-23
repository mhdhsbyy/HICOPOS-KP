<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $now = now();

        $activeQuery = Promo::with(['menus', 'category'])
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });

        $expiredQuery = Promo::with(['menus', 'category'])
            ->where('end_date', '<', $now);

        $inactiveQuery = Promo::with(['menus', 'category'])
            ->where('is_active', false)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });

        $activePromos = (is_null($status) || $status === 'active') ? $activeQuery->latest()->get() : collect();
        $expiredPromos = (is_null($status) || $status === 'expired') ? $expiredQuery->latest()->get() : collect();
        $inactivePromos = (is_null($status) || $status === 'inactive') ? $inactiveQuery->latest()->get() : collect();

        $total = Promo::count();
        $active = $activeQuery->count();
        $expired = $expiredQuery->count();
        $inactive = $inactiveQuery->count();

        return view('owner.promos.index', compact('activePromos', 'expiredPromos', 'inactivePromos', 'total', 'active', 'expired', 'inactive', 'status'));
    }

    public function create()
    {
        $menus = Menu::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('owner.promos.create', compact('menus', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'type' => ['required', 'in:percentage,flat'],
            'value' => ['required', 'numeric', 'min:0'],
            'scope' => ['required', 'in:item,category,transaction'],
            'target_id' => ['required_if:scope,category', 'nullable'],
            'menu_ids' => ['required_if:scope,item', 'array', 'min:1'],
            'menu_ids.*' => ['integer', 'exists:menus,id'],
            'min_quantity' => ['nullable', 'integer', 'min:1'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'name.required' => 'Nama promo wajib di isi.',
            'type.required' => 'Tipe diskon wajib dipilih.',
            'type.in' => 'Tipe diskon tidak valid.',
            'value.required' => 'Nilai diskon wajib diisi.',
            'value.numeric' => 'Nilai diskon harus berupa angka.',
            'value.min' => 'Nilai diskon tidak boleh kurang dari 0.',
            'scope.required' => 'Target promo wajib dipilih.',
            'scope.in' => 'Target promo tidak valid.',
            'target_id.required_if' => 'Target wajib dipilih untuk scope kategori.',
            'menu_ids.required_if' => 'Minimal satu menu wajib dipilih untuk scope item.',
            'menu_ids.min' => 'Minimal satu menu wajib dipilih untuk scope item.',
            'menu_ids.*.exists' => 'Menu yang dipilih tidak valid.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.date' => 'Format tanggal berakhir tidak valid.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
        ]);

        if ($validated['type'] === 'percentage') {
            if ($validated['value'] > 100) {
                return back()->withErrors(['value' => 'Persentase diskon maksimal 100%.'])->withInput();
            }
        }

        $menuIds = array_map('intval', $validated['menu_ids'] ?? []);
        unset($validated['menu_ids']);

        $validated['is_active'] = true;
        $validated['target_id'] = $validated['scope'] === 'category' ? ($validated['target_id'] ?? null) : null;

        $promo = Promo::create($validated);

        if ($validated['scope'] === 'item') {
            $promo->menus()->sync($menuIds);
        }

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        $menus = Menu::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        if ($promo->scope === 'category' && $promo->target?->trashed() && ! $categories->contains('id', $promo->target_id)) {
            $categories->push($promo->target);
        }

        if ($promo->scope === 'item') {
            foreach ($promo->menus as $menu) {
                if (! $menus->contains('id', $menu->id)) {
                    $menus->push($menu);
                }
            }
        }

        return view('owner.promos.edit', compact('promo', 'menus', 'categories'));
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'type' => ['required', 'in:percentage,flat'],
            'value' => ['required', 'numeric', 'min:0'],
            'scope' => ['required', 'in:item,category,transaction'],
            'target_id' => ['required_if:scope,category', 'nullable'],
            'menu_ids' => ['required_if:scope,item', 'array', 'min:1'],
            'menu_ids.*' => ['integer', 'exists:menus,id'],
            'min_quantity' => ['nullable', 'integer', 'min:1'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'name.required' => 'Nama promo wajib di isi.',
            'type.required' => 'Tipe diskon wajib dipilih.',
            'type.in' => 'Tipe diskon tidak valid.',
            'value.required' => 'Nilai diskon wajib diisi.',
            'value.numeric' => 'Nilai diskon harus berupa angka.',
            'value.min' => 'Nilai diskon tidak boleh kurang dari 0.',
            'scope.required' => 'Target promo wajib dipilih.',
            'scope.in' => 'Target promo tidak valid.',
            'target_id.required_if' => 'Target wajib dipilih untuk scope kategori.',
            'menu_ids.required_if' => 'Minimal satu menu wajib dipilih untuk scope item.',
            'menu_ids.min' => 'Minimal satu menu wajib dipilih untuk scope item.',
            'menu_ids.*.exists' => 'Menu yang dipilih tidak valid.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.date' => 'Format tanggal berakhir tidak valid.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
        ]);

        if ($validated['type'] === 'percentage') {
            if ($validated['value'] > 100) {
                return back()->withErrors(['value' => 'Persentase diskon maksimal 100%.'])->withInput();
            }
        }

        $menuIds = array_map('intval', $validated['menu_ids'] ?? []);
        unset($validated['menu_ids']);

        $validated['target_id'] = $validated['scope'] === 'category' ? ($validated['target_id'] ?? null) : null;
        $validated['is_active'] = $request->boolean('is_active');

        $promo->update($validated);
        $promo->menus()->sync($validated['scope'] === 'item' ? $menuIds : []);

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil dihapus. Data transaksi tetap tersimpan.');
    }

    public function toggleStatus(Request $request, Promo $promo)
    {
        $promo->is_active = ! $promo->is_active;
        $promo->save();

        if ($request->wantsJson()) {
            return response()->json(['status' => $promo->is_active]);
        }

        $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('owner.promos.index')
            ->with('success', "Promo berhasil {$status}.");
    }
}
