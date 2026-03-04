@auth
<a href="{{ route('dashboard') }}"
    class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
    {{ $label ?? 'Bayar Zakat Sekarang' }}
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
    </svg>
</a>
@else
<a href="{{ route('register') }}"
    class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
    Daftar &amp; Bayar Zakat
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
    </svg>
</a>
@endauth