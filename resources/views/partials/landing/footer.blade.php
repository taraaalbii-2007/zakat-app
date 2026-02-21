{{-- partials/landing/footer.blade.php --}}
<footer class="bg-neutral-900 text-neutral-400">

    {{-- Main Footer --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-16">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">

            {{-- Brand Column --}}
            <div class="col-span-2 lg:col-span-2">
                {{-- Logo --}}
                <a href="/" class="inline-flex items-center gap-3 mb-5 group">
                    <div class="w-9 h-9 bg-primary-500 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="font-bold text-white text-lg tracking-tight">Niat Zakat</span>
                        <span class="text-neutral-500 text-[10px] font-medium tracking-wider uppercase">Digital</span>
                    </div>
                </a>
                <p class="text-sm leading-relaxed text-neutral-400 mb-6 max-w-xs">
                    Platform manajemen zakat digital yang transparan, amanah, dan mudah digunakan untuk masjid dan lembaga amil zakat seluruh Indonesia.
                </p>

                {{-- Kontak Info --}}
                <div class="space-y-2.5">
                    <a href="mailto:admin@niatzakat.id" class="flex items-center gap-2.5 text-sm text-neutral-400 hover:text-white transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        admin@niatzakat.id
                    </a>
                    <a href="https://wa.me/6281234567890" class="flex items-center gap-2.5 text-sm text-neutral-400 hover:text-white transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        +62 812-3456-7890
                    </a>
                    <div class="flex items-start gap-2.5 text-sm text-neutral-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Jakarta Selatan, DKI Jakarta,<br>Indonesia 12190</span>
                    </div>
                </div>
            </div>

            {{-- Platform --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5">Platform</h4>
                <ul class="space-y-3">
                    @foreach([
                        ['Fitur Utama', '#fitur'],
                        ['Cara Kerja', '#cara-kerja'],
                        ['Statistik', '#statistik'],
                        ['Harga & Paket', '#'],
                        ['Changelog', '#'],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}" class="text-sm text-neutral-400 hover:text-primary-400 transition-colors duration-200">{{ $label }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Modul --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5">Modul</h4>
                <ul class="space-y-3">
                    @foreach([
                        'Penerimaan Zakat',
                        'Penyaluran Mustahik',
                        'Data Muzakki',
                        'Harga Emas & Perak',
                        'Laporan Konsolidasi',
                        'Dashboard Superadmin',
                    ] as $modul)
                    <li>
                        <span class="text-sm text-neutral-400">{{ $modul }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Legal --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-5">Informasi</h4>
                <ul class="space-y-3">
                    @foreach([
                        ['Tentang Kami', '#'],
                        ['Kebijakan Privasi', '#'],
                        ['Syarat & Ketentuan', '#'],
                        ['FAQ', '#'],
                        ['Blog', '#'],
                        ['Kontak', '#kontak'],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}" class="text-sm text-neutral-400 hover:text-primary-400 transition-colors duration-200">{{ $label }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-neutral-500 text-center sm:text-left">
                &copy; {{ date('Y') }} Niat Zakat Digital. Hak Cipta Dilindungi Undang-Undang.
            </p>
            <div class="flex items-center gap-1">
                <span class="w-2 h-2 bg-success-DEFAULT rounded-full animate-pulse-slow" style="background-color:#4caf50;"></span>
                <span class="text-xs text-neutral-500">Semua sistem beroperasi normal</span>
            </div>
            {{-- Versi --}}
            <span class="text-xs text-neutral-600 bg-neutral-800 px-2.5 py-1 rounded-lg font-mono">v1.0.0</span>
        </div>
    </div>

</footer>