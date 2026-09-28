<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MenuSeeder extends Seeder
{
    private array $colors = [
        'Kopi' => [118, 71, 6],
        'Non-Kopi' => [34, 139, 34],
        'Snack' => [210, 105, 30],
        'Makanan' => [178, 34, 34],
    ];

    private array $photoMap = [
        // Kopi
        'Vietnam Drip' => 'vietnamdrip.png',
        'Americano' => 'americano.png',
        'Bon Bon' => 'kopibonbon.png',
        'Latte' => 'latte.png',
        'Coconut Americano' => 'coconutamericano.png',
        'Kopi Jeruk' => 'kopijeruk.png',

        // Non-Kopi
        'Lemon Squash' => 'lemonsquash.png',
        'Pala Squash' => 'palasquash.png',
        'Teh Tarik' => 'tehtarik.png',
        'Lemon Tea' => 'lemontea.png',
        'Green Tea' => 'greentea.png',
        'Thai Tea' => 'thaitea.png',
        'Milosaurus' => 'milosaurus.png',
        'Milo' => 'milo.png',
        'Matcha' => 'matcha.png',
        'Teh Manis' => 'tehmanis.png',

        // Snack
        'Takoyaki' => 'takoyaki.png',
        'Kensos' => 'kensos.png',

        // Makanan
        'Nasi Goreng' => 'nasigoreng.png',
        'Indomie Goreng' => 'indomiegoreng.png',
        'Indomie Kuah' => 'indomiekuah.png',
        'Indomie Double' => 'indomiedouble.png',
    ];

    public function run(): void
    {
        $menusByCategory = [
            // ========================================
            // KOPI
            // ========================================
            'Kopi' => [
                [
                    'name' => 'Vietnam Drip',
                    'description' => 'Kopi Vietnam tradisional dengan kental manis',
                    'recipe' => "Bahan:\n- 20g kopi Vietnam robusta\n- 100ml air panas\n- 30ml susu kental manis\n- Es batu secukupnya\n\nCara membuat:\n1. Masukkan kopi ke dalam phin filter.\n2. Tekan plunger ringan, tuang air panas.\n3. Tunggu hingga tetesan kopi habis (3-4 menit).\n4. Masukkan susu kental manis ke dalam gelas.\n5. Tambahkan es batu.\n6. Tuangkan kopi yang sudah diseduh.\n7. Aduk dan sajikan.",
                    'price' => 15000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Americano',
                    'description' => 'Kopi hitam tanpa gula',
                    'recipe' => "Bahan:\n- 30ml espresso (single shot)\n- 120ml air panas\n\nCara membuat:\n1. Seduh espresso single shot.\n2. Tuangkan air panas ke dalam cangkir.\n3. Tambahkan espresso ke dalam air panas.\n4. Aduk perlahan.\n5. Sajikan tanpa gula.",
                    'price' => 15000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Bon Bon',
                    'description' => 'Kopi dengan susu dan gula aren',
                    'recipe' => "Bahan:\n- 30ml espresso (single shot)\n- 100ml susu full cream\n- 30ml gula aren cair\n- Es batu secukupnya\n\nCara membuat:\n1. Seduh espresso single shot.\n2. Masukkan gula aren cair ke dalam gelas.\n3. Tambahkan es batu.\n4. Tuangkan susu full cream.\n5. Terakhir, tuangkan espresso di atasnya.\n6. Aduk sebelum diminum.",
                    'price' => 15000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Latte',
                    'description' => 'Kopi dengan susu yang lembut',
                    'recipe' => "Bahan:\n- 30ml espresso (single shot)\n- 200ml susu full cream\n- Busa susu (steamed milk foam)\n\nCara membuat:\n1. Seduh espresso single shot ke dalam cangkir.\n2. Steam susu hingga suhu 65-70°C.\n3. Tuangkan steamed milk ke dalam espresso.\n4. Tambahkan busa susu tipis di bagian atas.\n5. Sajikan segera.",
                    'price' => 20000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Coconut Americano',
                    'description' => 'Kopi hitam dengan sentuhan kelapa',
                    'recipe' => "Bahan:\n- 30ml espresso (single shot)\n- 100ml air panas\n- 50ml santan kelapa\n- Es batu secukupnya\n\nCara membuat:\n1. Seduh espresso single shot.\n2. Tuangkan air panas ke dalam gelas.\n3. Tambahkan santan kelapa.\n4. Masukkan es batu.\n5. Tuangkan espresso di atasnya.\n6. Aduk dan sajikan.",
                    'price' => 20000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Kopi Jeruk',
                    'description' => 'Kopi dengan perasan jeruk segar',
                    'recipe' => "Bahan:\n- 30ml espresso (single shot)\n- 100ml air panas\n- 30ml perasan jeruk nipis\n- Gula secukupnya\n- Es batu secukupnya\n\nCara membuat:\n1. Seduh espresso single shot.\n2. Peras jeruk nipis ke dalam gelas.\n3. Tambahkan gula secukupnya, aduk hingga larut.\n4. Tuangkan air panas.\n5. Masukkan es batu.\n6. Tuangkan espresso di atasnya.\n7. Aduk dan sajikan.",
                    'price' => 25000,
                    'status' => 'active',
                ],
            ],

            // ========================================
            // NON-KOPI
            // ========================================
            'Non-Kopi' => [
                [
                    'name' => 'Lemon Squash',
                    'description' => 'Minuman lemon segar dengan soda',
                    'recipe' => "Bahan:\n- 50ml perasan lemon segar\n- 200ml soda water\n- 30ml sirup lemon\n- Es batu secukupnya\n- Irisan lemon (hiasan)\n\nCara membuat:\n1. Masukkan es batu ke dalam gelas.\n2. Tuangkan sirup lemon.\n3. Tambahkan perasan lemon segar.\n4. Tuangkan soda water.\n5. Aduk perlahan.\n6. Hias dengan irisan lemon.\n7. Sajikan segera.",
                    'price' => 12000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Pala Squash',
                    'description' => 'Minuman pala rempah dengan soda',
                    'recipe' => "Bahan:\n- 30ml sirup pala\n- 200ml soda water\n- 10ml perasan lemon\n- Es batu secukupnya\n- Biji pala parut (hiasan)\n\nCara membuat:\n1. Masukkan es batu ke dalam gelas.\n2. Tuangkan sirup pala.\n3. Tambahkan perasan lemon.\n4. Tuangkan soda water.\n5. Aduk perlahan.\n6. Taburi biji pala parut di atasnya.\n7. Sajikan segera.",
                    'price' => 12000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Teh Tarik',
                    'description' => 'Teh tarik tradisional dengan busa',
                    'recipe' => "Bahan:\n- 20g teh hitam\n- 150ml air panas\n- 100ml susu kental manis\n- Gula secukupnya\n\nCara membuat:\n1. Seduh teh hitam dengan air panas selama 3 menit.\n2. Saring teh ke dalam gelas tinggi.\n3. Tambahkan susu kental manis dan gula.\n4. Tarik teh dengan menuangkan ke gelas lain berulang kali.\n5. Ulangi hingga berbusa.\n6. Tuangkan ke dalam gelas saji.\n7. Sajikan hangat.",
                    'price' => 10000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Lemon Tea',
                    'description' => 'Teh dengan perasan lemon segar',
                    'recipe' => "Bahan:\n- 20g teh hitam\n- 150ml air panas\n- 30ml perasan lemon\n- Gula secukupnya\n- Es batu secukupnya\n\nCara membuat:\n1. Seduh teh hitam dengan air panas selama 3 menit.\n2. Saring teh ke dalam gelas.\n3. Tambahkan perasan lemon dan gula.\n4. Aduk hingga larut.\n5. Tambahkan es batu.\n6. Sajikan segera.",
                    'price' => 10000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Green Tea',
                    'description' => 'Teh hijau premium dengan susu',
                    'recipe' => "Bahan:\n- 20g bubuk green tea\n- 30ml air panas (70°C)\n- 200ml susu full cream\n- Gula secukupnya (opsional)\n\nCara membuat:\n1. Ayak bubuk green tea ke dalam mangkuk.\n2. Tuangkan air panas, aduk hingga larut.\n3. Steam susu hingga suhu 65°C.\n4. Tuangkan green tea ke dalam gelas.\n5. Tambahkan steamed milk perlahan.\n6. Aduk dan sajikan.",
                    'price' => 13000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Thai Tea',
                    'description' => 'Teh Thailand asli dengan susu kental manis',
                    'recipe' => "Bahan:\n- 20g daun teh Thai Tea\n- 200ml air panas\n- 50ml susu kental manis\n- Es batu secukupnya\n\nCara membuat:\n1. Seduh daun teh Thai Tea dengan air panas selama 5 menit.\n2. Saring teh dan biarkan dingin.\n3. Masukkan es batu ke dalam gelas.\n4. Tuangkan teh yang sudah disaring.\n5. Tambahkan susu kental manis di atasnya.\n6. Aduk sebelum diminum.",
                    'price' => 13000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Milosaurus',
                    'description' => 'Milo dengan topping dinosaurus',
                    'recipe' => "Bahan:\n- 30g bubuk Milo\n- 150ml susu full cream\n- Es batu secukupnya\n- Whipped cream (topping)\n- Cokelat serut (topping)\n\nCara membuat:\n1. Larutkan bubuk Milo dengan sedikit air panas.\n2. Tuangkan susu full cream ke dalam gelas.\n3. Tambahkan es batu.\n4. Tuangkan larutan Milo di atasnya.\n5. Beri whipped cream sebagai topping.\n6. Taburi cokelat serut di atasnya.\n7. Sajikan segera.",
                    'price' => 15000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Milo',
                    'description' => 'Milo klasik hangat atau dingin',
                    'recipe' => "Bahan:\n- 30g bubuk Milo\n- 150ml susu full cream\n- Gula secukupnya\n- Es batu secukupnya (versi dingin)\n\nCara membuat:\n1. Larutkan bubuk Milo dengan sedikit air panas.\n2. Tuangkan susu full cream.\n3. Tambahkan gula secukupnya.\n4. Aduk hingga larut.\n5. Untuk versi dingin, tambahkan es batu.\n6. Sajikan hangat atau dingin.",
                    'price' => 8000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Matcha',
                    'description' => 'Teh hijau premium Jepang',
                    'recipe' => "Bahan:\n- 20g bubuk matcha premium\n- 30ml air panas (70°C)\n- 200ml susu full cream\n- Gula secukupnya (opsional)\n\nCara membuat:\n1. Ayak bubuk matcha ke dalam matcha bowl.\n2. Tuangkan air panas, aduk dengan chasen (whisk) hingga berbusa.\n3. Steam susu hingga suhu 65°C.\n4. Tuangkan matcha ke dalam gelas.\n5. Tambahkan steamed milk perlahan.\n6. Aduk dan sajikan.",
                    'price' => 20000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Teh Manis',
                    'description' => 'Teh manis hangat atau dingin',
                    'recipe' => "Bahan:\n- 20g teh celup\n- 200ml air panas\n- Gula secukupnya\n- Es batu secukupnya (versi dingin)\n\nCara membuat:\n1. Seduh teh celup dengan air panas selama 3 menit.\n2. Tambahkan gula secukupnya, aduk hingga larut.\n3. Untuk versi dingin, masukkan es batu ke dalam gelas.\n4. Tuangkan teh yang sudah diseduh.\n5. Sajikan hangat atau dingin.",
                    'price' => 8000,
                    'status' => 'active',
                ],
            ],

            // ========================================
            // SNACK
            // ========================================
            'Snack' => [
                [
                    'name' => 'Takoyaki',
                    'description' => 'Bola-bola gurita khas Jepang',
                    'recipe' => "Bahan:\n- 200g tepung takoyaki\n- 300ml air\n- 1 butir telur\n- 100g gurita rebus, potong kecil\n- Minyak secukupnya\n- Saus takoyaki, mayones, bonito flakes\n\nCara membuat:\n1. Campur tepung takoyaki dengan air dan telur, aduk rata.\n2. Panaskan cetakan takoyaki, olesi minyak.\n3. Tuangkan adonan ke dalam cetakan.\n4. Taburi potongan gurita.\n5. Balik bola-bola takoyaki hingga matang merata.\n6. Angkat dan sajikan dengan saus, mayones, dan bonito flakes.",
                    'price' => 15000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Kensos',
                    'description' => 'Kentang goreng dengan saus sambal',
                    'recipe' => "Bahan:\n- 200g kentang goreng beku (siap pakai)\n- Minyak goreng secukupnya\n- Garam secukupnya\n- Saus sambal dan mayones (pelengkap)\n\nCara membuat:\n1. Panaskan minyak goreng dalam wajan.\n2. Goreng kentang beku dengan api sedang hingga keemasan.\n3. Angkat dan tiriskan di atas tisu untuk menyerap minyak berlebih.\n4. Taburi garam secukupnya.\n5. Sajikan hangat dengan saus sambal dan mayones.",
                    'price' => 15000,
                    'status' => 'active',
                ],
            ],

            // ========================================
            // MAKANAN
            // ========================================
            'Makanan' => [
                [
                    'name' => 'Nasi Goreng',
                    'description' => 'Nasi goreng spesial ala Hideout',
                    'recipe' => "Bahan:\n- 250g nasi putih dingin\n- 2 siung bawang putih, cincang\n- 1 butir telur\n- 100g ayam suwir\n- 2 sdm kecap manis\n- 1 sdm saus tiram\n- Garam dan merica secukupnya\n- Timun, kerupuk, dan bawang goreng (pelengkap)\n\nCara membuat:\n1. Panaskan minyak, tumis bawang putih hingga harum.\n2. Masak telur orak-arik.\n3. Tambahkan ayam suwir, aduk rata.\n4. Masukkan nasi putih, aduk dengan api besar.\n5. Tambahkan kecap manis, saus tiram, garam, dan merica.\n6. Aduk hingga bumbu meresap dan nasi agak kering.\n7. Sajikan dengan pelengkap timun, kerupuk, dan bawang goreng.",
                    'price' => 16000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Indomie Goreng',
                    'description' => 'Indomie goreng dengan telur ceplok',
                    'recipe' => "Bahan:\n- 1 bungkus Indomie Goreng\n- 1 butir telur\n- Sayuran hijau secukupnya\n- Kerupuk (pelengkap)\n\nCara membuat:\n1. Rebus Indomie hingga setengah matang, tiriskan.\n2. Panaskan minyak, orak-arik telur.\n3. Tambahkan Indomie dan bumbu Indomie.\n4. Aduk hingga rata dan matang.\n5. Sajikan dengan telur ceplok dan kerupuk.",
                    'price' => 8000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Indomie Kuah',
                    'description' => 'Indomie kuah dengan sayuran segar',
                    'recipe' => "Bahan:\n- 1 bungkus Indomie Kuah\n- 1 butir telur\n- Sayuran hijau secukupnya\n- Bawang goreng (pelengkap)\n\nCara membuat:\n1. Rebus air hingga mendidih.\n2. Masukkan Indomie dan bumbu kuah.\n3. Tambahkan sayuran hijau.\n4. Masak hingga matang.\n5. Pecahkan telur, aduk perlahan.\n6. Sajikan hangat dengan bawang goreng.",
                    'price' => 8000,
                    'status' => 'active',
                ],
                [
                    'name' => 'Indomie Double',
                    'description' => 'Indomie double porsi dengan telur',
                    'recipe' => "Bahan:\n- 2 bungkus Indomie Goreng\n- 2 butir telur\n- Sayuran hijau secukupnya\n- Kerupuk (pelengkap)\n\nCara membuat:\n1. Rebus Indomie hingga setengah matang, tiriskan.\n2. Panaskan minyak, orak-arik telur.\n3. Tambahkan Indomie dan bumbu Indomie.\n4. Aduk hingga rata dan matang.\n5. Sajikan dengan telur ceplok dan kerupuk.",
                    'price' => 12000,
                    'status' => 'active',
                ],
            ],
        ];

        foreach ($menusByCategory as $categoryName => $menus) {
            Storage::disk('public')->makeDirectory('menus/'.str($categoryName)->slug()->toString());

            $category = Category::where('name', $categoryName)->first();

            foreach ($menus as $data) {
                $path = $this->getPhoto($data['name'], $categoryName);

                Menu::create([
                    'code' => Menu::generateCode($category),
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'recipe' => $data['recipe'],
                    'photo' => $path,
                    'price' => $data['price'],
                    'category_id' => $category->id,
                    'status' => $data['status'],
                ]);
            }
        }
    }

    private function getPhoto(string $name, string $category): ?string
    {
        if (isset($this->photoMap[$name])) {
            $categorySlug = str($category)->slug()->toString();

            return 'menus/'.$categorySlug.'/'.$this->photoMap[$name];
        }

        return $this->generateImage($name, $category);
    }

    private function generateImage(string $name, string $category): string
    {
        $width = 400;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);

        $rgb = $this->colors[$category] ?? [100, 100, 100];
        $bg = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        $white = imagecolorallocate($image, 255, 255, 255);
        $centerX = intdiv($width, 2);
        $centerY = intdiv($height, 2) - 20;

        $fontFile = $this->getFont();
        if ($fontFile && function_exists('imagettftext')) {
            imagettftext($image, 20, 0, $centerX - 70, $centerY + 5, $white, $fontFile, $name);
        } else {
            imagestring($image, 5, $centerX - 60, $centerY, $name, $white);
        }

        $slug = str($name)->slug()->toString();
        $categorySlug = str($category)->slug()->toString();
        $filename = $slug.'.png';
        $diskPath = 'menus/'.$categorySlug.'/'.$filename;

        $tmpFile = storage_path('app/public/'.$diskPath);
        imagepng($image, $tmpFile);
        imagedestroy($image);

        return $diskPath;
    }

    private function getFont(): ?string
    {
        $paths = [
            'C:/Windows/Fonts/segoeui.ttf',
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/calibri.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
