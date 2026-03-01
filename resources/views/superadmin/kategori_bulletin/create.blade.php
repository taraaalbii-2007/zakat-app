@extends('layouts.app')

@section('title', 'Tambah Kategori Bulletin')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <a href="{{ route('superadmin.kategori-bulletin.index') }}"
                   class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Tambah Kategori Bulletin</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Buat kategori baru untuk mengelompokkan bulletin</p>
                </div>
            </div>
        </div>

        <form action="{{ route('superadmin.kategori-bulletin.store') }}" method="POST" class="p-4 sm:p-6">
            @csrf

           <div class="w-full space-y-6">

                {{-- Field Nama Kategori --}}
                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama_kategori"
                           id="nama_kategori"
                           value="{{ old('nama_kategori') }}"
                           maxlength="100"
                           required
                           autofocus
                           placeholder="Contoh: Berita Masjid, Pengumuman, Kajian"
                           class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('nama_kategori') border-red-500 @enderror">

                    @error('nama_kategori')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @else
                        <p class="mt-1.5 text-xs text-gray-400">Maksimal 100 karakter. Nama kategori harus unik.</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2 border-t border-gray-200">
                    <a href="{{ route('superadmin.kategori-bulletin.index') }}"
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
                        Simpan Kategori
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection