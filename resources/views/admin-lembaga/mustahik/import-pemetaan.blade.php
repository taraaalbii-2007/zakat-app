{{-- resources/views/admin-lembaga/mustahik/import-pemetaan.blade.php --}}

@extends('layouts.app')

@section('title', 'Pemetaan Kolom Import Mustahik')

@section('content')
    <div class="space-y-5">

        {{-- ── Info File ──────────────────────────────────────────────── --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-blue-800">
                <p>File berhasil dibaca. Ditemukan
                    <strong>{{ count($importSession['excel_headers']) }} kolom</strong> dan
                    <strong>{{ $importSession['total_rows'] }} baris</strong> data.
                </p>
                <p class="text-blue-600 text-xs mt-0.5">
                    Petakan kolom Excel ke kolom sistem, lalu klik
                    <strong>"Cek Pemetaan &amp; Preview"</strong> sebelum import.
                </p>
            </div>
        </div>

        {{-- ── Form Utama ─────────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('mustahik.import.proses') }}" id="form-import">
            @csrf

            {{-- Card Pemetaan Kolom --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-3">
                    <span
                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-primary text-white text-xs font-bold shrink-0">1</span>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Pemetaan Kolom</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Pasangkan kolom Excel ke kolom sistem.
                            Kolom <span class="text-red-500">*</span> wajib dipetakan.
                        </p>
                    </div>
                </div>

                <div class="px-6 py-5">
                    @php
                        $allFields = array_keys($systemColumns);
                        $pairs = array_chunk($allFields, 2);
                    @endphp

                    <div class="space-y-4">
                        @foreach ($pairs as $pair)
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                                @foreach ($pair as $fieldKey)
                                    @php
                                        $fieldInfo = $systemColumns[$fieldKey];
                                        $sysIdx = array_search($fieldKey, $allFields);
                                        $autoExcelIdx = null;
                                        foreach ($autoMapping as $eIdx => $mField) {
                                            if ($mField === $fieldKey) {
                                                $autoExcelIdx = $eIdx;
                                                break;
                                            }
                                        }
                                        $autoHeader =
                                            $autoExcelIdx !== null
                                                ? ($importSession['excel_headers'][$autoExcelIdx] ?? null)
                                                : null;
                                    @endphp

                                    <div>
                                        <label for="map_{{ $fieldKey }}"
                                            class="block text-sm font-medium text-gray-700 mb-1.5">
                                            {{ $fieldInfo['label'] }}
                                            @if ($fieldInfo['required'])
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>

                                        <select name="mapping[{{ $sysIdx }}]" id="map_{{ $fieldKey }}"
                                            data-field="{{ $fieldKey }}"
                                            data-required="{{ $fieldInfo['required'] ? '1' : '0' }}"
                                            data-sysidx="{{ $sysIdx }}"
                                            class="mapping-select block w-full px-3 py-2 text-sm border rounded-lg
                                               bg-white focus:outline-none focus:ring-2 focus:ring-primary/20
                                               focus:border-primary transition-all
                                               {{ $fieldInfo['required'] && !$autoHeader ? 'border-orange-300' : 'border-gray-300' }}">
                                            <option value="">— Abaikan / Tidak dipetakan —</option>
                                            @foreach ($importSession['excel_headers'] as $idx => $header)
                                                <option value="{{ $idx }}" @selected($autoExcelIdx === $idx)>
                                                    {{ $header }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if ($autoHeader)
                                            <p class="mt-1 flex items-center gap-1 text-xs text-green-600">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Auto-detected: <em>"{{ $autoHeader }}"</em>
                                            </p>
                                        @elseif ($fieldInfo['required'])
                                            <p class="mt-1 text-xs text-orange-500">Wajib — pilih kolom yang sesuai</p>
                                        @else
                                            <p class="mt-1 text-xs text-gray-400">Opsional</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Validation Banner --}}
                <div id="validation-banner" class="hidden px-6 pb-5">
                    <div id="validation-banner-inner"></div>
                </div>

            </div>{{-- /card --}}

        </form>{{-- /form-import --}}

        {{-- Form batal — standalone --}}
        <form method="POST" action="{{ route('mustahik.import.batal') }}" id="form-batal" style="display:none;">
            @csrf
        </form>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end mt-5 pt-5 border-t border-gray-200 gap-3">

            <button type="button" onclick="document.getElementById('form-batal').submit()"
                class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300
                   text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Batal Import
            </button>

            <button type="button" id="btn-cek-pemetaan"
                class="inline-flex items-center gap-2 px-5 py-2.5 border border-primary
                   text-sm font-medium text-primary bg-white hover:bg-primary/5
                   rounded-lg shadow-sm transition-all">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span id="btn-cek-label">Cek Pemetaan &amp; Preview</span>
                <svg id="cek-spinner" class="hidden animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                </svg>
            </button>

            <button type="button" id="btn-import" disabled onclick="openModalKonfirmasi()"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white rounded-lg
                   bg-gradient-to-r from-primary to-primary-600 shadow-md shadow-primary/30 transition-all
                   disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none
                   enabled:hover:shadow-lg enabled:hover:shadow-primary/40">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Import Data Mustahik
            </button>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         MODAL 1 — PREVIEW DATA
    ══════════════════════════════════════════════════════════════ --}}
    <div id="modal-preview"
        style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.6);
               backdrop-filter:blur(4px); padding:24px;"
        class="flex items-center justify-center">

        <div style="background:#fff; border-radius:20px; width:100%; max-width:1200px;
                    height:88vh; display:flex; flex-direction:column; overflow:hidden;
                    box-shadow:0 25px 80px rgba(0,0,0,0.25);">

            {{-- Modal Header --}}
            <div style="padding:20px 28px; border-bottom:1px solid #f1f5f9; display:flex;
                        align-items:center; justify-content:space-between; flex-shrink:0; gap:16px; background:#fff;">

                <div style="display:flex; align-items:center; gap:14px;">
                    <div style="display:inline-flex; align-items:center; justify-content:center;
                                width:36px; height:36px; border-radius:10px;
                                background:linear-gradient(135deg,#16a34a,#15803d);
                                color:#fff; font-size:14px; font-weight:700; flex-shrink:0;
                                box-shadow:0 2px 8px rgba(22,163,74,0.35);">2</div>
                    <div>
                        <h2 style="font-size:16px; font-weight:700; color:#0f172a; margin:0 0 2px;">Preview Data Import</h2>
                        <p style="font-size:12px; color:#64748b; margin:0;" id="modal-subtitle">Memeriksa data...</p>
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:16px; flex-shrink:0;">
                    {{-- Legenda --}}
                    <div id="legend-area" style="display:flex; align-items:center; gap:12px;">
                        <span style="display:flex; align-items:center; gap:6px; font-size:11px; color:#475569; font-weight:500;">
                            <span style="width:12px;height:12px;border-radius:4px;background:#fecaca;display:inline-block;border:1px solid #fca5a5;"></span>
                            Kosong wajib
                        </span>
                        <span style="display:flex; align-items:center; gap:6px; font-size:11px; color:#475569; font-weight:500;">
                            <span style="width:12px;height:12px;border-radius:4px;background:#fef08a;display:inline-block;border:1px solid #fde047;"></span>
                            Tidak ada di DB
                        </span>
                        <span style="display:flex; align-items:center; gap:6px; font-size:11px; color:#475569; font-weight:500;">
                            <span style="width:12px;height:12px;border-radius:4px;background:#e9d5ff;display:inline-block;border:1px solid #d8b4fe;"></span>
                            Duplikat di DB
                        </span>
                    </div>

                    <button onclick="closeModal()"
                        style="width:34px; height:34px; border-radius:10px; border:1px solid #e2e8f0;
                               background:#f8fafc; cursor:pointer; display:flex; align-items:center;
                               justify-content:center; flex-shrink:0; transition:all 0.15s;"
                        onmouseover="this.style.background='#fee2e2';this.style.borderColor='#fca5a5'"
                        onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                        <svg width="14" height="14" fill="none" stroke="#64748b" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Validation Result Panel --}}
            <div id="modal-validation"
                style="flex-shrink:0; display:none; max-height:260px; overflow-y:auto;
                       padding:0; border-bottom:1px solid #f1f5f9; background:#fafafa;">
                <div id="modal-validation-inner"></div>
            </div>

            {{-- Tabel Preview --}}
            <div style="flex:1; overflow:auto; min-height:0;">
                <table id="preview-table"
                    style="table-layout:fixed; width:max-content; min-width:100%; border-collapse:collapse;">
                    <thead style="position:sticky; top:0; z-index:20;">
                        <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                            <th style="position:sticky; left:0; z-index:30; background:#f8fafc;
                                       width:56px; min-width:56px; padding:14px 16px;
                                       text-align:center; font-size:11px; font-weight:700;
                                       letter-spacing:0.06em; text-transform:uppercase; color:#94a3b8;
                                       border-right:2px solid #e2e8f0;">#</th>
                            @foreach ($importSession['excel_headers'] as $colIdx => $header)
                                <th class="preview-th" data-col="{{ $colIdx }}"
                                    style="width:180px; min-width:180px; max-width:180px;
                                           padding:12px 16px 10px; text-align:left;
                                           border-right:1px solid #eef0f3; vertical-align:top;
                                           transition:background 0.2s;">
                                    <div style="font-size:11px; font-weight:700; letter-spacing:0.05em;
                                                text-transform:uppercase; color:#334155; white-space:nowrap;
                                                overflow:hidden; text-overflow:ellipsis;"
                                        title="{{ $header }}">{{ $header }}</div>
                                    <div class="col-mapped-label" data-col="{{ $colIdx }}"
                                        style="font-size:10px; color:#94a3b8; margin-top:4px;
                                               white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                                               font-weight:500;">tidak dipetakan</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="preview-tbody">
                        @foreach ($importSession['preview_rows'] as $rowIdx => $row)
                            <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.1s;"
                                onmouseover="this.style.background='#f8fafc'"
                                onmouseout="this.style.background=''">
                                <td style="position:sticky; left:0; z-index:10; background:inherit;
                                           width:56px; min-width:56px; padding:14px 16px;
                                           text-align:center; color:#94a3b8; font-size:13px; font-weight:600;
                                           border-right:2px solid #e2e8f0;">{{ $rowIdx + 1 }}</td>
                                @foreach ($importSession['excel_headers'] as $colIdx => $header)
                                    @php $val = $row[$colIdx] ?? null; @endphp
                                    <td class="preview-cell"
                                        data-col="{{ $colIdx }}"
                                        data-val="{{ $val }}"
                                        style="width:180px; min-width:180px; max-width:180px;
                                               padding:14px 16px; border-right:1px solid #f8fafc;
                                               overflow:hidden; transition:background 0.2s;">
                                        @if ($val === null || $val === '')
                                            <span style="color:#cbd5e1; font-size:13px;">—</span>
                                        @else
                                            <span style="display:block; overflow:hidden; text-overflow:ellipsis;
                                                         white-space:nowrap; color:#334155; font-size:13px;"
                                                title="{{ $val }}">{{ Str::limit((string) $val, 22) }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div id="preview-pagination"
                style="flex-shrink:0; display:flex; align-items:center; justify-content:space-between;
                       padding:14px 24px; border-top:1px solid #f1f5f9; background:#fafafa;">
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         MODAL 2 — KONFIRMASI IMPORT
    ══════════════════════════════════════════════════════════════ --}}
    <div id="modal-konfirmasi"
        style="display:none; position:fixed; inset:0; z-index:10000; background:rgba(15,23,42,0.6);
               backdrop-filter:blur(4px); padding:24px;"
        class="flex items-center justify-center">

        <div style="background:#fff; border-radius:20px; width:100%; max-width:480px;
                    box-shadow:0 25px 80px rgba(0,0,0,0.2); overflow:hidden;">

            <div style="padding:28px 28px 20px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                    <div style="width:40px; height:40px; border-radius:12px;
                                background:linear-gradient(135deg,#16a34a,#15803d);
                                display:flex; align-items:center; justify-content:center;
                                box-shadow:0 4px 12px rgba(22,163,74,0.3);">
                        <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <div>
                        <h3 style="font-size:16px; font-weight:700; color:#0f172a; margin:0 0 2px;">Konfirmasi Import</h3>
                        <p style="font-size:12px; color:#64748b; margin:0;">Periksa ringkasan data sebelum melanjutkan.</p>
                    </div>
                </div>
            </div>

            <div style="padding:20px 28px;">
                <p style="font-size:13px; color:#374151; margin:0 0 8px;">
                    Data yang valid akan tersimpan ke database lembaga Anda.
                </p>
            </div>

            <div style="padding:0 28px 24px; display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="closeModalKonfirmasi()"
                    style="padding:10px 22px; border-radius:10px; border:1px solid #e2e8f0;
                           background:#fff; color:#374151; font-size:13px; font-weight:500;
                           cursor:pointer; transition:all 0.15s;"
                    onmouseover="this.style.background='#f8fafc'"
                    onmouseout="this.style.background='#fff'">
                    Batal
                </button>
                <button type="button" id="btn-konfirm-lanjut" onclick="submitImport()"
                    style="padding:10px 24px; border-radius:10px; border:none;
                           background:linear-gradient(135deg,#16a34a,#15803d); color:#fff;
                           font-size:13px; font-weight:600; cursor:pointer;
                           display:flex; align-items:center; gap:8px;
                           box-shadow:0 4px 12px rgba(22,163,74,0.35); transition:all 0.15s;"
                    onmouseover="this.style.opacity='0.88'"
                    onmouseout="this.style.opacity='1'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Ya, Import Sekarang
                </button>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Konstanta dari server ───────────────────────────────────── */
    const REQUIRED_FIELDS = ['nama_lengkap', 'jenis_kelamin', 'alamat', 'kategori_mustahik'];
    const SYSTEM_FIELDS   = @json(array_keys($systemColumns));
    const PREVIEW_ROWS    = @json($importSession['preview_rows']);
    const TOTAL_ROWS      = {{ $importSession['total_rows'] }};
    const ROWS_PER_PAGE   = 20;

    const AJAX = {
        kategori : '{{ route('mustahik.import.cekKategori') }}',
        nik      : '{{ route('mustahik.import.cekNik') }}',
    };

    /* ── Elemen DOM ─────────────────────────────────────────────── */
    const selects         = document.querySelectorAll('.mapping-select');
    const btnCek          = document.getElementById('btn-cek-pemetaan');
    const btnImport       = document.getElementById('btn-import');
    const banner          = document.getElementById('validation-banner');
    const bannerInner     = document.getElementById('validation-banner-inner');
    const modalEl         = document.getElementById('modal-preview');
    const modalKonfirmasi = document.getElementById('modal-konfirmasi');
    const modalValidation = document.getElementById('modal-validation');
    const modalValInner   = document.getElementById('modal-validation-inner');
    const spinner         = document.getElementById('cek-spinner');
    const btnLabel        = document.getElementById('btn-cek-label');
    const pagEl           = document.getElementById('preview-pagination');
    const modalSubtitle   = document.getElementById('modal-subtitle');

    let currentPage = 1;

    /* ── Modal Preview ──────────────────────────────────────────── */
    window.openModal = function () {
        modalEl.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        currentPage = 1;
        renderPagination();
    };

    window.closeModal = function () {
        modalEl.style.display = 'none';
        document.body.style.overflow = '';
    };

    modalEl.addEventListener('click', function (e) {
        if (e.target === modalEl) closeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeModal(); closeModalKonfirmasi(); }
    });

    /* ── Modal Konfirmasi ───────────────────────────────────────── */
    window.openModalKonfirmasi = function () {
        modalKonfirmasi.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    window.closeModalKonfirmasi = function () {
        modalKonfirmasi.style.display = 'none';
        document.body.style.overflow = '';
    };

    modalKonfirmasi.addEventListener('click', function (e) {
        if (e.target === modalKonfirmasi) closeModalKonfirmasi();
    });

    /* ── Submit form ────────────────────────────────────────────── */
    window.submitImport = function () {
        var btn = document.getElementById('btn-konfirm-lanjut');
        btn.disabled = true;
        btn.innerHTML =
            '<svg class="animate-spin" width="15" height="15" fill="none" viewBox="0 0 24 24">'
            + '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>'
            + '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>'
            + ' Mengimport...';

        var form  = document.getElementById('form-import');
        var added = {};

        selects.forEach(function (sel) { sel.removeAttribute('name'); });
        form.querySelectorAll('input[type=hidden][name^="mapping["]').forEach(function (i) { i.remove(); });

        selects.forEach(function (sel) {
            var excelIdx = sel.value;
            var sysField = SYSTEM_FIELDS[parseInt(sel.dataset.sysidx)];
            if (excelIdx !== '' && !added[excelIdx] && sysField) {
                var inp  = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'mapping[' + excelIdx + ']';
                inp.value = sysField;
                form.appendChild(inp);
                added[excelIdx] = true;
            }
        });

        form.submit();
    };

    /* ── Pagination ─────────────────────────────────────────────── */
    function renderPagination() {
        var allRows    = document.querySelectorAll('#preview-tbody tr');
        var totalRows  = allRows.length;
        var totalPages = Math.ceil(totalRows / ROWS_PER_PAGE);

        allRows.forEach(function (tr, i) {
            var inPage = i >= (currentPage - 1) * ROWS_PER_PAGE && i < currentPage * ROWS_PER_PAGE;
            tr.style.display = inPage ? '' : 'none';
        });

        var start = (currentPage - 1) * ROWS_PER_PAGE + 1;
        var end   = Math.min(currentPage * ROWS_PER_PAGE, totalRows);

        var pageButtons = '';
        var maxVisible  = 5;
        var startPage   = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        var endPage     = Math.min(totalPages, startPage + maxVisible - 1);
        if (endPage - startPage < maxVisible - 1) startPage = Math.max(1, endPage - maxVisible + 1);

        for (var p = startPage; p <= endPage; p++) {
            var active = p === currentPage;
            pageButtons += '<button onclick="goPage(' + p + ')"'
                + ' style="min-width:34px; height:34px; padding:0 10px; border-radius:8px; cursor:pointer; font-size:12px;'
                + 'border:1.5px solid ' + (active ? '#16a34a' : '#e2e8f0') + ';'
                + 'background:' + (active ? '#16a34a' : '#fff') + ';'
                + 'color:' + (active ? '#fff' : '#64748b') + ';'
                + 'font-weight:' + (active ? '700' : '500') + '; transition:all 0.15s;">'
                + p + '</button>';
        }

        var btnBase = 'height:34px; padding:0 12px; border-radius:8px; font-size:12px; font-weight:500;'
            + 'border:1.5px solid #e2e8f0; transition:all 0.15s; display:inline-flex; align-items:center; gap:4px;';
        var prevDis = currentPage === 1;
        var nextDis = currentPage === totalPages;

        pagEl.innerHTML =
            '<span style="font-size:12px; color:#64748b; font-weight:500;">'
            + 'Menampilkan <strong style="color:#0f172a;">' + start + '–' + end + '</strong>'
            + ' dari <strong style="color:#0f172a;">' + totalRows + '</strong> baris preview'
            + (TOTAL_ROWS > totalRows ? ' <span style="color:#94a3b8;">(total ' + TOTAL_ROWS + ' baris)</span>' : '')
            + '</span>'
            + '<div style="display:flex; gap:6px; align-items:center;">'
            + '<button onclick="goPage(' + (currentPage - 1) + ')"' + (prevDis ? ' disabled' : '')
            + ' style="' + btnBase + 'cursor:' + (prevDis ? 'not-allowed' : 'pointer') + ';'
            + 'background:' + (prevDis ? '#f8fafc' : '#fff') + ';'
            + 'color:' + (prevDis ? '#cbd5e1' : '#374151') + ';">'
            + '<svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>'
            + 'Prev</button>'
            + pageButtons
            + '<button onclick="goPage(' + (currentPage + 1) + ')"' + (nextDis ? ' disabled' : '')
            + ' style="' + btnBase + 'cursor:' + (nextDis ? 'not-allowed' : 'pointer') + ';'
            + 'background:' + (nextDis ? '#f8fafc' : '#fff') + ';'
            + 'color:' + (nextDis ? '#cbd5e1' : '#374151') + ';">'
            + 'Next'
            + '<svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>'
            + '</button>'
            + '</div>';
    }

    window.goPage = function (page) {
        var allRows    = document.querySelectorAll('#preview-tbody tr');
        var totalPages = Math.ceil(allRows.length / ROWS_PER_PAGE);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderPagination();
    };

    /* ── Helpers ────────────────────────────────────────────────── */
    function getMapping() {
        var map = {};
        selects.forEach(function (sel) {
            if (sel.value !== '') map[sel.dataset.field] = parseInt(sel.value);
        });
        return map;
    }

    /**
     * Ambil nilai unik dari kolom tertentu di PREVIEW_ROWS.
     * FIX: tolak object/array, kosong, dan "null"/"undefined" string.
     */
    function uniqueValsInCol(colIdx) {
        return Array.from(new Set(
            PREVIEW_ROWS
                .map(function (row) {
                    var v = row[colIdx];
                    if (v === null || v === undefined) return '';
                    if (typeof v === 'object') return '';   // tolak rich-text object dari PhpSpreadsheet
                    var str = String(v).trim();
                    if (str === 'null' || str === 'undefined') return '';
                    return str;
                })
                .filter(function (v) { return v !== ''; })
        ));
    }

    function postJson(url, body) {
        return fetch(url, {
            method  : 'POST',
            headers : {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : (document.querySelector('meta[name="csrf-token"]') || {}).content || '',
                'Accept'       : 'application/json',
            },
            body: JSON.stringify(body),
        }).then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        });
    }

    /* ── Update header tabel ────────────────────────────────────── */
    function updateTableHeaders(mapping) {
        var colToLabel = {};
        Object.keys(mapping).forEach(function (fieldKey) {
            var colIdx = mapping[fieldKey];
            var lbl    = document.querySelector('label[for="map_' + fieldKey + '"]');
            colToLabel[colIdx] = lbl ? lbl.textContent.replace('*', '').trim() : fieldKey;
        });

        document.querySelectorAll('.col-mapped-label').forEach(function (el) {
            var col = parseInt(el.dataset.col);
            if (colToLabel[col]) {
                el.textContent      = '→ ' + colToLabel[col];
                el.style.color      = '#16a34a';
                el.style.fontWeight = '600';
            } else {
                el.textContent      = 'tidak dipetakan';
                el.style.color      = '#94a3b8';
                el.style.fontWeight = '500';
            }
        });

        document.querySelectorAll('.preview-th').forEach(function (th) {
            var col = parseInt(th.dataset.col);
            th.style.background   = colToLabel[col] ? '#f0fdf4' : '';
            th.style.borderBottom = '';
        });
    }

    /* ── Sorot sel preview ──────────────────────────────────────── */
    function highlightPreview(requiredCols, fkBadMap, dupBadMap, errorRowsByCol) {
        document.querySelectorAll('#preview-tbody tr').forEach(function (tr) {
            tr.querySelectorAll('.preview-cell').forEach(function (td) {
                var col    = parseInt(td.dataset.col);
                var rawVal = String(td.dataset.val == null ? '' : td.dataset.val).trim();
                var empty  = rawVal === '' || rawVal === 'null';
                td.style.background = '';
                td.style.outline    = '';

                if (requiredCols.has(col) && empty) {
                    td.style.background = '#fef2f2';
                } else if (dupBadMap.has(col) && !empty && dupBadMap.get(col).has(rawVal)) {
                    td.style.background = '#faf5ff';
                } else if (fkBadMap.has(col) && !empty && fkBadMap.get(col).has(rawVal.toLowerCase())) {
                    td.style.background = '#fefce8';
                }
            });
        });

        document.querySelectorAll('.preview-th').forEach(function (th) {
            var col = parseInt(th.dataset.col);
            if (errorRowsByCol && errorRowsByCol.has(col)) {
                var lbl = th.querySelector('.col-mapped-label');
                if (lbl) lbl.style.color = '#ef4444';
            }
        });
    }

    /* ── Build validation HTML ──────────────────────────────────── */
    function buildValidationHTML(errors, warnings) {
        if (errors.length === 0 && warnings.length === 0) {
            return '<div style="display:flex; align-items:center; gap:12px; padding:16px 24px;">'
                + '<svg width="18" height="18" fill="none" stroke="#16a34a" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>'
                + '</svg>'
                + '<span style="font-size:13px; font-weight:600; color:#16a34a;">Semua pemeriksaan lolos — klik <em>Import Data Mustahik</em> untuk melanjutkan.</span>'
                + '</div>';
        }

        var html = '';

        if (errors.length > 0) {
            var errorItems = errors.map(function (e, idx) {
                return '<div style="padding:12px 0;'
                    + (idx < errors.length - 1 ? 'border-bottom:1px solid #f3f4f6;' : '') + '">'
                    + '<div style="font-size:12px; color:#374151; line-height:1.8;">' + e + '</div>'
                    + '</div>';
            }).join('');

            html += '<div style="padding:14px 24px;">'
                + '<div style="display:flex; align-items:center; gap:6px; margin-bottom:10px;">'
                + '<svg width="13" height="13" fill="none" stroke="#dc2626" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>'
                + '</svg>'
                + '<span style="font-size:12px; font-weight:700; color:#dc2626;">'
                + errors.length + ' masalah ditemukan — import diblokir hingga diperbaiki</span>'
                + '</div>'
                + errorItems
                + '</div>';
        }

        if (warnings.length > 0) {
            var warnItems = warnings.map(function (w, idx) {
                return '<div style="padding:10px 0;'
                    + (idx < warnings.length - 1 ? 'border-bottom:1px solid #f3f4f6;' : '') + '">'
                    + '<div style="font-size:12px; color:#374151; line-height:1.7;">' + w + '</div>'
                    + '</div>';
            }).join('');

            html += '<div style="padding:14px 24px;'
                + (errors.length > 0 ? 'border-top:1px solid #f3f4f6;' : '') + '">'
                + '<div style="display:flex; align-items:center; gap:6px; margin-bottom:10px;">'
                + '<svg width="13" height="13" fill="none" stroke="#f59e0b" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>'
                + '</svg>'
                + '<span style="font-size:12px; font-weight:700; color:#92400e;">'
                + warnings.length + ' peringatan</span>'
                + '</div>'
                + warnItems
                + '</div>';
        }

        return html;
    }

    /* ── Update subtitle modal ──────────────────────────────────── */
    function updateSubtitle(errors, warnings, totalRows) {
        if (errors.length === 0 && warnings.length === 0) {
            modalSubtitle.innerHTML = '<span style="color:#16a34a; font-weight:600;">✓ Siap diimport</span>'
                + ' — ' + totalRows + ' baris data';
        } else if (errors.length > 0) {
            modalSubtitle.innerHTML = '<span style="color:#dc2626; font-weight:600;">'
                + errors.length + ' masalah ditemukan</span>'
                + (warnings.length > 0 ? ', ' + warnings.length + ' peringatan' : '')
                + ' — ' + totalRows + ' baris';
        } else {
            modalSubtitle.innerHTML = '<span style="color:#f59e0b; font-weight:600;">'
                + warnings.length + ' peringatan</span>'
                + ' — ' + totalRows + ' baris data';
        }
    }

    /* ── Cari nomor baris yang berisi nilai tertentu ────────────── */
    function findRowsWithVal(colIdx, badVals, exact) {
        var result = [];
        PREVIEW_ROWS.forEach(function (row, i) {
            var v     = String(row[colIdx] == null ? '' : row[colIdx]).trim();
            var match = exact ? badVals.has(v) : badVals.has(v.toLowerCase());
            if (v && match) result.push(i + 1);
        });
        return result;
    }

    function colLabel(fieldKey) {
        var el = document.querySelector('label[for="map_' + fieldKey + '"]');
        return el ? el.textContent.replace('*', '').trim() : fieldKey;
    }

    /* ── Banner ringkas di card ──────────────────────────────────── */
    function buildBannerSummary(errors, warnings) {
        if (errors.length > 0) {
            return '<div style="display:flex; align-items:center; gap:8px; padding:10px 14px;'
                + ' background:#fef2f2; border-radius:8px;">'
                + '<svg width="13" height="13" fill="none" stroke="#dc2626" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>'
                + '</svg>'
                + '<span style="font-size:12px; font-weight:600; color:#dc2626;">'
                + errors.length + ' masalah ditemukan — klik "Cek Pemetaan &amp; Preview" untuk melihat detail</span>'
                + '</div>';
        }
        if (warnings.length > 0) {
            return '<div style="display:flex; align-items:center; gap:8px; padding:10px 14px;'
                + ' background:#fffbeb; border-radius:8px;">'
                + '<svg width="13" height="13" fill="none" stroke="#f59e0b" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>'
                + '</svg>'
                + '<span style="font-size:12px; font-weight:600; color:#92400e;">'
                + warnings.length + ' peringatan</span>'
                + '</div>';
        }
        return '';
    }

    /* ── Validasi utama ─────────────────────────────────────────── */
    function runValidation() {
        btnCek.disabled      = true;
        btnImport.disabled   = true;
        spinner.classList.remove('hidden');
        btnLabel.textContent = 'Memeriksa...';

        var mapping        = getMapping();
        var errors         = [];
        var warnings       = [];
        var errorRowsByCol = new Map();

        /* 1. Cek kolom wajib terpetakan & tidak kosong */
        REQUIRED_FIELDS.forEach(function (rf) {
            var sel = document.querySelector('[data-field="' + rf + '"]');
            if (mapping[rf] === undefined) {
                errors.push('Kolom <strong>"' + colLabel(rf) + '"</strong> wajib dipetakan namun belum dipilih. Silakan pilih kolom yang sesuai dari dropdown di atas.');
                if (sel) {
                    sel.style.borderColor = '#ef4444';
                    sel.style.boxShadow   = '0 0 0 3px rgba(239,68,68,0.15)';
                }
            } else {
                var col       = mapping[rf];
                var emptyRows = [];
                PREVIEW_ROWS.forEach(function (row, i) {
                    var v = String(row[col] == null ? '' : row[col]).trim();
                    if (v === '' || v === 'null') emptyRows.push(i + 1);
                });
                if (emptyRows.length > 0) {
                    var rowsDisplay = emptyRows.length > 10
                        ? emptyRows.slice(0, 10).join(', ') + '... (+' + (emptyRows.length - 10) + ' lainnya)'
                        : emptyRows.join(', ');
                    errors.push(
                        'Kolom <strong>"' + colLabel(rf) + '"</strong> wajib diisi, namun <strong>'
                        + emptyRows.length + ' baris kosong</strong>.'
                        + '<br><span style="color:#9ca3af; font-size:11px; margin-top:2px; display:block;">Baris: ' + rowsDisplay + '</span>'
                    );
                    if (!errorRowsByCol.has(col)) errorRowsByCol.set(col, emptyRows);
                }
                if (sel) {
                    sel.style.borderColor = '';
                    sel.style.boxShadow   = '';
                }
            }
        });

        /* 2. Cek duplikat pemetaan kolom Excel */
        var usedCols = {};
        selects.forEach(function (sel) {
            if (!sel.value) return;
            if (usedCols[sel.value]) {
                var optText = (sel.options[sel.selectedIndex] || {}).text || sel.value;
                errors.push('Kolom Excel <strong>"' + optText + '"</strong> dipetakan ke lebih dari satu field sistem. Setiap kolom Excel hanya boleh dipetakan ke satu field.');
            } else {
                usedCols[sel.value] = sel;
            }
        });

        var fkBadMap  = new Map();
        var dupBadMap = new Map();
        var checks    = [];

        /* 3. Validasi Kategori — AJAX ke DB */
        if (mapping['kategori_mustahik'] !== undefined) {
            var colK = mapping['kategori_mustahik'];

            // FIX: filter bersih sebelum kirim ke server
            var valsKClean = uniqueValsInCol(colK).filter(function (v) {
                return typeof v === 'string' && v.trim() !== '' && v !== 'null';
            });

            if (valsKClean.length > 0) {
                checks.push(
                    postJson(AJAX.kategori, { values: valsKClean })
                        .then(function (res) {
                            if (res.not_found && res.not_found.length > 0) {
                                var badSet = new Set(res.not_found.map(function (v) { return v.toLowerCase(); }));
                                fkBadMap.set(colK, badSet);

                                var notFoundItems = res.not_found.map(function (badVal) {
                                    var rows = findRowsWithVal(colK, new Set([badVal.toLowerCase()]), false);
                                    var rowsDisplay = rows.length > 10
                                        ? rows.slice(0, 10).join(', ') + ' ... (+' + (rows.length - 10) + ' lainnya)'
                                        : rows.join(', ');
                                    return '<div style="display:flex; align-items:baseline; gap:8px; padding:5px 0; border-bottom:1px solid #f3f4f6;">'
                                        + '<span style="font-size:12px; color:#111827; font-weight:500; flex-shrink:0;">"' + badVal + '"</span>'
                                        + '<span style="font-size:11px; color:#9ca3af;">— baris: ' + rowsDisplay + '</span>'
                                        + '</div>';
                                }).join('');

                                var validList = (res.all_kategori || []).map(function (k) {
                                    return '<span style="display:inline-block; font-size:11px; color:#374151;'
                                        + ' background:#f3f4f6; border-radius:5px; padding:2px 8px; margin:2px 2px;">'
                                        + k + '</span>';
                                }).join('');

                                errors.push(
                                    'Kolom <strong>"' + colLabel('kategori_mustahik') + '"</strong> — '
                                    + '<strong>' + res.not_found.length + ' nilai tidak ditemukan</strong> di database:<br>'
                                    + '<div style="margin-top:8px;">' + notFoundItems + '</div>'
                                    + '<div style="margin-top:10px; padding-top:10px; border-top:1px solid #f3f4f6;">'
                                    + '<p style="font-size:11px; font-weight:600; color:#6b7280; margin:0 0 6px; text-transform:uppercase; letter-spacing:0.05em;">Kategori yang tersedia di sistem:</p>'
                                    + '<div style="line-height:2;">'
                                    + (validList || '<span style="font-size:11px; color:#9ca3af;">Tidak ada kategori terdaftar</span>')
                                    + '</div></div>'
                                );
                                if (!errorRowsByCol.has(colK)) errorRowsByCol.set(colK, []);
                            }
                        })
                        .catch(function () {
                            warnings.push('Tidak dapat memverifikasi kategori ke database. Periksa koneksi internet Anda.');
                        })
                );
            }
        }

        /* 4. Validasi NIK — AJAX ke DB */
        if (mapping['nik'] !== undefined) {
            var colN  = mapping['nik'];
            var valsN = uniqueValsInCol(colN).filter(function (v) {
                return v.replace(/\D/g, '').length >= 10;
            });

            if (valsN.length > 0) {
                checks.push(
                    postJson(AJAX.nik, { values: valsN })
                        .then(function (res) {
                            if (res.duplicates && res.duplicates.length > 0) {
                                var dupSet = new Set(res.duplicates);
                                dupBadMap.set(colN, dupSet);

                                var details = res.duplicates.map(function (nik) {
                                    var rows = findRowsWithVal(colN, new Set([nik]), true);
                                    var rowsDisplay = rows.length > 8
                                        ? rows.slice(0, 8).join(', ') + '... (+' + (rows.length - 8) + ')'
                                        : rows.join(', ');
                                    return '<div style="display:flex; align-items:baseline; gap:6px; margin:3px 0;">'
                                        + '<code style="font-size:11px; background:#f3e8ff; color:#7c3aed;'
                                        + ' padding:1px 7px; border-radius:5px; font-weight:600; flex-shrink:0;">'
                                        + nik + '</code>'
                                        + '<span style="color:#94a3b8; font-size:11px;">baris: ' + rowsDisplay + '</span>'
                                        + '</div>';
                                }).join('');

                                errors.push(
                                    'Kolom <strong>"NIK"</strong> — <strong>' + res.duplicates.length
                                    + ' NIK sudah terdaftar</strong> di database.'
                                    + ' Hapus atau perbaiki baris berikut sebelum import:<br>'
                                    + '<div style="margin-top:6px; padding:8px 10px; background:#fdf4ff; border-radius:8px; border:1px solid #e9d5ff;">'
                                    + details + '</div>'
                                );
                                if (!errorRowsByCol.has(colN)) errorRowsByCol.set(colN, []);
                            }
                        })
                        .catch(function () {
                            warnings.push('Tidak dapat memverifikasi NIK ke database. Periksa koneksi internet Anda.');
                        })
                );
            }
        }

        /* 5. Setelah semua AJAX selesai, render hasil */
        Promise.all(checks).then(function () {
            var validationHTML = buildValidationHTML(errors, warnings);

            // Banner di card utama
            if (errors.length > 0 || warnings.length > 0) {
                bannerInner.innerHTML = buildBannerSummary(errors, warnings);
                banner.classList.remove('hidden');
            } else {
                banner.classList.add('hidden');
            }

            // Panel di dalam modal
            modalValInner.innerHTML       = validationHTML;
            modalValidation.style.display = (errors.length > 0 || warnings.length > 0) ? 'block' : 'none';

            var requiredCols = new Set(
                REQUIRED_FIELDS
                    .filter(function (rf) { return mapping[rf] !== undefined; })
                    .map(function (rf) { return mapping[rf]; })
            );

            highlightPreview(requiredCols, fkBadMap, dupBadMap, errorRowsByCol);
            updateTableHeaders(mapping);
            updateSubtitle(errors, warnings, PREVIEW_ROWS.length);

            btnImport.disabled   = errors.length > 0;
            btnCek.disabled      = false;
            spinner.classList.add('hidden');
            btnLabel.textContent = 'Cek Pemetaan & Preview';

            openModal();
        });
    }

    /* ── Event Listeners ────────────────────────────────────────── */
    btnCek.addEventListener('click', runValidation);

    selects.forEach(function (sel) {
        sel.addEventListener('change', function () {
            this.style.borderColor = '';
            this.style.boxShadow   = '';
            btnImport.disabled     = true;
            banner.classList.add('hidden');
        });
    });

    /* ── Init ───────────────────────────────────────────────────── */
    updateTableHeaders(getMapping());

})();
</script>
@endpush