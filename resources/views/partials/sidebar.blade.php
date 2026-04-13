<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white text-gray-800 lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-out flex flex-col shadow-xl"
    style="border-right: 1px solid #eef2f7; box-shadow: 4px 0 24px rgba(0,0,0,0.04);">

    <style>
    #sidebar * { box-sizing: border-box; }

    /* Custom scrollbar */
    #sidebar nav::-webkit-scrollbar { width: 4px; }
    #sidebar nav::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
    #sidebar nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    #sidebar nav::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    /* Section label — gaya garis kiri & kanan - jarak lebih rapat */
    .sb-section-label {
        display: flex;
        align-items: center;
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 0.09em;
        color: #94a3b8;
        text-transform: uppercase;
        padding: 0 4px;
        margin: 8px 0 4px;
    }
    .sb-section-label:first-of-type {
        margin-top: 0px;
    }
    .sb-section-label::before,
    .sb-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
    }
    .sb-section-label::before { margin-right: 6px; }
    .sb-section-label::after  { margin-left: 6px; }

    /* Main item - jarak lebih rapat & garis tidak mepet */
    .sb-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 14px;
        margin: 2px 8px 2px 8px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: none;
        background: none;
        width: auto;
        text-align: left;
        position: relative;
        font-family: inherit;
        outline: none;
    }

    .sb-item::before {
        content: '';
        position: absolute;
        left: -4px;
        top: 50%;
        transform: translateY(-50%) scaleX(0);
        width: 3px;
        height: 55%;
        border-radius: 0 4px 4px 0;
        background: #16a34a;
        transition: transform 0.2s ease;
    }

    .sb-item:hover {
        background: #f0fdf4;
        color: #16a34a;
    }
    .sb-item:hover::before {
        transform: translateY(-50%) scaleX(1);
    }
    .sb-item:active {
        background: #dcfce7;
        color: #15803d;
    }
    .sb-item.active {
        background: #f0fdf4;
        color: #16a34a;
    }
    .sb-item.active::before {
        transform: translateY(-50%) scaleX(1);
    }

    /* Icon */
    .sb-icon {
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #94a3b8;
        transition: all 0.2s ease;
    }
    .sb-item:hover .sb-icon { color: #16a34a; }
    .sb-item.active .sb-icon { color: #16a34a; }
    .sb-icon svg { width: 18px; height: 18px; stroke-width: 1.8; }

    /* Badge */
    .sb-badge {
        margin-left: auto;
        font-size: 10.5px;
        font-weight: 700;
        background: #ef4444;
        color: white;
        border-radius: 20px;
        padding: 2px 7px;
        min-width: 20px;
        text-align: center;
        flex-shrink: 0;
    }
    .sb-badge-warn { background: #f59e0b; }

    /* Chevron */
    .sb-chevron {
        margin-left: auto;
        width: 14px;
        height: 14px;
        color: #cbd5e1;
        flex-shrink: 0;
        transition: transform 0.25s ease, color 0.2s;
    }
    details.sb-group[open] .sb-chevron { transform: rotate(180deg); }
    .sb-item:hover .sb-chevron { color: #94a3b8; }

    /* Sub list - jarak lebih rapat */
    .sb-sub-list {
        margin: 2px 0 2px 12px;
        padding-left: 16px;
        border-left: 1.5px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    /* Sub item - jarak lebih rapat & garis tidak mepet */
    .sb-sub-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 12px;
        margin: 0px 8px 0px 8px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        transition: all 0.15s ease;
        position: relative;
        width: auto;
    }

    .sb-sub-item::before {
        content: '';
        position: absolute;
        left: -4px;
        top: 50%;
        transform: translateY(-50%) scaleX(0);
        width: 2px;
        height: 40%;
        background: #16a34a;
        border-radius: 2px;
        transition: transform 0.2s ease;
    }

    .sb-sub-item:hover {
        background: #f0fdf4;
        color: #16a34a;
    }
    .sb-sub-item:hover::before {
        transform: translateY(-50%) scaleX(1);
    }
    .sb-sub-item.active {
        background: #f0fdf4;
        color: #16a34a;
    }
    .sb-sub-item.active::before {
        transform: translateY(-50%) scaleX(1);
    }

    .sb-sub-item svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
        color: #94a3b8;
        transition: color 0.15s;
        stroke-width: 1.8;
    }
    .sb-sub-item:hover svg { color: #16a34a; }
    .sb-sub-item.active svg { color: #16a34a; }

    /* Micro label - jarak lebih rapat */
    .sb-micro-label {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.07em;
        color: #a0abb9;
        text-transform: uppercase;
        padding: 4px 10px 2px 10px;
        margin-top: 2px;
    }

    /* Remove default details marker */
    details.sb-group > summary { list-style: none; }
    details.sb-group > summary::-webkit-details-marker { display: none; }

    /* Logo area - jarak lebih proporsional */
    .logo-container {
        padding: 14px 14px;
        border-bottom: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    
    .logo-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .logo-image {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e2e8f0;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: border-color 0.2s ease;
    }
    
    .logo-image:hover {
        border-color: #16a34a;
    }
    
    .logo-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 2px solid #e2e8f0;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: border-color 0.2s ease, transform 0.2s ease;
    }
    
    .logo-placeholder:hover {
        border-color: #16a34a;
        transform: scale(1.02);
    }
    
    .logo-placeholder span {
        font-size: 18px;
        font-weight: 800;
        color: white;
        letter-spacing: 1px;
    }
    
    .logo-text {
        min-width: 0;
        flex: 1;
    }
    
    .logo-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 2px;
        letter-spacing: -0.3px;
    }
    
    .logo-tagline {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 500;
        letter-spacing: 0.2px;
    }

    /* Navigation - padding lebih rapat */
    #sidebar nav {
        padding: 4px 0 8px;
    }

    /* Footer - padding lebih rapat */
    .sidebar-footer {
        padding: 10px 14px;
        border-top: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    
    /* Hover effect untuk item lebih smooth */
    .sb-item, .sb-sub-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    </style>

    {{-- Logo Section --}}
    <div class="logo-container">
        <div class="logo-wrapper">
            @if (!empty($appConfig->logo_aplikasi))
                <img src="{{ asset('storage/' . $appConfig->logo_aplikasi) }}"
                    alt="{{ $appConfig->nama_aplikasi }}"
                    class="logo-image">
            @else
                <div class="logo-placeholder">
                    <span>{{ strtoupper(substr($appConfig->nama_aplikasi ?? 'NZ', 0, 2)) }}</span>
                </div>
            @endif
            <div class="logo-text">
                <h1 class="logo-title">
                    {{ $appConfig->nama_aplikasi ?? 'Niat Zakat' }}
                </h1>
                <p class="logo-tagline">
                    {{ $appConfig->tagline ?? 'Dari Niat Timbul Manfaat' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3"
        style="scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent;">

        @php
            $currentRoute   = request()->route()->getName() ?? '';
            $isSuperadmin   = auth()->user() && auth()->user()->peran === 'superadmin';
            $isAdminLembaga = auth()->user() && auth()->user()->peran === 'admin_lembaga';
            $isAmil         = auth()->user() && auth()->user()->peran === 'amil';
            $isMuzakki      = auth()->user() && auth()->user()->peran === 'muzakki';

            $isActive = function ($routes, $exact = false) use ($currentRoute) {
                if ($exact) return $currentRoute === $routes;
                foreach ((array) $routes as $route) {
                    if (str_contains($currentRoute, $route)) return true;
                }
                return false;
            };
        @endphp

        {{-- ══ SUPERADMIN ══ --}}
        @if ($isSuperadmin)

            <span class="sb-section-label">Dashboard</span>
            <a href="{{ route('dashboard') }}" class="sb-item {{ $isActive('dashboard') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            <span class="sb-section-label">Data Master</span>
            @php $isMasterOpen = $isActive(['jenis-zakat','tipe-zakat','kategori-mustahik','harga-emas-perak']); @endphp
            <details class="sb-group" {{ $isMasterOpen ? 'open' : '' }}>
                <summary class="sb-item {{ $isMasterOpen ? 'active' : '' }}">
                    <span class="sb-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </span>
                    <span>Data Master</span>
                    <svg class="sb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="sb-sub-list">
                    <a href="{{ route('jenis-zakat.index') }}" class="sb-sub-item {{ $isActive('jenis-zakat') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Jenis Zakat</span>
                    </a>
                    <a href="{{ route('tipe-zakat.index') }}" class="sb-sub-item {{ $isActive('tipe-zakat') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 11h10M7 15h6"/>
                        </svg>
                        <span>Tipe Zakat</span>
                    </a>
                    <a href="{{ route('kategori-mustahik.index') }}" class="sb-sub-item {{ $isActive('kategori-mustahik') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Kategori Mustahik</span>
                    </a>
                    <a href="{{ route('harga-emas-perak.index') }}" class="sb-sub-item {{ $isActive('harga-emas-perak') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.657 0 3 .895 3 2s-1.343 2-3 2m0-4v2m0 10v2"/>
                        </svg>
                        <span>Harga Emas & Perak</span>
                    </a>
                </div>
            </details>

            <span class="sb-section-label">Pengguna</span>
            <a href="{{ route('pengguna.index') }}" class="sb-item {{ $isActive('pengguna') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <span>Pengguna</span>
            </a>

            <span class="sb-section-label">Artikel</span>
            @php $isArtikelOpen = $isActive(['bulletin','kategori-bulletin']); @endphp
            <details class="sb-group" {{ $isArtikelOpen ? 'open' : '' }}>
                <summary class="sb-item {{ $isArtikelOpen ? 'active' : '' }}">
                    <span class="sb-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </span>
                    <span>Bulletin</span>
                    @if (!empty($bulletinPendingCount) && $bulletinPendingCount > 0)
                        <span class="sb-badge">{{ $bulletinPendingCount > 99 ? '99+' : $bulletinPendingCount }}</span>
                    @else
                        <svg class="sb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    @endif
                </summary>
                <div class="sb-sub-list">
                    <a href="{{ route('superadmin.kategori-bulletin.index') }}" class="sb-sub-item {{ $isActive('kategori-bulletin') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        </svg>
                        <span>Kategori Bulletin</span>
                    </a>
                    <a href="{{ route('superadmin.bulletin.index') }}" class="sb-sub-item {{ $isActive('superadmin.bulletin') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Kelola Bulletin</span>
                        @if (!empty($bulletinPendingCount) && $bulletinPendingCount > 0)
                            <span class="sb-badge">{{ $bulletinPendingCount > 99 ? '99+' : $bulletinPendingCount }}</span>
                        @endif
                    </a>
                </div>
            </details>

            <span class="sb-section-label">Lembaga & Orang</span>
            <a href="{{ route('lembaga.index') }}" class="sb-item {{ $isActive('lembaga') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <span>Lembaga</span>
            </a>
            <a href="{{ route('superadmin.amil.index') }}" class="sb-item {{ $isActive('superadmin.amil') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
                <span>Amil</span>
            </a>
            <a href="{{ route('superadmin.mustahik.index') }}" class="sb-item {{ $isActive('superadmin.mustahik') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span>Mustahik</span>
            </a>
            <a href="{{ route('muzaki.index') }}" class="sb-item {{ $isActive('muzaki') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>
                <span>Muzaki</span>
            </a>

            <span class="sb-section-label">Transaksi</span>
            @php $isTrxOpen = $isActive(['transaksi-penerimaan','transaksi-penyaluran']); @endphp
            <details class="sb-group" {{ $isTrxOpen ? 'open' : '' }}>
                <summary class="sb-item {{ $isTrxOpen ? 'active' : '' }}">
                    <span class="sb-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </span>
                    <span>Transaksi</span>
                    <svg class="sb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="sb-sub-list">
                    <a href="{{ route('superadmin.transaksi-penerimaan.index') }}" class="sb-sub-item {{ $isActive('transaksi-penerimaan') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Transaksi Penerimaan</span>
                    </a>
                    <a href="{{ route('superadmin.transaksi-penyaluran.index') }}" class="sb-sub-item {{ $isActive('transaksi-penyaluran') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Transaksi Penyaluran</span>
                    </a>
                </div>
            </details>

            <span class="sb-section-label">Laporan & Sistem</span>
            <a href="{{ route('superadmin.testimoni.index') }}" class="sb-item {{ $isActive('superadmin.testimoni') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </span>
                <span>Kelola Testimoni</span>
            </a>
            <a href="{{ route('laporan-konsolidasi.index') }}" class="sb-item {{ $isActive('laporan-konsolidasi') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <span>Keuangan Seluruh Lembaga</span>
            </a>
            <a href="{{ route('log-aktivitas.index') }}" class="sb-item {{ $isActive('log-aktivitas') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <span>Log Aktivitas</span>
            </a>
            <a href="{{ route('konfigurasi-global.show') }}" class="sb-item {{ $isActive('konfigurasi-global') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <span>Konfigurasi Aplikasi</span>
            </a>
        @endif

        {{-- ══ ADMIN LEMBAGA ══ --}}
        @if ($isAdminLembaga)

            <span class="sb-section-label">Menu Utama</span>
            <a href="{{ route('dashboard') }}" class="sb-item {{ $isActive('dashboard') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            <span class="sb-section-label">Data Utama</span>
            @php $isAdminMasterOpen = $isActive(['program-zakat','rekening-lembaga']); @endphp
            <details class="sb-group" {{ $isAdminMasterOpen ? 'open' : '' }}>
                <summary class="sb-item {{ $isAdminMasterOpen ? 'active' : '' }}">
                    <span class="sb-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </span>
                    <span>Data Master</span>
                    <svg class="sb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="sb-sub-list">
                    <a href="{{ route('program-zakat.index') }}" class="sb-sub-item {{ $isActive('program-zakat') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Program Zakat</span>
                    </a>
                    <a href="{{ route('rekening-lembaga.index') }}" class="sb-sub-item {{ $isActive('rekening-lembaga') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Rekening Lembaga</span>
                    </a>
                </div>
            </details>

            <span class="sb-section-label">Konten</span>
            <a href="{{ route('admin-lembaga.bulletin.index') }}" class="sb-item {{ $isActive(['admin-lembaga.bulletin','bulletin-saya']) ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </span>
                <span>Bulletin Saya</span>
            </a>

            <span class="sb-section-label">SDM</span>
            <a href="{{ route('amil.index') }}" class="sb-item {{ $isActive('amil') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
                <span>Data Amil</span>
            </a>
            <a href="{{ route('mustahik.index') }}" class="sb-item {{ $isActive('mustahik') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span>Data Mustahik</span>
                @if (!empty($pendingMustahikCount) && $pendingMustahikCount > 0)
                    <span class="sb-badge sb-badge-warn">{{ $pendingMustahikCount > 99 ? '99+' : $pendingMustahikCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin-lembaga.muzaki.index') }}" class="sb-item {{ $isActive('admin-lembaga.muzaki') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>
                <span>Data Muzaki</span>
            </a>

            <span class="sb-section-label">Transaksi</span>
            <a href="{{ route('transaksi-penyaluran.index') }}" class="sb-item {{ $isActive('transaksi-penyaluran') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </span>
                <span>Penyaluran</span>
                @if (!empty($pendingApprovalCount) && $pendingApprovalCount > 0)
                    <span class="sb-badge">{{ $pendingApprovalCount > 99 ? '99+' : $pendingApprovalCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin-lembaga.setor-kas.pending') }}" class="sb-item {{ $isActive('setor-kas') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
                <span>Setor Kas Amil</span>
                @if (!empty($pendingSetorKasCount) && $pendingSetorKasCount > 0)
                    <span class="sb-badge sb-badge-warn">{{ $pendingSetorKasCount > 99 ? '99+' : $pendingSetorKasCount }}</span>
                @endif
            </a>

            <span class="sb-section-label">Laporan & Pengaturan</span>
            <a href="{{ route('laporan-keuangan.index') }}" class="sb-item {{ $isActive('laporan-keuangan') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <span>Laporan Keuangan</span>
            </a>
            <a href="{{ route('konfigurasi-integrasi.show') }}" class="sb-item {{ $isActive('konfigurasi-integrasi') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <span>Konfigurasi Lembaga</span>
            </a>
        @endif

        {{-- ══ AMIL ══ --}}
        @if ($isAmil)
            @php
                $transaksiRoutes = ['pemantauan-transaksi','transaksi-datang-langsung','transaksi-daring','transaksi-dijemput','transaksi-penyaluran'];
                $isTransaksiOpen = $isActive($transaksiRoutes);
                $isKasOpen       = $isActive(['kas-harian','setor-kas']);
                $totalNotifAmil  = ($sidebarCounts['daring'] ?? 0) + ($sidebarCounts['dijemput'] ?? 0);
            @endphp

            <span class="sb-section-label">Menu Utama</span>
            <a href="{{ route('dashboard') }}" class="sb-item {{ $isActive('dashboard') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            <span class="sb-section-label">Data Penerima</span>
            <a href="{{ route('mustahik.index') }}" class="sb-item {{ $isActive('mustahik') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span>Data Mustahik</span>
            </a>

            <span class="sb-section-label">Transaksi</span>
            <details class="sb-group" {{ $isTransaksiOpen ? 'open' : '' }}>
                <summary class="sb-item {{ $isTransaksiOpen ? 'active' : '' }}">
                    <span class="sb-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </span>
                    <span>Kelola Transaksi</span>
                    @if ($totalNotifAmil > 0)
                        <span class="sb-badge">{{ $totalNotifAmil > 99 ? '99+' : $totalNotifAmil }}</span>
                    @else
                        <svg class="sb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    @endif
                </summary>
                <div class="sb-sub-list">
                    <p class="sb-micro-label">Keseluruhan</p>
                    <a href="{{ route('pemantauan-transaksi.index') }}" class="sb-sub-item {{ $isActive('pemantauan-transaksi') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Pemantauan</span>
                    </a>
                    <p class="sb-micro-label">Metode</p>
                    <a href="{{ route('transaksi-datang-langsung.index') }}" class="sb-sub-item {{ $isActive('transaksi-datang-langsung') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Datang Langsung</span>
                    </a>
                    <a href="{{ route('transaksi-daring.index') }}" class="sb-sub-item {{ $isActive('transaksi-daring') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                        </svg>
                        <span>Daring</span>
                        @if (!empty($sidebarCounts['daring']) && $sidebarCounts['daring'] > 0)
                            <span class="sb-badge">{{ $sidebarCounts['daring'] > 99 ? '99+' : $sidebarCounts['daring'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('transaksi-dijemput.index') }}" class="sb-sub-item {{ $isActive('transaksi-dijemput') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Dijemput</span>
                        @if (!empty($sidebarCounts['dijemput']) && $sidebarCounts['dijemput'] > 0)
                            <span class="sb-badge sb-badge-warn">{{ $sidebarCounts['dijemput'] > 99 ? '99+' : $sidebarCounts['dijemput'] }}</span>
                        @endif
                    </a>
                    <p class="sb-micro-label">Penyaluran</p>
                    <a href="{{ route('transaksi-penyaluran.index') }}" class="sb-sub-item {{ $isActive('transaksi-penyaluran') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Transaksi Penyaluran</span>
                    </a>
                </div>
            </details>

            <span class="sb-section-label">Kas Anda</span>
            <details class="sb-group" {{ $isKasOpen ? 'open' : '' }}>
                <summary class="sb-item {{ $isKasOpen ? 'active' : '' }}">
                    <span class="sb-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </span>
                    <span>Kas</span>
                    <svg class="sb-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="sb-sub-list">
                    <a href="{{ route('kas-harian.index') }}" class="sb-sub-item {{ $isActive('kas-harian') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Kas Harian</span>
                    </a>
                    <a href="{{ route('amil.setor-kas.index') }}" class="sb-sub-item {{ $isActive('setor-kas') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                        <span>Setor Kas</span>
                    </a>
                </div>
            </details>

            <span class="sb-section-label">Kunjungan</span>
            <a href="{{ route('amil.kunjungan.index') }}" class="sb-item {{ $isActive('kunjungan') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <span>Kunjungan Mustahik</span>
            </a>
        @endif

        {{-- ══ MUZAKKI ══ --}}
        @if ($isMuzakki)
            <span class="sb-section-label">Menu Utama</span>
            <a href="{{ route('dashboard') }}" class="sb-item {{ $isActive('dashboard') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            <span class="sb-section-label">Zakat Saya</span>
            <a href="{{ route('transaksi-daring-muzakki.index') }}" class="sb-item {{ $isActive('transaksi-daring-muzakki') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
                <span>Bayar Zakat</span>
            </a>
            <a href="{{ route('riwayat-transaksi-muzakki.index') }}" class="sb-item {{ $isActive('riwayat-transaksi-muzakki') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </span>
                <span>Riwayat Zakat</span>
            </a>
            <a href="{{ route('muzakki.testimoni.index') }}" class="sb-item {{ $isActive('muzakki.testimoni') ? 'active' : '' }}">
                <span class="sb-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </span>
                <span>Testimoni Saya</span>
            </a>
        @endif

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <p class="text-[10px] text-slate-300 text-center font-semibold tracking-wide">
            © {{ date('Y') }} {{ $appConfig->nama_aplikasi ?? 'Niat Zakat' }}
        </p>
    </div>
</aside>

{{-- Toggle Button --}}
<button id="sidebar-toggle"
    class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-xl bg-white text-gray-500 shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 active:scale-95"
    style="border: 1px solid #f1f5f9; display: none;"
    aria-label="Buka menu">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

{{-- Overlay --}}
<div id="sidebar-overlay"
    class="fixed inset-0 bg-black/30 z-40 hidden backdrop-blur-sm transition-all duration-300">
</div>

@push('scripts')
<script>
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }

    function initSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const overlay = document.getElementById('sidebar-overlay');

        if (!sidebar) {
            console.warn('Sidebar: Elemen #sidebar tidak ditemukan');
            return;
        }

        const isDesktop = () => window.innerWidth >= 1024;

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
            if (toggleBtn) toggleBtn.setAttribute('aria-label', 'Buka menu');
        };

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            if (overlay && !isDesktop()) overlay.classList.remove('hidden');
            if (toggleBtn) toggleBtn.setAttribute('aria-label', 'Tutup menu');
        };

        if (toggleBtn) {
            const updateButtonVisibility = () => {
                if (isDesktop()) {
                    toggleBtn.style.display = 'none';
                } else {
                    toggleBtn.style.display = 'flex';
                }
            };
            
            updateButtonVisibility();
            
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });
            
            window.addEventListener('resize', function() {
                updateButtonVisibility();
            });
        }

        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !isDesktop() && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });

        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (isDesktop()) {
                    sidebar.classList.remove('-translate-x-full');
                    if (overlay) overlay.classList.add('hidden');
                } else {
                    closeSidebar();
                }
            }, 100);
        });

        if (isDesktop()) {
            sidebar.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
        } else {
            closeSidebar();
        }
    }
})();
</script>
@endpush