{{-- resources/views/superadmin/kontak/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pesan')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Pesan</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Pesan dari pengguna</p>
                    </div>
                    <div class="flex items-center gap-2">
                        {!! $kontak->status_badge !!}
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div class="mx-4 sm:mx-6 mt-4 rounded-xl bg-emerald-50 border border-emerald-200 p-3 flex items-center gap-2 text-sm text-emerald-800">
                    <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mx-4 sm:mx-6 mt-4 rounded-xl bg-red-50 border border-red-200 p-3 flex items-center gap-2 text-sm text-red-800">
                    <svg class="w-4 h-4 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-4 sm:p-6 space-y-6">

                {{-- Info Pengirim --}}
                <div class="flex flex-col sm:flex-row items-start gap-4 p-4 sm:p-5 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-xl font-bold text-primary">
                        {{ strtoupper(substr($kontak->nama, 0, 1)) }}
                    </div>
                    <div class="flex-1 space-y-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $kontak->nama }}</h3>
                            <a href="mailto:{{ $kontak->email }}"
                                class="text-sm text-primary hover:underline">{{ $kontak->email }}</a>
                        </div>
                        <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-500">
                            <span>
                                <span class="font-medium">Dikirim:</span>
                                {{ $kontak->created_at->format('d M Y, H:i') }} WIB
                                <span class="text-gray-400">({{ $kontak->created_at->diffForHumans() }})</span>
                            </span>
                            @if ($kontak->dibaca_at)
                                <span>
                                    <span class="font-medium">Dibaca:</span>
                                    {{ $kontak->dibaca_at->format('d M Y, H:i') }} WIB
                                </span>
                            @endif
                            @if ($kontak->dibalas_at)
                                <span>
                                    <span class="font-medium">Dibalas:</span>
                                    {{ $kontak->dibalas_at->format('d M Y, H:i') }} WIB
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Subjek & Pesan --}}
                <div>
                    <div class="mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Subjek</p>
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900">{{ $kontak->subjek }}</h4>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Isi Pesan</p>
                        <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $kontak->pesan }}</div>
                    </div>
                </div>

                {{-- Balasan Sebelumnya --}}
                @if ($kontak->sudahDibalas())
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 overflow-hidden">
                        <div class="px-4 sm:px-5 py-3 bg-emerald-100/60 border-b border-emerald-200 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            <span class="text-sm font-semibold text-emerald-800">Balasan Terkirim</span>
                            <span class="text-xs text-emerald-600 ml-auto">{{ $kontak->dibalas_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="px-4 sm:px-5 py-4 text-sm text-emerald-900 leading-relaxed whitespace-pre-wrap">{{ $kontak->balasan }}</div>
                    </div>
                @endif

                {{-- Form Balas --}}
                @if (!$kontak->sudahDibalas())
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Tulis Balasan
                        </h4>
                        <form action="{{ route('superadmin.kontak.balas', $kontak) }}" method="POST" id="balas-form">
                            @csrf
                            @method('POST')

                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-1.5">
                                    <p class="text-xs text-gray-500">
                                        Balasan akan dikirim ke: <span class="font-medium text-gray-700">{{ $kontak->email }}</span>
                                    </p>
                                </div>
                                <textarea name="balasan" id="balasan" rows="7"
                                    placeholder="Tulis balasan Anda di sini..."
                                    class="block w-full px-4 py-3 text-sm border rounded-xl bg-gray-50 placeholder-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary focus:bg-white resize-none
                                        {{ $errors->has('balasan') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">{{ old('balasan') }}</textarea>
                                @error('balasan')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" id="submit-balas"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    Kirim Balasan via Email
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    {{-- Sudah dibalas: opsi balas ulang --}}
                    <div>
                        <button type="button" onclick="document.getElementById('rebalas-section').classList.toggle('hidden')"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Balas Ulang
                        </button>
                        <div id="rebalas-section" class="hidden mt-4">
                            <form action="{{ route('superadmin.kontak.balas', $kontak) }}" method="POST">
                                @csrf
                                <textarea name="balasan" rows="5"
                                    placeholder="Tulis balasan tambahan Anda..."
                                    class="block w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary focus:bg-white resize-none mb-3"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Kirim Balasan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Footer --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <a href="{{ route('superadmin.kontak.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                    <div class="flex items-center gap-2">
                        {{-- Tandai belum dibaca --}}
                        @if ($kontak->sudahDibaca())
                            <form action="{{ route('superadmin.kontak.tandai-belum-dibaca', $kontak) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Tandai Belum Dibaca
                                </button>
                            </form>
                        @endif

                        {{-- Hapus --}}
                        <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Pesan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Pesan</h3>
            <p class="text-sm text-gray-500 mb-6 text-center">
                Apakah Anda yakin ingin menghapus pesan dari
                "<span class="font-semibold text-gray-700">{{ $kontak->nama }}</span>"? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="w-28 rounded-lg border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form action="{{ route('superadmin.kontak.destroy', $kontak) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-28 rounded-lg shadow-sm px-4 py-2.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Click backdrop to close --}}
    <script>
        document.getElementById('delete-modal').addEventListener('click', function (e) {
            if (e.target === this) this.classList.add('hidden');
        });

        // Prevent double submit on balas form
        const balasForm   = document.getElementById('balas-form');
        const submitBalas = document.getElementById('submit-balas');
        if (balasForm && submitBalas) {
            balasForm.addEventListener('submit', function () {
                submitBalas.disabled = true;
                submitBalas.innerHTML = `
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Mengirim...
                `;
            });
        }
    </script>
@endsection