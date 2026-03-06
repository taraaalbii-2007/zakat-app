{{-- resources/views/muzakki/testimoni/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Testimoni Saya')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ── Main Card ── --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Testimoni Saya</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $testimonis->total() }} Testimoni</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('muzakki.testimoni.create') }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="hidden sm:inline">Tulis Testimoni</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($testimonis->count() > 0)

            {{-- ── Desktop View ── --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-12 px-4 py-3"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Testimoni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($testimonis as $t)
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
                                data-target="detail-{{ $t->id }}">
                                <td class="px-4 py-4">
                                    <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex-1">
                                        {{-- Bintang --}}
                                        <div class="flex items-center gap-0.5 mb-1.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-1.5 text-xs text-gray-500 font-medium">{{ $t->rating }}/5</span>
                                        </div>

                                        <div class="text-sm font-medium text-gray-900 line-clamp-1">
                                            {{ $t->isi_testimoni }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $t->created_at->format('d/m/Y') }}
                                            @if ($t->pekerjaan)
                                                &middot; {{ $t->pekerjaan }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">Klik baris untuk melihat detail</div>
                                    </div>
                                </td>
                            </tr>

                            {{-- Expandable Content --}}
                            <tr id="detail-{{ $t->id }}" class="hidden expandable-content">
                                <td colspan="2" class="px-0 py-0">
                                    <div class="bg-gray-50 border-y border-gray-100">
                                        <div class="px-6 py-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                                {{-- Kolom 1: Detail Testimoni --}}
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Detail Testimoni</h4>
                                                    <div class="space-y-3">
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Nama Tampil</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $t->nama_pengirim }}</p>
                                                            </div>
                                                        </div>
                                                        @if ($t->pekerjaan)
                                                            <div class="flex items-start gap-2">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Pekerjaan</p>
                                                                    <p class="text-sm text-gray-900">{{ $t->pekerjaan }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Tanggal Kirim</p>
                                                                <p class="text-sm text-gray-900">{{ $t->created_at->format('d F Y, H:i') }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-yellow-400 mt-0.5 flex-shrink-0 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Rating</p>
                                                                <div class="flex items-center gap-0.5 mt-0.5">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <svg class="w-4 h-4 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                        </svg>
                                                                    @endfor
                                                                    <span class="ml-1 text-sm font-semibold text-gray-700">{{ $t->rating }}/5</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Isi Testimoni --}}
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Isi Testimoni</h4>
                                                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $t->isi_testimoni }}</p>
                                                    </div>
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

            {{-- ── Mobile View ── --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach ($testimonis as $t)
                    <div class="expandable-card">
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-{{ $t->id }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    {{-- Bintang --}}
                                    <div class="flex items-center gap-0.5 mb-1.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-1 text-xs text-gray-500">{{ $t->rating }}/5</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $t->isi_testimoni }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $t->created_at->format('d/m/Y') }}
                                        @if ($t->pekerjaan) &middot; {{ $t->pekerjaan }} @endif
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile flex-shrink-0 mt-1"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <div id="detail-mobile-{{ $t->id }}" class="hidden expandable-content-mobile">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Nama Tampil</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $t->nama_pengirim }}</p>
                                </div>
                                @if ($t->pekerjaan)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Pekerjaan</p>
                                        <p class="text-sm text-gray-900">{{ $t->pekerjaan }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Rating</p>
                                    <div class="flex items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-1 text-sm font-semibold text-gray-700">{{ $t->rating }}/5</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Kirim</p>
                                    <p class="text-sm text-gray-900">{{ $t->created_at->format('d F Y, H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1.5">Isi Testimoni</p>
                                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $t->isi_testimoni }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($testimonis->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $testimonis->withQueryString()->links() }}
                </div>
            @endif

        @else
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-50 mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h3 class="text-base font-medium text-gray-900 mb-2">Belum Ada Testimoni</h3>
                <p class="text-sm text-gray-500 mb-5">Bagikan pengalaman Anda berzakat melalui aplikasi ini.</p>
                <a href="{{ route('muzakki.testimoni.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tulis Testimoni Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.expandable-row').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, button')) return;
            var target = document.getElementById(this.dataset.target);
            var icon   = this.querySelector('.expand-icon');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-90');
        });
    });
    document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, button')) return;
            var target = document.getElementById(this.dataset.target);
            var icon   = this.querySelector('.expand-icon-mobile');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });
});
</script>
@endpush