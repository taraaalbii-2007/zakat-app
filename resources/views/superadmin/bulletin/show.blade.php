@extends('layouts.app')

@section('title', 'Detail Bulletin - ' . Str::limit($bulletin->judul, 50))

@push('styles')
<style>
    .bulletin-content { font-size: 15px; line-height: 1.7; color: #1f2937; max-width: 100%; word-wrap: break-word; }
    .bulletin-content p { margin-bottom: 1rem; }
    .bulletin-content p:empty { display: none; }
    .bulletin-content h1 { font-size: 1.75rem; font-weight: 700; margin-top: 2rem; margin-bottom: 1rem; }
    .bulletin-content h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.75rem; margin-bottom: 0.875rem; }
    .bulletin-content h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.75rem; }
    .bulletin-content ul, .bulletin-content ol { margin-left: 1.5rem; margin-bottom: 1rem; }
    .bulletin-content ul { list-style-type: disc; }
    .bulletin-content ol { list-style-type: decimal; }
    .bulletin-content blockquote { border-left: 4px solid #3b82f6; padding: 1rem 1.25rem; margin: 1.25rem 0; color: #4b5563; font-style: italic; background-color: #f9fafb; border-radius: 0.375rem; }
    .bulletin-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 1.5rem auto; display: block; }
    .bulletin-content iframe { max-width: 100%; width: 100%; height: 400px; border-radius: 0.5rem; margin: 1.25rem auto; display: block; }
</style>
@endpush

@section('content')
<div class="space-y-4 sm:space-y-5">

    {{-- APPROVAL PANEL (hanya pending) --}}
    @if($bulletin->isPending())
        <div class="bg-white rounded-xl shadow-card border border-yellow-200 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-yellow-800">Bulletin Memerlukan Persetujuan Anda</h3>
                        <p class="text-xs text-yellow-700 mt-0.5">
                            Dikirim oleh <strong>{{ $bulletin->author->username ?? '-' }}</strong>
                            @if($bulletin->lembaga) dari <strong>{{ $bulletin->lembaga->nama }}</strong>@endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="px-4 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- APPROVE --}}
                    <form method="POST" action="{{ route('superadmin.bulletin.approve', $bulletin->uuid) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Setujui bulletin ini? Bulletin akan langsung tampil di landing page.')"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Setujui Bulletin
                        </button>
                    </form>
                    {{-- REJECT --}}
                    <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tolak Bulletin
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Article Header --}}
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100">
            <div class="mb-3">
                <a href="{{ route('superadmin.bulletin.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            {{-- Status Badge --}}
            <div class="flex flex-wrap items-center gap-2 mb-3">
                @php
                    $statusBadge = [
                        'draft'    => 'bg-gray-100 text-gray-700',
                        'pending'  => 'bg-yellow-100 text-yellow-700',
                        'approved' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                    ];
                    $statusLabel = ['draft'=>'Draft','pending'=>'Menunggu Persetujuan','approved'=>'Disetujui','rejected'=>'Ditolak'];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge[$bulletin->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabel[$bulletin->status] ?? $bulletin->status }}
                </span>
                @if($bulletin->lembaga)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                        {{ $bulletin->lembaga->nama }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                        Konten Superadmin
                    </span>
                @endif
            </div>

            {{-- Rejection reason --}}
            @if($bulletin->isRejected() && $bulletin->rejection_reason)
                <div class="mb-3 px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700">
                        <span class="font-semibold">Alasan penolakan:</span> {{ $bulletin->rejection_reason }}
                    </p>
                    @if($bulletin->reviewer)
                        <p class="text-xs text-red-500 mt-1">
                            Direview oleh {{ $bulletin->reviewer->username }} pada {{ $bulletin->reviewed_at?->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            @endif

            @if($bulletin->isApproved() && $bulletin->reviewer)
                <div class="mb-3 px-4 py-3 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-xs text-green-700">
                        Disetujui oleh <strong>{{ $bulletin->reviewer->username }}</strong> pada {{ $bulletin->reviewed_at?->format('d M Y H:i') }}
                    </p>
                </div>
            @endif

            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 leading-tight mb-4">{{ $bulletin->judul }}</h1>

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ ($bulletin->published_at ?? $bulletin->created_at)->locale('id')->isoFormat('dddd, DD MMM YYYY') }}
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
                <span class="text-gray-300">•</span>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ number_format($bulletin->view_count) }} views
                </div>
            </div>
        </div>

        {{-- Thumbnail --}}
        @if($bulletin->thumbnail)
            <div class="w-full">
                <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="{{ $bulletin->judul }}"
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
                @if($bulletin->kategoriBulletin)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-100 uppercase tracking-wide">
                        {{ $bulletin->kategoriBulletin->nama_kategori }}
                    </span>
                @else
                    <div></div>
                @endif

                <div class="flex flex-wrap gap-2">
                    {{-- Edit (hanya milik superadmin atau yang editable) --}}
                    @if(is_null($bulletin->lembaga_id) || $bulletin->isEditable())
                        <a href="{{ route('superadmin.bulletin.edit', $bulletin->uuid) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                    <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
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

{{-- Reject Modal --}}
<div id="reject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-md shadow-xl rounded-xl bg-white">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tolak Bulletin</h3>
        <form method="POST" action="{{ route('superadmin.bulletin.reject', $bulletin->uuid) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason" rows="4" required
                          placeholder="Tuliskan alasan penolakan agar admin lembaga dapat memperbaiki bulletin..."
                          class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                <p class="mt-1 text-xs text-gray-400">Alasan ini akan ditampilkan kepada admin lembaga.</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                        class="px-5 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
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
        <p class="text-sm text-gray-500 mb-1 text-center">
            Apakah Anda yakin ingin menghapus "<span class="font-semibold text-gray-700">{{ Str::limit($bulletin->judul, 40) }}</span>"?
        </p>
        <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="px-5 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form method="POST" action="{{ route('superadmin.bulletin.destroy', $bulletin->uuid) }}" class="inline">
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
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('delete-modal').classList.add('hidden');
        document.getElementById('reject-modal').classList.add('hidden');
    }
});
['delete-modal','reject-modal'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
@endpush