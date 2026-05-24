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
                {{ request()->routeIs('admin.athletes.*') ? 'text-red-500' : 'text-gray-600 hover:text-gray-400' }}">
                <i class="fa-solid fa-users text-lg"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Peserta</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-gray-600 hover:text-gray-400 transition-all">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Logout</span>
                </button>
            </form>

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