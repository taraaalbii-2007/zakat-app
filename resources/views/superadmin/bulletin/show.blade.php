@extends('layouts.app')

@section('title', 'Detail Bulletin - ' . Str::limit($bulletin->judul, 50))

@push('styles')
<style>
    .bulletin-content {
        font-size: 15px;
        line-height: 1.7;
        color: #1f2937;
        max-width: 100%;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .bulletin-content p { margin-bottom: 1rem; }
    .bulletin-content p:empty { display: none; }
    .bulletin-content h1 { font-size: 1.75rem; font-weight: 700; margin-top: 2rem; margin-bottom: 1rem; color: #111827; line-height: 1.3; }
    .bulletin-content h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.75rem; margin-bottom: 0.875rem; color: #111827; line-height: 1.35; }
    .bulletin-content h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #111827; }
    .bulletin-content ul, .bulletin-content ol { margin-left: 1.5rem; margin-bottom: 1rem; padding-left: 0.5rem; }
    .bulletin-content ul { list-style-type: disc; }
    .bulletin-content ol { list-style-type: decimal; }
    .bulletin-content li { margin-bottom: 0.5rem; }
    .bulletin-content strong, .bulletin-content b { font-weight: 600; color: #111827; }
    .bulletin-content em, .bulletin-content i { font-style: italic; }
    .bulletin-content a { color: #2563eb; text-decoration: underline; }
    .bulletin-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1.5rem auto; display: block; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); }
    .bulletin-content blockquote { border-left: 4px solid #3b82f6; padding: 1rem 1.25rem; margin: 1.25rem 0; color: #4b5563; font-style: italic; background-color: #f9fafb; border-radius: 0.375rem; }
    .bulletin-content code { background-color: #f3f4f6; padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.875em; color: #dc2626; }
    .bulletin-content pre { background-color: #1f2937; color: #f9fafb; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin: 1.25rem 0; }
    .bulletin-content pre code { background: transparent; padding: 0; color: #f9fafb; }
    .bulletin-content iframe { max-width: 100%; width: 100%; height: 400px; border-radius: 0.5rem; margin: 1.25rem auto; display: block; }
    .bulletin-content table { width: 100%; border-collapse: collapse; margin: 1.25rem 0; font-size: 0.875rem; }
    .bulletin-content table th, .bulletin-content table td { padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: left; }
    .bulletin-content table th { background-color: #f9fafb; font-weight: 600; }

    @media (max-width: 640px) {
        .bulletin-content { font-size: 14px; }
        .bulletin-content h1 { font-size: 1.5rem; }
        .bulletin-content h2 { font-size: 1.25rem; }
        .bulletin-content iframe { height: 250px; }
    }
</style>
@endpush

@section('content')
<div class="space-y-4 sm:space-y-5">

    {{-- Main Content --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Article Header --}}
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100">

            {{-- Back button --}}
            <div class="mb-3">
                <a href="{{ route('superadmin.bulletin.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            {{-- Title --}}
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 leading-tight mb-4">
                {{ $bulletin->judul }}
            </h1>

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-xs font-bold">
                        {{ strtoupper(substr($bulletin->author->username ?? 'A', 0, 1)) }}
                    </div>
                    <span class="font-medium text-gray-700">{{ $bulletin->author->username ?? 'Admin' }}</span>
                </div>
                <span class="text-gray-300">•</span>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>
                        {{ ($bulletin->published_at ?? $bulletin->created_at)->locale('id')->isoFormat('dddd, DD MMM YYYY HH:mm') }} WIB
                    </span>
                </div>
                @if($bulletin->lokasi)
                    <span class="text-gray-300">•</span>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $bulletin->lokasi }}</span>
                    </div>
                @endif
                <span class="text-gray-300">•</span>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="font-medium">{{ number_format($bulletin->view_count) }} views</span>
                </div>
            </div>
        </div>

        {{-- Thumbnail --}}
        @if($bulletin->thumbnail)
            <div class="w-full">
                <img src="{{ Storage::url($bulletin->thumbnail) }}"
                     alt="{{ $bulletin->judul }}"
                     class="w-full h-auto max-h-[600px] object-contain bg-gray-100">
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

                {{-- Kategori Badge --}}
                @if($bulletin->kategoriBulletin)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-100 uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $bulletin->kategoriBulletin->nama_kategori }}
                    </span>
                @else
                    <div></div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('superadmin.bulletin.edit', $bulletin->uuid) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <button type="button" onclick="confirmDelete()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Delete Modal --}}
<div id="delete-modal"
     class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-xl bg-white">
        <div class="flex justify-center mb-4">
            <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Bulletin</h3>
        <p class="text-sm text-gray-500 mb-1 text-center">
            Apakah Anda yakin ingin menghapus bulletin
            "<span class="font-semibold text-gray-700">{{ Str::limit($bulletin->judul, 40) }}</span>"?
        </p>
        <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="closeDeleteModal()"
                    class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form method="POST" action="{{ route('superadmin.bulletin.destroy', $bulletin->uuid) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    document.getElementById('delete-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDeleteModal();
});

document.getElementById('delete-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush