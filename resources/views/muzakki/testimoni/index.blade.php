{{-- resources/views/muzakki/testimoni/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Testimoni Saya')

@section('content')
    <div class="space-y-6">

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Testimoni Saya</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Total: {{ $testimonis->total() }} Testimoni</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                            @if (request()->hasAny(['rating', 'start_date', 'end_date']))
                                <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold bg-green-600 text-white rounded-full">
                                    {{ collect(['rating', 'start_date', 'end_date'])->filter(fn($k) => request($k))->count() }}
                                </span>
                            @endif
                        </button>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('muzakki.testimoni.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tulis Testimoni
                        </a>
                    </div>
                </div>
            </div>

            {{-- Statistik Bar --}}
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($testimonis->total()) }}</span>
                        <span class="text-sm text-gray-500">Testimoni</span>
                    </div>

                    <!-- Stats Ringkasan Desktop -->
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            <span class="text-xs text-gray-500">Rating Rata-rata:</span>
                            <span class="text-xs font-semibold text-gray-700">
                                @php
                                    $avgRating = $testimonis->avg('rating');
                                @endphp
                                {{ number_format($avgRating, 1) }} / 5
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['rating', 'start_date', 'end_date']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('muzakki.testimoni.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Rating</label>
                            <select name="rating"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Rating</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>★★★★★ (5)</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>★★★★☆ (4)</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>★★★☆☆ (3)</option>
                                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>★★☆☆☆ (2)</option>
                                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆ (1)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['rating', 'start_date', 'end_date']))
                            <a href="{{ route('muzakki.testimoni.index') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" onclick="toggleFilter()"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>

            {{-- Active Filters Tags --}}
            @if (request()->hasAny(['rating', 'start_date', 'end_date']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('rating'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Rating: {{ request('rating') }} ★
                                <button onclick="removeFilter('rating')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('start_date'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Mulai: {{ request('start_date') }}
                                <button onclick="removeFilter('start_date')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('end_date'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Akhir: {{ request('end_date') }}
                                <button onclick="removeFilter('end_date')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($testimonis->count() > 0)

                {{-- ── DESKTOP VIEW ── --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-3 text-center w-10"></th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">TESTIMONI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($testimonis as $testimoni)
                                @php
                                    $hasNamaJiwa = false;
                                    $namaJiwaList = [];
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-green-50/20 transition-colors cursor-pointer expandable-row"
                                    data-target="detail-{{ $testimoni->id }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex-1">
                                            {{-- Rating Bintang --}}
                                            <div class="flex items-center gap-0.5 mb-1.5">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 fill-current {{ $i <= $testimoni->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @endfor
                                                <span class="ml-1.5 text-xs text-gray-500 font-medium">{{ $testimoni->rating }}/5</span>
                                            </div>

                                            {{-- Isi Testimoni (preview) --}}
                                            <div class="text-sm font-medium text-gray-800 line-clamp-2">
                                                {{ $testimoni->isi_testimoni }}
                                            </div>

                                            {{-- Info tambahan --}}
                                            <div class="text-xs text-gray-500 mt-1.5">
                                                {{ $testimoni->created_at->format('d/m/Y') }}
                                                @if ($testimoni->pekerjaan)
                                                    &middot; {{ $testimoni->pekerjaan }}
                                                @endif
                                            </div>

                                            {{-- Badges --}}
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                    {{ $testimoni->nama_pengirim }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $testimoni->id }}" class="hidden expandable-content border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Testimoni</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                {{-- Kolom 1: Informasi Pengirim --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Informasi Pengirim</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Nama Tampil</p>
                                                            <p class="text-sm font-medium text-gray-800">{{ $testimoni->nama_pengirim }}</p>
                                                        </div>
                                                        @if ($testimoni->pekerjaan)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Pekerjaan</p>
                                                                <p class="text-sm text-gray-800">{{ $testimoni->pekerjaan }}</p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal Dibuat</p>
                                                            <p class="text-sm text-gray-800">{{ $testimoni->created_at->format('d F Y, H:i') }}</p>
                                                        </div>
                                                        @if ($testimoni->updated_at != $testimoni->created_at)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Terakhir Diperbarui</p>
                                                                <p class="text-sm text-gray-800">{{ $testimoni->updated_at->format('d F Y, H:i') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Rating --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Rating</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Rating</p>
                                                            <div class="flex items-center gap-0.5 mt-1">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <svg class="w-5 h-5 fill-current {{ $i <= $testimoni->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                    </svg>
                                                                @endfor
                                                                <span class="ml-2 text-sm font-semibold text-gray-700">{{ $testimoni->rating }}/5</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Isi Testimoni (full width) --}}
                                                <div class="md:col-span-2">
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Isi Testimoni</h4>
                                                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $testimoni->isi_testimoni }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── MOBILE VIEW ── --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($testimonis as $testimoni)
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-{{ $testimoni->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    {{-- Rating Bintang --}}
                                    <div class="flex items-center gap-0.5 mb-1.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 fill-current {{ $i <= $testimoni->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-1 text-xs text-gray-500">{{ $testimoni->rating }}/5</span>
                                    </div>

                                    {{-- Preview Isi Testimoni --}}
                                    <p class="text-sm font-medium text-gray-800 line-clamp-2">
                                        {{ $testimoni->isi_testimoni }}
                                    </p>

                                    {{-- Info tambahan --}}
                                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                        <span class="text-xs text-gray-500">{{ $testimoni->created_at->format('d/m/Y') }}</span>
                                        @if ($testimoni->pekerjaan)
                                            <span class="text-xs text-gray-400">&middot; {{ $testimoni->pekerjaan }}</span>
                                        @endif
                                    </div>

                                    {{-- Badges --}}
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                            {{ $testimoni->nama_pengirim }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">Klik untuk detail</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile flex-shrink-0 mt-1"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $testimoni->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Informasi Pengirim</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Nama Tampil:</span> {{ $testimoni->nama_pengirim }}</p>
                                            @if ($testimoni->pekerjaan)
                                                <p><span class="text-gray-500">Pekerjaan:</span> {{ $testimoni->pekerjaan }}</p>
                                            @endif
                                            <p><span class="text-gray-500">Tanggal Dibuat:</span> {{ $testimoni->created_at->format('d F Y, H:i') }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Rating</h4>
                                        <div class="flex items-center gap-0.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 fill-current {{ $i <= $testimoni->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-1 text-sm font-semibold text-gray-700">{{ $testimoni->rating }}/5</span>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Isi Testimoni</h4>
                                        <div class="bg-white border border-gray-200 rounded-xl p-3">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $testimoni->isi_testimoni }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($testimonis->hasPages())
                    <div class="px-5 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $testimonis->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                    </div>

                    @if (request()->hasAny(['rating', 'start_date', 'end_date']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('muzakki.testimoni.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada testimoni yang ditulis</p>
                        <a href="{{ route('muzakki.testimoni.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tulis testimoni sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .rotate-90 {
            transform: rotate(90deg);
        }
        .rotate-180 {
            transform: rotate(180deg);
        }
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter button
            const filterBtn = document.getElementById('filterButton');
            if (filterBtn) {
                filterBtn.addEventListener('click', toggleFilter);
            }

            // ── Desktop expandable rows ──
            document.querySelectorAll('.expandable-row').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon = this.querySelector('.expand-icon');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-90');
                    }
                });
            });

            // ── Mobile expandable cards ──
            document.querySelectorAll('.expandable-row-mobile').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon = this.querySelector('.expand-icon-mobile');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    }
                });
            });
        });

        // ── Filter functions ──
        function toggleFilter() {
            const panel = document.getElementById('filter-panel');
            if (panel) panel.classList.toggle('hidden');
        }

        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
    </script>
@endpush