{{-- partials/landing/content.blade.php --}}

{{-- ================================================================
     SECTION 1: FITUR UTAMA
     ================================================================ --}}
<section id="fitur" class="py-20 lg:py-28 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center mb-16">
            <span class="inline-block text-xs font-semibold text-primary-500 bg-primary-50 border border-primary-100 px-4 py-1.5 rounded-full mb-4 tracking-wider uppercase">Fitur Unggulan</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-neutral-900 mb-4 leading-tight">
                Semua yang Anda Butuhkan<br class="hidden sm:block"> untuk Kelola Zakat
            </h2>
            <p class="text-neutral-500 text-base max-w-2xl mx-auto leading-relaxed">
                Dari pencatatan penerimaan hingga penyaluran ke mustahik, semua terdokumentasi dengan rapi dan transparan.
            </p>
        </div>

        {{-- Feature Grid --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Fitur 1: Transaksi Penerimaan --}}
            <div class="group bg-white rounded-2xl border border-neutral-200 p-6 hover:border-primary-300 hover:shadow-card-hover transition-all duration-300">
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center mb-5 group-hover:bg-primary-500 transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary-500 group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-neutral-900 mb-2">Transaksi Penerimaan</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Catat penerimaan zakat fitrah, maal, infaq, dan sedekah dari muzakki dengan validasi dan verifikasi amil.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-xs bg-primary-50 text-primary-600 px-2.5 py-1 rounded-lg font-medium">Zakat Fitrah</span>
                    <span class="text-xs bg-primary-50 text-primary-600 px-2.5 py-1 rounded-lg font-medium">Zakat Maal</span>
                    <span class="text-xs bg-primary-50 text-primary-600 px-2.5 py-1 rounded-lg font-medium">Infaq</span>
                </div>
            </div>

            {{-- Fitur 2: Penyaluran Mustahik --}}
            <div class="group bg-white rounded-2xl border border-neutral-200 p-6 hover:border-primary-300 hover:shadow-card-hover transition-all duration-300">
                <div class="w-12 h-12 bg-secondary-50 rounded-xl flex items-center justify-center mb-5 group-hover:bg-secondary-500 transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-secondary-600 group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-neutral-900 mb-2">Penyaluran ke Mustahik</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Salurkan zakat ke 8 asnaf dengan metode tunai, transfer, maupun barang — lengkap alur persetujuan amil.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-xs bg-secondary-50 text-secondary-700 px-2.5 py-1 rounded-lg font-medium">Tunai</span>
                    <span class="text-xs bg-secondary-50 text-secondary-700 px-2.5 py-1 rounded-lg font-medium">Transfer</span>
                    <span class="text-xs bg-secondary-50 text-secondary-700 px-2.5 py-1 rounded-lg font-medium">Barang</span>
                </div>
            </div>

            {{-- Fitur 3: Harga Emas Perak --}}
            <div class="group bg-white rounded-2xl border border-neutral-200 p-6 hover:border-primary-300 hover:shadow-card-hover transition-all duration-300">
                <div class="w-12 h-12 bg-accent-50 rounded-xl flex items-center justify-center mb-5 group-hover:bg-accent-600 transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-accent-700 group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-neutral-900 mb-2">Harga Emas & Perak</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Update harga emas dan perak per gram secara berkala untuk kalkulasi nisab zakat maal yang akurat dan otomatis.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-xs bg-accent-50 text-accent-700 px-2.5 py-1 rounded-lg font-medium">Auto-Nisab</span>
                    <span class="text-xs bg-accent-50 text-accent-700 px-2.5 py-1 rounded-lg font-medium">Multi Sumber</span>
                </div>
            </div>

            {{-- Fitur 4: Laporan Konsolidasi --}}
            <div class="group bg-white rounded-2xl border border-neutral-200 p-6 hover:border-primary-300 hover:shadow-card-hover transition-all duration-300">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:bg-info transition-colors duration-300" style="background-color:#e3f2fd;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 group-hover:text-white transition-colors duration-300" style="color:#1976d2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-neutral-900 mb-2">Laporan Konsolidasi</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Laporan bulanan & tahunan per masjid dengan breakdown jenis zakat, kategori mustahik. Export PDF & Excel siap cetak.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background-color:#e3f2fd;color:#1565c0;">PDF</span>
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background-color:#e3f2fd;color:#1565c0;">Excel</span>
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background-color:#e3f2fd;color:#1565c0;">Multi-Masjid</span>
                </div>
            </div>

            {{-- Fitur 5: Manajemen Mustahik --}}
            <div class="group bg-white rounded-2xl border border-neutral-200 p-6 hover:border-primary-300 hover:shadow-card-hover transition-all duration-300">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:bg-warning-dark transition-colors duration-300" style="background-color:#fff8e1;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 group-hover:text-white transition-colors duration-300" style="color:#f57c00" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-neutral-900 mb-2">Data Mustahik & Muzakki</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Kelola data penerima dan pembayar zakat terstruktur berdasarkan kategori asnaf dengan riwayat transaksi lengkap.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background-color:#fff8e1;color:#e65100;">8 Asnaf</span>
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background-color:#fff8e1;color:#e65100;">Riwayat Lengkap</span>
                </div>
            </div>

            {{-- Fitur 6: Multi-Masjid Superadmin --}}
            <div class="group bg-white rounded-2xl border border-neutral-200 p-6 hover:border-primary-300 hover:shadow-card-hover transition-all duration-300">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 group-hover:bg-danger-dark transition-colors duration-300" style="background-color:#fce4ec;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 group-hover:text-white transition-colors duration-300" style="color:#c62828" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-neutral-900 mb-2">Dashboard Superadmin</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">Pantau seluruh masjid dalam satu dashboard terpusat. Konfigurasi global, manajemen user, dan audit trail lengkap.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background-color:#fce4ec;color:#b71c1c;">Multi-Masjid</span>
                    <span class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background-color:#fce4ec;color:#b71c1c;">Audit Trail</span>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ================================================================
     SECTION 2: STATISTIK
     ================================================================ --}}
