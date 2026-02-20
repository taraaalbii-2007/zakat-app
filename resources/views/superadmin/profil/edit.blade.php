@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        {{-- ── Header ── --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Profil</h1>
                <p class="text-gray-600 mt-1">Perbarui data akun Anda</p>
            </div>
            <a href="{{ route('superadmin.profil.show') }}"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Profil
            </a>
        </div>

        {{-- ══════════════════════════════════════════════════
             FORM EDIT PROFIL (TANPA FOTO)
        ══════════════════════════════════════════════════ --}}
        <form action="{{ route('superadmin.profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ── Data Akun ── --}}
            <div class="mb-10">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">
                    Data Akun
                </h2>
                
                {{-- Peringatan perubahan email --}}
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h4 class="font-medium text-amber-800">Perhatian: Perubahan Email</h4>
                            <p class="text-sm text-amber-700 mt-1">
                                Jika Anda mengubah email, Anda akan <strong>otomatis logout</strong> dan harus login ulang 
                                menggunakan email baru. Email notifikasi akan dikirim ke alamat email baru Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username"
                            value="{{ old('username', $user->username) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('username') border-red-500 @enderror"
                            placeholder="Masukkan username" required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('email') border-red-500 @enderror"
                            placeholder="email@contoh.com" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Info readonly fields --}}
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Peran</label>
                        <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-500">
                            Super Admin <span class="text-xs text-gray-400 ml-1">(tidak dapat diubah)</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bergabung Sejak</label>
                        <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-500">
                            {{ optional($user->created_at)->format('d F Y') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Action Buttons ── --}}
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.profil.show') }}"
                    class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-primary-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>

        @if($user->is_google_user)
        {{-- Info jika login via Google --}}
        <div class="mt-10 pt-6 border-t border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-3">Ubah Password</h2>
            <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-lg">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-700">
                    Akun Anda terdaftar melalui <strong>Google OAuth</strong>. 
                    Ubah password melalui pengaturan akun Google Anda.
                </p>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection