@extends('layouts.guest')

@section('title', 'Hitung Zakat — Niat Zakat')
@section('meta_description', 'Hitung zakat penghasilan, maal, fitrah, pertanian, peternakan, rikaz, dan perniagaan dengan mudah dan akurat bersama Niat Zakat.')

@section('styles')
<style>
    /* ── Base Elements ───────────────────────────────── */
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
        border: 1.5px solid #d1d5db;
        border-radius: 0.625rem;
        padding: 0.625rem 0.875rem 0.625rem 2.75rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: #171717;
        background: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .nz-input:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,0.10);
    }
    .nz-input::placeholder { color: #9ca3af; }

    .nz-input-plain {
        width: 100%;
        border: 1.5px solid #d1d5db;
        border-radius: 0.625rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: #171717;
        background: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .nz-input-plain:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,0.10);
    }
    .nz-input-plain::placeholder { color: #9ca3af; }

    .nz-input-auto {
        width: 100%;
        border: 1.5px solid #bbf7d0;
        border-radius: 0.625rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #15803d;
        background: #f0fdf4;
        outline: none;
        cursor: default;
    }

    .nz-select {
        width: 100%;
        border: 1.5px solid #d1d5db;
        border-radius: 0.625rem;
        padding: 0.625rem 2.25rem 0.625rem 0.875rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: #171717;
        background: #ffffff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E") no-repeat right 0.65rem center / 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
    }
    .nz-select:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,0.10);
    }

    .nz-prefix {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        pointer-events: none;
    }
    .nz-suffix {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        font-weight: 600;
        color: #9ca3af;
        pointer-events: none;
    }

    /* ── Tab ─────────────────────────────────────────── */
    .nz-tab-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
        justify-content: center;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.875rem;
        padding: 0.375rem;
        box-shadow: 0 1px 6px rgba(0,0,0,0.05);
    }
    .nz-tab {
        color: #6b7280;
        cursor: pointer;
        white-space: nowrap;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.45rem 1rem;
        border-radius: 0.5rem;
        border: none;
        background: transparent;
        transition: all 0.15s;
    }
    .nz-tab:hover { color: #171717; background: #f5f5f5; }
    .nz-tab-active { background: #16a34a !important; color: #fff !important; box-shadow: 0 2px 8px rgba(22,163,74,0.25); }

    /* ── Panel ───────────────────────────────────────── */
    .nz-panel { display: none; }
    .nz-panel-active { display: block; }

    /* ── Toggle Buttons ──────────────────────────────── */
    .nz-toggle-wrap { display: flex; background: #f3f4f6; border-radius: 0.625rem; padding: 0.25rem; gap: 0.25rem; }
    .nz-toggle-btn {
        flex: 1;
        padding: 0.4rem 0.65rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 0.45rem;
        transition: all 0.15s;
        cursor: pointer;
        color: #6b7280;
        border: none;
        background: transparent;
    }
    .nz-toggle-btn-active {
        background: #16a34a;
        color: #fff;
        box-shadow: 0 1px 5px rgba(22,163,74,0.22);
    }

    /* ── Stepper ─────────────────────────────────────── */
    .nz-stepper-btn {
        width: 2.1rem; height: 2.1rem;
        border-radius: 0.45rem;
        background: #f5f5f5;
        border: 1.5px solid #e5e5e5;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background 0.12s;
        font-weight: 700; font-size: 1rem; color: #404040;
        flex-shrink: 0; user-select: none;
    }
    .nz-stepper-btn:hover { background: #e5e5e5; }

    /* ── Result Card ─────────────────────────────────── */
    .nz-result-card {
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.1rem 1.35rem;
    }

    /* ── Calc Rows ───────────────────────────────────── */
    .nz-calc-row {
        display: flex; justify-content: space-between; align-items: baseline;
        font-size: 0.82rem; padding: 0.2rem 0;
    }
    .nz-calc-row .lbl { color: #6b7280; line-height: 1.4; }
    .nz-calc-row .val { font-weight: 600; color: #374151; text-align: right; min-width: max-content; padding-left: 0.5rem; }
    .nz-calc-row.hl .lbl { color: #374151; font-weight: 600; }
    .nz-calc-row.hl .val { color: #111827; font-weight: 700; }

    /* ── Section Divider ─────────────────────────────── */
    .nz-sec {
        display: flex; align-items: center; gap: 0.5rem;
        margin: 0.8rem 0 0.45rem;
    }
    .nz-sec span {
        font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.07em;
        color: #9ca3af; white-space: nowrap;
    }
    .nz-sec::before, .nz-sec::after { content:''; flex:1; height:1px; background:#e5e7eb; }

    /* ── Divider ─────────────────────────────────────── */
    .nz-div { height:1px; background:#e5e7eb; margin:0.6rem 0; }

    /* ── Formula Box ─────────────────────────────────── */
    .nz-formula {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-left: 3px solid #16a34a;
        border-radius: 0.625rem;
        padding: 0.8rem 1rem;
        margin-bottom: 1.25rem;
    }
    .nz-formula-title {
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.07em;
        color: #16a34a; margin-bottom: 0.4rem;
    }
    .nz-formula-text {
        font-size: 0.78rem; color: #475569; line-height: 1.7;
    }
    .nz-formula-text strong { color: #1e293b; }

    /* ── Zakat Result ─────────────────────────────────── */
    .nz-zakat-result {
        background: #f0fdf4;
        border: 2px solid #16a34a;
        border-radius: 1rem;
        padding: 1.1rem 1.35rem;
        margin-bottom: 0.875rem;
    }

    /* ── Status ──────────────────────────────────────── */
    .nz-status {
        border-radius: 0.625rem;
        padding: 0.75rem 1rem;
        font-size: 0.79rem;
        font-weight: 500;
        line-height: 1.55;
        margin-bottom: 0.875rem;
    }
    .nz-s-empty { background:#fefce8; border:1.5px solid #fef08a; color:#713f12; }
    .nz-s-ok    { background:#f0fdf4; border:1.5px solid #bbf7d0; color:#14532d; }
    .nz-s-no    { background:#f5f5f5; border:1.5px solid #e5e5e5; color:#525252; }
</style>
@endsection

@section('content')

@include('partials.landing.page-hero', [
    'breadcrumb'    => 'Hitung Zakat',
    'badge'         => 'Kalkulator Zakat',
    'heroTitle'     => 'Hitung Zakat Anda',
    'heroHighlight' => 'dengan Mudah & Akurat',
    'heroSubtitle'  => 'Ketahui besaran zakat yang wajib Anda tunaikan. Nisab dan kadar sesuai ketentuan syariat dan BAZNAS.'
])

<section class="pt-2 pb-14">
<div class="px-4 sm:px-10 lg:px-20">

    {{-- ── Tab Navigation ──────────────────────────────── --}}
    <div class="flex justify-center mb-8">
        <div class="nz-tab-wrap">
            <button onclick="nzTab('penghasilan')" id="t-penghasilan" class="nz-tab nz-tab-active">Penghasilan</button>
            <button onclick="nzTab('maal')"        id="t-maal"        class="nz-tab">Maal (Harta)</button>
            <button onclick="nzTab('fitrah')"      id="t-fitrah"      class="nz-tab">Fitrah</button>
            <button onclick="nzTab('pertanian')"   id="t-pertanian"   class="nz-tab">Pertanian</button>
            <button onclick="nzTab('ternak')"      id="t-ternak"      class="nz-tab">Hewan Ternak</button>
            <button onclick="nzTab('perniagaan')"  id="t-perniagaan"  class="nz-tab">Perniagaan</button>
            <button onclick="nzTab('rikaz')"       id="t-rikaz"       class="nz-tab">Rikaz</button>
        </div>
    </div>

    {{-- ── Card ───────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden"
        style="box-shadow:0 8px 40px rgba(0,0,0,0.07),0 1px 3px rgba(0,0,0,0.05)">


        {{-- ════════════════════════════════════════════════════ --}}
        {{-- PANEL 1: ZAKAT PENGHASILAN / PROFESI               --}}
        {{-- ════════════════════════════════════════════════════ --}}
        <div id="p-penghasilan" class="nz-panel nz-panel-active">
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">
            <div class="p-8">
                <h3 class="text-base font-bold text-neutral-800 mb-1">Zakat Penghasilan / Profesi</h3>
                <p class="text-xs text-neutral-400 mb-4 leading-relaxed">Wajib atas setiap penghasilan yang mencapai nisab setara 85 gram emas per tahun.</p>

                <div class="nz-formula">
                    <p class="nz-formula-title">Rumus Zakat Penghasilan</p>
                    <p class="nz-formula-text">
                        <strong>Nisab</strong> = 85 gram emas × harga emas/gram = Rp {{ number_format($nisabMaal,0,',','.') }}/tahun<br>
                        <strong>Penghasilan Bersih</strong> = Total Penghasilan &minus; Kebutuhan Pokok<br>
                        <strong>Zakat</strong> = 2,5% &times; Penghasilan Bersih<br>
                        <strong>Syarat</strong>: Penghasilan bersih &ge; Nisab (bulan atau tahun). Tidak disyaratkan haul.
                    </p>
                </div>

                <div class="flex mb-5">
                    <div class="nz-toggle-wrap w-full">
                        <button onclick="pgSetPeriode('bulan')" id="pg-btn-bln" class="nz-toggle-btn nz-toggle-btn-active">Per Bulan</button>
                        <button onclick="pgSetPeriode('tahun')" id="pg-btn-thn" class="nz-toggle-btn">Per Tahun</button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="nz-label">Gaji / Penghasilan Utama</label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pg-gaji" placeholder="Contoh: 5.000.000" class="nz-input" oninput="nzRp(this);pgHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Penghasilan Lain-lain <span class="text-neutral-300 normal-case font-normal">(opsional)</span></label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pg-lain" placeholder="Freelance, bonus, dll" class="nz-input" oninput="nzRp(this);pgHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Kebutuhan Pokok / Cicilan <span class="text-neutral-300 normal-case font-normal">(opsional)</span></label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pg-pokok" placeholder="Makan, transport, cicilan" class="nz-input" oninput="nzRp(this);pgHitung()"></div>
                    </div>
                </div>
            </div>

            <div class="p-8 flex flex-col">
                <h3 class="text-base font-bold text-neutral-800 mb-4">Hasil Kalkulasi</h3>
                <div class="nz-result-card mb-4">
                    <div class="nz-sec"><span>Komponen Penghasilan</span></div>
                    <div class="nz-calc-row"><span class="lbl">Gaji / Penghasilan Utama</span><span class="val" id="pg-r-gaji">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">+ Penghasilan Lain-lain</span><span class="val" id="pg-r-lain">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">&minus; Kebutuhan Pokok</span><span class="val" id="pg-r-pokok">Rp 0</span></div>
                    <div class="nz-div"></div>
                    <div class="nz-calc-row hl"><span class="lbl">Penghasilan Bersih</span><span class="val" id="pg-r-bersih">Rp 0</span></div>

                    <div class="nz-sec"><span>Nisab (85gr Emas)</span></div>
                    <div class="nz-calc-row"><span class="lbl">85 gr &times; Rp {{ number_format($hargaEmasPerGram,0,',','.') }}/gr</span><span class="val">Rp {{ number_format($nisabMaal,0,',','.') }}/tahun</span></div>
                    <div class="nz-calc-row hl"><span class="lbl">Nisab <span id="pg-r-nisab-lbl">(per bulan)</span></span><span class="val" id="pg-r-nisab">Rp {{ number_format($nisabBulanan,0,',','.') }}</span></div>

                    <div class="nz-sec"><span>Perhitungan Zakat</span></div>
                    <div class="nz-calc-row"><span class="lbl">Status Nisab</span><span class="val" id="pg-r-status">—</span></div>
                    <div class="nz-calc-row"><span class="lbl">Kadar Zakat</span><span class="val">2,5%</span></div>
                    <div class="nz-calc-row"><span class="lbl">Penghasilan Bersih &times; 2,5%</span><span class="val" id="pg-r-kalkulasi">—</span></div>
                </div>
                <div class="nz-zakat-result">
                    <p class="text-xs text-green-600 mb-1 font-bold uppercase tracking-wide">Zakat yang Harus Dibayar</p>
                    <p class="text-2xl font-extrabold text-green-700" id="pg-r-zakat">Rp 0</p>
                    <p class="text-xs text-green-600 mt-1" id="pg-r-info">2,5% dari penghasilan bersih per bulan</p>
                </div>
                <div class="mt-auto">@include('partials.zakat-btn')</div>
            </div>
        </div>
        </div>


        {{-- ════════════════════════════════════════════════════ --}}
        {{-- PANEL 2: ZAKAT MAAL (HARTA)                        --}}
        {{-- ════════════════════════════════════════════════════ --}}
        <div id="p-maal" class="nz-panel">
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">
            <div class="p-8">
                <h3 class="text-base font-bold text-neutral-800 mb-1">Zakat Maal (Harta)</h3>
                <p class="text-xs text-neutral-400 mb-4 leading-relaxed">Mencakup emas, perak, tabungan, investasi, dan properti yang telah mencapai nisab dan haul 1 tahun.</p>

                <div class="nz-formula">
                    <p class="nz-formula-title">Rumus Zakat Maal</p>
                    <p class="nz-formula-text">
                        <strong>Nisab Emas</strong> = 85 gram &times; harga emas = Rp {{ number_format($nisabMaal,0,',','.') }}<br>
                        <strong>Nisab Perak</strong> = 595 gram &times; harga perak/gram<br>
                        <strong>Harta Bersih</strong> = Total Aset &minus; Utang Jangka Pendek<br>
                        <strong>Zakat</strong> = 2,5% &times; Harta Bersih<br>
                        <strong>Haul</strong>: Harta sudah dimiliki &ge; 1 tahun hijriyah
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="nz-label">Emas Batangan / Perhiasan (gram)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative">
                                <input type="text" id="ml-emas-gr" placeholder="Gram (cth: 100)" class="nz-input-plain" oninput="nzNum(this);mlHitung()">
                                <span class="nz-suffix">gr</span>
                            </div>
                            <input type="text" id="ml-emas-val" class="nz-input-auto" readonly placeholder="Nilai (Rp otomatis)">
                        </div>
                        <p class="text-xs text-neutral-400 mt-1">Nilai = gram &times; Rp {{ number_format($hargaEmasPerGram,0,',','.') }}/gr (BAZNAS)</p>
                    </div>
                    <div>
                        <label class="nz-label">Perak (gram)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative">
                                <input type="text" id="ml-perak-gr" placeholder="Gram (cth: 600)" class="nz-input-plain" oninput="nzNum(this);mlHitung()">
                                <span class="nz-suffix">gr</span>
                            </div>
                            <input type="text" id="ml-perak-val" class="nz-input-auto" readonly placeholder="Nilai (Rp otomatis)">
                        </div>
                        <p class="text-xs text-neutral-400 mt-1">Nilai = gram &times; harga perak. Nisab perak 595 gram.</p>
                    </div>
                    <div>
                        <label class="nz-label">Harga Perak per Gram (Rp)</label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="ml-perak-harga" placeholder="Contoh: 12.000" class="nz-input" value="12.000" oninput="nzRp(this);mlHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Tabungan / Deposito</label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="ml-tabungan" placeholder="Saldo rekening bank" class="nz-input" oninput="nzRp(this);mlHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Investasi / Saham / Piutang <span class="text-neutral-300 normal-case font-normal">(opsional)</span></label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="ml-investasi" placeholder="Reksa dana, saham, piutang" class="nz-input" oninput="nzRp(this);mlHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Properti Investasi <span class="text-neutral-300 normal-case font-normal">(opsional)</span></label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="ml-properti" placeholder="Properti untuk disewakan/dijual" class="nz-input" oninput="nzRp(this);mlHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Hutang Jangka Pendek <span class="text-neutral-300 normal-case font-normal">(dikurangkan)</span></label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="ml-hutang" placeholder="Utang yang jatuh tempo" class="nz-input" oninput="nzRp(this);mlHitung()"></div>
                    </div>
                </div>
            </div>

            <div class="p-8 flex flex-col">
                <h3 class="text-base font-bold text-neutral-800 mb-4">Hasil Kalkulasi</h3>
                <div class="nz-result-card mb-4">
                    <div class="nz-sec"><span>Aset Fisik &rarr; Nilai Rupiah</span></div>
                    <div class="nz-calc-row"><span class="lbl">Emas (<span id="ml-r-gr-emas">0</span> gr &times; Rp {{ number_format($hargaEmasPerGram,0,',','.') }})</span><span class="val" id="ml-r-emas">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">Perak (<span id="ml-r-gr-perak">0</span> gr &times; harga)</span><span class="val" id="ml-r-perak">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">Tabungan / Deposito</span><span class="val" id="ml-r-tabungan">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">Investasi / Saham</span><span class="val" id="ml-r-investasi">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">Properti Investasi</span><span class="val" id="ml-r-properti">Rp 0</span></div>
                    <div class="nz-div"></div>
                    <div class="nz-calc-row hl"><span class="lbl">Total Aset</span><span class="val" id="ml-r-aset">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">&minus; Hutang Jangka Pendek</span><span class="val" id="ml-r-hutang">Rp 0</span></div>
                    <div class="nz-div"></div>
                    <div class="nz-calc-row hl"><span class="lbl">Harta Bersih</span><span class="val" id="ml-r-bersih">Rp 0</span></div>

                    <div class="nz-sec"><span>Nisab &amp; Kadar Zakat</span></div>
                    <div class="nz-calc-row"><span class="lbl">Nisab (85 gr emas)</span><span class="val">Rp {{ number_format($nisabMaal,0,',','.') }}</span></div>
                    <div class="nz-calc-row"><span class="lbl">Status Nisab</span><span class="val" id="ml-r-status">—</span></div>
                    <div class="nz-calc-row"><span class="lbl">Kadar Zakat</span><span class="val">2,5%</span></div>
                    <div class="nz-calc-row"><span class="lbl">Harta Bersih &times; 2,5%</span><span class="val" id="ml-r-kalkulasi">—</span></div>
                </div>
                <div class="nz-zakat-result">
                    <p class="text-xs text-green-600 mb-1 font-bold uppercase tracking-wide">Zakat yang Harus Dibayar</p>
                    <p class="text-2xl font-extrabold text-green-700" id="ml-r-zakat">Rp 0</p>
                    <p class="text-xs text-green-600 mt-1">2,5% dari harta bersih (jika sudah haul 1 tahun)</p>
                </div>
                <div id="ml-status" class="nz-status nz-s-empty">Masukkan data harta untuk menghitung kewajiban zakat Anda.</div>
                <div class="mt-auto">@include('partials.zakat-btn')</div>
            </div>
        </div>
        </div>


        {{-- ════════════════════════════════════════════════════ --}}
        {{-- PANEL 3: ZAKAT FITRAH                              --}}
        {{-- ════════════════════════════════════════════════════ --}}
        <div id="p-fitrah" class="nz-panel">
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">
            <div class="p-8">
                <h3 class="text-base font-bold text-neutral-800 mb-1">Zakat Fitrah</h3>
                <p class="text-xs text-neutral-400 mb-4 leading-relaxed">Wajib dikeluarkan setiap jiwa yang ditanggung, sebelum shalat Idul Fitri.</p>

                <div class="nz-formula">
                    <p class="nz-formula-title">Rumus Zakat Fitrah</p>
                    <p class="nz-formula-text">
                        <strong>Kewajiban per jiwa</strong> = 1 sha&rsquo; = 3,5 liter = 2,5 kg makanan pokok<br>
                        <strong>Total Fisik</strong> = Jumlah jiwa &times; 2,5 kg<br>
                        <strong>Total Uang</strong> = Total fisik &times; harga beras/kg<br>
                        <strong>Tidak ada kadar persentase</strong> — kewajiban per jiwa adalah tetap (1 sha&rsquo;)<br>
                        <strong>Waktu</strong>: Sejak awal Ramadan s.d. sebelum shalat Idul Fitri
                    </p>
                </div>

                <div class="mb-4">
                    <label class="nz-label">Tampilkan Hasil Dalam</label>
                    <div class="nz-toggle-wrap mt-2">
                        <button onclick="ftJenis('uang')"  id="ft-btn-uang"  class="nz-toggle-btn nz-toggle-btn-active">Uang (Rupiah)</button>
                        <button onclick="ftJenis('beras')" id="ft-btn-beras" class="nz-toggle-btn">Beras (Fisik)</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="nz-label">Harga Beras per Kg (di daerah Anda)</label>
                    <div class="relative"><span class="nz-prefix">Rp</span>
                    <input type="text" id="ft-harga" class="nz-input" value="14.000" oninput="nzRp(this);ftHitung()"></div>
                    <p class="text-xs text-neutral-400 mt-1">Referensi BAZNAS 2024: Rp 14.000 &ndash; Rp 20.000/kg (sesuai kualitas &amp; wilayah)</p>
                </div>

                <div class="mb-4">
                    <label class="nz-label">Jumlah Jiwa yang Ditanggung</label>
                    <div class="flex items-center gap-4 mt-2">
                        <button class="nz-stepper-btn" onclick="ftStep(-1)">&#8722;</button>
                        <span id="ft-jiwa-disp" class="text-3xl font-extrabold text-neutral-800 w-12 text-center tabular-nums">1</span>
                        <button class="nz-stepper-btn" onclick="ftStep(1)">&#43;</button>
                        <span class="text-sm text-neutral-400 font-medium">jiwa</span>
                    </div>
                    <p class="text-xs text-neutral-400 mt-2">Termasuk diri sendiri, istri, anak, dan siapapun yang menjadi tanggungan nafkah.</p>
                </div>

                <div class="bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-3">
                    <p class="text-xs text-neutral-400 mb-1 font-semibold uppercase tracking-wide">Kewajiban per jiwa</p>
                    <p class="font-bold text-neutral-800" id="ft-perjijwa">2,5 kg beras = Rp 35.000</p>
                </div>
            </div>

            <div class="p-8 flex flex-col">
                <h3 class="text-base font-bold text-neutral-800 mb-4">Hasil Kalkulasi</h3>
                <div class="nz-result-card mb-4">
                    <div class="nz-sec"><span>Fisik (Beras)</span></div>
                    <div class="nz-calc-row"><span class="lbl">Kewajiban per jiwa (1 sha&rsquo;)</span><span class="val">2,5 kg beras</span></div>
                    <div class="nz-calc-row"><span class="lbl">Jumlah jiwa</span><span class="val" id="ft-r-jiwa">1 jiwa</span></div>
                    <div class="nz-calc-row hl"><span class="lbl">Total Fisik Beras</span><span class="val" id="ft-r-total-beras">2,5 kg</span></div>

                    <div class="nz-sec"><span>Konversi ke Uang</span></div>
                    <div class="nz-calc-row"><span class="lbl">Harga beras/kg</span><span class="val" id="ft-r-harga">Rp 14.000</span></div>
                    <div class="nz-calc-row"><span class="lbl">Total beras &times; harga beras</span><span class="val" id="ft-r-konversi">2,5 kg &times; Rp 14.000</span></div>
                    <div class="nz-div"></div>
                    <div class="nz-calc-row hl"><span class="lbl">Total Nilai (Uang)</span><span class="val" id="ft-r-total-uang">Rp 35.000</span></div>

                    <div class="nz-sec"><span>Ringkasan per Jiwa</span></div>
                    <div class="nz-calc-row"><span class="lbl">Nilai per jiwa</span><span class="val" id="ft-r-perjiwa">Rp 35.000</span></div>
                    <div class="nz-calc-row hl"><span class="lbl">Total (<span id="ft-r-jiwa2">1</span> jiwa)</span><span class="val" id="ft-r-total">Rp 35.000</span></div>
                </div>
                <div class="nz-zakat-result">
                    <p class="text-xs text-green-600 mb-1 font-bold uppercase tracking-wide">Total yang Harus Dibayar</p>
                    <p class="text-2xl font-extrabold text-green-700" id="ft-r-zakat">Rp 35.000</p>
                    <p class="text-xs text-green-600 mt-1" id="ft-r-info">untuk 1 jiwa &middot; setara 2,5 kg beras</p>
                </div>
                <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 text-xs text-amber-800 leading-relaxed mb-4">
                    Zakat fitrah wajib dibayarkan sebelum shalat Idul Fitri. Boleh berupa beras 2,5 kg per jiwa atau senilai uangnya. Membayar lebih awal lebih afdhal agar amil dapat menyalurkan tepat waktu.
                </div>
                <div class="mt-auto">@include('partials.zakat-btn', ['label'=>'Bayar Zakat Fitrah'])</div>
            </div>
        </div>
        </div>


        {{-- ════════════════════════════════════════════════════ --}}
        {{-- PANEL 4: ZAKAT PERTANIAN                           --}}
        {{-- ════════════════════════════════════════════════════ --}}
        <div id="p-pertanian" class="nz-panel">
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">
            <div class="p-8">
                <h3 class="text-base font-bold text-neutral-800 mb-1">Zakat Pertanian</h3>
                <p class="text-xs text-neutral-400 mb-4 leading-relaxed">Wajib atas hasil panen berupa padi, jagung, gandum, dan sejenisnya yang dapat dimakan dan disimpan.</p>

                <div class="nz-formula">
                    <p class="nz-formula-title">Rumus Zakat Pertanian</p>
                    <p class="nz-formula-text">
                        <strong>Nisab</strong> = 652,8 kg hasil bersih panen<br>
                        <strong>Kadar</strong>: 10% (air hujan/sungai alami) &mdash; 5% (irigasi/disiram sendiri)<br>
                        <strong>Waktu</strong>: Dikeluarkan saat panen, tidak disyaratkan haul<br>
                        <strong>Zakat</strong> = Hasil Panen &times; Kadar (5% atau 10%)
                    </p>
                </div>

                <div class="mb-4">
                    <label class="nz-label">Sumber Pengairan</label>
                    <div class="nz-toggle-wrap mt-2">
                        <button onclick="ptJenis('hujan')"   id="pt-btn-hujan"   class="nz-toggle-btn nz-toggle-btn-active">Air Hujan / Alami (10%)</button>
                        <button onclick="ptJenis('irigasi')" id="pt-btn-irigasi" class="nz-toggle-btn">Irigasi / Pompa (5%)</button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="nz-label">Hasil Panen Kotor (kg)</label>
                        <div class="relative">
                            <input type="text" id="pt-panen-kg" placeholder="Contoh: 1000" class="nz-input-plain" oninput="nzNum(this);ptHitung()">
                            <span class="nz-suffix">kg</span>
                        </div>
                    </div>
                    <div>
                        <label class="nz-label">Biaya Panen / Produksi (dikurangkan) <span class="text-neutral-300 normal-case font-normal">(opsional)</span></label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pt-biaya" placeholder="Biaya pupuk, tenaga kerja, dll" class="nz-input" oninput="nzRp(this);ptHitung()"></div>
                        <p class="text-xs text-neutral-400 mt-1">Jika menggunakan irigasi berbayar, biaya dapat dikurangi dari hasil panen sebelum dihitung zakat.</p>
                    </div>
                    <div>
                        <label class="nz-label">Harga Jual per Kg (Rp)</label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pt-harga-kg" placeholder="Contoh: 6.000" class="nz-input" oninput="nzRp(this);ptHitung()"></div>
                    </div>
                </div>
            </div>

            <div class="p-8 flex flex-col">
                <h3 class="text-base font-bold text-neutral-800 mb-4">Hasil Kalkulasi</h3>
                <div class="nz-result-card mb-4">
                    <div class="nz-sec"><span>Fisik (Hasil Panen)</span></div>
                    <div class="nz-calc-row"><span class="lbl">Hasil panen kotor</span><span class="val" id="pt-r-panen">0 kg</span></div>
                    <div class="nz-calc-row hl"><span class="lbl">Hasil Panen Bersih</span><span class="val" id="pt-r-bersih-kg">0 kg</span></div>

                    <div class="nz-sec"><span>Konversi ke Uang</span></div>
                    <div class="nz-calc-row"><span class="lbl">Harga jual/kg</span><span class="val" id="pt-r-harga">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">Hasil bersih &times; harga jual</span><span class="val" id="pt-r-nilai">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">&minus; Biaya produksi</span><span class="val" id="pt-r-biaya">Rp 0</span></div>
                    <div class="nz-div"></div>
                    <div class="nz-calc-row hl"><span class="lbl">Total Nilai Bersih</span><span class="val" id="pt-r-total-nilai">Rp 0</span></div>

                    <div class="nz-sec"><span>Nisab &amp; Kadar</span></div>
                    <div class="nz-calc-row"><span class="lbl">Nisab</span><span class="val">652,8 kg</span></div>
                    <div class="nz-calc-row"><span class="lbl">Status Nisab</span><span class="val" id="pt-r-status">—</span></div>
                    <div class="nz-calc-row"><span class="lbl">Sumber Air</span><span class="val" id="pt-r-air">Air Hujan / Alami</span></div>
                    <div class="nz-calc-row"><span class="lbl">Kadar Zakat</span><span class="val" id="pt-r-kadar">10%</span></div>
                    <div class="nz-calc-row"><span class="lbl">Total Nilai &times; Kadar</span><span class="val" id="pt-r-kalkulasi">—</span></div>
                </div>
                <div class="nz-zakat-result">
                    <p class="text-xs text-green-600 mb-1 font-bold uppercase tracking-wide">Zakat yang Harus Dibayar</p>
                    <p class="text-2xl font-extrabold text-green-700" id="pt-r-zakat">Rp 0</p>
                    <p class="text-xs text-green-600 mt-1" id="pt-r-info">10% dari nilai hasil panen (air hujan)</p>
                </div>
                <div id="pt-status" class="nz-status nz-s-empty">Masukkan data hasil panen untuk menghitung kewajiban zakat.</div>
                <div class="mt-auto">@include('partials.zakat-btn')</div>
            </div>
        </div>
        </div>


        {{-- ════════════════════════════════════════════════════ --}}
        {{-- PANEL 5: HEWAN TERNAK                              --}}
        {{-- ════════════════════════════════════════════════════ --}}
        <div id="p-ternak" class="nz-panel">
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">
            <div class="p-8">
                <h3 class="text-base font-bold text-neutral-800 mb-1">Zakat Hewan Ternak</h3>
                <p class="text-xs text-neutral-400 mb-4 leading-relaxed">Wajib atas ternak yang digembalakan secara bebas dan telah mencapai nisab serta haul 1 tahun.</p>

                <div class="nz-formula">
                    <p class="nz-formula-title">Ketentuan Zakat Ternak</p>
                    <p class="nz-formula-text">
                        <strong>Unta</strong>: Nisab 5 ekor &mdash; wajib 1 ekor kambing (5&ndash;9 ekor)<br>
                        <strong>Sapi / Kerbau</strong>: Nisab 30 ekor &mdash; wajib 1 ekor tabi&rsquo; (30&ndash;39 ekor), 1 musinnah (40&ndash;59 ekor)<br>
                        <strong>Kambing / Domba</strong>: Nisab 40 ekor &mdash; wajib 1 ekor kambing (40&ndash;120 ekor)<br>
                        <strong>Syarat</strong>: Digembalakan (sa&rsquo;imah), bukan untuk bekerja, sudah haul 1 tahun<br>
                        Kadar berkisar <strong>2,5% &ndash; 5%</strong> dari nilai ternak (pendekatan kontemporer)
                    </p>
                </div>

                <div class="mb-4">
                    <label class="nz-label">Jenis Ternak</label>
                    <select id="tk-jenis" class="nz-select mt-2" onchange="tkHitung()">
                        <option value="sapi">Sapi / Kerbau</option>
                        <option value="kambing">Kambing / Domba</option>
                        <option value="unta">Unta</option>
                    </select>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="nz-label">Jumlah Ternak (ekor)</label>
                        <div class="relative">
                            <input type="text" id="tk-jumlah" placeholder="Contoh: 30" class="nz-input-plain" oninput="nzNum(this);tkHitung()">
                            <span class="nz-suffix">ekor</span>
                        </div>
                    </div>
                    <div>
                        <label class="nz-label">Harga Pasar per Ekor (Rp)</label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="tk-harga" placeholder="Contoh: 15.000.000" class="nz-input" oninput="nzRp(this);tkHitung()"></div>
                    </div>
                </div>
            </div>

            <div class="p-8 flex flex-col">
                <h3 class="text-base font-bold text-neutral-800 mb-4">Hasil Kalkulasi</h3>
                <div class="nz-result-card mb-4">
                    <div class="nz-sec"><span>Fisik (Ternak)</span></div>
                    <div class="nz-calc-row"><span class="lbl">Jenis ternak</span><span class="val" id="tk-r-jenis">Sapi / Kerbau</span></div>
                    <div class="nz-calc-row"><span class="lbl">Jumlah ternak</span><span class="val" id="tk-r-jumlah">0 ekor</span></div>
                    <div class="nz-calc-row hl"><span class="lbl">Nisab ternak ini</span><span class="val" id="tk-r-nisab">30 ekor</span></div>

                    <div class="nz-sec"><span>Konversi ke Uang</span></div>
                    <div class="nz-calc-row"><span class="lbl">Harga pasar per ekor</span><span class="val" id="tk-r-harga">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">Jumlah &times; harga per ekor</span><span class="val" id="tk-r-nilai">Rp 0</span></div>
                    <div class="nz-div"></div>
                    <div class="nz-calc-row hl"><span class="lbl">Total Nilai Ternak</span><span class="val" id="tk-r-total-nilai">Rp 0</span></div>

                    <div class="nz-sec"><span>Kadar Zakat</span></div>
                    <div class="nz-calc-row"><span class="lbl">Status Nisab</span><span class="val" id="tk-r-status">—</span></div>
                    <div class="nz-calc-row"><span class="lbl">Kadar Zakat (2,5%)</span><span class="val">2,5%</span></div>
                    <div class="nz-calc-row"><span class="lbl">Total Nilai &times; 2,5%</span><span class="val" id="tk-r-kalkulasi">—</span></div>
                </div>
                <div class="nz-zakat-result">
                    <p class="text-xs text-green-600 mb-1 font-bold uppercase tracking-wide">Zakat yang Harus Dibayar</p>
                    <p class="text-2xl font-extrabold text-green-700" id="tk-r-zakat">Rp 0</p>
                    <p class="text-xs text-green-600 mt-1" id="tk-r-info">2,5% dari nilai total ternak</p>
                </div>
                <div id="tk-status" class="nz-status nz-s-empty">Masukkan data ternak untuk menghitung kewajiban zakat.</div>
                <div class="mt-auto">@include('partials.zakat-btn')</div>
            </div>
        </div>
        </div>


        {{-- ════════════════════════════════════════════════════ --}}
        {{-- PANEL 6: ZAKAT PERNIAGAAN                          --}}
        {{-- ════════════════════════════════════════════════════ --}}
        <div id="p-perniagaan" class="nz-panel">
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">
            <div class="p-8">
                <h3 class="text-base font-bold text-neutral-800 mb-1">Zakat Perniagaan</h3>
                <p class="text-xs text-neutral-400 mb-4 leading-relaxed">Wajib atas harta yang diperjualbelikan dengan tujuan mendapat keuntungan, setelah haul 1 tahun.</p>

                <div class="nz-formula">
                    <p class="nz-formula-title">Rumus Zakat Perniagaan</p>
                    <p class="nz-formula-text">
                        <strong>Nisab</strong> = Senilai 85 gram emas = Rp {{ number_format($nisabMaal,0,',','.') }}<br>
                        <strong>Haul</strong> = 1 tahun hijriyah dihitung dari awal usaha / kepemilikan<br>
                        <strong>Harta Perniagaan</strong> = Stok Barang + Piutang Lancar + Kas &minus; Hutang<br>
                        <strong>Zakat</strong> = 2,5% &times; Harta Perniagaan Bersih
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="nz-label">Nilai Stok / Persediaan Barang</label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pn-stok" placeholder="Nilai pasar stok saat ini" class="nz-input" oninput="nzRp(this);pnHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Kas / Uang Tunai Usaha</label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pn-kas" placeholder="Termasuk rekening usaha" class="nz-input" oninput="nzRp(this);pnHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Piutang Dagang yang Dapat Ditagih <span class="text-neutral-300 normal-case font-normal">(opsional)</span></label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pn-piutang" placeholder="Piutang yang hampir pasti tertagih" class="nz-input" oninput="nzRp(this);pnHitung()"></div>
                    </div>
                    <div>
                        <label class="nz-label">Hutang Dagang / Kewajiban <span class="text-neutral-300 normal-case font-normal">(dikurangkan)</span></label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="pn-hutang" placeholder="Hutang yang jatuh tempo dalam 1 tahun" class="nz-input" oninput="nzRp(this);pnHitung()"></div>
                    </div>
                </div>
            </div>

            <div class="p-8 flex flex-col">
                <h3 class="text-base font-bold text-neutral-800 mb-4">Hasil Kalkulasi</h3>
                <div class="nz-result-card mb-4">
                    <div class="nz-sec"><span>Komponen Harta Perniagaan</span></div>
                    <div class="nz-calc-row"><span class="lbl">Nilai stok barang</span><span class="val" id="pn-r-stok">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">+ Kas / Uang tunai</span><span class="val" id="pn-r-kas">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">+ Piutang dagang</span><span class="val" id="pn-r-piutang">Rp 0</span></div>
                    <div class="nz-div"></div>
                    <div class="nz-calc-row hl"><span class="lbl">Total Aset Dagang</span><span class="val" id="pn-r-aset">Rp 0</span></div>
                    <div class="nz-calc-row"><span class="lbl">&minus; Hutang dagang</span><span class="val" id="pn-r-hutang">Rp 0</span></div>
                    <div class="nz-div"></div>
                    <div class="nz-calc-row hl"><span class="lbl">Harta Perniagaan Bersih</span><span class="val" id="pn-r-bersih">Rp 0</span></div>

                    <div class="nz-sec"><span>Nisab &amp; Kadar</span></div>
                    <div class="nz-calc-row"><span class="lbl">Nisab (85 gr emas)</span><span class="val">Rp {{ number_format($nisabMaal,0,',','.') }}</span></div>
                    <div class="nz-calc-row"><span class="lbl">Status Nisab</span><span class="val" id="pn-r-status">—</span></div>
                    <div class="nz-calc-row"><span class="lbl">Kadar Zakat</span><span class="val">2,5%</span></div>
                    <div class="nz-calc-row"><span class="lbl">Harta Bersih &times; 2,5%</span><span class="val" id="pn-r-kalkulasi">—</span></div>
                </div>
                <div class="nz-zakat-result">
                    <p class="text-xs text-green-600 mb-1 font-bold uppercase tracking-wide">Zakat yang Harus Dibayar</p>
                    <p class="text-2xl font-extrabold text-green-700" id="pn-r-zakat">Rp 0</p>
                    <p class="text-xs text-green-600 mt-1">2,5% dari harta perniagaan bersih</p>
                </div>
                <div id="pn-status" class="nz-status nz-s-empty">Masukkan data usaha untuk menghitung kewajiban zakat.</div>
                <div class="mt-auto">@include('partials.zakat-btn')</div>
            </div>
        </div>
        </div>


        {{-- ════════════════════════════════════════════════════ --}}
        {{-- PANEL 7: RIKAZ (HARTA TEMUAN)                      --}}
        {{-- ════════════════════════════════════════════════════ --}}
        <div id="p-rikaz" class="nz-panel">
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-neutral-100">
            <div class="p-8">
                <h3 class="text-base font-bold text-neutral-800 mb-1">Zakat Rikaz (Harta Temuan)</h3>
                <p class="text-xs text-neutral-400 mb-4 leading-relaxed">Rikaz adalah harta karun atau harta temuan dari peninggalan zaman jahiliyah. Wajib dizakati segera setelah ditemukan.</p>

                <div class="nz-formula">
                    <p class="nz-formula-title">Rumus Zakat Rikaz</p>
                    <p class="nz-formula-text">
                        <strong>Tidak ada nisab</strong> &mdash; berapapun jumlahnya wajib dizakati<br>
                        <strong>Tidak ada haul</strong> &mdash; wajib langsung saat ditemukan<br>
                        <strong>Kadar Zakat</strong> = 20% (seperlima) dari nilai harta yang ditemukan<br>
                        <strong>Zakat</strong> = 20% &times; Nilai Harta Temuan<br>
                        <strong>Dasar</strong>: HR. Bukhari &amp; Muslim &mdash; &ldquo;Pada rikaz wajib seperlima&rdquo;
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="nz-label">Jenis Harta Temuan</label>
                        <select id="rk-jenis" class="nz-select mt-1" onchange="rkHitung()">
                            <option value="uang">Uang / Logam (nilai langsung)</option>
                            <option value="emas">Emas (input gram)</option>
                            <option value="barang">Barang Berharga lainnya</option>
                        </select>
                    </div>

                    <div id="rk-gram-wrap" style="display:none">
                        <label class="nz-label">Berat Emas yang Ditemukan (gram)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative">
                                <input type="text" id="rk-emas-gr" placeholder="Gram" class="nz-input-plain" oninput="nzNum(this);rkHitung()">
                                <span class="nz-suffix">gr</span>
                            </div>
                            <input type="text" id="rk-emas-val" class="nz-input-auto" readonly placeholder="Nilai (Rp otomatis)">
                        </div>
                        <p class="text-xs text-neutral-400 mt-1">Nilai = gram &times; Rp {{ number_format($hargaEmasPerGram,0,',','.') }}/gr (harga BAZNAS)</p>
                    </div>

                    <div id="rk-nilai-wrap">
                        <label class="nz-label">Nilai Harta Temuan</label>
                        <div class="relative"><span class="nz-prefix">Rp</span>
                        <input type="text" id="rk-nilai" placeholder="Estimasi nilai pasar" class="nz-input" oninput="nzRp(this);rkHitung()"></div>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 text-xs text-amber-800 leading-relaxed mt-4">
                    Rikaz berbeda dengan luqathah (barang temuan biasa). Rikaz adalah harta peninggalan zaman sebelum Islam yang ditemukan terpendam di tanah. Konsultasikan dengan ulama untuk penentuan status harta temuan Anda.
                </div>
            </div>

            <div class="p-8 flex flex-col">
                <h3 class="text-base font-bold text-neutral-800 mb-4">Hasil Kalkulasi</h3>
                <div class="nz-result-card mb-4">
                    <div class="nz-sec"><span>Fisik Harta Temuan</span></div>
                    <div class="nz-calc-row"><span class="lbl">Jenis harta</span><span class="val" id="rk-r-jenis">Uang / Logam</span></div>
                    <div class="nz-calc-row" id="rk-r-gram-row" style="display:none"><span class="lbl">Berat emas</span><span class="val" id="rk-r-gram">0 gr</span></div>

                    <div class="nz-sec"><span>Konversi ke Uang</span></div>
                    <div class="nz-calc-row" id="rk-r-harga-row" style="display:none">
                        <span class="lbl">Harga emas/gr (BAZNAS)</span><span class="val">Rp {{ number_format($hargaEmasPerGram,0,',','.') }}</span>
                    </div>
                    <div class="nz-calc-row hl"><span class="lbl">Nilai Harta Temuan</span><span class="val" id="rk-r-nilai">Rp 0</span></div>

                    <div class="nz-sec"><span>Kadar Zakat Rikaz</span></div>
                    <div class="nz-calc-row"><span class="lbl">Nisab</span><span class="val">Tidak disyaratkan</span></div>
                    <div class="nz-calc-row"><span class="lbl">Haul</span><span class="val">Tidak disyaratkan</span></div>
                    <div class="nz-calc-row"><span class="lbl">Kadar Zakat</span><span class="val">20% (seperlima)</span></div>
                    <div class="nz-calc-row"><span class="lbl">Nilai Harta &times; 20%</span><span class="val" id="rk-r-kalkulasi">—</span></div>
                </div>
                <div class="nz-zakat-result">
                    <p class="text-xs text-green-600 mb-1 font-bold uppercase tracking-wide">Zakat yang Harus Dibayar</p>
                    <p class="text-2xl font-extrabold text-green-700" id="rk-r-zakat">Rp 0</p>
                    <p class="text-xs text-green-600 mt-1">20% dari nilai harta temuan &mdash; wajib segera</p>
                </div>
                <div class="bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-3 text-xs text-neutral-500 leading-relaxed mb-4">
                    Sisa harta setelah dizakati (80%) menjadi milik penemu. Zakat rikaz diserahkan kepada amil atau baitul mal.
                </div>
                <div class="mt-auto">@include('partials.zakat-btn')</div>
            </div>
        </div>
        </div>


    </div>{{-- end card --}}

    <p class="text-center text-xs text-neutral-400 mt-6 leading-relaxed">
        Kalkulator ini bersifat estimasi. Harga emas berdasarkan data BAZNAS terbaru (Rp {{ number_format($hargaEmasPerGram,0,',','.') }}/gram).
        Untuk kepastian hukum, konsultasikan dengan amil zakat atau ulama setempat.
    </p>

</div>
</section>
@endsection


{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- NOTE: Buat file partials/zakat-btn.blade.php dengan isi:        --}}
{{--   @auth                                                          --}}
{{--   <a href="{{ route('dashboard') }}" class="...">Bayar Zakat Sekarang</a> --}}
{{--   @else                                                          --}}
{{--   <a href="{{ route('register') }}" class="...">Daftar & Bayar Zakat</a>  --}}
{{--   @endauth                                                        --}}
{{-- Atau ganti @include('partials.zakat-btn') dengan kode di bawah: --}}


@section('scripts')
<script>
(function () {

    const HARGA_EMAS = {{ $hargaEmasPerGram ?? 1900000 }};
    const NISAB_MAAL = HARGA_EMAS * 85;
    const NISAB_BLN  = Math.round(NISAB_MAAL / 12);

    // ── State ────────────────────────────────────────────────
    let pgPeriode = 'bulan';
    let ftJiwaVal = 1;
    let ftJenisVal = 'uang';
    let ptAir = 'hujan';  // 10%
    let rkJenisVal = 'uang';

    // ── Helpers ──────────────────────────────────────────────
    function rp(n) {
        if (!n || isNaN(n) || n === 0) return 'Rp 0';
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }
    function parseRp(id) {
        const el = document.getElementById(id);
        if (!el || !el.value) return 0;
        return parseFloat(el.value.replace(/\./g,'').replace(',','.')) || 0;
    }
    function parseNum(id) {
        const el = document.getElementById(id);
        if (!el || !el.value) return 0;
        // Hapus titik ribuan, ganti koma desimal ke titik
        return parseFloat(el.value.replace(/\./g,'').replace(',','.')) || 0;
    }
    function set(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }
    function setStatus(id, cls, msg) {
        const el = document.getElementById(id);
        if (!el) return;
        el.className = 'nz-status ' + cls;
        el.textContent = msg;
    }
    function show(id, visible) {
        const el = document.getElementById(id);
        if (el) el.style.display = visible ? '' : 'none';
    }

    window.nzRp = function(el) {
        const raw = el.value.replace(/\D/g,'');
        el.value = raw ? parseInt(raw,10).toLocaleString('id-ID') : '';
    };

    // Format angka desimal (untuk gram, kg, ekor) — hanya angka & koma
    window.nzNum = function(el) {
        // Izinkan angka, satu koma/titik saja
        let val = el.value.replace(/[^0-9.,]/g,'');
        // Normalisasi: hanya satu tanda desimal
        const parts = val.split(/[.,]/);
        if (parts.length > 2) val = parts[0] + ',' + parts.slice(1).join('');
        el.value = val;
    };

    // ── Tab Switch ───────────────────────────────────────────
    const TABS = ['penghasilan','maal','fitrah','pertanian','ternak','perniagaan','rikaz'];
    window.nzTab = function(tab) {
        TABS.forEach(t => {
            document.getElementById('p-' + t).classList.toggle('nz-panel-active', t === tab);
            document.getElementById('t-' + t).classList.toggle('nz-tab-active', t === tab);
        });
    };

    // ════════════════════════════════════════════════════════
    // 1. ZAKAT PENGHASILAN
    // ════════════════════════════════════════════════════════
    window.pgSetPeriode = function(p) {
        pgPeriode = p;
        document.getElementById('pg-btn-bln').classList.toggle('nz-toggle-btn-active', p==='bulan');
        document.getElementById('pg-btn-thn').classList.toggle('nz-toggle-btn-active', p==='tahun');
        set('pg-r-nisab-lbl', p==='bulan' ? '(per bulan)' : '(per tahun)');
        pgHitung();
    };
    window.pgHitung = function() {
        const gaji    = parseRp('pg-gaji');
        const lain    = parseRp('pg-lain');
        const pokok   = parseRp('pg-pokok');
        const total   = gaji + lain;
        const bersih  = Math.max(0, total - pokok);
        const nisab   = pgPeriode === 'bulan' ? NISAB_BLN : NISAB_MAAL;
        const cukup   = bersih >= nisab;
        const zakat   = cukup ? bersih * 0.025 : 0;

        set('pg-r-gaji',       rp(gaji));
        set('pg-r-lain',       rp(lain));
        set('pg-r-pokok',      rp(pokok));
        set('pg-r-bersih',     rp(bersih));
        set('pg-r-nisab',      rp(nisab));
        set('pg-r-status',     cukup ? 'Sudah mencapai nisab' : 'Belum mencapai nisab');
        set('pg-r-kalkulasi',  cukup ? rp(bersih) + ' x 2,5% = ' + rp(zakat) : 'Tidak wajib zakat');
        set('pg-r-zakat',      rp(zakat));
        set('pg-r-info',       '2,5% dari penghasilan bersih ' + (pgPeriode==='bulan'?'per bulan':'per tahun'));
    };

    // ════════════════════════════════════════════════════════
    // 2. ZAKAT MAAL
    // ════════════════════════════════════════════════════════
    window.mlHitung = function() {
        const grEmas    = parseNum('ml-emas-gr');
        const nilaiEmas = grEmas * HARGA_EMAS;
        const hargaPerak= parseRp('ml-perak-harga') || 12000;
        const grPerak   = parseNum('ml-perak-gr');
        const nilaiPerak= grPerak * hargaPerak;
        const tabungan  = parseRp('ml-tabungan');
        const investasi = parseRp('ml-investasi');
        const properti  = parseRp('ml-properti');
        const hutang    = parseRp('ml-hutang');

        // Auto-fill nilai emas & perak
        const emVal = document.getElementById('ml-emas-val');
        if (emVal) emVal.value = nilaiEmas > 0 ? Math.round(nilaiEmas).toLocaleString('id-ID') : '';
        const pkVal = document.getElementById('ml-perak-val');
        if (pkVal) pkVal.value = nilaiPerak > 0 ? Math.round(nilaiPerak).toLocaleString('id-ID') : '';

        const aset   = nilaiEmas + nilaiPerak + tabungan + investasi + properti;
        const bersih = Math.max(0, aset - hutang);
        const cukup  = bersih >= NISAB_MAAL;
        const zakat  = cukup ? bersih * 0.025 : 0;

        set('ml-r-gr-emas',   grEmas > 0 ? grEmas.toLocaleString('id-ID') : '0');
        set('ml-r-gr-perak',  grPerak > 0 ? grPerak.toLocaleString('id-ID') : '0');
        set('ml-r-emas',      rp(nilaiEmas));
        set('ml-r-perak',     rp(nilaiPerak));
        set('ml-r-tabungan',  rp(tabungan));
        set('ml-r-investasi', rp(investasi));
        set('ml-r-properti',  rp(properti));
        set('ml-r-aset',      rp(aset));
        set('ml-r-hutang',    rp(hutang));
        set('ml-r-bersih',    rp(bersih));
        set('ml-r-status',    cukup ? 'Sudah mencapai nisab' : 'Belum mencapai nisab');
        set('ml-r-kalkulasi', cukup ? rp(bersih) + ' x 2,5% = ' + rp(zakat) : 'Tidak wajib zakat');
        set('ml-r-zakat',     rp(zakat));

        if (aset === 0) {
            setStatus('ml-status','nz-s-empty','Masukkan data harta untuk menghitung kewajiban zakat Anda.');
        } else if (!cukup) {
            setStatus('ml-status','nz-s-no','Harta bersih ' + rp(bersih) + ' belum mencapai nisab ' + rp(NISAB_MAAL) + '. Tidak wajib zakat maal, namun tetap dianjurkan berinfak.');
        } else {
            setStatus('ml-status','nz-s-ok','Harta Anda mencapai nisab. Jika sudah haul 1 tahun hijriyah, wajib membayar zakat sebesar ' + rp(zakat) + '.');
        }
    };

    // ════════════════════════════════════════════════════════
    // 3. ZAKAT FITRAH
    // ════════════════════════════════════════════════════════
    window.ftStep = function(d) {
        ftJiwaVal = Math.max(1, Math.min(100, ftJiwaVal + d));
        ftHitung();
    };
    window.ftJenis = function(j) {
        ftJenisVal = j;
        document.getElementById('ft-btn-uang').classList.toggle('nz-toggle-btn-active', j==='uang');
        document.getElementById('ft-btn-beras').classList.toggle('nz-toggle-btn-active', j==='beras');
        ftHitung();
    };
    window.ftHitung = function() {
        const harga      = parseRp('ft-harga') || 14000;
        const totalBeras = ftJiwaVal * 2.5;       // fisik: total kg
        const perJiwa    = 2.5 * harga;           // konversi per jiwa
        const totalUang  = ftJiwaVal * perJiwa;   // total uang

        set('ft-jiwa-disp',    ftJiwaVal);
        set('ft-r-jiwa',       ftJiwaVal + ' jiwa');
        set('ft-r-jiwa2',      ftJiwaVal);
        set('ft-r-total-beras', totalBeras.toFixed(1) + ' kg');
        set('ft-r-harga',      rp(harga) + '/kg');
        set('ft-r-konversi',   totalBeras.toFixed(1) + ' kg x ' + rp(harga) + ' = ' + rp(totalUang));
        set('ft-r-total-uang', rp(totalUang));
        set('ft-r-perjiwa',    ftJenisVal === 'uang' ? rp(perJiwa) : '2,5 kg beras');
        set('ft-perjijwa',     '2,5 kg beras = ' + rp(perJiwa));

        if (ftJenisVal === 'uang') {
            set('ft-r-total',  rp(totalUang));
            set('ft-r-zakat',  rp(totalUang));
            set('ft-r-info',   'untuk ' + ftJiwaVal + ' jiwa · setara ' + totalBeras.toFixed(1) + ' kg beras');
        } else {
            set('ft-r-total',  totalBeras.toFixed(1) + ' kg beras');
            set('ft-r-zakat',  totalBeras.toFixed(1) + ' kg beras');
            set('ft-r-info',   'untuk ' + ftJiwaVal + ' jiwa · setara ' + rp(totalUang));
        }
    };

    // ════════════════════════════════════════════════════════
    // 4. ZAKAT PERTANIAN
    // ════════════════════════════════════════════════════════
    window.ptJenis = function(j) {
        ptAir = j;
        document.getElementById('pt-btn-hujan').classList.toggle('nz-toggle-btn-active', j==='hujan');
        document.getElementById('pt-btn-irigasi').classList.toggle('nz-toggle-btn-active', j==='irigasi');
        ptHitung();
    };
    window.ptHitung = function() {
        const panenKg  = parseNum('pt-panen-kg');
        const biaya    = parseRp('pt-biaya');
        const hargaKg  = parseRp('pt-harga-kg');
        const kadar    = ptAir === 'hujan' ? 0.10 : 0.05;
        const kadarTxt = ptAir === 'hujan' ? '10%' : '5%';
        const airTxt   = ptAir === 'hujan' ? 'Air Hujan / Alami' : 'Irigasi / Pompa';
        const cukup    = panenKg >= 652.8;
        const nilaiKotor = panenKg * hargaKg;
        const nilaiTotal = Math.max(0, nilaiKotor - biaya);
        const zakat    = cukup ? nilaiTotal * kadar : 0;

        set('pt-r-panen',       panenKg.toLocaleString('id-ID') + ' kg');
        set('pt-r-bersih-kg',   panenKg.toLocaleString('id-ID') + ' kg');
        set('pt-r-harga',       hargaKg > 0 ? rp(hargaKg) + '/kg' : 'Rp 0/kg');
        set('pt-r-nilai',       rp(nilaiKotor));
        set('pt-r-biaya',       rp(biaya));
        set('pt-r-total-nilai', rp(nilaiTotal));
        set('pt-r-status',      cukup ? 'Sudah mencapai nisab' : 'Belum mencapai nisab (652,8 kg)');
        set('pt-r-air',         airTxt);
        set('pt-r-kadar',       kadarTxt);
        set('pt-r-kalkulasi',   cukup ? rp(nilaiTotal) + ' x ' + kadarTxt + ' = ' + rp(zakat) : 'Tidak wajib zakat');
        set('pt-r-zakat',       rp(zakat));
        set('pt-r-info',        kadarTxt + ' dari nilai hasil panen (' + airTxt.toLowerCase() + ')');

        if (panenKg === 0) {
            setStatus('pt-status','nz-s-empty','Masukkan data hasil panen untuk menghitung kewajiban zakat.');
        } else if (!cukup) {
            setStatus('pt-status','nz-s-no','Hasil panen ' + panenKg.toLocaleString('id-ID') + ' kg belum mencapai nisab 652,8 kg. Tidak wajib zakat.');
        } else {
            setStatus('pt-status','nz-s-ok','Hasil panen mencapai nisab. Wajib membayar zakat ' + kadarTxt + ' = ' + rp(zakat) + ' saat panen.');
        }
    };

    // ════════════════════════════════════════════════════════
    // 5. HEWAN TERNAK
    // ════════════════════════════════════════════════════════
    const NISAB_TERNAK = { sapi: 30, kambing: 40, unta: 5 };
    const NAMA_TERNAK  = { sapi: 'Sapi / Kerbau', kambing: 'Kambing / Domba', unta: 'Unta' };
    window.tkHitung = function() {
        const jenis   = document.getElementById('tk-jenis').value;
        const jumlah  = parseNum('tk-jumlah');
        const harga   = parseRp('tk-harga');
        const nisab   = NISAB_TERNAK[jenis];
        const cukup   = jumlah >= nisab;
        const nilai   = jumlah * harga;
        const zakat   = cukup ? nilai * 0.025 : 0;

        set('tk-r-jenis',      NAMA_TERNAK[jenis]);
        set('tk-r-jumlah',     jumlah.toLocaleString('id-ID') + ' ekor');
        set('tk-r-nisab',      nisab + ' ekor');
        set('tk-r-harga',      rp(harga));
        set('tk-r-nilai',      jumlah.toLocaleString('id-ID') + ' x ' + rp(harga) + ' = ' + rp(nilai));
        set('tk-r-total-nilai',rp(nilai));
        set('tk-r-status',     cukup ? 'Sudah mencapai nisab' : 'Belum mencapai nisab (' + nisab + ' ekor)');
        set('tk-r-kalkulasi',  cukup ? rp(nilai) + ' x 2,5% = ' + rp(zakat) : 'Tidak wajib zakat');
        set('tk-r-zakat',      rp(zakat));
        set('tk-r-info',       '2,5% dari nilai total ternak (pendekatan kontemporer)');

        if (jumlah === 0) {
            setStatus('tk-status','nz-s-empty','Masukkan data ternak untuk menghitung kewajiban zakat.');
        } else if (!cukup) {
            setStatus('tk-status','nz-s-no','Jumlah ternak belum mencapai nisab ' + nisab + ' ekor. Tidak wajib zakat.');
        } else {
            setStatus('tk-status','nz-s-ok','Ternak mencapai nisab. Jika sudah haul 1 tahun, wajib membayar zakat ' + rp(zakat) + '.');
        }
    };

    // ════════════════════════════════════════════════════════
    // 6. ZAKAT PERNIAGAAN
    // ════════════════════════════════════════════════════════
    window.pnHitung = function() {
        const stok    = parseRp('pn-stok');
        const kas     = parseRp('pn-kas');
        const piutang = parseRp('pn-piutang');
        const hutang  = parseRp('pn-hutang');
        const aset    = stok + kas + piutang;
        const bersih  = Math.max(0, aset - hutang);
        const cukup   = bersih >= NISAB_MAAL;
        const zakat   = cukup ? bersih * 0.025 : 0;

        set('pn-r-stok',      rp(stok));
        set('pn-r-kas',       rp(kas));
        set('pn-r-piutang',   rp(piutang));
        set('pn-r-aset',      rp(aset));
        set('pn-r-hutang',    rp(hutang));
        set('pn-r-bersih',    rp(bersih));
        set('pn-r-status',    cukup ? 'Sudah mencapai nisab' : 'Belum mencapai nisab');
        set('pn-r-kalkulasi', cukup ? rp(bersih) + ' x 2,5% = ' + rp(zakat) : 'Tidak wajib zakat');
        set('pn-r-zakat',     rp(zakat));

        if (aset === 0) {
            setStatus('pn-status','nz-s-empty','Masukkan data usaha untuk menghitung kewajiban zakat.');
        } else if (!cukup) {
            setStatus('pn-status','nz-s-no','Harta perniagaan bersih ' + rp(bersih) + ' belum mencapai nisab. Tidak wajib zakat.');
        } else {
            setStatus('pn-status','nz-s-ok','Harta perniagaan mencapai nisab. Jika sudah haul 1 tahun, wajib membayar zakat ' + rp(zakat) + '.');
        }
    };

    // ════════════════════════════════════════════════════════
    // 7. RIKAZ
    // ════════════════════════════════════════════════════════
    window.rkHitung = function() {
        rkJenisVal = document.getElementById('rk-jenis').value;
        const isEmas = rkJenisVal === 'emas';

        show('rk-gram-wrap', isEmas);
        show('rk-nilai-wrap', !isEmas);
        show('rk-r-gram-row', isEmas);
        show('rk-r-harga-row', isEmas);

        let nilai = 0;
        if (isEmas) {
            const gr = parseNum('rk-emas-gr');
            nilai = gr * HARGA_EMAS;
            const rkEmasVal = document.getElementById('rk-emas-val');
            if (rkEmasVal) rkEmasVal.value = nilai > 0 ? Math.round(nilai).toLocaleString('id-ID') : '';
            set('rk-r-gram', gr.toLocaleString('id-ID') + ' gr');
        } else {
            nilai = parseRp('rk-nilai');
        }

        const zakat = nilai * 0.20;
        const namaJenis = {uang:'Uang / Logam', emas:'Emas', barang:'Barang Berharga'};

        set('rk-r-jenis',     namaJenis[rkJenisVal] || 'Uang / Logam');
        set('rk-r-nilai',     rp(nilai));
        set('rk-r-kalkulasi', nilai > 0 ? rp(nilai) + ' x 20% = ' + rp(zakat) : '—');
        set('rk-r-zakat',     rp(zakat));
    };

    // Init
    pgHitung();
    mlHitung();
    ftHitung();
    ptHitung();
    tkHitung();
    pnHitung();
    rkHitung();

})();
</script>

{{-- ── Tombol Bayar (inline karena @include partial mungkin belum ada) ── --}}
{{-- Ganti semua @include('partials.zakat-btn') dengan snippet ini jika partial belum ada: --}}
{{--
@auth
<a href="{{ route('dashboard') }}"
    class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md">
    Bayar Zakat Sekarang
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
    </svg>
</a>
@else
<a href="{{ route('register') }}"
    class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md">
    Daftar & Bayar Zakat
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
    </svg>
</a>
@endauth
--}}
@endsection