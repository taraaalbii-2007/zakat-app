{{-- resources/views/amil/profil/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ── Hero Card ── --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Profil Saya</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Informasi profil dan data diri amil</p>
                </div>
                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <a href="{{ route('profil.edit') }}"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profil
                    </a>
                    <a href="{{ route('profil.email.edit') }}"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Ubah Email
                    </a>
                    <a href="{{ route('profil.password.edit') }}"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-amber-300 text-amber-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-amber-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Ubah Password
                    </a>
                    <a href="{{ route('profil.edit') }}#section-ttd"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        {{ $amil->tanda_tangan_url ? 'Ganti TTD' : 'Upload TTD' }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm">
                {{ session('info') }}
            </div>
        @endif

        {{-- Content Body --}}
        <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

            {{-- Profile Header with Photo --}}
            <div>
                @php
                    $isAktif    = $amil->status === 'aktif';
                    $isCuti     = $amil->status === 'cuti';
                    $dotColor   = $isAktif ? 'bg-emerald-500' : ($isCuti ? 'bg-amber-400' : 'bg-red-400');
                    $statusClass= $isAktif
                        ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                        : ($isCuti ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-red-100 text-red-800 border-red-200');
                    
                    // Avatar logic - only show avatar if no photo
                    $hasPhoto = $amil->foto && $amil->foto_url && $amil->foto_url !== '#';
                    $nama = $amil->nama_lengkap ?? $amil->user->username ?? 'Amil';
                    $inisial = strtoupper(substr($nama, 0, 1));
                    $avatarColors = [
                        'A' => 'bg-gradient-to-br from-blue-500 to-blue-600',
                        'B' => 'bg-gradient-to-br from-green-500 to-green-600', 
                        'C' => 'bg-gradient-to-br from-purple-500 to-purple-600',
                        'D' => 'bg-gradient-to-br from-pink-500 to-pink-600',
                        'E' => 'bg-gradient-to-br from-indigo-500 to-indigo-600',
                        'F' => 'bg-gradient-to-br from-teal-500 to-teal-600',
                        'G' => 'bg-gradient-to-br from-orange-500 to-orange-600',
                        'H' => 'bg-gradient-to-br from-cyan-500 to-cyan-600',
                        'I' => 'bg-gradient-to-br from-rose-500 to-rose-600',
                        'J' => 'bg-gradient-to-br from-amber-500 to-amber-600',
                        'K' => 'bg-gradient-to-br from-lime-500 to-lime-600',
                        'L' => 'bg-gradient-to-br from-emerald-500 to-emerald-600',
                        'M' => 'bg-gradient-to-br from-sky-500 to-sky-600',
                        'N' => 'bg-gradient-to-br from-violet-500 to-violet-600',
                        'O' => 'bg-gradient-to-br from-fuchsia-500 to-fuchsia-600',
                        'P' => 'bg-gradient-to-br from-rose-500 to-rose-600',
                        'Q' => 'bg-gradient-to-br from-cyan-500 to-cyan-600',
                        'R' => 'bg-gradient-to-br from-indigo-500 to-indigo-600',
                        'S' => 'bg-gradient-to-br from-blue-500 to-blue-600',
                        'T' => 'bg-gradient-to-br from-teal-500 to-teal-600',
                        'U' => 'bg-gradient-to-br from-purple-500 to-purple-600',
                        'V' => 'bg-gradient-to-br from-pink-500 to-pink-600',
                        'W' => 'bg-gradient-to-br from-emerald-500 to-emerald-600',
                        'X' => 'bg-gradient-to-br from-orange-500 to-orange-600',
                        'Y' => 'bg-gradient-to-br from-amber-500 to-amber-600',
                        'Z' => 'bg-gradient-to-br from-lime-500 to-lime-600',
                    ];
                    $avatarColor = $avatarColors[$inisial] ?? 'bg-gradient-to-br from-primary to-primary-600';
                @endphp

                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                    {{-- Photo with Avatar Fallback --}}
                    <div class="flex-shrink-0 mx-auto sm:mx-0">
                        <div class="relative">
                            @if($hasPhoto)
                                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-full overflow-hidden shadow-sm bg-primary/20">
                                    <img src="{{ $amil->foto_url }}" alt="{{ $amil->nama_lengkap }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                {{-- Avatar Fallback A, B, C etc --}}
                                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-full overflow-hidden shadow-sm {{ $avatarColor }} flex items-center justify-center">
                                    <span class="text-white text-2xl sm:text-3xl font-bold">{{ $inisial }}</span>
                                </div>
                            @endif
                            {{-- Status dot --}}
                            <span class="absolute bottom-0.5 right-0.5 w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-white {{ $dotColor }}"></span>
                        </div>
                    </div>

                    {{-- User Info --}}
                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                                {{ $amil->nama_lengkap }}
                            </h2>
                        </div>

                        <p class="text-xs sm:text-sm text-gray-500 mb-2">
                            <span class="font-mono">{{ $amil->kode_amil }}</span>
                            @if($amil->masjid)
                                &bull; {{ $amil->masjid->nama }}
                            @endif
                        </p>

                        <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Amil
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                {{ ucfirst($amil->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Data Pribadi --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Data Pribadi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach([
                        ['Nama Lengkap',  $amil->nama_lengkap],
                        ['Jenis Kelamin', $amil->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'],
                        ['Tempat Lahir',  $amil->tempat_lahir],
                        ['Tanggal Lahir', optional($amil->tanggal_lahir)->format('d F Y').($amil->umur ? ' ('.$amil->umur.' th)' : '')],
                        ['Nomor Telepon', $amil->telepon],
                        ['Email',         $amil->email],
                        ['Alamat',        $amil->alamat],
                    ] as [$label, $value])
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">{{ $label }}</label>
                        @if($value)
                            <p class="text-sm text-gray-900">{{ $value }}</p>
                        @else
                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Data Tugas --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Data Tugas</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Kode Amil</label>
                        <p class="text-sm font-mono font-semibold text-gray-900">{{ $amil->kode_amil }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Masjid</label>
                        <p class="text-sm text-gray-900">{{ optional($amil->masjid)->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Wilayah Tugas</label>
                        @if($amil->wilayah_tugas)
                            <p class="text-sm text-gray-900">{{ $amil->wilayah_tugas }}</p>
                        @else
                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Mulai Tugas</label>
                        @if($amil->tanggal_mulai_tugas)
                            <p class="text-sm text-gray-900">
                                {{ optional($amil->tanggal_mulai_tugas)->format('d F Y') }}
                                @if($amil->masa_tugas)
                                    <span class="text-gray-400 text-xs ml-1">({{ $amil->masa_tugas }})</span>
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Selesai Tugas</label>
                        <p class="text-sm text-gray-900">
                            {{ optional($amil->tanggal_selesai_tugas)->format('d F Y') ?? 'Masih aktif' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ ucfirst($amil->status) }}
                        </span>
                    </div>
                    @if($amil->keterangan)
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Keterangan</label>
                        <p class="text-sm text-gray-900">{{ $amil->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Tanda Tangan --}}
            <div id="section-ttd">
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Tanda Tangan</h4>
                @if($amil->tanda_tangan_url)
                    <div class="flex flex-col sm:flex-row items-start gap-5">
                        <div class="border border-gray-200 rounded-xl bg-gray-50 p-4 inline-flex items-center justify-center">
                            <img src="{{ $amil->tanda_tangan_url }}"
                                 alt="TTD {{ $amil->nama_lengkap }}"
                                 class="h-20 max-w-[240px] object-contain">
                        </div>
                        <div class="space-y-1 pt-1">
                            <p class="text-sm font-semibold text-gray-800">{{ $amil->nama_lengkap }}</p>
                            <p class="text-xs text-gray-400">Amil &bull; {{ optional($amil->masjid)->nama }}</p>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                Tanda tangan ini akan otomatis tampil di kwitansi zakat yang Anda buat.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4 p-4 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Belum ada tanda tangan</p>
                            <p class="text-xs text-gray-400 mt-0.5">Upload tanda tangan agar muncul otomatis di kwitansi zakat.</p>
                        </div>
                    </div>
                @endif
            </div>

            <hr class="border-gray-200">

            {{-- Akun Login --}}
            <div>
                <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Akun Login</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Username</label>
                        <p class="text-sm text-gray-900">{{ optional($amil->pengguna)->username ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Email Akun</label>
                        <p class="text-sm text-gray-900">{{ optional($amil->pengguna)->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bergabung Sejak</label>
                        <p class="text-sm text-gray-900">
                            {{ optional($amil->pengguna?->created_at)->format('d F Y') ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection