{{--
    Footer publik. Kolom "Program" sengaja memuat seluruh halaman segmen —
    inilah jaring tautan internal yang membuat setiap halaman bisa ditemukan
    crawler dari halaman mana pun, termasuk dari halaman kalkulator yang
    nantinya paling banyak menerima trafik pencarian.
--}}
<footer class="bg-black border-t border-gray-900 py-14">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">

            <div class="md:col-span-1">
                <h4 class="text-white font-black tracking-tighter text-2xl mb-3">STAR <span class="text-red-800">JASMANI</span></h4>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Penyedia layanan program latihan jasmani profesional berbasis sport science di Jakarta.
                </p>
            </div>

            <div>
                <h5 class="text-white font-bold uppercase tracking-widest text-xs mb-4">Program</h5>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('program.kedinasan') }}" class="text-gray-500 hover:text-red-500 transition-colors">Persiapan Kedinasan TNI &amp; POLRI</a></li>
                    <li><a href="{{ route('program.kebugaran') }}" class="text-gray-500 hover:text-red-500 transition-colors">Kebugaran &amp; Strength Conditioning</a></li>
                    <li><a href="{{ route('program.atlet') }}" class="text-gray-500 hover:text-red-500 transition-colors">Monitoring Performa Atlet</a></li>
                    <li><a href="{{ route('kalkulator.polri') }}" class="text-gray-500 hover:text-red-500 transition-colors">Kalkulator Nilai Samapta POLRI</a></li>
                    <li><a href="{{ route('daftar') }}" class="text-gray-500 hover:text-red-500 transition-colors">Pendaftaran</a></li>
                </ul>
            </div>

            <div>
                <h5 class="text-white font-bold uppercase tracking-widest text-xs mb-4">Kontak</h5>
                <ul class="space-y-2.5 text-sm">
                    <li class="flex items-center gap-3 text-gray-500">
                        <i class="fa-solid fa-envelope text-red-800 w-4"></i>
                        <a href="mailto:starjasmani@gmail.com" class="hover:text-red-500 transition-colors">starjasmani@gmail.com</a>
                    </li>
                    <li class="flex items-center gap-3 text-gray-500">
                        <i class="fa-brands fa-whatsapp text-red-800 w-4"></i>
                        <a href="https://wa.me/6285603875675" class="hover:text-red-500 transition-colors">+62 856 0387 5675</a>
                    </li>
                    <li class="flex items-center gap-3 text-gray-500">
                        <i class="fa-solid fa-location-dot text-red-800 w-4"></i>
                        <span>Jakarta, Indonesia</span>
                    </li>
                </ul>
            </div>

            <div>
                <h5 class="text-white font-bold uppercase tracking-widest text-xs mb-4">Ikuti Kami</h5>
                <div class="flex gap-3">
                    <a href="https://www.instagram.com/star_jasmani/" target="_blank" rel="noopener" aria-label="Instagram Star Jasmani"
                        class="w-10 h-10 rounded-full border border-gray-800 flex items-center justify-center text-gray-500 hover:text-white hover:border-red-800 hover:bg-red-800 transition-all"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@star.jasmani" target="_blank" rel="noopener" aria-label="TikTok Star Jasmani"
                        class="w-10 h-10 rounded-full border border-gray-800 flex items-center justify-center text-gray-500 hover:text-white hover:border-red-800 hover:bg-red-800 transition-all"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://wa.me/6285603875675" target="_blank" rel="noopener" aria-label="WhatsApp Star Jasmani"
                        class="w-10 h-10 rounded-full border border-gray-800 flex items-center justify-center text-gray-500 hover:text-white hover:border-red-800 hover:bg-red-800 transition-all"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="mailto:starjasmani@gmail.com" aria-label="Email Star Jasmani"
                        class="w-10 h-10 rounded-full border border-gray-800 flex items-center justify-center text-gray-500 hover:text-white hover:border-red-800 hover:bg-red-800 transition-all"><i class="fa-solid fa-envelope"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-900 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-gray-600 text-xs">&copy; {{ date('Y') }} <span class="text-gray-400 font-bold">STAR JASMANI</span>. All Rights Reserved.</p>
            <p class="text-gray-600 text-xs">Professional S&amp;C Coaching by Fariz Fahrun, S.Or.</p>
        </div>
    </div>
</footer>
