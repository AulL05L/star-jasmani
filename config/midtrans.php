<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Payment Gateway
    |--------------------------------------------------------------------------
    | Keys are available at https://dashboard.sandbox.midtrans.com (sandbox)
    | or https://dashboard.midtrans.com (production).
    | Never commit real keys — store them in .env only.
    */

    'server_key'    => env('MIDTRANS_SERVER_KEY'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'snap_url'      => env('MIDTRANS_SNAP_URL', 'https://app.sandbox.midtrans.com/snap/snap.js'),

    /*
    | Harga PDF laporan kalkulator, dalam rupiah penuh (tanpa desimal).
    | Ditaruh di sini karena harga adalah hal yang paling mungkin berubah, dan
    | mengubahnya tidak seharusnya menuntut penyuntingan controller.
    |
    | Order yang sudah terlanjur dibuat menyimpan nominalnya sendiri, jadi
    | mengubah nilai ini tidak mengacaukan tagihan yang sedang berjalan.
    */
    'pdf_price'     => (int) env('MIDTRANS_PDF_PRICE', 5000),

];
