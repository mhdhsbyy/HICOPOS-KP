<?php

namespace App\Console\Commands;

use App\Models\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateMenuThumbnails extends Command
{
    protected $signature = 'menus:thumbnails {menuId?}';

    protected $description = 'Generate compressed WebP thumbnails for menu photos (originals kept untouched)';

    public function handle(): int
    {
        if (! function_exists('imagewebp')) {
            $this->error('GD imagewebp() tidak tersedia. Instal ekstensi GD dengan dukungan WebP.');

            return self::FAILURE;
        }

        $query = $this->argument('menuId')
            ? Menu::whereKey($this->argument('menuId'))
            : Menu::query();

        $count = 0;

        foreach ($query->get() as $menu) {
            if (! $menu->photo) {
                continue;
            }

            $result = self::makeThumb($menu->photo);

            if (is_string($result) && str_ends_with($result, '.webp')) {
                $count++;
                $this->info("OK  {$menu->name} -> {$result}");
            } else {
                $this->warn("SKIP {$menu->name} ({$result})");
            }
        }

        $this->info("Selesai. {$count} thumbnail dibuat.");

        return self::SUCCESS;
    }

    public static function makeThumb(string $photo): bool|string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($photo)) {
            return 'file tidak ada';
        }

        $thumbPath = self::thumbPath($photo);

        if ($disk->exists($thumbPath)) {
            return 'thumbnail sudah ada';
        }

        $fullPath = $disk->path($photo);

        $info = @getimagesize($fullPath);
        if (! $info) {
            return 'gambar tidak valid';
        }

        $source = match ($info[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($fullPath),
            IMAGETYPE_PNG => imagecreatefrompng($fullPath),
            IMAGETYPE_WEBP => imagecreatefromwebp($fullPath),
            default => null,
        };

        if (! $source) {
            return 'format tidak didukung';
        }

        $srcW = imagesx($source);
        $srcH = imagesy($source);

        $maxDim = 320;
        $scale = min(1, $maxDim / max($srcW, $srcH));
        $dstW = max(1, (int) round($srcW * $scale));
        $dstH = max(1, (int) round($srcH * $scale));

        $thumb = imagecreatetruecolor($dstW, $dstH);

        if ($info[2] === IMAGETYPE_PNG) {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
        $ok = imagewebp($thumb, $disk->path($thumbPath), 80);

        imagedestroy($source);
        imagedestroy($thumb);

        if (! $ok) {
            return 'gagal menulis webp';
        }

        return $thumbPath;
    }

    public static function thumbPath(string $photo): string
    {
        $info = pathinfo($photo);

        return $info['dirname'].'/'.$info['filename'].'.webp';
    }
}
