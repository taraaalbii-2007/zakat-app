@extends('layouts.app')

@section('title', 'Edit Kategori Bulletin')

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
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Kategori Bulletin</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui data kategori "{{ $kategoriBulletin->nama_kategori }}"</p>
                </div>
            </div>
        </div>

        <form action="{{ route('superadmin.kategori-bulletin.update', $kategoriBulletin->uuid) }}"
              method="POST" class="p-4 sm:p-6">
            @csrf
            @method('PUT')

            <div class="max-w-lg space-y-6">

                {{-- Info Kategori --}}
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Kategori saat ini</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $kategoriBulletin->nama_kategori }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Digunakan oleh
                            <span class="font-medium {{ $kategoriBulletin->bulletins_count > 0 ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ $kategoriBulletin->bulletins_count }} bulletin
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Field Nama Kategori --}}
                <div>
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kategori Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama_kategori"
                           id="nama_kategori"
                           value="{{ old('nama_kategori', $kategoriBulletin->nama_kategori) }}"
                           maxlength="100"
                           required
                           autofocus
                           placeholder="Masukkan nama kategori"
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
                        <p class="mt-1.5 text-xs text-gray-400">Maksimal 100 karakter.</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                    <a href="{{ route('superadmin.kategori-bulletin.index') }}"
                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Perbarui Kategori
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection