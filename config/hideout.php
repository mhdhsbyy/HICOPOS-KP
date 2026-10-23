<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rekening Bank
    |--------------------------------------------------------------------------
    |
    | Daftar rekening yang dipakai untuk metode pembayaran "Transfer Bank" di
    | layar POS. Modal transfer bank membacanya langsung dari file ini lewat
    | config('hideout.bank_accounts'), jadi kasir cukup mengubah daftar ini
    | tanpa menyentuh Blade.
    |
    | Setiap akun punya tiga kunci:
    |   bank            -> nama bank (BCA, Mandiri, ...)
    |   account_number  -> nomor rekening
    |   account_name    -> nama pemilik rekening
    |
    */

    'bank_accounts' => [
        [
            'bank' => 'BRI',
            'account_number' => '1234567890',
            'account_name' => 'Hideout Coffee',
        ],
    ],

];
