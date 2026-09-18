<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4
    |--------------------------------------------------------------------------
    |
    | Isi GA_MEASUREMENT_ID di .env dengan Measurement ID dari properti GA4
    | (bentuknya "G-XXXXXXXXXX"). Selama dikosongkan, tidak ada skrip pelacak
    | yang dimuat sama sekali — halaman tetap bersih dan tidak ada data yang
    | dikirim ke pihak ketiga.
    |
    */

    'ga_measurement_id' => env('GA_MEASUREMENT_ID'),

];
