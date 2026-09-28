<?php

namespace App\Models;

use App\Console\Commands\GenerateMenuThumbnails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description', 'recipe', 'photo', 'price', 'category_id', 'status'];

    protected static function booted(): void
    {
        static::saved(function (Menu $menu) {
            if (! $menu->photo) {
                return;
            }

            $disk = Storage::disk('public');
            $thumb = GenerateMenuThumbnails::thumbPath($menu->photo);

            if (! $disk->exists($thumb)) {
                GenerateMenuThumbnails::makeThumb($menu->photo);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getThumbUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        $thumb = GenerateMenuThumbnails::thumbPath($this->photo);

        return Storage::disk('public')->exists($thumb)
            ? Storage::url($thumb)
            : Storage::url($this->photo);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function categoryPrefix(Category $category): string
    {
        $words = explode('-', str($category->name)->slug()->toString());
        $prefix = collect($words)
            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');

        if (mb_strlen($prefix) === 1) {
            $prefix .= strtoupper(substr($words[0], 1, 1));
        }

        return $prefix;
    }

    public static function generateCode(Category $category, ?int $exceptId = null): string
    {
        $prefix = self::categoryPrefix($category);

        $query = Menu::where('category_id', $category->id);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }
        $count = $query->count() + 1;

        $code = $prefix.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);

        while (Menu::where('code', $code)->exists()) {
            $count++;
            $code = $prefix.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    public static function reindexCodes(int $categoryId, ?int $exceptId = null): void
    {
        $prefix = self::categoryPrefix(Category::findOrFail($categoryId));

        $query = Menu::where('category_id', $categoryId);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        $menus = $query->orderBy('code')->orderBy('id')->get();

        foreach ($menus as $menu) {
            $menu->code = null;
            $menu->saveQuietly();
        }

        foreach ($menus as $index => $menu) {
            $menu->code = $prefix.'-'.str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $menu->saveQuietly();
        }
    }
}