<section id="statistik" class="py-20 lg:py-24 bg-primary-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <span class="inline-block text-xs font-semibold text-primary-200 bg-primary-600 border border-primary-400 px-4 py-1.5 rounded-full mb-4 tracking-wider uppercase">Angka Nyata</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-3">Dipercaya Ribuan Lembaga</h2>
            <p class="text-primary-200 text-base max-w-xl mx-auto">Bersama kami, dana zakat tersalurkan tepat sasaran dan terdokumentasi dengan baik.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-primary-600 rounded-2xl p-6 text-center border border-primary-400">
                <p class="text-4xl font-extrabold text-white mb-1">500+</p>
                <p class="text-sm text-primary-200 font-medium">Masjid Terdaftar</p>
            </div>
            <div class="bg-primary-600 rounded-2xl p-6 text-center border border-primary-400">
                <p class="text-4xl font-extrabold text-white mb-1">Rp 2,5M</p>
                <p class="text-sm text-primary-200 font-medium">Total Zakat Dikelola</p>
            </div>
            <div class="bg-primary-600 rounded-2xl p-6 text-center border border-primary-400">
                <p class="text-4xl font-extrabold text-white mb-1">12.450</p>
                <p class="text-sm text-primary-200 font-medium">Mustahik Dibantu</p>
            </div>
            <div class="bg-primary-600 rounded-2xl p-6 text-center border border-primary-400">
                <p class="text-4xl font-extrabold text-white mb-1">99,9%</p>
                <p class="text-sm text-primary-200 font-medium">Uptime Sistem</p>
            </div>
        </div>

        {{-- Jenis Zakat yang Didukung --}}
        <div class="mt-12 bg-primary-600 rounded-2xl border border-primary-400 p-6 lg:p-8">
            <p class="text-center text-primary-200 text-sm font-semibold mb-6 uppercase tracking-wider">Jenis Zakat yang Didukung</p>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach(['Zakat Fitrah', 'Zakat Maal', 'Zakat Profesi', 'Zakat Perniagaan', 'Zakat Pertanian', 'Zakat Emas & Perak', 'Zakat Saham', 'Infaq', 'Sedekah', 'Wakaf'] as $jenis)
                <span class="px-4 py-2 bg-white text-primary-700 text-sm font-semibold rounded-xl shadow-nz">{{ $jenis }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ================================================================
     SECTION 3: CARA KERJA
     ================================================================ --}}
