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

        $activeQuery = Promo::withCount('transactions')->with(['menu', 'category'])
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            });

        $expiredQuery = Promo::withCount('transactions')->with(['menu', 'category'])
            ->where('end_date', '<', $now);

        $inactiveQuery = Promo::withCount('transactions')->with(['menu', 'category'])
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
        $validated['target_id'] = $validated['scope'] === 'transaction' ? null : ($validated['target_id'] ?? null);

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

        $validated['target_id'] = $validated['scope'] === 'transaction' ? null : ($validated['target_id'] ?? null);
        $validated['is_active'] = $request->boolean('is_active');

        $promo->update($validated);

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        if ($promo->transactions()->exists()) {
            return back()->with('error', "Promo '{$promo->name}' tidak bisa dihapus karena sudah memiliki data transaksi.");
        }

        $promo->delete();

        return redirect()->route('owner.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
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
