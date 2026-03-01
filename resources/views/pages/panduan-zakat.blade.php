@extends('layouts.guest')

@section('title', 'Panduan Zakat')

@section('content')

    @include('partials.landing.page-hero', [
        'breadcrumb'   => 'Panduan Zakat',
        'badge'        => 'Panduan Zakat',
        'heroTitle'    => 'Panduan Zakat',
        'heroSubtitle' => 'Informasi lengkap jenis zakat, metode penerimaan, dan cara pembayaran yang tersedia di sistem kami.'
    ])

    {{-- Padding sesuai hero: px-4 sm:px-10 lg:px-20 --}}
    <div class="w-full px-4 sm:px-10 lg:px-20 py-10">
        <div class="flex flex-col lg:flex-row gap-10">

            {{-- SIDEBAR --}}
            <aside class="lg:w-56 flex-shrink-0">
                <div class="sticky top-24 bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-primary-600 px-5 py-4">
                        <p class="text-xs font-semibold text-primary-200 uppercase tracking-widest mb-0.5">Daftar Isi</p>
                        <h3 class="text-white font-bold text-sm">Panduan Zakat</h3>
                    </div>
                    <nav class="p-3 space-y-0.5">
                        @php
                            $navItems = [
                                ['id' => 'jenis-zakat',       'label' => 'Jenis Zakat'],
                                ['id' => 'zakat-fitrah',      'label' => 'Zakat Fitrah'],
                                ['id' => 'zakat-mal',         'label' => 'Zakat Mal'],
                                ['id' => 'zakat-profesi',     'label' => 'Zakat Profesi'],
                                ['id' => 'fidyah',            'label' => 'Fidyah'],
                                ['id' => 'metode-penerimaan', 'label' => 'Metode Penerimaan'],
                                ['id' => 'metode-pembayaran', 'label' => 'Metode Pembayaran'],
                                ['id' => 'mustahik',          'label' => '8 Golongan Mustahik'],
                            ];
                        @endphp
                        @foreach($navItems as $item)
                            <a href="#{{ $item['id'] }}"
                               class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-700 transition-colors duration-150">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                    <div class="mx-3 mb-3 p-4 bg-primary-50 rounded-xl border border-primary-100">
                        <p class="text-xs text-primary-700 font-medium mb-2">Siap menunaikan zakat?</p>
                        <a href="{{ route('hitung-zakat') }}"
                           class="block text-center bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors">
                            Hitung Zakat Sekarang
                        </a>
                    </div>
                </div>
            </aside>

            {{-- KONTEN UTAMA --}}
            <main class="flex-1 min-w-0 space-y-10">

                {{-- ===== JENIS ZAKAT ===== --}}
                <section id="jenis-zakat" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-7 bg-primary-600 rounded-full"></div>
                        <h2 class="text-xl font-bold text-gray-900">Jenis Zakat</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                        Sistem ini melayani empat jenis kewajiban: Zakat Fitrah, Zakat Mal, Zakat Profesi, dan Fidyah.
                        Setiap jenis memiliki nisab, kadar, dan cara perhitungan berbeda.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @php
                            $daftarJenis = [
                                ['nama' => 'Zakat Fitrah',  'anchor' => '#zakat-fitrah',  'desc' => 'Wajib setiap Muslim menjelang Idul Fitri. Besarnya 2,5 kg beras atau Rp 50.000 per jiwa (BAZNAS 2024).'],
                                ['nama' => 'Zakat Mal',     'anchor' => '#zakat-mal',     'desc' => 'Zakat harta yang telah mencapai nisab 85 gram emas dan sudah dimiliki selama satu tahun (haul). Kadar 2,5%.'],
                                ['nama' => 'Zakat Profesi', 'anchor' => '#zakat-profesi', 'desc' => 'Zakat penghasilan dari pekerjaan. Nisab setara 85 gram emas per tahun. Kadar 2,5% dari total penghasilan.'],
                                ['nama' => 'Fidyah',        'anchor' => '#fidyah',        'desc' => 'Kewajiban bagi yang tidak mampu berpuasa secara permanen. Dibayar per hari puasa yang ditinggalkan.'],
                            ];
                        @endphp
                        @foreach($daftarJenis as $jenis)
                            <a href="{{ $jenis['anchor'] }}"
                               class="block bg-white border border-gray-200 rounded-xl p-4 hover:border-primary-400 hover:shadow-card transition-all duration-150 group">
                                <div class="flex items-center justify-between mb-1.5">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $jenis['nama'] }}</h4>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $jenis['desc'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== ZAKAT FITRAH ===== --}}
                <section id="zakat-fitrah" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-7 bg-primary-600 rounded-full"></div>
                        <h2 class="text-xl font-bold text-gray-900">Zakat Fitrah</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                        Wajib ditunaikan sebelum shalat Idul Fitri. Dapat dibayar untuk diri sendiri maupun ditanggung untuk anggota
                        keluarga yang menjadi tanggungan. Sistem menggunakan konstanta <strong class="text-primary-700">Rp 50.000/jiwa</strong> dan
                        <strong class="text-primary-700">2,5 kg/jiwa</strong> sesuai ketetapan BAZNAS 2024.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $ketentuanFitrah = [
                                ['label' => 'Uang per Jiwa',  'nilai' => 'Rp 50.000', 'sub' => 'BAZNAS 2024'],
                                ['label' => 'Beras per Jiwa', 'nilai' => '2,5 kg',    'sub' => 'atau 3,5 liter'],
                                ['label' => 'Batas Waktu',    'nilai' => 'Sebelum Id','sub' => 'sebelum shalat Idul Fitri'],
                            ];
                        @endphp
                        @foreach($ketentuanFitrah as $k)
                            <div class="bg-primary-50 border border-primary-100 rounded-xl p-4 text-center">
                                <p class="text-xs text-primary-600 uppercase tracking-wider mb-1">{{ $k['label'] }}</p>
                                <p class="text-lg font-bold text-primary-700">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 text-sm text-primary-800">
                        Jika jumlah yang dibayarkan melebihi kewajiban, selisihnya otomatis dicatat sebagai
                        <strong>infaq sukarela</strong> oleh sistem.
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== ZAKAT MAL ===== --}}
                <section id="zakat-mal" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-7 bg-primary-600 rounded-full"></div>
                        <h2 class="text-xl font-bold text-gray-900">Zakat Mal</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                        Zakat harta atas emas, perak, uang, dan aset senilainya. Wajib jika harta telah mencapai nisab
                        dan sudah dimiliki selama satu tahun penuh (haul). Nilai nisab mengikuti harga emas terkini
                        yang diambil otomatis dari database sistem.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $ketentuanMal = [
                                ['label' => 'Nisab',  'nilai' => '85 gram emas', 'sub' => 'mengikuti harga emas terkini'],
                                ['label' => 'Kadar',  'nilai' => '2,5%',         'sub' => 'dari total nilai harta'],
                                ['label' => 'Haul',   'nilai' => '1 Tahun',      'sub' => 'kepemilikan penuh'],
                            ];
                        @endphp
                        @foreach($ketentuanMal as $k)
                            <div class="bg-primary-50 border border-primary-100 rounded-xl p-4 text-center">
                                <p class="text-xs text-primary-600 uppercase tracking-wider mb-1">{{ $k['label'] }}</p>
                                <p class="text-lg font-bold text-primary-700">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 font-mono text-sm text-gray-700">
                        Zakat = Total Harta × 2,5%
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: Rp 100.000.000 × 2,5% = Rp 2.500.000</span>
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== ZAKAT PROFESI ===== --}}
                <section id="zakat-profesi" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-7 bg-primary-600 rounded-full"></div>
                        <h2 class="text-xl font-bold text-gray-900">Zakat Profesi</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                        Zakat atas penghasilan dari gaji atau pekerjaan. Nisab dihitung per bulan (1/12 dari nilai 85 gram emas).
                        Jika penghasilan bulanan melebihi nisab tersebut, wajib zakat 2,5%.
                    </p>
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <h4 class="font-semibold text-gray-800 mb-3 text-sm">Contoh Perhitungan</h4>
                        <div class="space-y-1.5 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Gaji per bulan</span>
                                <span class="font-medium">Rp 10.000.000</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Kadar zakat</span>
                                <span class="font-medium">2,5%</span>
                            </div>
                            <div class="flex justify-between py-2 bg-primary-50 rounded-lg px-3">
                                <span class="font-semibold text-gray-800">Zakat per bulan</span>
                                <span class="font-bold text-primary-700">Rp 250.000</span>
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== FIDYAH ===== --}}
                <section id="fidyah" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-7 bg-primary-600 rounded-full"></div>
                        <h2 class="text-xl font-bold text-gray-900">Fidyah</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                        Kewajiban bagi yang tidak mampu berpuasa Ramadhan secara permanen (sakit kronis, lansia).
                        Sistem menggunakan konstanta <strong class="text-primary-700">675 gram/hari</strong> sesuai BAZNAS 2024. Tersedia tiga tipe fidyah:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $tipesFidyah = [
                                [
                                    'tipe'  => 'Bahan Mentah',
                                    'nilai' => '675 gram/hari',
                                    'desc'  => 'Serahkan bahan makanan pokok secara fisik. Total berat dihitung dari 675 gram dikali jumlah hari.',
                                ],
                                [
                                    'tipe'  => 'Makanan Matang',
                                    'nilai' => '1 porsi/hari',
                                    'desc'  => 'Isi menu, jumlah porsi, harga per porsi, dan cara serah: langsung dibagikan, dijamu, atau via lembaga.',
                                ],
                                [
                                    'tipe'  => 'Uang Tunai',
                                    'nilai' => 'Nominal × hari',
                                    'desc'  => 'Bayar tunai, transfer, atau QRIS. Total dihitung dari harga fidyah per hari dikali jumlah hari puasa yang ditinggalkan.',
                                ],
                            ];
                        @endphp
                        @foreach($tipesFidyah as $tipe)
                            <div class="bg-white border border-gray-200 rounded-xl p-4">
                                <h4 class="font-bold text-gray-900 text-sm mb-1">{{ $tipe['tipe'] }}</h4>
                                <p class="text-base font-bold text-primary-600 mb-2">{{ $tipe['nilai'] }}</p>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $tipe['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 text-sm text-primary-800">
                        Pilih <strong>Jenis: Fidyah</strong> saat mengisi formulir, kemudian isi jumlah hari puasa
                        yang ditinggalkan dan pilih tipe fidyah yang sesuai.
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== METODE PENERIMAAN ===== --}}
                <section id="metode-penerimaan" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-7 bg-primary-600 rounded-full"></div>
                        <h2 class="text-xl font-bold text-gray-900">Metode Penerimaan</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                        Tersedia tiga metode penerimaan zakat. Masing-masing memiliki alur status yang berbeda.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- Datang Langsung --}}
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-primary-600 px-4 py-3">
                                <p class="text-white font-bold text-sm">Datang Langsung</p>
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-600 leading-relaxed mb-4">
                                    Muzaki datang ke masjid dan menyerahkan zakat langsung kepada amil.
                                    Amil menginput transaksi, kwitansi langsung diterbitkan.
                                </p>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alur Status</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full font-medium">Menunggu</span>
                                    <span class="text-gray-300 text-xs">→</span>
                                    <span class="text-xs bg-primary-100 text-primary-700 px-2 py-1 rounded-full font-medium">Terverifikasi</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Tunai dan beras otomatis terverifikasi. Transfer dan QRIS perlu konfirmasi amil.</p>
                            </div>
                        </div>

                        {{-- Dijemput --}}
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-primary-700 px-4 py-3">
                                <p class="text-white font-bold text-sm">Dijemput Amil</p>
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-600 leading-relaxed mb-4">
                                    Muzaki mengajukan penjemputan. Amil datang ke lokasi muzaki untuk mengambil zakat.
                                </p>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alur Status</p>
                                <div class="space-y-1.5">
                                    @php
                                        $alurJemput = [
                                            'Menunggu'          => 'Request masuk',
                                            'Diterima'          => 'Amil konfirmasi',
                                            'Dalam Perjalanan'  => 'Amil berangkat',
                                            'Sampai Lokasi'     => 'Amil tiba',
                                            'Selesai'           => 'Zakat diterima',
                                        ];
                                    @endphp
                                    @foreach($alurJemput as $status => $ket)
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="bg-primary-50 text-primary-700 border border-primary-100 px-2 py-0.5 rounded-full whitespace-nowrap">{{ $status }}</span>
                                            <span class="text-gray-400">{{ $ket }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Daring --}}
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-primary-600 px-4 py-3">
                                <p class="text-white font-bold text-sm">Daring (Online)</p>
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-600 leading-relaxed mb-4">
                                    Muzaki isi formulir online, transfer atau scan QRIS, lalu upload bukti.
                                    Amil mengkonfirmasi setelah bukti diterima.
                                </p>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alur Status</p>
                                <div class="space-y-1.5">
                                    @php
                                        $alurDaring = [
                                            'Menunggu Konfirmasi' => 'Menunggu cek amil',
                                            'Dikonfirmasi'        => 'Bukti valid, lunas',
                                            'Ditolak'             => 'Bukti tidak valid',
                                        ];
                                    @endphp
                                    @foreach($alurDaring as $status => $ket)
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="bg-primary-50 text-primary-700 border border-primary-100 px-2 py-0.5 rounded-full whitespace-nowrap">{{ $status }}</span>
                                            <span class="text-gray-400">{{ $ket }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== METODE PEMBAYARAN ===== --}}
                <section id="metode-pembayaran" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-7 bg-primary-600 rounded-full"></div>
                        <h2 class="text-xl font-bold text-gray-900">Metode Pembayaran</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                        Tersedia enam metode pembayaran. Ketersediaannya bergantung pada jenis zakat dan metode penerimaan yang dipilih.
                    </p>

                    <div class="space-y-2.5 mb-5">
                        @php
                            $metodeBayar = [
                                [
                                    'nama'     => 'Tunai',
                                    'catatan'  => 'Otomatis terverifikasi',
                                    'desc'     => 'Bayar cash langsung kepada amil. Transaksi otomatis terverifikasi tanpa konfirmasi tambahan.',
                                    'tersedia' => ['Datang Langsung', 'Dijemput Amil'],
                                ],
                                [
                                    'nama'     => 'Transfer Bank',
                                    'catatan'  => 'Perlu konfirmasi amil',
                                    'desc'     => 'Transfer ke rekening masjid, kemudian upload foto bukti transfer. Amil akan mengecek dan mengkonfirmasi.',
                                    'tersedia' => ['Datang Langsung', 'Dijemput Amil', 'Daring'],
                                ],
                                [
                                    'nama'     => 'QRIS',
                                    'catatan'  => 'Perlu konfirmasi amil',
                                    'desc'     => 'Scan kode QRIS masjid via dompet digital atau mobile banking. Upload screenshot bukti, amil mengkonfirmasi.',
                                    'tersedia' => ['Datang Langsung', 'Dijemput Amil', 'Daring'],
                                ],
                                [
                                    'nama'     => 'Beras',
                                    'catatan'  => 'Khusus Zakat Fitrah',
                                    'desc'     => 'Serahkan beras fisik kepada amil. Hanya jumlah kilogram yang dicatat, otomatis terverifikasi.',
                                    'tersedia' => ['Datang Langsung', 'Dijemput Amil'],
                                ],
                                [
                                    'nama'     => 'Bahan Mentah',
                                    'catatan'  => 'Khusus Fidyah',
                                    'desc'     => 'Serahkan bahan makanan pokok secara fisik. Total berat = 675 gram × jumlah hari puasa yang ditinggalkan.',
                                    'tersedia' => ['Datang Langsung', 'Dijemput Amil'],
                                ],
                                [
                                    'nama'     => 'Makanan Matang',
                                    'catatan'  => 'Khusus Fidyah',
                                    'desc'     => 'Isi detail menu, jumlah porsi, harga per porsi, dan cara penyerahan: langsung dibagikan, dijamu, atau via lembaga.',
                                    'tersedia' => ['Datang Langsung', 'Dijemput Amil'],
                                ],
                            ];
                        @endphp
                        @foreach($metodeBayar as $m)
                            <div class="bg-white border border-gray-200 rounded-xl p-4">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $m['nama'] }}</h4>
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-primary-100 text-primary-700">{{ $m['catatan'] }}</span>
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed mb-2.5">{{ $m['desc'] }}</p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($m['tersedia'] as $t)
                                        <span class="text-xs border border-primary-100 bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full">{{ $t }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Status Transaksi --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <h4 class="font-semibold text-gray-800 mb-3 text-sm">Status Transaksi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @php
                                $statusList = [
                                    ['kode' => 'Menunggu',      'cls' => 'bg-gray-200 text-gray-700',       'ket' => 'Transaksi masuk, belum terverifikasi. Berlaku untuk transfer dan QRIS yang menunggu konfirmasi amil.'],
                                    ['kode' => 'Terverifikasi', 'cls' => 'bg-primary-100 text-primary-700', 'ket' => 'Transaksi sah. Tunai otomatis terverifikasi. Transfer dan QRIS diverifikasi setelah dikonfirmasi amil.'],
                                    ['kode' => 'Ditolak',       'cls' => 'bg-gray-800 text-white',          'ket' => 'Ditolak amil. Contohnya bukti transfer tidak valid, nominal tidak sesuai, atau foto tidak terbaca.'],
                                ];
                            @endphp
                            @foreach($statusList as $s)
                                <div class="bg-white border border-gray-200 rounded-lg p-3">
                                    <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full mb-2 {{ $s['cls'] }}">{{ $s['kode'] }}</span>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $s['ket'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== 8 GOLONGAN MUSTAHIK ===== --}}
                <section id="mustahik" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-7 bg-primary-600 rounded-full"></div>
                        <h2 class="text-xl font-bold text-gray-900">8 Golongan Penerima Zakat</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                        Allah SWT menetapkan dalam QS. At-Taubah: 60 bahwa zakat hanya boleh disalurkan kepada delapan golongan berikut.
                        Masjid mendistribusikan zakat sesuai program penyaluran yang aktif.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @php
                            $mustahik = [
                                ['no' => '1', 'nama' => 'Fakir',         'ket' => 'Tidak memiliki harta sama sekali atau sangat sedikit sehingga tidak mampu memenuhi kebutuhan pokok.'],
                                ['no' => '2', 'nama' => 'Miskin',        'ket' => 'Memiliki penghasilan namun belum cukup untuk memenuhi kebutuhan pokok sehari-hari secara layak.'],
                                ['no' => '3', 'nama' => 'Amil',          'ket' => 'Orang atau lembaga yang ditugaskan mengumpulkan, mengelola, dan mendistribusikan zakat kepada yang berhak.'],
                                ['no' => '4', 'nama' => 'Muallaf',       'ket' => 'Orang yang baru masuk Islam atau yang sedang dirangkul hatinya agar semakin dekat dengan Islam.'],
                                ['no' => '5', 'nama' => 'Riqab',         'ket' => 'Hamba sahaya yang ingin merdeka. Kini dimaknai sebagai membantu orang yang terbelenggu kemiskinan struktural.'],
                                ['no' => '6', 'nama' => 'Gharim',        'ket' => 'Orang yang terlilit utang bukan karena kemaksiatan dan tidak mampu melunasinya.'],
                                ['no' => '7', 'nama' => 'Fi Sabilillah', 'ket' => 'Orang yang berjuang di jalan Allah, termasuk pendidik agama, ulama, dan pejuang kepentingan Islam.'],
                                ['no' => '8', 'nama' => 'Ibnu Sabil',    'ket' => 'Musafir yang kehabisan bekal di perjalanan, meskipun di daerah asalnya tergolong berkecukupan.'],
                            ];
                        @endphp
                        @foreach($mustahik as $item)
                            <div class="flex gap-3 p-4 bg-white border border-gray-200 rounded-xl">
                                <div class="w-7 h-7 rounded-full bg-primary-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ $item['no'] }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm mb-0.5">{{ $item['nama'] }}</p>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $item['ket'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- CTA --}}
                <div class="bg-primary-600 rounded-2xl p-8 text-center">
                    <h3 class="text-lg font-bold text-white mb-2">Tunaikan Zakat Sekarang</h3>
                    <p class="text-primary-100 text-sm mb-5 max-w-md mx-auto">
                        Pilih metode penerimaan yang paling mudah bagi Anda. Zakat langsung tersalurkan kepada yang berhak.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('hitung-zakat') }}"
                           class="inline-block bg-white text-primary-700 font-bold px-6 py-3 rounded-xl hover:bg-primary-50 transition-colors text-sm">
                            Hitung Zakat Saya
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-block bg-primary-500 hover:bg-primary-400 border border-primary-400 text-white font-bold px-6 py-3 rounded-xl transition-colors text-sm">
                            Daftar dan Bayar Zakat
                        </a>
                    </div>
                </div>

            </main>
        </div>
    </div>

@endsection