<section id="cara-kerja" class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-16">
            <span class="inline-block text-xs font-semibold text-primary-500 bg-primary-50 border border-primary-100 px-4 py-1.5 rounded-full mb-4 tracking-wider uppercase">Cara Kerja</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-neutral-900 mb-4 leading-tight">
                Mulai dalam 3 Langkah Mudah
            </h2>
            <p class="text-neutral-500 text-base max-w-xl mx-auto leading-relaxed">
                Tidak perlu konfigurasi rumit. Daftar, setup masjid, dan langsung kelola zakat hari ini.
            </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- Step 1 --}}
            <div class="relative">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-14 h-14 bg-primary-500 rounded-2xl flex items-center justify-center shadow-nz-lg">
                        <span class="text-white font-extrabold text-xl">1</span>
                    </div>
                    <div class="pt-1">
                        <h3 class="text-lg font-bold text-neutral-900 mb-2">Daftar & Verifikasi</h3>
                        <p class="text-sm text-neutral-500 leading-relaxed">Daftarkan lembaga atau masjid Anda. Tim kami akan memverifikasi dan mengaktifkan akun dalam 1×24 jam.</p>
                    </div>
                </div>
                {{-- Connector line --}}
                <div class="hidden lg:block absolute top-7 left-full w-full h-px border-t-2 border-dashed border-primary-200 -translate-x-8" style="width: calc(100% - 3.5rem);"></div>
            </div>

            {{-- Step 2 --}}
            <div class="relative">
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-14 h-14 bg-secondary-500 rounded-2xl flex items-center justify-center shadow-nz-lg">
                        <span class="text-white font-extrabold text-xl">2</span>
                    </div>
                    <div class="pt-1">
                        <h3 class="text-lg font-bold text-neutral-900 mb-2">Setup & Konfigurasi</h3>
                        <p class="text-sm text-neutral-500 leading-relaxed">Atur jenis zakat, kategori mustahik, data amil, dan konfigurasi masjid sesuai kebutuhan lembaga Anda.</p>
                    </div>
                </div>
                <div class="hidden lg:block absolute top-7 left-full w-full h-px border-t-2 border-dashed border-primary-200 -translate-x-8" style="width: calc(100% - 3.5rem);"></div>
            </div>

            {{-- Step 3 --}}
            <div>
                <div class="flex items-start gap-5">
                    <div class="flex-shrink-0 w-14 h-14 bg-accent-600 rounded-2xl flex items-center justify-center shadow-nz-lg">
                        <span class="text-white font-extrabold text-xl">3</span>
                    </div>
                    <div class="pt-1">
                        <h3 class="text-lg font-bold text-neutral-900 mb-2">Kelola & Laporkan</h3>
                        <p class="text-sm text-neutral-500 leading-relaxed">Mulai catat penerimaan, salurkan ke mustahik, dan generate laporan konsolidasi kapan saja — transparan dan akuntabel.</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Alur Transaksi --}}
        <div class="mt-16 bg-neutral-50 rounded-3xl border border-neutral-200 p-6 lg:p-10">
            <h3 class="text-center text-lg font-bold text-neutral-800 mb-8">Alur Transaksi Penyaluran</h3>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                @php
                $steps = [
                    ['icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'label' => 'Draft', 'color' => 'bg-neutral-300 text-neutral-700'],
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Disetujui', 'color' => 'bg-primary-100 text-primary-700'],
                    ['icon' => 'M5 13l4 4L19 7', 'label' => 'Disalurkan', 'color' => 'bg-accent-100 text-accent-800'],
                ];
                @endphp
                @foreach($steps as $i => $step)
                    <div class="flex flex-col items-center text-center flex-1">
                        <div class="w-12 h-12 {{ $step['color'] }} rounded-xl flex items-center justify-center mb-2 font-semibold shadow-soft">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-neutral-700">{{ $step['label'] }}</span>
                    </div>
                    @if($i < count($steps) - 1)
                    <div class="hidden sm:block flex-shrink-0 w-12 h-px border-t-2 border-dashed border-neutral-300"></div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</section>


{{-- ================================================================
     SECTION 4: KEUNGGULAN / WHY US
     ================================================================ --}}
<section class="py-20 lg:py-24 bg-primary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left: Text --}}
            <div>
                <span class="inline-block text-xs font-semibold text-primary-500 bg-white border border-primary-200 px-4 py-1.5 rounded-full mb-5 tracking-wider uppercase">Mengapa Niat Zakat?</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-neutral-900 mb-6 leading-tight">
                    Transparansi Adalah<br>Prioritas Kami
                </h2>
                <p class="text-neutral-500 text-base leading-relaxed mb-8">
                    Setiap rupiah zakat yang masuk dan keluar tercatat dengan detail. Muzakki dapat memantau, amil dapat melaporkan, dan mustahik dapat menerima dengan tepat.
                </p>
                <div class="space-y-4">
                    @php
                    $keunggulan = [
                        ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Sistem Verifikasi Berlapis', 'desc' => 'Setiap transaksi melalui alur persetujuan amil sebelum terealisasi.'],
                        ['icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Laporan Real-Time', 'desc' => 'Dashboard terupdate secara langsung. Export laporan kapan saja dalam format PDF atau Excel.'],
                        ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Keamanan Data Terjamin', 'desc' => 'Enkripsi data, autentikasi berlapis, UUID pada semua entitas penting.'],
                    ];
                    @endphp
                    @foreach($keunggulan as $item)
                    <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-neutral-100 shadow-soft hover:shadow-card transition-all duration-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-neutral-800 mb-0.5">{{ $item['title'] }}</h4>
                            <p class="text-xs text-neutral-500 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Summary Card --}}
            <div class="flex justify-center lg:justify-end">
                <div class="w-full max-w-sm space-y-4">
                    {{-- Saldo Card --}}
                    <div class="bg-white rounded-2xl border border-neutral-200 p-5 shadow-card">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Ringkasan Bulan Ini</p>
                            <span class="text-xs bg-primary-50 text-primary-600 px-2.5 py-1 rounded-full font-semibold">Februari 2026</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                                    <span class="text-sm text-neutral-600">Total Penerimaan</span>
                                </div>
                                <span class="text-sm font-bold text-neutral-900">Rp 48.250.000</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-secondary-500 rounded-full"></div>
                                    <span class="text-sm text-neutral-600">Total Penyaluran</span>
                                </div>
                                <span class="text-sm font-bold text-neutral-900">Rp 32.100.000</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-accent-500 rounded-full"></div>
                                    <span class="text-sm text-neutral-600">Saldo Akhir</span>
                                </div>
                                <span class="text-sm font-bold text-primary-500">Rp 16.150.000</span>
                            </div>
                            <div class="pt-2 border-t border-neutral-100">
                                <div class="flex justify-between text-xs text-neutral-400">
                                    <span>Muzakki: <strong class="text-neutral-700">269</strong></span>
                                    <span>Mustahik: <strong class="text-neutral-700">312</strong></span>
                                    <span>Amil: <strong class="text-neutral-700">8</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Card --}}
                    <div class="bg-white rounded-2xl border border-neutral-200 p-5 shadow-card">
                        <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-4">Penyaluran per Asnaf</p>
                        <div class="space-y-3">
                            @php
                            $asnaf = [
                                ['nama' => 'Fakir', 'persen' => 35, 'warna' => 'bg-primary-500'],
                                ['nama' => 'Miskin', 'persen' => 28, 'warna' => 'bg-secondary-500'],
                                ['nama' => 'Amil', 'persen' => 12, 'warna' => 'bg-accent-500'],
                                ['nama' => 'Lainnya', 'persen' => 25, 'warna' => 'bg-neutral-300'],
                            ];
                            @endphp
                            @foreach($asnaf as $item)
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-neutral-600 font-medium">{{ $item['nama'] }}</span>
                                    <span class="text-neutral-800 font-bold">{{ $item['persen'] }}%</span>
                                </div>
                                <div class="w-full bg-neutral-100 rounded-full h-1.5">
                                    <div class="{{ $item['warna'] }} h-1.5 rounded-full" style="width: {{ $item['persen'] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ================================================================
     SECTION 5: TESTIMONI
     ================================================================ --}}
