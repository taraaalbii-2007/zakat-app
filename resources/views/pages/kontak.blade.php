{{-- resources/views/pages/kontak.blade.php --}}
@extends('layouts.guest')

@section('title', 'Hubungi Kami')

@section('content')
    @include('partials.landing.page-hero', [
        'heroTitle' => 'Hubungi Kami',
        'heroSubtitle' => 'Ada pertanyaan atau butuh bantuan? Kami siap membantu Anda.',
    ])

    <section class="py-10 sm:py-14 bg-white">
        <div class="w-full px-4 sm:px-10 lg:px-20">

            {{-- Alert Success --}}
            @if (session('success'))
                <div class="mb-8 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- reCAPTCHA error --}}
            @if($errors->has('recaptcha'))
                <div class="mb-8 rounded-xl bg-red-50 border border-red-200 p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm font-medium text-red-700">{{ $errors->first('recaptcha') }}</p>
                </div>
            @endif

            {{-- ── Grid: Form kiri (3), Info kanan (2) ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12">
                {{-- Kolom Kiri: Form Kirim Pesan --}}
                <div class="lg:col-span-3 order-2 lg:order-1">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Kirim Pesan</h3>
                            <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah dan kami akan segera merespons.</p>
                        </div>

                        {{-- Info notice --}}
                        <div class="mb-5 flex items-start gap-2.5 px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl">
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                Halaman ini dilindungi oleh reCAPTCHA Google.
                                Pastikan nama, email, subjek <span class="font-medium">(min. 15 karakter)</span>, dan pesan
                                <span class="font-medium">(min. 20 karakter)</span> diisi dengan benar sebelum mengirim.
                            </p>
                        </div>

                        <form action="{{ route('kontak.store') }}" method="POST" class="space-y-5" id="kontak-form">
                            @csrf

                            {{-- Hidden reCAPTCHA v3 token --}}
                            @if ($recaptcha && $recaptcha->isEnabled())
                                <input type="hidden" name="recaptcha_token" id="recaptcha_token" value="">
                                
                                {{-- Load reCAPTCHA script di sini --}}
                                <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptcha->RECAPTCHA_SITE_KEY }}"></script>
                            @endif

                            {{-- Nama & Email --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="nama" name="nama"
                                            value="{{ old('nama', auth()->user()?->name) }}"
                                            placeholder="Masukkan nama lengkap"
                                            class="block w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl bg-white placeholder-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                                {{ $errors->has('nama') ? 'border-red-400 ring-2 ring-red-100' : 'border-gray-200' }}">
                                    </div>
                                    @error('nama')
                                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Alamat Email <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                            </svg>
                                        </div>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', auth()->user()?->email) }}" placeholder="nama@email.com"
                                            class="block w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl bg-white placeholder-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                                {{ $errors->has('email') ? 'border-red-400 ring-2 ring-red-100' : 'border-gray-200' }}">
                                    </div>
                                    @error('email')
                                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Subjek --}}
                            <div>
                                <label for="subjek" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Subjek <span class="text-red-500">*</span>
                                    <span class="text-xs font-normal text-gray-400 ml-1">min. 15 karakter</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="subjek" name="subjek" value="{{ old('subjek') }}"
                                        placeholder="Contoh: Pertanyaan tentang zakat fitrah"
                                        class="block w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl bg-white placeholder-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                                            {{ $errors->has('subjek') ? 'border-red-400 ring-2 ring-red-100' : 'border-gray-200' }}">
                                </div>
                                @error('subjek')
                                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Pesan --}}
                            <div>
                                <label for="pesan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Pesan <span class="text-red-500">*</span>
                                    <span class="text-xs font-normal text-gray-400 ml-1">min. 20 karakter</span>
                                </label>
                                <textarea id="pesan" name="pesan" rows="6" placeholder="Tuliskan pesan Anda di sini..."
                                    class="block w-full px-4 py-2.5 text-sm border rounded-xl bg-white placeholder-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary resize-none
                                        {{ $errors->has('pesan') ? 'border-red-400 ring-2 ring-red-100' : 'border-gray-200' }}">{{ old('pesan') }}</textarea>
                                <div class="flex items-start justify-between mt-1.5">
                                    @error('pesan')
                                        <p class="text-xs text-red-600 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @else
                                        <span></span>
                                    @enderror
                                    <span id="char-count" class="text-xs text-gray-400 ml-auto">0 / 5000</span>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="pt-1">
                                <button type="submit" id="submit-btn"
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary-600 active:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-md disabled:opacity-60 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kolom Kanan: Info Kontak --}}
                <div class="lg:col-span-2 order-1 lg:order-2">
                    <!-- ... konten info kontak tetap sama ... -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-5 h-full">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Info Kontak</h2>
                            <p class="mt-1 text-sm text-gray-500 leading-relaxed">
                                Hubungi kami melalui salah satu channel berikut.
                            </p>
                        </div>

                        <div class="space-y-3">
                            {{-- Email Admin --}}
                            @if ($config->email_admin)
                                <div
                                    class="flex items-center gap-4 p-3.5 bg-gray-50 rounded-xl border border-gray-100 hover:border-primary/30 hover:bg-primary/5 transition-colors group">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-xl bg-primary/10 group-hover:bg-primary/15 flex items-center justify-center transition-colors">
                                        <svg class="w-4.5 h-4.5 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" style="width:18px;height:18px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-none mb-0.5">
                                            Email</p>
                                        <a href="mailto:{{ $config->email_admin }}"
                                            class="text-sm font-medium text-gray-800 hover:text-primary transition-colors break-all">
                                            {{ $config->email_admin }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- WhatsApp Support --}}
                            @if ($config->whatsapp_support)
                                <div
                                    class="flex items-center gap-4 p-3.5 bg-gray-50 rounded-xl border border-gray-100 hover:border-green-300 hover:bg-green-50 transition-colors group">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center transition-colors">
                                        <svg style="width:18px;height:18px;" class="text-green-600" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-none mb-0.5">
                                            WhatsApp</p>
                                        <a href="{{ $config->whatsapp_link }}" target="_blank" rel="noopener"
                                            class="text-sm font-medium text-gray-800 hover:text-green-600 transition-colors">
                                            {{ $config->whatsapp_support }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Telepon Admin --}}
                            @if ($config->telepon_admin)
                                <div
                                    class="flex items-center gap-4 p-3.5 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-300 hover:bg-blue-50 transition-colors group">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center transition-colors">
                                        <svg style="width:18px;height:18px;" class="text-blue-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-none mb-0.5">
                                            Telepon</p>
                                        <a href="tel:{{ $config->telepon_admin }}"
                                            class="text-sm font-medium text-gray-800 hover:text-blue-600 transition-colors">
                                            {{ $config->telepon_admin }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Alamat Kantor --}}
                            @if ($config->alamat_kantor)
                                <div
                                    class="flex items-start gap-4 p-3.5 bg-gray-50 rounded-xl border border-gray-100 hover:border-orange-300 hover:bg-orange-50 transition-colors group">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-xl bg-orange-100 group-hover:bg-orange-200 flex items-center justify-center transition-colors">
                                        <svg style="width:18px;height:18px;" class="text-orange-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-none mb-0.5">
                                            Alamat</p>
                                        <p class="text-sm font-medium text-gray-800 leading-relaxed">
                                            {{ $config->alamat_kantor }}</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Jam Operasional --}}
                            <div class="flex items-center gap-4 p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                                <div
                                    class="flex-shrink-0 w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                                    <svg style="width:18px;height:18px;" class="text-violet-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider leading-none mb-0.5">
                                        Jam Operasional</p>
                                    <p class="text-sm font-medium text-gray-800">Senin – Jumat, 08.00 – 17.00 WIB</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Respon email dalam 1×24 jam kerja</p>
                                </div>
                            </div>
                        </div>

                        {{-- Social Media --}}
                        @if ($config->instagram_url || $config->facebook_url || $config->twitter_url || $config->youtube_url)
                            <div class="pt-1 border-t border-gray-100">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Media Sosial
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @if ($config->instagram_url)
                                        <a href="{{ $config->instagram_url }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-pink-100 text-pink-700 rounded-lg hover:bg-pink-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                            </svg>
                                            Instagram
                                        </a>
                                    @endif
                                    @if ($config->facebook_url)
                                        <a href="{{ $config->facebook_url }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                            </svg>
                                            Facebook
                                        </a>
                                    @endif
                                    @if ($config->twitter_url)
                                        <a href="{{ $config->twitter_url }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                            </svg>
                                            Twitter / X
                                        </a>
                                    @endif
                                    @if ($config->youtube_url)
                                        <a href="{{ $config->youtube_url }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                            </svg>
                                            YouTube
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Character counter
    const pesanEl = document.getElementById('pesan');
    const countEl = document.getElementById('char-count');
    if (pesanEl && countEl) {
        const update = () => {
            const len = pesanEl.value.length;
            countEl.textContent = len + ' / 5000';
            countEl.classList.toggle('text-red-500', len > 4800);
            countEl.classList.toggle('text-gray-400', len <= 4800);
        };
        pesanEl.addEventListener('input', update);
        update();
    }

    // reCAPTCHA v3 handling
    @if ($recaptcha && $recaptcha->isEnabled())
    const form = document.getElementById('kontak-form');
    const submitBtn = document.getElementById('submit-btn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Memverifikasi...
            `;
            
            // Execute reCAPTCHA
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $recaptcha->RECAPTCHA_SITE_KEY }}', {action: 'kontak'})
                    .then(function(token) {
                        // Set token to hidden input
                        document.getElementById('recaptcha_token').value = token;
                        // Submit form
                        form.submit();
                    })
                    .catch(function(error) {
                        console.error('reCAPTCHA error:', error);
                        // Re-enable button on error
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Kirim Pesan
                        `;
                        
                        // Show error message
                        alert('Gagal memverifikasi reCAPTCHA. Silakan muat ulang halaman dan coba lagi.');
                    });
            });
        });
    }
    @else
    // Without reCAPTCHA: normal form submit with loading state
    const form2 = document.getElementById('kontak-form');
    const submitBtn2 = document.getElementById('submit-btn');
    if (form2 && submitBtn2) {
        form2.addEventListener('submit', function() {
            submitBtn2.disabled = true;
            submitBtn2.innerHTML = `
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Mengirim...
            `;
        });
    }
    @endif
</script>
@endpush