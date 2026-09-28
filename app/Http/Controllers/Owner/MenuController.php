<?php

namespace App\Http\Controllers\Owner;

use App\Console\Commands\GenerateMenuThumbnails;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $status = in_array($request->query('status'), ['active', 'inactive'])
            ? $request->query('status')
            : null;

        $categories = Category::with(['menus' => function ($query) use ($status) {
            $query->withCount('transactionItems')->orderBy('code');

            if ($status) {
                $query->where('status', $status);
            }
        }])
            ->whereHas('menus', function ($query) use ($status) {
                if ($status) {
                    $query->where('status', $status);
                }
            })
            ->orderBy('id')
            ->get();

        $menus = $categories->mapWithKeys(fn ($c) => [$c->name => $c->menus]);

        $total = Menu::count();
        $active = Menu::where('status', 'active')->count();
        $inactive = $total - $active;

        return view('owner.menus.index', compact('menus', 'total', 'active', 'inactive', 'status'));
    }

    public function loadMore(Request $request)
    {
        $page = max(1, (int) $request->input('page', 1));
        $menus = Menu::with('category')->withCount('transactionItems')->orderBy('code')->paginate(12, ['*'], 'page', $page);

        return response()->json([
            'html' => view('owner.menus._cards', ['menus' => $menus])->render(),
            'hasMore' => $menus->hasMorePages(),
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('owner.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:menus'],
            'description' => ['nullable', 'string', 'max:1000'],
            'recipe' => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'price' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ], [
            'name.required' => 'Nama menu wajib diisi.',
            'name.unique' => 'Nama menu sudah digunakan.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
        ]);

        if ($request->hasFile('photo')) {
            $category = Category::find($validated['category_id']);
            $categorySlug = str($category->name)->slug()->toString();
            $validated['photo'] = $request->file('photo')->store('menus/'.$categorySlug, 'public');
        }

        $category = Category::find($validated['category_id']);

        $validated['status'] = 'active';
        $validated['code'] = Menu::generateCode($category);

        Menu::create($validated);

        return redirect()->route('owner.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::orderBy('name')->get();

        return view('owner.menus.edit', compact('menu', 'categories'));
    }

    public function show(Menu $menu)
    {
        $menu->load('category');

        return view('owner.menus.show', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:menus,name,'.$menu->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'recipe' => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'price' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'name.required' => 'Nama menu wajib diisi.',
            'name.unique' => 'Nama menu sudah digunakan.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        if ($request->hasFile('photo')) {
            if ($menu->photo) {
                $oldDir = dirname($menu->photo);
                Storage::disk('public')->delete($menu->photo);
                Storage::disk('public')->delete(GenerateMenuThumbnails::thumbPath($menu->photo));

                $files = Storage::disk('public')->files($oldDir);
                if (empty($files)) {
                    Storage::disk('public')->deleteDirectory($oldDir);
                }
            }
            $category = Category::find($validated['category_id']);
            $categorySlug = str($category->name)->slug()->toString();
            $validated['photo'] = $request->file('photo')->store('menus/'.$categorySlug, 'public');
        }

        $categoryChanged = $menu->category_id != $validated['category_id'];
        $oldCategoryId = $menu->category_id;

        $menu->update($validated);

        if ($categoryChanged) {
            $newCategory = Category::find($validated['category_id']);

            $menu->update(['code' => Menu::generateCode($newCategory, $menu->id)]);

            Menu::reindexCodes($oldCategoryId);
        }

        return redirect()->route('owner.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->transactionItems()->exists()) {
            return back()->with('error', "Menu '{$menu->name}' tidak bisa dihapus karena sudah memiliki data transaksi.");
        }

        if ($menu->photo) {
            $dir = dirname($menu->photo);
            Storage::disk('public')->delete($menu->photo);
            Storage::disk('public')->delete(GenerateMenuThumbnails::thumbPath($menu->photo));

            $files = Storage::disk('public')->files($dir);
            if (empty($files)) {
                Storage::disk('public')->deleteDirectory($dir);
            }
        }

        $categoryId = $menu->category_id;

        $menu->delete();

        Menu::reindexCodes($categoryId);

        return redirect()->route('owner.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleStatus(Request $request, Menu $menu)
    {
        $menu->status = $menu->status === 'active' ? 'inactive' : 'active';
        $menu->save();

        if ($request->wantsJson()) {
            return response()->json(['status' => $menu->status]);
        }

        $status = $menu->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('owner.menus.index')
            ->with('success', "Menu berhasil {$status}.");
    }
}