<section id="testimoni" class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-16">
            <span class="inline-block text-xs font-semibold text-primary-500 bg-primary-50 border border-primary-100 px-4 py-1.5 rounded-full mb-4 tracking-wider uppercase">Testimoni</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-neutral-900 mb-4">Kata Mereka yang Sudah Pakai</h2>
            <p class="text-neutral-500 text-base max-w-xl mx-auto">Ribuan amil dan pengurus masjid sudah merasakan kemudahan Niat Zakat.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $testimoni = [
                [
                    'nama' => 'Ustadz Ahmad Fauzi',
                    'peran' => 'Ketua Amil — Masjid Al-Ikhlas, Bandung',
                    'isi' => 'Sebelum pakai Niat Zakat, laporan kami masih manual di Excel. Sekarang semua terekap otomatis, laporan bulanan bisa di-generate dalam hitungan menit. Alhamdulillah sangat membantu.',
                    'bintang' => 5,
                    'inisial' => 'AF',
                    'warna' => 'bg-primary-500',
                ],
                [
                    'nama' => 'Bapak Hendra Kusuma',
                    'peran' => 'Bendahara — BAZNAS Kabupaten Bogor',
                    'isi' => 'Fitur penyaluran dengan alur persetujuan amil sangat membantu memastikan tidak ada penyaluran yang tidak terverifikasi. Transparansi benar-benar terjaga.',
                    'bintang' => 5,
                    'inisial' => 'HK',
                    'warna' => 'bg-secondary-600',
                ],
                [
                    'nama' => 'Ibu Siti Rahmawati',
                    'peran' => 'Admin Zakat — Masjid Raya At-Taqwa',
                    'isi' => 'Sangat mudah digunakan! Data mustahik lengkap, bisa filter berdasarkan asnaf. Export ke Excel dan PDF-nya juga tampilan profesional, langsung bisa diserahkan ke pengurus.',
                    'bintang' => 5,
                    'inisial' => 'SR',
                    'warna' => 'bg-accent-700',
                ],
            ];
            @endphp

            @foreach($testimoni as $t)
            <div class="bg-neutral-50 rounded-2xl border border-neutral-200 p-6 hover:shadow-card-hover hover:border-primary-200 transition-all duration-300">
                {{-- Stars --}}
                <div class="flex gap-1 mb-4">
                    @for($i = 0; $i < $t['bintang']; $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-warning" fill="currentColor" viewBox="0 0 20 20" style="color:#ff9800">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    @endfor
                </div>
                {{-- Quote --}}
                <p class="text-sm text-neutral-600 leading-relaxed mb-5 italic">"{{ $t['isi'] }}"</p>
                {{-- Author --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $t['warna'] }} rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ $t['inisial'] }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-neutral-900">{{ $t['nama'] }}</p>
                        <p class="text-xs text-neutral-400">{{ $t['peran'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ================================================================
     SECTION 6: CTA / KONTAK
     ================================================================ --}}
<section id="kontak" class="py-20 lg:py-24 bg-primary-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-nz-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-4 leading-tight">
            Siap Memulai Pengelolaan<br>Zakat yang Lebih Baik?
        </h2>
        <p class="text-primary-200 text-base max-w-xl mx-auto mb-10 leading-relaxed">
            Bergabung bersama 500+ lembaga yang sudah mempercayakan pengelolaan zakatnya kepada Niat Zakat Digital.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-primary-600 font-bold text-sm rounded-xl hover:bg-primary-50 shadow-nz-lg transition-all duration-200 hover:-translate-y-0.5">
                Daftar Gratis Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
            <a href="mailto:admin@niatzakat.id"
               class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-transparent text-white font-semibold text-sm rounded-xl border border-primary-300 hover:bg-primary-600 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Hubungi Kami
            </a>
        </div>
    </div>
</section>