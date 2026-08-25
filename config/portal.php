<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Portal Star Performance
    |--------------------------------------------------------------------------
    |
    | Star Performance adalah aplikasi terpisah (database dan autentikasi
    | sendiri) yang dipasang di subdomain. Halaman depan hanya menautkannya,
    | tidak memanggil kodenya. Bila dikosongkan, kartu portal di halaman
    | depan tidak ditampilkan sama sekali.
    |
    */

    'performance_url' => env('PERFORMANCE_URL'),

];
