{{-- resources/views/admin-lembaga/amil/import-pemetaan.blade.php --}}

@extends('layouts.app')

@section('title', 'Pemetaan Kolom Import Amil')

@section('content')
<div class="space-y-5">

    {{-- ── Form Utama ─────────────────────────────────────────────── --}}
    <form method="POST" action="{{ route('import.proses') }}" id="form-import">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-primary text-white text-xs font-bold shrink-0">1</span>
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
                    $pairs     = array_chunk($allFields, 2);
                @endphp

                <div class="space-y-4">
                    @foreach ($pairs as $pair)
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                            @foreach ($pair as $fieldKey)
                                @php
                                    $fieldInfo    = $systemColumns[$fieldKey];
                                    $sysIdx       = array_search($fieldKey, $allFields);
                                    $autoExcelIdx = null;
                                    foreach ($autoMapping as $eIdx => $mField) {
                                        if ($mField === $fieldKey) { $autoExcelIdx = $eIdx; break; }
                                    }
                                    $autoHeader = $autoExcelIdx !== null
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

                                    <select
                                        name="mapping[{{ $sysIdx }}]"
                                        id="map_{{ $fieldKey }}"
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
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
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

        </div>

    </form>

    {{-- Form batal standalone --}}
    <form method="POST" action="{{ route('import.batal') }}" id="form-batal" style="display:none;">
        @csrf
    </form>

    {{-- Action Buttons --}}
    <div class="flex items-center justify-end mt-5 pt-5 border-t border-gray-200 gap-3">

        <button type="button" onclick="document.getElementById('form-batal').submit()"
            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300
                   text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Batal Import
        </button>

        <button type="button" id="btn-cek-pemetaan"
            class="inline-flex items-center gap-2 px-5 py-2.5 border border-primary
                   text-sm font-medium text-primary bg-white hover:bg-primary/5
                   rounded-lg shadow-sm transition-all">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span id="btn-cek-label">Cek Pemetaan &amp; Preview</span>
            <svg id="cek-spinner" class="hidden animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
        </button>

        <button type="button" id="btn-import" disabled
            onclick="openModalKonfirmasi()"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white rounded-lg
                   bg-gradient-to-r from-primary to-primary-600 shadow-md shadow-primary/30 transition-all
                   disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none
                   enabled:hover:shadow-lg enabled:hover:shadow-primary/40">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            Import Data Amil
        </button>

    </div>
</div>

