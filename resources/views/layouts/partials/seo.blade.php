{{--
    Blok meta SEO terpusat.

    Setiap halaman publik meng-override isinya lewat @section, mis.
        @section('meta_description', 'Ringkasan satu kalimat halaman ini.')
    Halaman privat (dashboard, hasil kalkulator milik orang lain) cukup memasang
        @section('meta_robots', 'noindex, nofollow')
    supaya tidak pernah masuk indeks Google.
--}}
@php
    // yieldContent() sudah meng-escape isi @section. Nilainya dipakai lagi di dalam
    // atribut content="..." yang di-escape sekali lagi oleh {{ }}, jadi tanpa decode
    // ini sebuah "&" berubah jadi "&amp;amp;" di og:title. Decode dulu, biarkan
    // Blade yang meng-escape sekali saja.
    $seoDecode = fn (string $v) => trim(html_entity_decode($v, ENT_QUOTES, 'UTF-8'));

    $seoTitle  = $seoDecode($__env->yieldContent('title', 'Star Jasmani'));
    $seoDesc   = $seoDecode($__env->yieldContent('meta_description',
        'Star Jasmani — pelatihan fisik bersertifikasi untuk persiapan kedinasan TNI/POLRI, kebugaran umum, dan pendampingan atlet. Coba kalkulator nilai Samapta POLRI gratis.'));
    $seoImage  = $seoDecode($__env->yieldContent('meta_image', asset('pict/bg-home.jpg')));
    // Semua halaman di balik login adalah area privat: jangan pernah diindeks,
    // walau suatu saat URL-nya bocor ke luar.
    $seoRobots = $seoDecode($__env->yieldContent('meta_robots',
        auth()->check() ? 'noindex, nofollow' : 'index, follow, max-image-preview:large'));
    $seoCanon  = $seoDecode($__env->yieldContent('meta_canonical', url()->current()));
@endphp

<meta name="description" content="{{ $seoDesc }}" />
<meta name="robots" content="{{ $seoRobots }}" />
<link rel="canonical" href="{{ $seoCanon }}" />
<meta name="author" content="Star Jasmani" />
<meta name="theme-color" content="#991b1b" />

{{-- Open Graph — dipakai WhatsApp, Facebook, LinkedIn saat link dibagikan --}}
<meta property="og:type" content="website" />
<meta property="og:site_name" content="Star Jasmani" />
<meta property="og:locale" content="id_ID" />
<meta property="og:title" content="{{ $seoTitle }}" />
<meta property="og:description" content="{{ $seoDesc }}" />
<meta property="og:url" content="{{ $seoCanon }}" />
<meta property="og:image" content="{{ $seoImage }}" />
<meta property="og:image:alt" content="{{ $seoTitle }}" />

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $seoTitle }}" />
<meta name="twitter:description" content="{{ $seoDesc }}" />
<meta name="twitter:image" content="{{ $seoImage }}" />

@stack('schema')
