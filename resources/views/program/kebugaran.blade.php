@extends('layouts.app')

@section('title', 'Pelatih Kebugaran & Strength Conditioning Jakarta | Star Jasmani')
@section('meta_description', 'Program kebugaran dan strength conditioning di Jakarta bersama pelatih bersertifikasi ICCA. Weight management, body shaping, functional strength training, dan program pemulihan pasca cedera — dirancang dari hasil asesmen, bukan program generik.')

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Service',
            'name'        => 'Program Kebugaran & Strength Conditioning',
            'serviceType' => 'Pelatihan kebugaran dan strength conditioning',
            'url'         => route('program.kebugaran'),
            'description' => 'Program latihan kebugaran, weight management, functional strength training, dan pemulihan pasca cedera berbasis sport science.',
            'areaServed'  => ['@type' => 'AdministrativeArea', 'name' => 'DKI Jakarta, Indonesia'],
            'provider'    => ['@type' => 'Organization', 'name' => 'Star Jasmani', 'url' => url('/')],
            'audience'    => ['@type' => 'Audience', 'audienceType' => 'Individu yang ingin meningkatkan kebugaran dan komposisi tubuh'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Kebugaran & Strength Conditioning', 'item' => route('program.kebugaran')],
            ],
        ],
        [
            '@type'      => 'FAQPage',
            'mainEntity' => collect([
                ['Apakah program ini cocok untuk pemula yang belum pernah latihan?', 'Cocok. Program selalu dimulai dari asesmen kondisi fisik awal, termasuk analisis postur, sehingga beban latihan pertama disesuaikan dengan kemampuan nyata peserta, bukan dengan standar orang lain.'],
                ['Apa bedanya dengan program latihan yang saya unduh dari internet?', 'Program unduhan tidak mengetahui postur, komposisi tubuh, riwayat cedera, dan kapasitas awal Anda. Di sini ketiga hal itu diukur lebih dulu, lalu latihan disusun progresif dan dievaluasi ulang secara berkala.'],
                ['Apakah ada program untuk pemulihan setelah cedera?', 'Ada. Program pemulihan mencakup latihan penguatan otot pendukung berupa stability dan mobility, serta protokol kembali ke olahraga atau return to sport.'],
                ['Apa itu analisis postur APECS?', 'APECS dipakai untuk mengidentifikasi ketidakseimbangan otot dan potensi risiko cedera sebelum beban latihan diberikan, sehingga program tidak memperburuk masalah yang sudah ada.'],
                ['Di mana lokasi latihannya?', 'Star Jasmani berbasis di Jakarta. Detail lokasi dan jadwal dapat ditanyakan langsung melalui WhatsApp.'],
            ])->map(fn ($qa) => [
                '@type'          => 'Question',
                'name'           => $qa[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa[1]],
            ])->all(),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
@include('layouts.partials.public-header')

{{-- HERO --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-24">
    <div class="container mx-auto px-6 max-w-4xl">
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center gap-2 text-xs text-gray-600 uppercase tracking-widest">
                <li><a href="{{ route('home') }}" class="hover:text-red-500 transition-colors">Beranda</a></li>
                <li><i class="fa-solid fa-chevron-right text-[8px]"></i></li>
                <li class="text-gray-400">Kebugaran &amp; S&amp;C</li>
            </ol>
        </nav>

        <span class="inline-block text-red-500 text-[11px] font-bold uppercase tracking-[0.25em] mb-4">Modern Lifestyle</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tighter leading-tight mb-6">
            Pelatih Kebugaran &amp; <span class="text-red-800">Strength Conditioning</span> di Jakarta
        </h1>
        <p class="text-gray-400 text-base md:text-lg leading-relaxed mb-8 max-w-3xl">
            Transformasi fisik yang bertahan lama tidak lahir dari program yang diunduh, melainkan dari program
            yang dibuat untuk tubuh Anda sendiri. Kami mengukur dulu postur, komposisi tubuh, dan kapasitas awal
            Anda — baru menyusun latihannya.
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('daftar') }}"
                class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-solid fa-user-plus"></i> Daftar Program
            </a>
            <a href="https://wa.me/6285603875675" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 border-2 border-zinc-700 hover:border-red-800 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-brands fa-whatsapp text-lg"></i> Konsultasi Gratis
            </a>
        </div>
    </div>
</section>

{{-- FOKUS PROGRAM --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Fokus Program</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="grid sm:grid-cols-2 gap-5">
            @foreach([
                ['fa-weight-scale', 'Weight Management & Body Shaping', 'Pengelolaan berat badan dan pembentukan komposisi tubuh yang terukur, dipantau lewat pencatatan BMI dan komposisi tubuh secara berkala.'],
                ['fa-dumbbell', 'Functional Strength Training', 'Kekuatan yang terpakai di kehidupan nyata — bukan sekadar angka beban, tapi pola gerak yang stabil dan aman.'],
                ['fa-person-walking', 'Analisis Postur & Biomekanika (APECS)', 'Mengidentifikasi ketidakseimbangan otot dan risiko cedera sebelum beban latihan diberikan.'],
                ['fa-flask', 'Program Berbasis Sport Science', 'Periodisasi latihan yang punya arah jelas, dievaluasi ulang dari data sesi, bukan dari perasaan.'],
            ] as $fokus)
                <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-6 hover:border-red-900 transition-colors">
                    <i class="fa-solid {{ $fokus[0] }} text-red-700 text-xl mb-4"></i>
                    <h3 class="text-white font-bold text-base mb-2">{{ $fokus[1] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $fokus[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PEMULIHAN --}}
<section class="bg-zinc-950 border-y border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <span class="inline-block text-red-500 text-[11px] font-bold uppercase tracking-[0.25em] mb-3">Recovery &amp; Rehabilitation</span>
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Program Pemulihan Pasca Cedera</h2>
        <div class="w-16 h-1 bg-red-800 mb-6"></div>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-6 max-w-3xl">
            Kembali berlatih terlalu cepat setelah cedera adalah cara tercepat untuk cedera lagi. Program ini
            dirancang untuk mengembalikan performa secara bertahap, dengan urutan yang benar.
        </p>
        <div class="grid sm:grid-cols-2 gap-4">
            @foreach([
                ['Stability & Mobility', 'Penguatan otot-otot pendukung di sekitar area cedera, memulihkan kendali gerak sebelum menambah beban.'],
                ['Return to Sport Protocol', 'Tahapan terstruktur untuk kembali ke aktivitas olahraga penuh, dengan kriteria kesiapan yang diukur.'],
            ] as $item)
                <div class="bg-black border border-zinc-900 rounded-xl p-6">
                    <h3 class="text-white font-bold text-base mb-2">{{ $item[0] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $item[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ALUR --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Bagaimana Programnya Berjalan</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="space-y-5">
            @foreach([
                ['01', 'Asesmen Awal', 'Pengukuran BMI dan komposisi tubuh, analisis postur APECS, serta asesmen kemampuan fisik awal. Ini yang jadi titik tolak, sekaligus pembanding untuk mengukur kemajuan nanti.'],
                ['02', 'Penyusunan Program', 'Periodisasi latihan disusun dari hasil asesmen dan target pribadi Anda — bukan template yang sama untuk semua orang.'],
                ['03', 'Evaluasi Berkala', 'Data tiap sesi dicatat dan ditinjau ulang, sehingga program bisa dikoreksi ketika kemajuan melambat atau target berubah.'],
            ] as $tahap)
                <div class="flex gap-5 bg-zinc-950 border border-zinc-900 rounded-2xl p-6">
                    <span class="shrink-0 text-red-800 font-black text-2xl leading-none">{{ $tahap[0] }}</span>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-2">{{ $tahap[1] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $tahap[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- COACH --}}
@include('layouts.partials.public-coach')

{{-- FAQ --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-3xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Pertanyaan yang Sering Diajukan</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="space-y-4">
            @foreach([
                ['Apakah program ini cocok untuk pemula yang belum pernah latihan?', 'Cocok. Program selalu dimulai dari asesmen kondisi fisik awal, termasuk analisis postur, sehingga beban latihan pertama disesuaikan dengan kemampuan nyata Anda, bukan dengan standar orang lain.'],
                ['Apa bedanya dengan program latihan yang saya unduh dari internet?', 'Program unduhan tidak mengetahui postur, komposisi tubuh, riwayat cedera, dan kapasitas awal Anda. Di sini ketiga hal itu diukur lebih dulu, lalu latihan disusun progresif dan dievaluasi ulang secara berkala.'],
                ['Apakah ada program untuk pemulihan setelah cedera?', 'Ada. Program pemulihan mencakup latihan penguatan otot pendukung berupa stability dan mobility, serta protokol kembali ke olahraga atau return to sport.'],
                ['Apa itu analisis postur APECS?', 'APECS dipakai untuk mengidentifikasi ketidakseimbangan otot dan potensi risiko cedera sebelum beban latihan diberikan, sehingga program tidak memperburuk masalah yang sudah ada.'],
                ['Di mana lokasi latihannya?', 'Star Jasmani berbasis di Jakarta. Detail lokasi dan jadwal dapat ditanyakan langsung melalui WhatsApp.'],
            ] as $faq)
                <details class="group bg-zinc-950 border border-zinc-900 rounded-2xl overflow-hidden">
                    <summary class="cursor-pointer list-none px-6 py-5 flex items-center justify-between gap-4">
                        <h3 class="text-white font-bold text-sm md:text-base">{{ $faq[0] }}</h3>
                        <i class="fa-solid fa-plus text-red-700 text-sm shrink-0 group-open:hidden"></i>
                        <i class="fa-solid fa-minus text-red-500 text-sm shrink-0 hidden group-open:block"></i>
                    </summary>
                    <p class="px-6 pb-6 text-gray-500 text-sm leading-relaxed">{{ $faq[1] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-zinc-950 border-t border-zinc-900 py-16">
    <div class="container mx-auto px-6 max-w-3xl text-center">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-4">Mulai dari Data, Bukan Tebakan</h2>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-8 max-w-xl mx-auto">
            Ceritakan target Anda, dan kami jelaskan bagaimana asesmen awal serta programnya akan berjalan.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('daftar') }}"
                class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-solid fa-user-plus"></i> Daftar Sekarang
            </a>
            <a href="https://wa.me/6285603875675" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 border-2 border-zinc-700 hover:border-red-800 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-brands fa-whatsapp text-lg"></i> Tanya via WhatsApp
            </a>
        </div>
    </div>
</section>

@include('layouts.partials.public-footer')
@endsection
