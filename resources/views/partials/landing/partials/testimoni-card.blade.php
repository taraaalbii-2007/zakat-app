{{-- ============================================================
     PARTIAL: Testimoni Card Modern
     resources/views/partials/landing/partials/testimoni-card.blade.php
     Variable: $t (object Testimoni), $i (index 0-based)
     ============================================================ --}}

@php
use Illuminate\Support\Str;
$initial = strtoupper(Str::substr($t->nama_pengirim, 0, 1));

// Warna-warna modern untuk avatar gradient (Tailwind-inspired)
$avatarColors = [
    ['from' => '#4f46e5', 'to' => '#7c3aed'], // indigo-600 to violet-600
    ['from' => '#db2777', 'to' => '#e11d48'], // pink-600 to rose-600
    ['from' => '#0d9488', 'to' => '#059669'], // teal-600 to emerald-600
    ['from' => '#d97706', 'to' => '#dc2626'], // amber-600 to red-600
    ['from' => '#2563eb', 'to' => '#7c3aed'], // blue-600 to violet-600
    ['from' => '#9333ea', 'to' => '#c026d3'], // purple-600 to fuchsia-600
];
$avatarColor = $avatarColors[$i % count($avatarColors)];
@endphp

<div class="swiper-slide h-auto">
    <div class="testimonial-card group">
        {{-- Decorative elements --}}
        <div class="testimonial-card__dots"></div>
        <div class="testimonial-card__glow"></div>
        
        {{-- Quote icon - Pojok Kanan Atas --}}
        <svg class="testimonial-card__quote-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 11H6C5.44772 11 5 10.5523 5 10V7C5 6.44772 5.44772 6 6 6H9C9.55228 6 10 6.44772 10 7V11ZM10 11V15C10 16.1046 9.10457 17 8 17H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M19 11H15C14.4477 11 14 10.5523 14 10V7C14 6.44772 14.4477 6 15 6H18C18.5523 6 19 6.44772 19 7V11ZM19 11V15C19 16.1046 18.1046 17 17 17H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>

        {{-- Konten utama --}}
        <div class="testimonial-card__content">
            <p class="testimonial-card__text">{{ Str::limit($t->isi_testimoni, 140) }}</p>

            {{-- Rating bintang diperbesar --}}
            <div class="testimonial-card__stars" aria-label="Rating {{ $t->rating }} dari 5">
                @for($s = 1; $s <= 5; $s++)
                    <svg class="testimonial-card__star {{ $s <= $t->rating ? 'testimonial-card__star--filled' : 'testimonial-card__star--empty' }}" 
                         viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
        </div>

        {{-- Footer: Avatar + Info --}}
        <div class="testimonial-card__footer">
            {{-- Avatar modern dengan gradient --}}
            <div class="testimonial-card__avatar" 
                 style="background: linear-gradient(135deg, {{ $avatarColor['from'] }}, {{ $avatarColor['to'] }})">
                <span class="testimonial-card__avatar-text">{{ $initial }}</span>
            </div>
            
            <div class="testimonial-card__info">
                <span class="testimonial-card__name">{{ $t->nama_pengirim }}</span>
                @if($t->pekerjaan)
                    <span class="testimonial-card__job">{{ $t->pekerjaan }}</span>
                @endif
                <span class="testimonial-card__date">
                    {{ $t->created_at ? $t->created_at->translatedFormat('d M Y') : '' }}
                </span>
            </div>
        </div>
    </div>
</div>

@once
<style>
.testimonial-card {
    position: relative;
    background: #ffffff;
    border-radius: 28px;
    padding: 32px;
    height: 100%;
    min-height: 300px;
    display: flex;
    flex-direction: column;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.03);
    box-shadow: 
        0 4px 20px -2px rgba(0, 0, 0, 0.05),
        0 0 0 1px rgba(0, 0, 0, 0.02);
    overflow: hidden;
}

.testimonial-card:hover {
    transform: translateY(-8px);
    box-shadow: 
        0 25px 35px -12px rgba(79, 70, 229, 0.15),
        0 0 0 1px rgba(79, 70, 229, 0.15);
}

