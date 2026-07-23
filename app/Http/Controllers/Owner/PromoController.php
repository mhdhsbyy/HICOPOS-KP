<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $now = now();

        $activePromos = Promo::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->latest()->get();

        $expiredPromos = Promo::where('is_active', true)
            ->where('end_date', '<', $now)
            ->latest()->get();

        $inactivePromos = Promo::where('is_active', false)->latest()->get();

        $total = Promo::count();
        $active = $activePromos->count();
        $expired = $expiredPromos->count();
        $inactive = $inactivePromos->count();

        return view('owner.promos.index', compact('activePromos', 'expiredPromos', 'inactivePromos', 'total', 'active', 'expired', 'inactive'));
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
            'target_id' => ['required_if:scope,item', 'required_if:scope,category', 'nullable'],
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
            'target_id.required_if' => 'Target wajib dipilih untuk scope item atau kategori.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.date' => 'Format tanggal berakhir tidak valid.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
        ]);

        if ($validated['type'] === 'percentage') {
            if ($validated['value'] > 100) {
                return back()->withErrors(['value' => 'Persentase diskon maksimal 100%.'])->withInput();
            }
        }

        $validated['is_active'] = true;
        $validated['target_id'] = $validated['scope'] === 'transaction' ? null : $validated['target_id'];

        Promo::create($validated);

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        $menus = Menu::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

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
            'target_id' => ['required_if:scope,item', 'required_if:scope,category', 'nullable'],
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
            'target_id.required_if' => 'Target wajib dipilih untuk scope item atau kategori.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.date' => 'Format tanggal berakhir tidak valid.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
        ]);

        if ($validated['type'] === 'percentage') {
            if ($validated['value'] > 100) {
                return back()->withErrors(['value' => 'Persentase diskon maksimal 100%.'])->withInput();
            }
        }

        $validated['target_id'] = $validated['scope'] === 'transaction' ? null : $validated['target_id'];

        $promo->update($validated);

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    public function toggleStatus(Promo $promo)
    {
        $promo->is_active = ! $promo->is_active;
        $promo->save();

        $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('owner.promos.index')
            ->with('success', "Promo berhasil {$status}.");
    }
}
