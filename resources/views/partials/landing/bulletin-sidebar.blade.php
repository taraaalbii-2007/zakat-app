{{-- resources/views/partials/landing/bulletin-sidebar.blade.php --}}
<aside>
    <div style="position: sticky; top: 2rem;">
        <div class="mb-4 lg:mb-6">
            <h2 class="text-base lg:text-lg font-bold text-gray-900 pb-2 lg:pb-3 border-b-2 border-green-600">
                Buletin Terkait
            </h2>
        </div>
        <div class="space-y-4 lg:space-y-5">
            @forelse($related as $rel)
                @php
                    $relThumb = $rel->thumbnail ? asset('storage/' . $rel->thumbnail) : null;
                    $relKat   = $rel->kategoriBulletin->nama_kategori ?? null;
                    $relDate  = \Carbon\Carbon::parse($rel->published_at ?? $rel->created_at)->isoFormat('DD MMM YYYY');
                @endphp
                <article class="group">
                    <a href="{{ route('artikel.show', $rel->slug) }}" class="flex gap-3">
                        <div class="w-24 lg:w-28 flex-shrink-0 overflow-hidden rounded bg-white">
                            <div class="relative w-full" style="aspect-ratio: 4/3; background-color: #f0fdf4;">
                                @if($relThumb)
                                    <img src="{{ $relThumb }}"
                                         alt="{{ $rel->judul }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-green-50">
                                        <svg class="w-8 h-8 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm lg:text-base font-bold text-gray-900 group-hover:text-green-600 transition-colors leading-tight mb-2 line-clamp-3">
                                {{ $rel->judul }}
                            </h3>
                            <div class="flex flex-col gap-0.5 text-xs text-gray-500">
                                @if($relKat)
                                    <span class="text-green-600 font-semibold text-xs">{{ $relKat }}</span>
                                @endif
                                <span class="text-xs">{{ $relDate }}</span>
                            </div>
                        </div>
                    </a>
                </article>
            @empty
                <p class="text-sm text-gray-400">Belum ada buletin terkait.</p>
            @endforelse
        </div>
    </div>
</aside>