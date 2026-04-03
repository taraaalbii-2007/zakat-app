{{-- resources/views/muzakki/profil/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Header ── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-5 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Profil Saya</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi profil dan data diri muzakki</p>
                    </div>

                    {{-- ── Action Buttons ── --}}
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('muzakki.profil.edit') }}"
                            class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Profil
                        </a>

                        @if (!$user->is_google_user)
                            <a href="{{ route('muzakki.profil.email.edit') }}"
                                class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Ubah Email
                            </a>
                            <a href="{{ route('muzakki.profil.password.edit') }}"
                                class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-amber-300 text-amber-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-amber-50 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Ubah Password
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Alert ── --}}
            @if (session('success'))
                <div class="mx-4 sm:mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mx-4 sm:mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

                {{-- ── Profile Header ── --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                    {{-- Photo --}}
                    <div class="flex-shrink-0 mx-auto sm:mx-0">
                        <div class="relative">
                            <div
                                class="h-20 w-20 sm:h-24 sm:w-24 rounded-full overflow-hidden shadow-sm bg-primary/20 flex items-center justify-center">
                                <img src="{{ $muzakki->foto_url }}" alt="{{ $muzakki->nama }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <span
                                class="absolute bottom-0.5 right-0.5 w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-white
                            {{ $muzakki->is_active ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-1">{{ $muzakki->nama }}</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mb-2">
                            @if ($muzakki->lembaga)
                                {{ $muzakki->lembaga->nama }}
                            @endif
                        </p>
                        <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Muzakki
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border
                            {{ $muzakki->is_active
                                ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                : 'bg-red-100 text-red-800 border-red-200' }}">
                                {{ $muzakki->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                            @if ($user->is_google_user)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                            fill="#4285F4" />
                                        <path
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                            fill="#34A853" />
                                        <path
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                            fill="#FBBC05" />
                                        <path
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                            fill="#EA4335" />
                                    </svg>
                                    Login via Google
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- ── Statistik Zakat ── --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Ringkasan Zakat</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @php
                            $statItems = [
                                [
                                    'label' => 'Total Transaksi',
                                    'value' => $stats['total_transaksi'],
                                    'suffix' => 'transaksi',
                                    'color' => 'bg-blue-50 border-blue-100',
                                    'text' => 'text-blue-700',
                                    'icon' =>
                                        'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                ],
                                [
                                    'label' => 'Bulan Ini',
                                    'value' => $stats['transaksi_bulan_ini'],
                                    'suffix' => 'transaksi',
                                    'color' => 'bg-amber-50 border-amber-100',
                                    'text' => 'text-amber-700',
                                    'icon' =>
                                        'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                                ],
                                [
                                    'label' => 'Terverifikasi',
                                    'value' => $stats['total_verified'],
                                    'suffix' => 'transaksi',
                                    'color' => 'bg-emerald-50 border-emerald-100',
                                    'text' => 'text-emerald-700',
                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                ],
                                [
                                    'label' => 'Total Zakat',
                                    'value' => 'Rp ' . number_format($stats['total_nominal'], 0, ',', '.'),
                                    'suffix' => '',
                                    'color' => 'bg-purple-50 border-purple-100',
                                    'text' => 'text-purple-700',
                                    'icon' =>
                                        'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                ],
                            ];
                        @endphp

                        @foreach ($statItems as $item)
                            <div class="border {{ $item['color'] }} rounded-xl p-3 sm:p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 {{ $item['text'] }}" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span class="text-xs font-medium text-gray-500">{{ $item['label'] }}</span>
                                </div>
                                <p class="text-base sm:text-lg font-bold {{ $item['text'] }}">{{ $item['value'] }}</p>
                                @if ($item['suffix'])
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item['suffix'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- ── Data Pribadi ── --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Data Pribadi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach ([['Nama Lengkap', $muzakki->nama], ['Nomor Telepon', $muzakki->telepon], ['Email', $muzakki->email], ['NIK', $muzakki->nik], ['Alamat', $muzakki->alamat], ['Lembaga', optional($muzakki->lembaga)->nama]] as [$label, $value])
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                    {{ $label }}
                                </label>
                                @if ($value)
                                    <p class="text-sm text-gray-900">{{ $value }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic">Belum diisi</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- ── Akun Login ── --}}
                <div>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Akun Login</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Username</label>
                            <p class="text-sm text-gray-900">{{ optional($muzakki->pengguna)->username ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Email
                                Akun</label>
                            <p class="text-sm text-gray-900">{{ optional($muzakki->pengguna)->email ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Metode
                                Login</label>
                            @if ($user->is_google_user)
                                <span class="inline-flex items-center gap-1.5 text-sm text-blue-700 font-medium">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                            fill="#4285F4" />
                                        <path
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                            fill="#34A853" />
                                        <path
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                            fill="#FBBC05" />
                                        <path
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                            fill="#EA4335" />
                                    </svg>
                                    Google OAuth
                                </span>
                            @else
                                <span class="text-sm text-gray-900">Email & Password</span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bergabung
                                Sejak</label>
                            <p class="text-sm text-gray-900">
                                {{ optional($muzakki->pengguna?->created_at)->format('d F Y') ?? '-' }}
                            </p>
                        </div>
                    </div>

                    @if ($user->is_google_user)
                        <div class="mt-4 flex items-start gap-3 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-blue-700">
                                Akun Anda terdaftar melalui <strong>Google OAuth</strong>.
                                Untuk mengubah email atau password, lakukan melalui pengaturan akun Google Anda.
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
@endsection
