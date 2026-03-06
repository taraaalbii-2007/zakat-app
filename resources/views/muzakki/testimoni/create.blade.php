{{-- resources/views/muzakki/testimoni/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tulis Testimoni')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <a href="{{ route('muzakki.testimoni.index') }}"
                   class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Tulis Testimoni</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Bagikan pengalaman Anda menggunakan Niat Zakat</p>
                </div>
            </div>
        </div>

        <form action="{{ route('muzakki.testimoni.store') }}" method="POST" class="p-4 sm:p-6">
            @csrf

            @if($errors->any())
                <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="w-full space-y-6">

                {{-- Nama Tampil --}}
                <div>
                    <label for="nama_pengirim" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Tampil <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama_pengirim"
                           id="nama_pengirim"
                           value="{{ old('nama_pengirim', auth()->user()?->muzakki?->nama) }}"
                           required
                           placeholder="Nama yang ditampilkan di testimoni"
                           class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('nama_pengirim') border-red-500 @enderror">
                    @error('nama_pengirim')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <p class="mt-1.5 text-xs text-gray-400">Nama ini yang akan tampil di halaman utama website.</p>
                    @enderror
                </div>

                {{-- Pekerjaan --}}
                <div>
                    <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                        Pekerjaan <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <input type="text"
                           name="pekerjaan"
                           id="pekerjaan"
                           value="{{ old('pekerjaan') }}"
                           placeholder="Contoh: Pengusaha, Guru, Karyawan Swasta"
                           class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                </div>

                {{-- Rating Bintang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rating <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1" id="starContainer">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" data-value="{{ $i }}"
                                    class="star-btn w-10 h-10 rounded-xl transition-all hover:scale-110 focus:outline-none"
                                    onclick="setRating({{ $i }})">
                                    <svg class="w-8 h-8 mx-auto star-icon text-gray-300 fill-current transition-colors" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <span id="ratingLabel" class="text-sm text-gray-500"></span>
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">
                    @error('rating')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <p class="mt-1.5 text-xs text-gray-400">Klik bintang untuk memberi penilaian.</p>
                    @enderror
                </div>

                {{-- Isi Testimoni --}}
                <div>
                    <label for="isiTestimoni" class="block text-sm font-medium text-gray-700 mb-2">
                        Testimoni <span class="text-red-500">*</span>
                    </label>
                    <textarea name="isi_testimoni"
                              id="isiTestimoni"
                              rows="5"
                              maxlength="500"
                              placeholder="Ceritakan pengalaman Anda menggunakan Niat Zakat untuk berzakat..."
                              class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none @error('isi_testimoni') border-red-500 @enderror">{{ old('isi_testimoni') }}</textarea>
                    <div class="flex items-center justify-between mt-1.5">
                        @error('isi_testimoni')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @else
                            <p class="text-xs text-gray-400">Minimal 10 karakter.</p>
                        @enderror
                        <p class="text-xs text-gray-400 ml-auto"><span id="charCount">0</span>/500</p>
                    </div>
                </div>

                {{-- Info --}}
                <div class="px-4 py-3 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-blue-700">Testimoni Anda akan ditinjau oleh admin sebelum ditampilkan di halaman utama. Biasanya membutuhkan waktu 1×24 jam.</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2 border-t border-gray-200">
                    <a href="{{ route('muzakki.testimoni.index') }}"
                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Kirim Testimoni
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const labels = ['', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik', 'Luar Biasa!'];
    let currentRating = {{ old('rating', 0) }};

    function setRating(val) {
        currentRating = val;
        document.getElementById('ratingInput').value = val;
        document.getElementById('ratingLabel').textContent = labels[val] || '';
        renderStars(val);
    }

    function renderStars(val) {
        document.querySelectorAll('.star-icon').forEach((star, idx) => {
            if (idx < val) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    document.querySelectorAll('.star-btn').forEach((btn) => {
        btn.addEventListener('mouseenter', () => renderStars(parseInt(btn.dataset.value)));
        btn.addEventListener('mouseleave', () => renderStars(currentRating));
    });

    if (currentRating > 0) setRating(currentRating);

    // Karakter counter
    const textarea = document.getElementById('isiTestimoni');
    const counter  = document.getElementById('charCount');
    function updateCount() {
        counter.textContent = textarea.value.length;
    }
    textarea.addEventListener('input', updateCount);
    updateCount();
</script>
@endpush