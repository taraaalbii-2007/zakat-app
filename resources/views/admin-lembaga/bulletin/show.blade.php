@extends('layouts.app')

@section('title', 'Detail Bulletin - ' . Str::limit($bulletin->judul, 50))

@push('styles')
<style>
    .bulletin-content { font-size: 15px; line-height: 1.7; color: #1f2937; }
    .bulletin-content p { margin-bottom: 1rem; }
    .bulletin-content p:empty { display: none; }
    .bulletin-content h1 { font-size: 1.75rem; font-weight: 700; margin: 2rem 0 1rem; }
    .bulletin-content h2 { font-size: 1.5rem; font-weight: 700; margin: 1.75rem 0 0.875rem; }
    .bulletin-content h3 { font-size: 1.25rem; font-weight: 600; margin: 1.5rem 0 0.75rem; }
    .bulletin-content ul, .bulletin-content ol { margin-left: 1.5rem; margin-bottom: 1rem; }
    .bulletin-content ul { list-style-type: disc; }
    .bulletin-content ol { list-style-type: decimal; }
    .bulletin-content blockquote { border-left: 4px solid #3b82f6; padding: 1rem 1.25rem; margin: 1.25rem 0; background: #f9fafb; border-radius: .375rem; font-style: italic; }
    .bulletin-content img { max-width: 100%; border-radius: .5rem; margin: 1.5rem auto; display: block; }
</style>
@endpush

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100">
            <div class="mb-3">
                <a href="{{ route('admin-lembaga.bulletin.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            {{-- Status Banner --}}
            @if($bulletin->isPending())
                <div class="mb-4 px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800">Menunggu Persetujuan Superadmin</p>
                        <p class="text-xs text-yellow-700 mt-0.5">Bulletin ini sedang direview. Anda tidak dapat mengeditnya saat ini.</p>
                    </div>
                </div>
            @elseif($bulletin->isApproved())
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-green-800">Bulletin Disetujui & Aktif</p>
                        <p class="text-xs text-green-700 mt-0.5">Bulletin ini sudah tampil di landing page website.</p>
                    </div>
                </div>
            @elseif($bulletin->isRejected())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800">Bulletin Ditolak</p>
                        @if($bulletin->rejection_reason)
                            <p class="text-xs text-red-700 mt-0.5">
                                <span class="font-medium">Alasan:</span> {{ $bulletin->rejection_reason }}
                            </p>
                        @endif
                        <p class="text-xs text-red-600 mt-1">Silakan edit bulletin dan ajukan ulang.</p>
                    </div>
                </div>
            @elseif($bulletin->isDraft())
                <div class="mb-4 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Status: Draft</p>
                        <p class="text-xs text-gray-500 mt-0.5">Bulletin ini belum dikirim. Klik "Ajukan ke Superadmin" untuk meminta persetujuan.</p>
                    </div>
                </div>
            @endif

            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight mb-3">{{ $bulletin->judul }}</h1>

            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ ($bulletin->published_at ?? $bulletin->created_at)->format('d M Y') }}
                </div>
                @if($bulletin->lokasi)
                    <span class="text-gray-300">•</span>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $bulletin->lokasi }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Thumbnail --}}
        @if($bulletin->thumbnail)
            <div class="w-full">
                <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="{{ $bulletin->judul }}"
                     class="w-full h-auto max-h-[500px] object-contain bg-gray-100">
                @if($bulletin->image_caption)
                    <div class="px-4 sm:px-6 py-2.5 bg-gray-50 border-t border-gray-100">
                        <p class="text-sm text-gray-600 italic text-center">{{ $bulletin->image_caption }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Konten --}}
        <article class="px-4 sm:px-6 py-6 sm:py-8">
            <div class="bulletin-content">
                {!! $bulletin->konten !!}
            </div>
        </article>

        {{-- Footer Actions --}}
        <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-gray-50">
            <div class="flex flex-wrap items-center justify-between gap-3">
                @if($bulletin->kategoriBulletin)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-100">
                        {{ $bulletin->kategoriBulletin->nama_kategori }}
                    </span>
                @else
                    <div></div>
                @endif

                <div class="flex flex-wrap gap-2">
                    {{-- Edit --}}
                    @if($bulletin->isEditable())
                        <a href="{{ route('admin-lembaga.bulletin.edit', $bulletin->uuid) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                    {{-- Ajukan --}}
                    @if($bulletin->isDraft() || $bulletin->isRejected())
                        <form method="POST" action="{{ route('admin-lembaga.bulletin.submit', $bulletin->uuid) }}">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Kirim bulletin ini untuk persetujuan superadmin?')"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Ajukan ke Superadmin
                            </button>
                        </form>
                    @endif
                    {{-- Hapus --}}
                    @if(!$bulletin->isApproved())
                        <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-xl bg-white">
        <div class="flex justify-center mb-4">
            <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Bulletin</h3>
        <p class="text-sm text-gray-500 mb-6 text-center">Apakah Anda yakin? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="px-5 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form method="POST" action="{{ route('admin-lembaga.bulletin.destroy', $bulletin->uuid) }}">
                @csrf @method('DELETE')
                <button type="submit" class="px-5 py-2 rounded-lg bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('keydown', e => { if (e.key === 'Escape') document.getElementById('delete-modal').classList.add('hidden'); });
document.getElementById('delete-modal')?.addEventListener('click', function(e) { if (e.target === this) this.classList.add('hidden'); });
</script>
@endpush