{{--
    Pelacak pengunjung (Google Analytics 4).

    Dua penjagaan sengaja dipasang:
      - tanpa GA_MEASUREMENT_ID di .env, blok ini tidak menghasilkan apa pun;
      - kunjungan pengguna yang sedang login tidak dihitung, supaya statistik
        tidak tercemar oleh admin dan member yang membuka dashboard-nya sendiri
        setiap hari.
--}}
@php($gaId = config('analytics.ga_measurement_id'))

@if ($gaId && ! auth()->check())
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @json($gaId));
    </script>
@endif
