{{-- resources/views/partials/landing/sections/laporan.blade.php --}}
{{--
    PROPS yang dikirim dari LandingController:
    $laporanPublished = collection LaporanKeuanganLembaga (status=published, with lembaga)
--}}

@php
    $hasData = isset($laporanPublished) && $laporanPublished->count() > 0;
@endphp

<section id="laporan-landing" class="relative py-20 lg:py-28 overflow-hidden bg-gradient-to-b from-white to-green-50/40">

    {{-- Dekorasi background --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-100/50 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -left-16 w-72 h-72 bg-emerald-100/40 rounded-full blur-2xl"></div>
        {{-- Grid dot pattern --}}
        <svg class="absolute inset-0 w-full h-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lap-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="#16a34a"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#lap-dots)"/>
        </svg>
    </div>

    <div class="relative w-full px-4 sm:px-10 lg:px-20">

        {{-- ── Header ────────────────────────────────────── --}}
        <div class="text-center mb-12 nz-reveal">
            <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200/70 rounded-full px-4 py-1.5 text-sm font-medium text-green-700 mb-4">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Keuangan
            </div>
            <h2 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-3 tracking-tight">
                Transparansi Keuangan <span class="text-green-600">Lembaga Zakat</span>
            </h2>
            <p class="text-neutral-500 text-base max-w-xl mx-auto">
                Laporan keuangan resmi yang telah dipublikasikan oleh lembaga-lembaga zakat terdaftar — terbuka dan dapat diakses oleh siapa saja.
            </p>
        </div>

        @if($hasData)
            {{-- ── Grid Card Laporan ──────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
                @foreach($laporanPublished->take(3) as $laporan)
                    @php
                        $bulanNama = [
                            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                            9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                        ][$laporan->bulan] ?? '-';
                        $rasio = $laporan->total_penerimaan > 0
                            ? round(($laporan->total_penyaluran / $laporan->total_penerimaan) * 100)
                            : 0;
                        $rasio = min($rasio, 100);
                        $lembagaNama = $laporan->lembaga->nama ?? 'Lembaga';
                        $lembagaKode = $laporan->lembaga->kode_lembaga ?? '';
                        $kota        = $laporan->lembaga->kota_nama ?? '';
                        $publishedAt = $laporan->published_at
                            ? \Carbon\Carbon::parse($laporan->published_at)->isoFormat('DD MMM YYYY')
                            : '';
                    @endphp

                    <article class="lap-card nz-reveal group bg-white border border-neutral-100 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden flex flex-col">

                        {{-- Header Card --}}
                        <div class="p-5 border-b border-neutral-50 flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                    @if($lembagaKode)
                                        <span class="text-[10px] font-semibold tracking-widest text-green-700 bg-green-50 border border-green-200/60 rounded-full px-2 py-0.5 uppercase">
                                            {{ $lembagaKode }}
                                        </span>
                                    @endif
                                    <span class="text-xs font-medium text-neutral-400 bg-neutral-50 rounded-full px-2 py-0.5">
                                        {{ $bulanNama }} {{ $laporan->tahun }}
                                    </span>
                                </div>
                                <h3 class="font-semibold text-neutral-800 text-sm leading-snug line-clamp-2 group-hover:text-green-700 transition-colors">
                                    {{ $lembagaNama }}
                                </h3>
                                @if($kota)
                                    <p class="text-xs text-neutral-400 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $kota }}
                                    </p>
                                @endif
                            </div>
                            {{-- Published badge --}}
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/60 rounded-full px-2 py-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                                    Published
                                </span>
                            </div>
                        </div>

                        {{-- Body: Angka Keuangan --}}
                        <div class="p-5 flex-1 flex flex-col gap-4">

                            {{-- Penerimaan & Penyaluran --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-green-50/60 rounded-xl p-3">
                                    <p class="text-[10px] font-medium text-green-600 mb-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                        </svg>
                                        Penerimaan
                                    </p>
                                    <p class="text-sm font-bold text-green-700 leading-tight">
                                        Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="bg-orange-50/60 rounded-xl p-3">
                                    <p class="text-[10px] font-medium text-orange-600 mb-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                        Penyaluran
                                    </p>
                                    <p class="text-sm font-bold text-orange-700 leading-tight">
                                        Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Saldo Akhir --}}
                            <div class="flex items-center justify-between bg-neutral-50 rounded-xl px-4 py-3">
                                <div>
                                    <p class="text-[10px] font-medium text-neutral-500 mb-0.5">Saldo Akhir</p>
                                    <p class="text-sm font-bold {{ $laporan->saldo_akhir >= 0 ? 'text-neutral-800' : 'text-red-600' }}">
                                        Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-medium text-neutral-500 mb-0.5">Rasio Salur</p>
                                    <p class="text-sm font-bold text-neutral-700">{{ $rasio }}%</p>
                                </div>
                            </div>

                            {{-- Progress Bar Rasio --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] text-neutral-400 font-medium">Progress Penyaluran</span>
                                    <span class="text-[10px] font-semibold {{ $rasio >= 80 ? 'text-green-600' : ($rasio >= 50 ? 'text-amber-600' : 'text-neutral-500') }}">{{ $rasio }}%</span>
                                </div>
                                <div class="h-2 bg-neutral-100 rounded-full overflow-hidden">
                                    <div
                                        class="h-full rounded-full transition-all duration-700 {{ $rasio >= 80 ? 'bg-green-500' : ($rasio >= 50 ? 'bg-amber-400' : 'bg-neutral-300') }}"
                                        style="width: {{ $rasio }}%"
                                    ></div>
                                </div>
                            </div>

                            {{-- Muzakki & Mustahik --}}
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-neutral-400">Muzakki</p>
                                        <p class="text-xs font-semibold text-neutral-700">{{ number_format($laporan->jumlah_muzakki) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-neutral-400">Mustahik</p>
                                        <p class="text-xs font-semibold text-neutral-700">{{ number_format($laporan->jumlah_mustahik) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Card --}}
                        <div class="px-5 py-3.5 border-t border-neutral-50 flex items-center justify-between">
                            <span class="text-[10px] text-neutral-400 flex items-center gap-1">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $publishedAt }}
                            </span>
                            <a href="{{ route('laporan.index') }}"
                               class="text-xs font-semibold text-green-600 hover:text-green-700 flex items-center gap-1 transition-colors">
                                Lihat detail
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- CTA Lihat Semua --}}
            <div class="text-center nz-reveal">
                <a href="{{ route('laporan.index') }}"
                   class="inline-flex items-center gap-2 px-7 py-3 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 shadow-md shadow-green-200 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5">
                    Lihat Semua Laporan
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        @else
            {{-- Empty state --}}
            <div class="text-center py-16 nz-reveal">
                <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-neutral-400 text-sm">Belum ada laporan yang dipublikasikan.</p>
            </div>
        @endif
    </div>
</section>