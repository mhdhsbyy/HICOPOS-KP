<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class CashierController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('kasir')->withCount('transactions');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cashiers = $query->latest()->paginate(5)->withQueryString();

        $total = User::role('kasir')->count();
        $active = User::role('kasir')->where('status', 'active')->count();
        $inactive = $total - $active;

        return view('owner.cashiers.index', compact('cashiers', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('owner.cashiers.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'regex:/^.+@.+$/', 'max:255', 'unique:users'],
                'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'max:20'],
                'password' => ['required', 'min:8', Rules\Password::defaults()],
                'password_confirmation' => ['required', 'same:password'],
            ], [
                'email.regex' => 'Email harus menggunakan format yang benar (contoh: nama@domain.com).',
                'phone.regex' => 'Format nomor telepon tidak valid.',
                'password_confirmation.same' => 'Konfirmasi password tidak cocok dengan password.',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except(['password_confirmation']));
        }

        $user = User::create([
            'username' => $validated['username'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? '',
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? '',
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        $user->assignRole('kasir');

        return redirect()->route('owner.cashiers.index')
            ->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function edit(User $cashier)
    {
        return view('owner.cashiers.edit', compact('cashier'));
    }

    public function update(Request $request, User $cashier)
    {
        try {
            $validated = $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$cashier->id],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'regex:/^.+@.+$/', 'max:255', 'unique:users,email,'.$cashier->id],
                'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'max:20'],
                'password' => ['nullable', 'min:8', Rules\Password::defaults()],
                'password_confirmation' => ['required_with:password', 'same:password'],
                'status' => ['required', 'in:active,inactive'],
            ], [
                'email.regex' => 'Email harus menggunakan format yang benar (contoh: nama@domain.com).',
                'phone.regex' => 'Format nomor telepon tidak valid.',
                'password_confirmation.same' => 'Konfirmasi password tidak cocok dengan password.',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except(['password_confirmation']));
        }

        $data = [
            'username' => $validated['username'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? '',
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? '',
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $cashier->update($data);

        return redirect()->route('owner.cashiers.index')
            ->with('success', 'Data kasir berhasil diperbarui.');
    }

    public function destroy(User $cashier)
    {
        if ($cashier->transactions()->exists()) {
            return back()->with('error', "Kasir '{$cashier->username}' tidak bisa dihapus karena sudah memiliki data transaksi.");
        }

        $cashier->delete();

        return redirect()->route('owner.cashiers.index')
            ->with('success', 'Kasir berhasil dihapus.');
    }

    public function toggleStatus(Request $request, User $cashier)
    {
        $cashier->status = $cashier->status === 'active' ? 'inactive' : 'active';
        $cashier->save();

        if ($request->wantsJson()) {
            return response()->json(['status' => $cashier->status]);
        }

        $status = $cashier->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('owner.cashiers.index')
            ->with('success', "Kasir berhasil {$status}.");
    }
}