/* Decorative dots pattern */
.testimonial-card__dots {
    position: absolute;
    top: 0;
    right: 0;
    width: 140px;
    height: 140px;
    background-image: radial-gradient(circle at 20px 20px, rgba(79, 70, 229, 0.04) 2px, transparent 2px);
    background-size: 24px 24px;
    opacity: 0.6;
    pointer-events: none;
    z-index: 0;
}

/* Glow effect on hover */
.testimonial-card__glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: radial-gradient(circle at 0% 0%, rgba(79, 70, 229, 0.03), transparent 60%);
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
    z-index: 0;
}

.testimonial-card:hover .testimonial-card__glow {
    opacity: 1;
}

/* Quote icon - Pojok Kanan Atas */
.testimonial-card__quote-icon {
    position: absolute;
    top: 20px;
    right: 24px;
    width: 48px;
    height: 48px;
    color: rgba(79, 70, 229, 0.08);
    transition: all 0.3s ease;
    z-index: 0;
}

.testimonial-card:hover .testimonial-card__quote-icon {
    color: rgba(79, 70, 229, 0.15);
    transform: scale(1.1) rotate(-3deg);
}

/* Content */
.testimonial-card__content {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 24px;
    padding-right: 20px; /* Space for quote icon */
}

.testimonial-card__text {
    font-size: 0.9375rem;
    line-height: 1.7;
    color: #334155;
    font-weight: 400;
    margin: 0;
    flex: 1;
}

/* Stars - Diperbesar */
.testimonial-card__stars {
    display: flex;
    gap: 8px;
    margin-top: auto;
}

.testimonial-card__star {
    width: 22px;
    height: 22px;
    transition: all 0.2s ease;
}

.testimonial-card__star--filled {
    color: #f59e0b; /* amber-500 */
    filter: drop-shadow(0 2px 4px rgba(245, 158, 11, 0.25));
}

.testimonial-card__star--empty {
    color: #e2e8f0; /* slate-200 */
}

/* Footer */
.testimonial-card__footer {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

/* Modern avatar */
.testimonial-card__avatar {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 15px -6px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}

.testimonial-card:hover .testimonial-card__avatar {
    transform: scale(1.05) rotate(2deg);
    border-radius: 16px;
    box-shadow: 0 10px 20px -8px {{ $avatarColor['from'] }}80;
}

.testimonial-card__avatar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
    pointer-events: none;
}

.testimonial-card__avatar-text {
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
    letter-spacing: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
}

/* Info section - Pekerjaan di bawah nama */
.testimonial-card__info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.testimonial-card__name {
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a; /* slate-900 */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.4;
}

.testimonial-card__job {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #64748b; /* slate-500 */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
}

.testimonial-card__date {
    font-size: 0.75rem;
    color: #94a3b8; /* slate-400 */
    font-weight: 400;
    display: inline-flex;
    align-items: center;
    background: rgba(0, 0, 0, 0.02);
    padding: 2px 8px;
    border-radius: 30px;
    width: fit-content;
    border: 1px solid rgba(0, 0, 0, 0.02);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .testimonial-card {
        padding: 24px;
    }
    
    .testimonial-card__quote-icon {
        width: 40px;
        height: 40px;
        top: 16px;
        right: 20px;
    }
    
    .testimonial-card__content {
        padding-right: 16px;
    }
    
    .testimonial-card__text {
        font-size: 0.875rem;
    }
    
    .testimonial-card__star {
        width: 20px;
        height: 20px;
    }
    
    .testimonial-card__avatar {
        width: 52px;
        height: 52px;
    }
    
    .testimonial-card__stars {
        gap: 6px;
    }
}

/* Dark mode support jika diperlukan */
@media (prefers-color-scheme: dark) {
    .testimonial-card {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.03);
    }
    
    .testimonial-card__text {
        color: #e2e8f0;
    }
    
    .testimonial-card__name {
        color: #f1f5f9;
    }
    
    .testimonial-card__job {
        color: #94a3b8;
    }
    
    .testimonial-card__date {
        color: #64748b;
        background: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.02);
    }
    
    .testimonial-card__footer {
        border-top-color: rgba(255, 255, 255, 0.05);
    }
    
    .testimonial-card__star--empty {
        color: #334155;
    }
}
</style>
@endonce