@extends('layouts.guest')

@section('title', 'Hitung Zakat — Niat Zakat')
@section('meta_description', 'Hitung zakat penghasilan, maal, dan fitrah Anda dengan mudah dan akurat bersama Niat Zakat.')

@section('styles')
<style>
    .nz-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #525252;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.375rem;
    }

    .nz-input {
        width: 100%;
        border: 1.5px solid #e5e5e5;
        border-radius: 0.625rem;
        padding: 0.625rem 0.875rem 0.625rem 2.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: #171717;
        background: #fafafa;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        outline: none;
    }

    .nz-input:focus {
        border-color: #16a34a;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.10);
    }

    .nz-prefix {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        font-weight: 600;
        color: #a3a3a3;
        pointer-events: none;
    }

    .nz-tab { color: #737373; cursor: pointer; white-space: nowrap; }
    .nz-tab:hover { color: #171717; background: #f5f5f5; }
    .nz-tab-active { background: #16a34a !important; color: #fff !important; box-shadow: 0 2px 10px rgba(22,163,74,0.25); }

    .nz-panel { display: none; }
    .nz-panel-active { display: block; }

    .nz-period-btn, .nz-fitrah-btn {
        flex: 1;
        padding: 0.45rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all 0.2s;
        cursor: pointer;
        color: #737373;
        border: none;
        background: transparent;
    }

    .nz-period-btn-active, .nz-fitrah-btn-active {
        background: #16a34a;
        color: #fff;
        box-shadow: 0 1px 6px rgba(22,163,74,0.2);
    }

    .nz-result-card {
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
    }

    .nz-status {
        border-radius: 0.75rem;
        padding: 0.875rem 1.125rem;
        font-size: 0.8rem;
        font-weight: 500;
        line-height: 1.6;
    }

    .nz-status-empty { background: #fefce8; border: 1.5px solid #fef08a; color: #713f12; }
    .nz-status-ok    { background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #14532d; }
    .nz-status-no    { background: #f5f5f5; border: 1.5px solid #e5e5e5; color: #525252; }

    .nz-stepper-btn {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.5rem;
        background: #f5f5f5;
        border: 1.5px solid #e5e5e5;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s;
        font-weight: 700;
        font-size: 1.1rem;
        color: #404040;
        flex-shrink: 0;
        user-select: none;
    }

    .nz-stepper-btn:hover { background: #e5e5e5; }
    .nz-divider { height: 1px; background: #f0f0f0; margin: 1.25rem 0; }
</style>
@endsection

@section('content')

@include('partials.landing.page-hero', [
    'breadcrumb'    => 'Hitung Zakat',
    'badge'         => 'Kalkulator Zakat',
    'heroTitle'     => 'Hitung Zakat Anda',
    'heroHighlight' => 'dengan Mudah & Akurat',
    'heroSubtitle'  => 'Ketahui besaran zakat yang wajib Anda tunaikan. Nisab dihitung berdasarkan harga emas terkini sesuai ketentuan BAZNAS.'
])

{{-- ── KALKULATOR ───────────────────────────────────────────────────── --}}
<section class="py-14 bg-neutral-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Tab Navigation --}}
        <div class="flex justify-center mb-10">
            <div class="inline-flex bg-white border border-neutral-200 rounded-xl p-1 gap-1 shadow-sm">
                <button onclick="nzSwitchTab('penghasilan')" id="nz-tab-penghasilan"
                    class="nz-tab nz-tab-active px-5 sm:px-7 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200">
                    Zakat Penghasilan
                </button>
                <button onclick="nzSwitchTab('maal')" id="nz-tab-maal"
                    class="nz-tab px-5 sm:px-7 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200">
                    Zakat Maal
                </button>
                <button onclick="nzSwitchTab('fitrah')" id="nz-tab-fitrah"
                    class="nz-tab px-5 sm:px-7 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200">
                    Zakat Fitrah
                </button>
            </div>
        </div>

        {{-- Card Kalkulator --}}
        <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden"
            style="box-shadow: 0 8px 40px rgba(0,0,0,0.07), 0 1px 3px rgba(0,0,0,0.05)">

            {{-- ══════════════════════════════════════════════ --}}
            {{-- PANEL: ZAKAT PENGHASILAN                      --}}
            {{-- ══════════════════════════════════════════════ --}}
            <div id="nz-panel-penghasilan" class="nz-panel nz-panel-active">
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">

                    <div class="p-8">
                        <h3 class="text-base font-bold text-neutral-800 mb-1">Komponen Penghasilan</h3>
                        <p class="text-xs text-neutral-400 mb-6 leading-relaxed">
                            Masukkan total penghasilan dan kebutuhan pokok untuk mengetahui kewajiban zakat.
                        </p>

                        {{-- Toggle periode --}}
                        <div class="flex bg-neutral-100 rounded-lg p-1 mb-6 gap-1">
                            <button onclick="nzSetPeriode('bulan')" id="nz-pg-bulan"
                                class="nz-period-btn nz-period-btn-active">Per Bulan</button>
                            <button onclick="nzSetPeriode('tahun')" id="nz-pg-tahun"
                                class="nz-period-btn">Per Tahun</button>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="nz-label">Gaji / Penghasilan Utama</label>
                                <div class="relative">
                                    <span class="nz-prefix">Rp</span>
                                    <input type="text" id="pg-gaji" placeholder="0" class="nz-input"
                                        oninput="nzFormatRp(this); nzHitungPenghasilan()">
                                </div>
                            </div>
                            <div>
                                <label class="nz-label">Penghasilan Lain-lain
                                    <span class="text-neutral-300 normal-case font-normal ml-1">(opsional)</span>
                                </label>
                                <div class="relative">
                                    <span class="nz-prefix">Rp</span>
                                    <input type="text" id="pg-lain" placeholder="0" class="nz-input"
                                        oninput="nzFormatRp(this); nzHitungPenghasilan()">
                                </div>
                            </div>
                            <div>
                                <label class="nz-label">Kebutuhan Pokok / Cicilan
                                    <span class="text-neutral-300 normal-case font-normal ml-1">(opsional)</span>
                                </label>
                                <div class="relative">
                                    <span class="nz-prefix">Rp</span>
                                    <input type="text" id="pg-kebutuhan" placeholder="0" class="nz-input"
                                        oninput="nzFormatRp(this); nzHitungPenghasilan()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 flex flex-col">
                        <h3 class="text-base font-bold text-neutral-800 mb-6">Hasil Kalkulasi</h3>

                        <div class="nz-result-card mb-4">
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Total Penghasilan</span>
                                    <span class="font-semibold text-neutral-800" id="pg-r-total">Rp 0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Kebutuhan Pokok</span>
                                    <span class="font-semibold text-neutral-800" id="pg-r-kebutuhan">Rp 0</span>
                                </div>
                                <div class="nz-divider"></div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Penghasilan Bersih</span>
                                    <span class="font-bold text-neutral-900" id="pg-r-bersih">Rp 0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Nisab <span id="pg-r-nisab-label">(per bulan)</span></span>
                                    <span class="font-semibold text-neutral-800" id="pg-r-nisab">Rp {{ number_format($nisabBulanan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="nz-result-card mb-4 bg-primary-50 border-primary-200">
                            <p class="text-xs text-neutral-500 mb-1 font-medium uppercase tracking-wide">Zakat yang Harus Dibayar</p>
                            <p class="text-2xl font-extrabold text-primary-600" id="pg-r-zakat">Rp 0</p>
                            <p class="text-xs text-neutral-400 mt-1" id="pg-r-zakat-info">2.5% dari penghasilan bersih</p>
                        </div>

                        <div id="pg-status" class="nz-status nz-status-empty mb-6">
                            Masukkan data penghasilan untuk menghitung kewajiban zakat Anda.
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                Bayar Zakat Sekarang
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- PANEL: ZAKAT MAAL                             --}}
            {{-- ══════════════════════════════════════════════ --}}
            <div id="nz-panel-maal" class="nz-panel">
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">

                    <div class="p-8">
                        <h3 class="text-base font-bold text-neutral-800 mb-1">Komponen Harta</h3>
                        <p class="text-xs text-neutral-400 mb-7 leading-relaxed">
                            Harta yang telah mencapai nisab dan dimiliki selama 1 tahun (haul) wajib dizakati.
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="nz-label">Tabungan / Deposito</label>
                                <div class="relative">
                                    <span class="nz-prefix">Rp</span>
                                    <input type="text" id="ml-tabungan" placeholder="0" class="nz-input"
                                        oninput="nzFormatRp(this); nzHitungMaal()">
                                </div>
                            </div>
                            <div>
                                <label class="nz-label">Emas / Perhiasan (nilai pasar)</label>
                                <div class="relative">
                                    <span class="nz-prefix">Rp</span>
                                    <input type="text" id="ml-emas" placeholder="0" class="nz-input"
                                        oninput="nzFormatRp(this); nzHitungMaal()">
                                </div>
                            </div>
                            <div>
                                <label class="nz-label">Properti Investasi
                                    <span class="text-neutral-300 normal-case font-normal ml-1">(opsional)</span>
                                </label>
                                <div class="relative">
                                    <span class="nz-prefix">Rp</span>
                                    <input type="text" id="ml-properti" placeholder="0" class="nz-input"
                                        oninput="nzFormatRp(this); nzHitungMaal()">
                                </div>
                            </div>
                            <div>
                                <label class="nz-label">Saham / Piutang
                                    <span class="text-neutral-300 normal-case font-normal ml-1">(opsional)</span>
                                </label>
                                <div class="relative">
                                    <span class="nz-prefix">Rp</span>
                                    <input type="text" id="ml-saham" placeholder="0" class="nz-input"
                                        oninput="nzFormatRp(this); nzHitungMaal()">
                                </div>
                            </div>
                            <div>
                                <label class="nz-label">Hutang / Kewajiban
                                    <span class="text-neutral-300 normal-case font-normal ml-1">(dikurangkan)</span>
                                </label>
                                <div class="relative">
                                    <span class="nz-prefix">Rp</span>
                                    <input type="text" id="ml-hutang" placeholder="0" class="nz-input"
                                        oninput="nzFormatRp(this); nzHitungMaal()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 flex flex-col">
                        <h3 class="text-base font-bold text-neutral-800 mb-6">Hasil Kalkulasi</h3>

                        <div class="nz-result-card mb-4">
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Total Aset</span>
                                    <span class="font-semibold text-neutral-800" id="ml-r-aset">Rp 0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Total Hutang</span>
                                    <span class="font-semibold text-neutral-800" id="ml-r-hutang">Rp 0</span>
                                </div>
                                <div class="nz-divider"></div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Harta Bersih</span>
                                    <span class="font-bold text-neutral-900" id="ml-r-bersih">Rp 0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Nisab (85gr emas)</span>
                                    <span class="font-semibold text-neutral-800">Rp {{ number_format($nisabMaal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="nz-result-card mb-4 bg-primary-50 border-primary-200">
                            <p class="text-xs text-neutral-500 mb-1 font-medium uppercase tracking-wide">Zakat yang Harus Dibayar</p>
                            <p class="text-2xl font-extrabold text-primary-600" id="ml-r-zakat">Rp 0</p>
                            <p class="text-xs text-neutral-400 mt-1">2.5% dari harta bersih</p>
                        </div>

                        <div id="ml-status" class="nz-status nz-status-empty mb-6">
                            Masukkan data harta untuk menghitung kewajiban zakat Anda.
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                Bayar Zakat Sekarang
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- PANEL: ZAKAT FITRAH                           --}}
            {{-- ══════════════════════════════════════════════ --}}
            <div id="nz-panel-fitrah" class="nz-panel">
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">

                    <div class="p-8">
                        <h3 class="text-base font-bold text-neutral-800 mb-1">Jumlah Jiwa</h3>
                        <p class="text-xs text-neutral-400 mb-7 leading-relaxed">
                            Zakat fitrah wajib dikeluarkan untuk setiap jiwa yang ditanggung sebelum Idul Fitri.
                        </p>

                        {{-- Stepper --}}
                        <div class="mb-7">
                            <label class="nz-label mb-3">Jumlah Tanggungan</label>
                            <div class="flex items-center gap-4 mt-3">
                                <button class="nz-stepper-btn" onclick="nzStepFitrah(-1)">−</button>
                                <span id="ft-jiwa-display"
                                    class="text-3xl font-extrabold text-neutral-800 w-12 text-center tabular-nums">1</span>
                                <button class="nz-stepper-btn" onclick="nzStepFitrah(1)">+</button>
                                <span class="text-sm text-neutral-400 font-medium">jiwa</span>
                            </div>
                        </div>

                        {{-- Toggle jenis --}}
                        <div class="mb-7">
                            <label class="nz-label mb-3">Jenis Pembayaran</label>
                            <div class="flex bg-neutral-100 rounded-lg p-1 gap-1 mt-3">
                                <button onclick="nzSetFitrahJenis('uang')" id="nz-ft-uang"
                                    class="nz-fitrah-btn nz-fitrah-btn-active">Uang</button>
                                <button onclick="nzSetFitrahJenis('beras')" id="nz-ft-beras"
                                    class="nz-fitrah-btn">Beras</button>
                            </div>
                        </div>

                        <div class="bg-neutral-50 border border-neutral-200 rounded-xl px-5 py-4 text-sm">
                            <p class="text-neutral-400 mb-1 text-xs">Kewajiban per jiwa:</p>
                            <p class="font-bold text-neutral-800" id="ft-perjijwa-display">Rp 50.000</p>
                        </div>
                    </div>

                    <div class="p-8 flex flex-col">
                        <h3 class="text-base font-bold text-neutral-800 mb-6">Hasil Kalkulasi</h3>

                        <div class="nz-result-card mb-4">
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Jumlah Jiwa</span>
                                    <span class="font-semibold text-neutral-800" id="ft-r-jiwa">1 jiwa</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Kewajiban per Jiwa</span>
                                    <span class="font-semibold text-neutral-800" id="ft-r-perjiwanominal">Rp 50.000</span>
                                </div>
                                <div class="nz-divider"></div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-500 font-medium">Total Zakat Fitrah</span>
                                    <span class="font-bold text-neutral-900" id="ft-r-total-label">Rp 50.000</span>
                                </div>
                            </div>
                        </div>

                        <div class="nz-result-card mb-4 bg-primary-50 border-primary-200">
                            <p class="text-xs text-neutral-500 mb-1 font-medium uppercase tracking-wide">Total yang Harus Dibayar</p>
                            <p class="text-2xl font-extrabold text-primary-600" id="ft-r-zakat">Rp 50.000</p>
                            <p class="text-xs text-neutral-400 mt-1" id="ft-r-info">untuk 1 jiwa</p>
                        </div>

                        <div class="bg-amber-50 border border-amber-100 rounded-xl px-5 py-4 text-xs text-amber-800 leading-relaxed mb-6">
                            Zakat fitrah wajib ditunaikan sebelum shalat Idul Fitri. Dapat dibayarkan dalam bentuk uang senilai 2,5 kg beras atau beras 2,5 kg langsung per jiwa.
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                Bayar Zakat Fitrah
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>{{-- end card --}}

        <p class="text-center text-xs text-neutral-400 mt-6 leading-relaxed">
            Kalkulator ini bersifat estimasi. Untuk kepastian hukum, konsultasikan dengan amil zakat atau ulama setempat.
        </p>

    </div>
</section>

@endsection

@section('scripts')
<script>
(function () {
    const HARGA_EMAS = {{ $hargaEmasPerGram ?? 1900000 }};
    const NISAB_MAAL = HARGA_EMAS * 85;
    const NISAB_BLN  = Math.round(NISAB_MAAL / 12);
    const NISAB_THN  = NISAB_MAAL;
    const ZAKAT_PCT  = 0.025;
    const FITRAH_RP  = 50000;
    const FITRAH_KG  = 2.5;

    let pgPeriode = 'bulan';
    let ftJiwa    = 1;
    let ftJenis   = 'uang';

    function rp(n) {
        if (!n || isNaN(n)) return 'Rp 0';
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }

    function parseRp(el) {
        return parseFloat((el.value || '0').replace(/\./g, '').replace(',', '.')) || 0;
    }

    function setStatus(id, state, msg) {
        const el = document.getElementById(id);
        el.className = 'nz-status nz-status-' + state;
        el.textContent = msg;
    }

    window.nzFormatRp = function (el) {
        const raw = el.value.replace(/\D/g, '');
        el.value = raw ? parseInt(raw, 10).toLocaleString('id-ID') : '';
    };

    window.nzSwitchTab = function (tab) {
        ['penghasilan', 'maal', 'fitrah'].forEach(t => {
            document.getElementById('nz-panel-' + t).classList.toggle('nz-panel-active', t === tab);
            document.getElementById('nz-tab-' + t).classList.toggle('nz-tab-active', t === tab);
        });
    };

    // ── Penghasilan ──────────────────────────────────────────────
    window.nzSetPeriode = function (p) {
        pgPeriode = p;
        document.getElementById('nz-pg-bulan').classList.toggle('nz-period-btn-active', p === 'bulan');
        document.getElementById('nz-pg-tahun').classList.toggle('nz-period-btn-active', p === 'tahun');
        document.getElementById('pg-r-nisab-label').textContent = p === 'bulan' ? '(per bulan)' : '(per tahun)';
        nzHitungPenghasilan();
    };

    window.nzHitungPenghasilan = function () {
        const gaji      = parseRp(document.getElementById('pg-gaji'));
        const lain      = parseRp(document.getElementById('pg-lain'));
        const kebutuhan = parseRp(document.getElementById('pg-kebutuhan'));
        const total     = gaji + lain;
        const bersih    = Math.max(0, total - kebutuhan);
        const nisab     = pgPeriode === 'bulan' ? NISAB_BLN : NISAB_THN;
        const zakat     = bersih >= nisab ? bersih * ZAKAT_PCT : 0;

        document.getElementById('pg-r-total').textContent     = rp(total);
        document.getElementById('pg-r-kebutuhan').textContent = rp(kebutuhan);
        document.getElementById('pg-r-bersih').textContent    = rp(bersih);
        document.getElementById('pg-r-nisab').textContent     = rp(nisab);
        document.getElementById('pg-r-zakat').textContent     = rp(zakat);
        document.getElementById('pg-r-zakat-info').textContent =
            '2.5% dari penghasilan bersih ' + (pgPeriode === 'bulan' ? 'per bulan' : 'per tahun');

        if (total === 0) {
            setStatus('pg-status', 'empty', 'Masukkan data penghasilan untuk menghitung kewajiban zakat Anda.');
        } else if (bersih < nisab) {
            setStatus('pg-status', 'no', 'Penghasilan bersih belum mencapai nisab. Tidak wajib zakat, namun Anda tetap bisa berinfak.');
        } else {
            setStatus('pg-status', 'ok', 'Penghasilan Anda telah mencapai nisab. Wajib membayar zakat sebesar ' + rp(zakat) + (pgPeriode === 'bulan' ? ' per bulan.' : ' per tahun.'));
        }
    };

    // ── Maal ─────────────────────────────────────────────────────
    window.nzHitungMaal = function () {
        const tabungan = parseRp(document.getElementById('ml-tabungan'));
        const emas     = parseRp(document.getElementById('ml-emas'));
        const properti = parseRp(document.getElementById('ml-properti'));
        const saham    = parseRp(document.getElementById('ml-saham'));
        const hutang   = parseRp(document.getElementById('ml-hutang'));
        const aset     = tabungan + emas + properti + saham;
        const bersih   = Math.max(0, aset - hutang);
        const zakat    = bersih >= NISAB_MAAL ? bersih * ZAKAT_PCT : 0;

        document.getElementById('ml-r-aset').textContent   = rp(aset);
        document.getElementById('ml-r-hutang').textContent = rp(hutang);
        document.getElementById('ml-r-bersih').textContent = rp(bersih);
        document.getElementById('ml-r-zakat').textContent  = rp(zakat);

        if (aset === 0) {
            setStatus('ml-status', 'empty', 'Masukkan data harta untuk menghitung kewajiban zakat Anda.');
        } else if (bersih < NISAB_MAAL) {
            setStatus('ml-status', 'no', 'Harta bersih belum mencapai nisab. Tidak wajib zakat, namun Anda tetap bisa berinfak.');
        } else {
            setStatus('ml-status', 'ok', 'Harta Anda mencapai nisab. Jika sudah melewati haul (1 tahun), wajib membayar zakat sebesar ' + rp(zakat) + '.');
        }
    };

    // ── Fitrah ───────────────────────────────────────────────────
    window.nzStepFitrah = function (d) {
        ftJiwa = Math.max(1, Math.min(100, ftJiwa + d));
        updateFitrah();
    };

    window.nzSetFitrahJenis = function (j) {
        ftJenis = j;
        document.getElementById('nz-ft-uang').classList.toggle('nz-fitrah-btn-active', j === 'uang');
        document.getElementById('nz-ft-beras').classList.toggle('nz-fitrah-btn-active', j === 'beras');
        updateFitrah();
    };

    function updateFitrah() {
        document.getElementById('ft-jiwa-display').textContent = ftJiwa;
        document.getElementById('ft-r-jiwa').textContent = ftJiwa + ' jiwa';

        if (ftJenis === 'uang') {
            const total = ftJiwa * FITRAH_RP;
            document.getElementById('ft-perjijwa-display').textContent  = rp(FITRAH_RP);
            document.getElementById('ft-r-perjiwanominal').textContent  = rp(FITRAH_RP);
            document.getElementById('ft-r-zakat').textContent           = rp(total);
            document.getElementById('ft-r-total-label').textContent     = rp(total);
            document.getElementById('ft-r-info').textContent            = 'untuk ' + ftJiwa + ' jiwa';
        } else {
            const totalKg = ftJiwa * FITRAH_KG;
            document.getElementById('ft-perjijwa-display').textContent  = FITRAH_KG + ' kg beras';
            document.getElementById('ft-r-perjiwanominal').textContent  = FITRAH_KG + ' kg beras';
            document.getElementById('ft-r-zakat').textContent           = totalKg.toFixed(1) + ' kg beras';
            document.getElementById('ft-r-total-label').textContent     = totalKg.toFixed(1) + ' kg beras';
            document.getElementById('ft-r-info').textContent            = 'untuk ' + ftJiwa + ' jiwa';
        }
    }

    updateFitrah();
})();
</script>
@endsection