{{-- Modal Preview --}}
<div id="modal-preview"
     style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); padding:24px;"
     class="flex items-center justify-center">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:1200px;
                height:85vh; display:flex; flex-direction:column; overflow:hidden;
                box-shadow:0 20px 60px rgba(0,0,0,0.2);">

        <div style="padding:18px 24px; border-bottom:1px solid #e5e7eb; display:flex;
                    align-items:center; justify-content:space-between; flex-shrink:0; gap:16px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="display:inline-flex; align-items:center; justify-content:center;
                             width:28px; height:28px; border-radius:50%;
                             background:var(--color-primary,#16a34a);
                             color:#fff; font-size:12px; font-weight:700;">2</span>
                <h2 style="font-size:15px; font-weight:600; color:#111827; margin:0;">Preview Data Amil</h2>
            </div>
            <button onclick="closeModal()"
                style="width:32px; height:32px; border-radius:8px; border:1px solid #e5e7eb;
                       background:#f9fafb; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                onmouseover="this.style.background='#f3f4f6'"
                onmouseout="this.style.background='#f9fafb'">
                <svg width="14" height="14" fill="none" stroke="#6b7280" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="modal-validation"
             style="flex-shrink:0; display:none; max-height:220px; overflow-y:auto;
                    padding:16px 24px; border-bottom:1px solid #e5e7eb; background:#fafafa;">
            <div id="modal-validation-inner"></div>
        </div>

        <div style="flex:1; overflow:auto; min-height:0;">
            <table id="preview-table"
                   style="table-layout:fixed; width:max-content; min-width:100%; border-collapse:collapse;">
                <thead style="position:sticky; top:0; z-index:20;">
                    <tr style="background:#f8fafc; border-bottom:2px solid #e5e7eb;">
                        <th style="position:sticky; left:0; z-index:30; background:#f8fafc;
                                   width:56px; min-width:56px; padding:14px 16px;
                                   text-align:center; font-size:11px; font-weight:700;
                                   text-transform:uppercase; color:#9ca3af;
                                   border-right:1px solid #e5e7eb;">#</th>
                        @foreach ($importSession['excel_headers'] as $colIdx => $header)
                            <th class="preview-th" data-col="{{ $colIdx }}"
                                style="width:160px; min-width:160px; max-width:160px;
                                       padding:10px 16px 8px; text-align:left;
                                       border-right:1px solid #eef0f3; vertical-align:top;">
                                <div style="font-size:11px; font-weight:700; text-transform:uppercase;
                                            color:#374151; white-space:nowrap; overflow:hidden;
                                            text-overflow:ellipsis;" title="{{ $header }}">{{ $header }}</div>
                                <div class="col-mapped-label" data-col="{{ $colIdx }}"
                                     style="font-size:10px; color:#9ca3af; margin-top:4px;
                                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    tidak dipetakan
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="preview-tbody">
                    @foreach ($importSession['preview_rows'] as $rowIdx => $row)
                        <tr style="border-bottom:1px solid #f3f4f6;"
                            onmouseover="this.style.background='#f9fafb'"
                            onmouseout="this.style.background=''">
                            <td style="position:sticky; left:0; z-index:10; background:#fff;
                                       width:56px; min-width:56px; padding:14px 16px;
                                       text-align:center; color:#9ca3af; font-size:13px; font-weight:500;
                                       border-right:1px solid #e5e7eb;">{{ $rowIdx + 1 }}</td>
                            @foreach ($importSession['excel_headers'] as $colIdx => $header)
                                @php $val = $row[$colIdx] ?? null; @endphp
                                <td class="preview-cell"
                                    data-col="{{ $colIdx }}"
                                    data-val="{{ $val }}"
                                    style="width:160px; min-width:160px; max-width:160px;
                                           padding:14px 16px; border-right:1px solid #f9fafb; overflow:hidden;">
                                    @if ($val === null || $val === '')
                                        <span style="color:#d1d5db; font-size:13px;">—</span>
                                    @else
                                        <span style="display:block; overflow:hidden; text-overflow:ellipsis;
                                                     white-space:nowrap; color:#374151; font-size:13px;"
                                              title="{{ $val }}">{{ Str::limit((string) $val, 20) }}</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="preview-pagination"
             style="flex-shrink:0; display:flex; align-items:center; justify-content:space-between;
                    padding:12px 20px; border-top:1px solid #e5e7eb; background:#f9fafb;
                    font-size:12px; color:#6b7280;">
        </div>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div id="modal-konfirmasi"
     style="display:none; position:fixed; inset:0; z-index:10000; background:rgba(0,0,0,0.5); padding:24px;"
     class="flex items-center justify-center">
    <div style="background:#fff; border-radius:16px; width:100%; max-width:480px;
                box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">

        <div style="padding:24px 28px 20px; border-bottom:1px solid #f3f4f6;">
            <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0 0 4px;">Konfirmasi Import</h3>
            <p style="font-size:12px; color:#6b7280; margin:0;">Periksa ringkasan data sebelum melanjutkan.</p>
        </div>

        <div style="padding:20px 28px;">
            <p style="font-size:13px; color:#374151; margin:0 0 8px;">
                Akun login akan dibuat otomatis.
            </p>
        </div>

        <div style="padding:0 28px 24px; display:flex; gap:10px; justify-content:flex-end;">
            <button type="button" onclick="closeModalKonfirmasi()"
                style="padding:9px 20px; border-radius:8px; border:1px solid #d1d5db;
                       background:#fff; color:#374151; font-size:13px; font-weight:500; cursor:pointer;"
                onmouseover="this.style.background='#f9fafb'"
                onmouseout="this.style.background='#fff'">
                Batal
            </button>
            <button type="button" id="btn-konfirm-lanjut" onclick="submitImport()"
                style="padding:9px 22px; border-radius:8px; border:none;
                       background:#16a34a; color:#fff; font-size:13px; font-weight:600;
                       cursor:pointer; display:flex; align-items:center; gap:7px;"
                onmouseover="this.style.opacity='0.85'"
                onmouseout="this.style.opacity='1'">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
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

    const REQUIRED_FIELDS = ['nama_lengkap','jenis_kelamin','tempat_lahir','tanggal_lahir',
                             'alamat','telepon','email','status','tanggal_mulai_tugas'];
    const SYSTEM_FIELDS   = @json(array_keys($systemColumns));
    const PREVIEW_ROWS    = @json($importSession['preview_rows']);
    const TOTAL_ROWS      = {{ $importSession['total_rows'] }};
    const ROWS_PER_PAGE   = 20;

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

    let currentPage = 1;

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
    modalEl.addEventListener('click', function (e) { if (e.target === modalEl) closeModal(); });

    window.openModalKonfirmasi = function () {
        modalKonfirmasi.style.display = 'flex';
        document.body.style.overflow  = 'hidden';
    };
    window.closeModalKonfirmasi = function () {
        modalKonfirmasi.style.display = 'none';
        document.body.style.overflow  = '';
    };
    modalKonfirmasi.addEventListener('click', function (e) { if (e.target === modalKonfirmasi) closeModalKonfirmasi(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeModal(); closeModalKonfirmasi(); } });

    window.submitImport = function () {
        var btn = document.getElementById('btn-konfirm-lanjut');
        btn.disabled  = true;
        btn.innerHTML = '<svg class="animate-spin" width="15" height="15" fill="none" viewBox="0 0 24 24">'
            + '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>'
            + '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg> Mengimport...';

        var form  = document.getElementById('form-import');
        var added = {};
        selects.forEach(function (sel) { sel.removeAttribute('name'); });
        form.querySelectorAll('input[type=hidden][name^="mapping["]').forEach(function (i) { i.remove(); });
        selects.forEach(function (sel) {
            var excelIdx = sel.value;
            var sysField = SYSTEM_FIELDS[parseInt(sel.dataset.sysidx)];
            if (excelIdx !== '' && !added[excelIdx] && sysField) {
                var inp = document.createElement('input');
                inp.type  = 'hidden';
                inp.name  = 'mapping[' + excelIdx + ']';
                inp.value = sysField;
                form.appendChild(inp);
                added[excelIdx] = true;
            }
        });
        form.submit();
    };

    function renderPagination() {
        var allRows    = document.querySelectorAll('#preview-tbody tr');
        var totalRows  = allRows.length;
        var totalPages = Math.ceil(totalRows / ROWS_PER_PAGE);
        allRows.forEach(function (tr, i) {
            tr.style.display = (i >= (currentPage-1)*ROWS_PER_PAGE && i < currentPage*ROWS_PER_PAGE) ? '' : 'none';
        });
        var start = (currentPage-1)*ROWS_PER_PAGE + 1;
        var end   = Math.min(currentPage*ROWS_PER_PAGE, totalRows);
        var pageButtons = Array.from({length: totalPages}, function(_,i) {
            var p = i+1, active = p === currentPage;
            return '<button onclick="goPage('+p+')" style="padding:4px 9px;border-radius:6px;cursor:pointer;font-size:12px;'
                + 'border:1px solid '+(active?'#374151':'#e5e7eb')+';background:'+(active?'#374151':'#fff')+';'
                + 'color:'+(active?'#fff':'#6b7280')+';font-weight:'+(active?'600':'400')+';">'+p+'</button>';
        }).join('');
        var prevDis = currentPage===1, nextDis = currentPage===totalPages;
        var bs = function(d) { return 'padding:4px 10px;border-radius:6px;font-size:12px;border:1px solid #e5e7eb;'
            +'background:'+(d?'#f9fafb':'#fff')+';color:'+(d?'#d1d5db':'#374151')+';cursor:'+(d?'not-allowed':'pointer')+';'; };
        pagEl.innerHTML = '<span style="color:#6b7280;">Menampilkan <strong style="color:#374151;">'+start+'–'+end+'</strong>'
            +' dari <strong style="color:#374151;">'+totalRows+'</strong> baris</span>'
            +'<div style="display:flex;gap:5px;align-items:center;">'
            +'<button onclick="goPage('+(currentPage-1)+')"'+(prevDis?' disabled':'')+' style="'+bs(prevDis)+'">← Prev</button>'
            +pageButtons
            +'<button onclick="goPage('+(currentPage+1)+')"'+(nextDis?' disabled':'')+' style="'+bs(nextDis)+'">Next →</button>'
            +'</div>';
    }
    window.goPage = function (page) {
        var allRows = document.querySelectorAll('#preview-tbody tr');
        var totalPages = Math.ceil(allRows.length / ROWS_PER_PAGE);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderPagination();
    };

    function getMapping() {
        var map = {};
        selects.forEach(function (sel) { if (sel.value !== '') map[sel.dataset.field] = parseInt(sel.value); });
        return map;
    }

    function updateTableHeaders(mapping) {
        var colToLabel = {};
        Object.keys(mapping).forEach(function (k) {
            var lbl = document.querySelector('label[for="map_'+k+'"]');
            colToLabel[mapping[k]] = lbl ? lbl.textContent.replace('*','').trim() : k;
        });
        document.querySelectorAll('.col-mapped-label').forEach(function (el) {
            var col = parseInt(el.dataset.col);
            if (colToLabel[col]) { el.textContent='→ '+colToLabel[col]; el.style.color='#16a34a'; el.style.fontWeight='600'; }
            else                 { el.textContent='tidak dipetakan'; el.style.color='#9ca3af'; el.style.fontWeight='400'; }
        });
        document.querySelectorAll('.preview-th').forEach(function (th) {
            th.style.background = colToLabel[parseInt(th.dataset.col)] ? '#f0f9ff' : '';
        });
    }

    function highlightPreview(requiredCols) {
        document.querySelectorAll('#preview-tbody tr').forEach(function (tr) {
            tr.querySelectorAll('.preview-cell').forEach(function (td) {
                var col    = parseInt(td.dataset.col);
                var rawVal = String(td.dataset.val == null ? '' : td.dataset.val).trim();
                var empty  = rawVal === '' || rawVal === 'null';
                td.style.background = (requiredCols.has(col) && empty) ? '#fee2e2' : '';
            });
        });
    }

    function buildBannerHTML(errors, warnings) {
        if (errors.length === 0 && warnings.length === 0) {
            return '<div style="padding:12px 0;display:flex;align-items:center;gap:10px;">'
                + '<svg width="16" height="16" fill="none" stroke="#16a34a" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>'
                + '<span style="font-size:13px;color:#374151;font-weight:500;">Semua pemeriksaan lolos. Klik <strong>Import Data Amil</strong> untuk melanjutkan.</span>'
                + '</div>';
        }
        var html = '';
        if (errors.length > 0) {
            html += '<div style="margin-bottom:'+(warnings.length?'16px':'0')+'">'
                + '<p style="font-size:12px;font-weight:600;color:#dc2626;margin:0 0 8px;">'+errors.length+' masalah ditemukan — import diblokir hingga diperbaiki</p>'
                + '<ul style="list-style:none;margin:0;padding:0;font-size:12px;color:#374151;max-height:200px;overflow-y:auto;">'
                + errors.map(function(e){return '<li style="padding:6px 0;border-bottom:1px solid #f3f4f6;line-height:1.6;">'+e+'</li>';}).join('')
                + '</ul></div>';
        }
        if (warnings.length > 0) {
            html += '<div><p style="font-size:12px;font-weight:600;color:#92400e;margin:0 0 8px;">'+warnings.length+' peringatan</p>'
                + '<ul style="list-style:none;margin:0;padding:0;font-size:12px;color:#374151;max-height:200px;overflow-y:auto;">'
                + warnings.map(function(w){return '<li style="padding:6px 0;border-bottom:1px solid #f3f4f6;line-height:1.6;">'+w+'</li>';}).join('')
                + '</ul></div>';
        }
        return html;
    }

    function runValidation() {
        btnCek.disabled      = true;
        btnImport.disabled   = true;
        spinner.classList.remove('hidden');
        btnLabel.textContent = 'Memeriksa...';

        var mapping  = getMapping();
        var errors   = [];
        var warnings = [];

        REQUIRED_FIELDS.forEach(function (rf) {
            var sel = document.querySelector('[data-field="'+rf+'"]');
            if (mapping[rf] === undefined) {
                var label = (document.querySelector('label[for="map_'+rf+'"]') || {}).textContent;
                label = label ? label.replace('*','').trim() : rf;
                errors.push('Kolom <strong>"'+label+'"</strong> wajib dipetakan namun belum dipilih.');
                if (sel) { sel.style.borderColor='#ef4444'; sel.style.boxShadow='0 0 0 2px #fee2e2'; }
            } else {
                var col = mapping[rf];
                var emptyRows = [];
                PREVIEW_ROWS.forEach(function(row,i) {
                    var v = String(row[col]==null?'':row[col]).trim();
                    if (v===''||v==='null') emptyRows.push(i+1);
                });
                if (emptyRows.length > 0) {
                    var labelE = (document.querySelector('label[for="map_'+rf+'"]')||{}).textContent;
                    labelE = labelE ? labelE.replace('*','').trim() : rf;
                    errors.push('Kolom <strong>"'+labelE+'"</strong> wajib diisi — baris kosong: <strong>'+emptyRows.join(', ')+'</strong>');
                }
                if (sel) { sel.style.borderColor=''; sel.style.boxShadow=''; }
            }
        });

        var usedCols = {};
        selects.forEach(function(sel) {
            if (!sel.value) return;
            if (usedCols[sel.value]) errors.push('Kolom Excel <strong>"'+(sel.options[sel.selectedIndex]||{}).text+'"</strong> dipetakan ke lebih dari satu field.');
            else usedCols[sel.value] = sel;
        });

        var html = buildBannerHTML(errors, warnings);

        if (errors.length > 0 || warnings.length > 0) { bannerInner.innerHTML=html; banner.classList.remove('hidden'); }
        else banner.classList.add('hidden');

        modalValInner.innerHTML       = html;
        modalValidation.style.display = (errors.length > 0 || warnings.length > 0) ? 'block' : 'none';

        var requiredCols = new Set(REQUIRED_FIELDS.filter(function(rf){return mapping[rf]!==undefined;}).map(function(rf){return mapping[rf];}));
        highlightPreview(requiredCols);
        updateTableHeaders(mapping);

        btnImport.disabled   = errors.length > 0;
        btnCek.disabled      = false;
        spinner.classList.add('hidden');
        btnLabel.textContent = 'Cek Pemetaan & Preview';

        openModal();
    }

    btnCek.addEventListener('click', runValidation);
    selects.forEach(function(sel) {
        sel.addEventListener('change', function() {
            this.style.borderColor=''; this.style.boxShadow='';
            btnImport.disabled=true; banner.classList.add('hidden');
        });
    });

    updateTableHeaders(getMapping());
})();
</script>
@endpush