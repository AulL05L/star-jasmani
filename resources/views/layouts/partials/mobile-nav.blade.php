{{-- Bottom Navigation Mobile --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-gray-950 border-t border-gray-800 px-2 pb-safe">
    <div class="flex items-center justify-around py-2">

        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
                class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all
                {{ request()->routeIs('admin.dashboard') ? 'text-red-500' : 'text-gray-600 hover:text-gray-400' }}">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Home</span>
            </a>

            <a href="{{ route('admin.samapta.index') }}"
                class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all
                {{ request()->routeIs('admin.samapta.index') ? 'text-red-500' : 'text-gray-600 hover:text-gray-400' }}">
                <i class="fa-solid fa-list text-lg"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Nilai</span>
            </a>

            <a href="{{ route('admin.samapta.create') }}"
                class="flex flex-col items-center gap-1 -mt-6 px-4 py-3 bg-red-800 rounded-2xl shadow-lg shadow-red-900/50 transition-all hover:bg-red-700">
                <i class="fa-solid fa-plus text-white text-xl"></i>
                <span class="text-[10px] font-black uppercase tracking-widest text-white">Input</span>
            </a>

            <a href="{{ route('admin.athletes.index') }}"
                class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all
                {{ request()->routeIs('admin.athletes.index', 'admin.athletes.show', 'admin.athletes.edit') ? 'text-red-500' : 'text-gray-600 hover:text-gray-400' }}">
                <i class="fa-solid fa-users text-lg"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Peserta</span>
            </a>

            <button onclick="toggleMoreMenu()"
                class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all
                {{ request()->routeIs('admin.benchmark', 'admin.athletes.create', 'admin.athletes.import*', 'admin.institutions.*', 'admin.batches.*') ? 'text-red-500' : 'text-gray-600 hover:text-gray-400' }}">
                <i class="fa-solid fa-ellipsis text-lg"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Lainnya</span>
            </button>

        @else
            <a href="{{ route('member.dashboard') }}"
                class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all
                {{ request()->routeIs('member.dashboard') ? 'text-red-500' : 'text-gray-600 hover:text-gray-400' }}">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Home</span>
            </a>

            <a href="https://wa.me/6285603875675" target="_blank"
                class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-gray-600 hover:text-gray-400 transition-all">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Coach</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-gray-600 hover:text-gray-400 transition-all">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Logout</span>
                </button>
            </form>
        @endif
    </div>
</nav>

@if(auth()->user()->isAdmin())
{{-- Overlay --}}
<div id="more-overlay"
    class="lg:hidden fixed inset-0 bg-black/60 z-[45] opacity-0 pointer-events-none transition-opacity duration-300"
    onclick="toggleMoreMenu()">
</div>

{{-- Slide-up Drawer --}}
<div id="more-menu"
    class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-gray-950 border-t border-gray-800 rounded-t-2xl translate-y-full transition-transform duration-300 ease-out">
    <div class="p-5">

        {{-- Handle bar --}}
        <div class="w-10 h-1 bg-gray-700 rounded-full mx-auto mb-5"></div>

        <p class="text-gray-600 text-[10px] uppercase tracking-widest font-bold px-2 mb-2">Menu Admin</p>

        <div class="space-y-1">
            <a href="{{ route('admin.benchmark') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                {{ request()->routeIs('admin.benchmark') ? 'bg-red-900/30 text-red-400' : 'text-gray-300 hover:bg-gray-800' }}">
                <i class="fa-solid fa-table-list w-5 text-center text-gray-500"></i>
                <span class="text-sm font-bold">Benchmark Nilai</span>
            </a>

            <a href="{{ route('admin.athletes.create') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                {{ request()->routeIs('admin.athletes.create') ? 'bg-red-900/30 text-red-400' : 'text-gray-300 hover:bg-gray-800' }}">
                <i class="fa-solid fa-user-plus w-5 text-center text-gray-500"></i>
                <span class="text-sm font-bold">Tambah Peserta</span>
            </a>

            <a href="{{ route('admin.athletes.import') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                {{ request()->routeIs('admin.athletes.import*') ? 'bg-red-900/30 text-red-400' : 'text-gray-300 hover:bg-gray-800' }}">
                <i class="fa-solid fa-file-arrow-up w-5 text-center text-gray-500"></i>
                <span class="text-sm font-bold">Import Atlet</span>
            </a>

            <a href="{{ route('admin.batches.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                {{ request()->routeIs('admin.batches.*') ? 'bg-red-900/30 text-red-400' : 'text-gray-300 hover:bg-gray-800' }}">
                <i class="fa-solid fa-layer-group w-5 text-center text-gray-500"></i>
                <span class="text-sm font-bold">Kelola Batch</span>
            </a>

            <a href="{{ route('admin.institutions.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                {{ request()->routeIs('admin.institutions.*') ? 'bg-red-900/30 text-red-400' : 'text-gray-300 hover:bg-gray-800' }}">
                <i class="fa-solid fa-building w-5 text-center text-gray-500"></i>
                <span class="text-sm font-bold">Instansi &amp; Bobot</span>
            </a>
        </div>

        <div class="border-t border-gray-800 mt-3 pt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-900/20 transition-all">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                    <span class="text-sm font-bold">Logout</span>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
function toggleMoreMenu() {
    const menu = document.getElementById('more-menu');
    const overlay = document.getElementById('more-overlay');
    const isOpen = !menu.classList.contains('translate-y-full');

    if (isOpen) {
        menu.classList.add('translate-y-full');
        overlay.classList.remove('opacity-100', 'pointer-events-auto');
        overlay.classList.add('opacity-0', 'pointer-events-none');
    } else {
        menu.classList.remove('translate-y-full');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100', 'pointer-events-auto');
    }
}

// Tutup drawer kalau pindah halaman (back button, dll)
window.addEventListener('popstate', function() {
    const menu = document.getElementById('more-menu');
    if (menu && !menu.classList.contains('translate-y-full')) {
        toggleMoreMenu();
    }
});
</script>
@endif
