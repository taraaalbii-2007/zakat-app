@extends('layouts.guest')

@section('title', 'Panduan Zakat')

@section('content')

    @include('partials.landing.page-hero', [
    'breadcrumb'    => 'Panduan Zakat',
    'badge'         => 'Panduan Zakat',
    'heroTitle'     => 'Panduan Zakat',
    'heroSubtitle'  => 'Informasi lengkap jenis zakat, metode penerimaan, dan cara pembayaran yang tersedia di sistem kami.'
])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col lg:flex-row gap-10">

            {{-- SIDEBAR --}}
            <aside class="lg:w-60 flex-shrink-0">
                <div class="sticky top-24 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 px-5 py-4">
                        <p class="text-xs font-semibold text-emerald-200 uppercase tracking-widest mb-0.5">Daftar Isi</p>
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
                               class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                    <div class="mx-3 mb-3 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <p class="text-xs text-emerald-700 font-medium mb-2">Siap menunaikan zakat?</p>
                        <a href="{{ route('hitung-zakat') }}"
                           class="block text-center bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors">
                            Hitung Zakat Sekarang
                        </a>
                    </div>
                </div>
            </aside>

            {{-- KONTEN UTAMA --}}
            <main class="flex-1 min-w-0 space-y-14">

                {{-- ===== JENIS ZAKAT ===== --}}
                <section id="jenis-zakat" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-1 h-8 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Jenis Zakat</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-5">
                        Sistem ini melayani empat jenis kewajiban: Zakat Fitrah, Zakat Mal, Zakat Profesi, dan Fidyah.
                        Setiap jenis memiliki nisab, kadar, dan cara perhitungan berbeda.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                               class="block bg-white border border-gray-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-sm transition-all duration-150 group">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-bold text-gray-900">{{ $jenis['nama'] }}</h4>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $jenis['desc'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== ZAKAT FITRAH ===== --}}
                <section id="zakat-fitrah" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-1 h-8 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Zakat Fitrah</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-5">
                        Wajib ditunaikan sebelum shalat Idul Fitri. Dapat dibayar untuk diri sendiri maupun ditanggung untuk anggota
                        keluarga yang menjadi tanggungan. Sistem menggunakan konstanta <strong>Rp 50.000/jiwa</strong> dan
                        <strong>2,5 kg/jiwa</strong> sesuai ketetapan BAZNAS 2024.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        @php
                            $ketentuanFitrah = [
                                ['label' => 'Uang per Jiwa',  'nilai' => 'Rp 50.000', 'sub' => 'BAZNAS 2024'],
                                ['label' => 'Beras per Jiwa', 'nilai' => '2,5 kg',    'sub' => 'atau 3,5 liter'],
                                ['label' => 'Batas Waktu',    'nilai' => 'Sebelum Id','sub' => 'sebelum shalat Idul Fitri'],
                            ];
                        @endphp
                        @foreach($ketentuanFitrah as $k)
                            <div class="bg-white border-2 border-emerald-100 rounded-xl p-5 text-center">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ $k['label'] }}</p>
                                <p class="text-xl font-bold text-emerald-700">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                        Jika jumlah yang dibayarkan melebihi kewajiban, selisihnya otomatis dicatat sebagai
                        <strong>infaq sukarela</strong> oleh sistem.
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== ZAKAT MAL ===== --}}
                <section id="zakat-mal" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-1 h-8 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Zakat Mal</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-5">
                        Zakat harta atas emas, perak, uang, dan aset senilainya. Wajib jika harta telah mencapai nisab
                        dan sudah dimiliki selama satu tahun penuh (haul). Nilai nisab mengikuti harga emas terkini
                        yang diambil otomatis dari database sistem.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        @php
                            $ketentuanMal = [
                                ['label' => 'Nisab',  'nilai' => '85 gram emas', 'sub' => 'mengikuti harga emas terkini'],
                                ['label' => 'Kadar',  'nilai' => '2,5%',         'sub' => 'dari total nilai harta'],
                                ['label' => 'Haul',   'nilai' => '1 Tahun',      'sub' => 'kepemilikan penuh'],
                            ];
                        @endphp
                        @foreach($ketentuanMal as $k)
                            <div class="bg-white border-2 border-blue-100 rounded-xl p-5 text-center">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ $k['label'] }}</p>
                                <p class="text-xl font-bold text-blue-700">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 font-mono text-sm text-gray-700">
                        Zakat = Total Harta x 2,5%
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: Rp 100.000.000 x 2,5% = Rp 2.500.000</span>
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== ZAKAT PROFESI ===== --}}
                <section id="zakat-profesi" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-1 h-8 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Zakat Profesi</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-5">
                        Zakat atas penghasilan dari gaji atau pekerjaan. Nisab dihitung per bulan (1/12 dari nilai 85 gram emas).
                        Jika penghasilan bulanan melebihi nisab tersebut, wajib zakat 2,5%.
                    </p>
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <h4 class="font-semibold text-gray-800 mb-3">Contoh Perhitungan</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Gaji per bulan</span>
                                <span class="font-medium">Rp 10.000.000</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Kadar zakat</span>
                                <span class="font-medium">2,5%</span>
                            </div>
                            <div class="flex justify-between py-2 bg-emerald-50 rounded-lg px-3 mt-1">
                                <span class="font-semibold text-gray-800">Zakat per bulan</span>
                                <span class="font-bold text-emerald-700">Rp 250.000</span>
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== FIDYAH ===== --}}
                <section id="fidyah" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-1 h-8 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Fidyah</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-5">
                        Kewajiban bagi yang tidak mampu berpuasa Ramadhan secara permanen (sakit kronis, lansia).
                        Sistem menggunakan konstanta <strong>675 gram/hari</strong> sesuai BAZNAS 2024. Tersedia tiga tipe fidyah:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        @php
                            $tipesFidyah = [
                                [
                                    'tipe'  => 'Bahan Mentah',
                                    'kode'  => 'bahan_mentah',
                                    'nilai' => '675 gram/hari',
                                    'desc'  => 'Serahkan bahan makanan pokok secara fisik. Sistem menghitung total berat = 675 gram x jumlah hari.',
                                ],
                                [
                                    'tipe'  => 'Makanan Matang',
                                    'kode'  => 'makanan_matang',
                                    'nilai' => '1 porsi/hari',
                                    'desc'  => 'Isi menu, jumlah box, harga per box, dan cara serah: langsung dibagikan, dijamu, atau via lembaga.',
                                ],
                                [
                                    'tipe'  => 'Uang Tunai',
                                    'kode'  => 'tunai',
                                    'nilai' => 'Nominal x hari',
                                    'desc'  => 'Bayar tunai/transfer/QRIS. Total = harga fidyah per hari x jumlah hari puasa yang ditinggalkan.',
                                ],
                            ];
                        @endphp
                        @foreach($tipesFidyah as $tipe)
                            <div class="bg-white border border-gray-200 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $tipe['tipe'] }}</h4>
                                    <span class="text-xs font-mono bg-gray-100 text-gray-500 px-2 py-0.5 rounded">{{ $tipe['kode'] }}</span>
                                </div>
                                <p class="text-base font-bold text-emerald-700 mb-2">{{ $tipe['nilai'] }}</p>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $tipe['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                        Pilih <strong>Jenis: Fidyah</strong> saat mengisi formulir, kemudian isi jumlah hari puasa
                        yang ditinggalkan dan pilih tipe fidyah yang sesuai.
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== METODE PENERIMAAN ===== --}}
                <section id="metode-penerimaan" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-1 h-8 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Metode Penerimaan</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Tersedia tiga metode penerimaan zakat. Masing-masing memiliki alur status yang berbeda.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        {{-- Datang Langsung --}}
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-emerald-600 px-4 py-3">
                                <p class="text-white font-bold text-sm">Datang Langsung</p>
                                <p class="text-emerald-200 text-xs font-mono mt-0.5">datang_langsung</p>
                            </div>
                            <div class="p-5">
                                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                    Muzaki datang ke masjid dan menyerahkan zakat langsung kepada amil.
                                    Amil menginput transaksi, kwitansi langsung diterbitkan.
                                </p>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alur Status Transaksi</p>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-medium">pending</span>
                                    <span class="text-gray-300 text-xs">&#8594;</span>
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">verified</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Tunai dan beras otomatis verified. Transfer/QRIS perlu konfirmasi amil.</p>
                            </div>
                        </div>

                        {{-- Dijemput --}}
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-blue-600 px-4 py-3">
                                <p class="text-white font-bold text-sm">Dijemput Amil</p>
                                <p class="text-blue-200 text-xs font-mono mt-0.5">dijemput</p>
                            </div>
                            <div class="p-5">
                                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                    Muzaki mengajukan penjemputan. Amil datang ke lokasi muzaki untuk mengambil zakat.
                                </p>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alur Status Penjemputan</p>
                                <div class="space-y-1">
                                    @php
                                        $alurJemput = [
                                            'menunggu'         => 'Request masuk',
                                            'diterima'         => 'Amil konfirmasi',
                                            'dalam_perjalanan' => 'Amil berangkat',
                                            'sampai_lokasi'    => 'Amil tiba',
                                            'selesai'          => 'Zakat diterima',
                                        ];
                                    @endphp
                                    @foreach($alurJemput as $kode => $label)
                                        <div class="flex items-center gap-2 text-xs text-gray-600">
                                            <span class="font-mono bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded">{{ $kode }}</span>
                                            <span class="text-gray-400">{{ $label }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Daring --}}
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-violet-600 px-4 py-3">
                                <p class="text-white font-bold text-sm">Daring (Online)</p>
                                <p class="text-violet-200 text-xs font-mono mt-0.5">daring</p>
                            </div>
                            <div class="p-5">
                                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                    Muzaki isi formulir online, transfer atau scan QRIS, lalu upload bukti.
                                    Amil mengkonfirmasi setelah bukti diterima.
                                </p>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alur Status Konfirmasi</p>
                                <div class="space-y-1">
                                    @php
                                        $alurDaring = [
                                            'menunggu_konfirmasi' => 'Menunggu cek amil',
                                            'dikonfirmasi'        => 'Bukti valid, lunas',
                                            'ditolak'             => 'Bukti tidak valid',
                                        ];
                                    @endphp
                                    @foreach($alurDaring as $kode => $label)
                                        <div class="flex items-center gap-2 text-xs text-gray-600">
                                            <span class="font-mono bg-violet-50 text-violet-700 px-1.5 py-0.5 rounded">{{ $kode }}</span>
                                            <span class="text-gray-400">{{ $label }}</span>
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
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-1 h-8 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">Metode Pembayaran</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Tersedia enam metode pembayaran. Ketersediaannya bergantung pada jenis zakat dan metode penerimaan yang dipilih.
                    </p>

                    <div class="space-y-3 mb-6">
                        @php
                            $metodeBayar = [
                                [
                                    'nama'     => 'Tunai',
                                    'kode'     => 'tunai',
                                    'badge'    => 'bg-emerald-100 text-emerald-700',
                                    'tag'      => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'catatan'  => 'Otomatis verified',
                                    'desc'     => 'Bayar cash langsung kepada amil. Transaksi langsung otomatis terverifikasi tanpa konfirmasi tambahan.',
                                    'tersedia' => ['datang_langsung', 'dijemput'],
                                ],
                                [
                                    'nama'     => 'Transfer Bank',
                                    'kode'     => 'transfer',
                                    'badge'    => 'bg-blue-100 text-blue-700',
                                    'tag'      => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'catatan'  => 'Perlu konfirmasi amil',
                                    'desc'     => 'Transfer ke rekening masjid, kemudian upload foto bukti transfer. Amil akan mengecek dan mengkonfirmasi.',
                                    'tersedia' => ['datang_langsung', 'dijemput', 'daring'],
                                ],
                                [
                                    'nama'     => 'QRIS',
                                    'kode'     => 'qris',
                                    'badge'    => 'bg-violet-100 text-violet-700',
                                    'tag'      => 'bg-violet-50 text-violet-700 border-violet-100',
                                    'catatan'  => 'Perlu konfirmasi amil',
                                    'desc'     => 'Scan kode QRIS masjid via dompet digital atau mobile banking. Upload screenshot bukti. Amil mengkonfirmasi setelah dicek.',
                                    'tersedia' => ['datang_langsung', 'dijemput', 'daring'],
                                ],
                                [
                                    'nama'     => 'Beras',
                                    'kode'     => 'beras',
                                    'badge'    => 'bg-amber-100 text-amber-700',
                                    'tag'      => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'catatan'  => 'Khusus Zakat Fitrah',
                                    'desc'     => 'Serahkan beras fisik kepada amil. Tidak ada nominal uang, hanya jumlah kilogram yang dicatat. Otomatis verified.',
                                    'tersedia' => ['datang_langsung', 'dijemput'],
                                ],
                                [
                                    'nama'     => 'Bahan Mentah',
                                    'kode'     => 'bahan_mentah',
                                    'badge'    => 'bg-orange-100 text-orange-700',
                                    'tag'      => 'bg-orange-50 text-orange-700 border-orange-100',
                                    'catatan'  => 'Khusus Fidyah',
                                    'desc'     => 'Serahkan bahan makanan pokok secara fisik. Total berat = 675 gram x jumlah hari. Dicatat berat yang diterima amil.',
                                    'tersedia' => ['datang_langsung', 'dijemput'],
                                ],
                                [
                                    'nama'     => 'Makanan Matang',
                                    'kode'     => 'makanan_matang',
                                    'badge'    => 'bg-rose-100 text-rose-700',
                                    'tag'      => 'bg-rose-50 text-rose-700 border-rose-100',
                                    'catatan'  => 'Khusus Fidyah',
                                    'desc'     => 'Isi detail menu, jumlah porsi, harga per porsi, dan cara penyerahan: langsung dibagikan, dijamu di masjid, atau via lembaga.',
                                    'tersedia' => ['datang_langsung', 'dijemput'],
                                ],
                            ];
                        @endphp
                        @foreach($metodeBayar as $m)
                            <div class="bg-white border border-gray-200 rounded-xl p-5">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <h4 class="font-bold text-gray-900">{{ $m['nama'] }}</h4>
                                    <span class="font-mono text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded">{{ $m['kode'] }}</span>
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $m['badge'] }}">{{ $m['catatan'] }}</span>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $m['desc'] }}</p>
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Tersedia untuk:</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($m['tersedia'] as $t)
                                            <span class="text-xs border px-2 py-0.5 rounded-full {{ $m['tag'] }}">{{ $t }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                        <h4 class="font-semibold text-gray-800 mb-3 text-sm">Status Transaksi</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @php
                                $statusList = [
                                    ['kode' => 'pending',  'cls' => 'bg-yellow-100 text-yellow-700', 'ket' => 'Transaksi masuk, belum terverifikasi. Berlaku untuk transfer dan QRIS yang menunggu konfirmasi amil.'],
                                    ['kode' => 'verified', 'cls' => 'bg-green-100 text-green-700',   'ket' => 'Transaksi sah. Tunai otomatis verified. Transfer/QRIS verified setelah dikonfirmasi amil.'],
                                    ['kode' => 'rejected', 'cls' => 'bg-red-100 text-red-700',       'ket' => 'Ditolak amil. Misalnya bukti transfer tidak valid, nominal tidak sesuai, atau foto tidak terbaca.'],
                                ];
                            @endphp
                            @foreach($statusList as $s)
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-full mb-2 {{ $s['cls'] }}">{{ $s['kode'] }}</span>
                                    <p class="text-xs text-gray-600 leading-relaxed">{{ $s['ket'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- ===== 8 GOLONGAN MUSTAHIK ===== --}}
                <section id="mustahik" class="scroll-mt-28">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-1 h-8 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-900">8 Golongan Penerima Zakat</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-5">
                        Allah SWT menetapkan dalam QS. At-Taubah: 60 bahwa zakat hanya boleh disalurkan kepada delapan golongan berikut.
                        Masjid mendistribusikan zakat Anda sesuai program penyaluran yang aktif.
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
                            <div class="flex gap-4 p-4 bg-white border border-gray-200 rounded-xl">
                                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold text-sm flex items-center justify-center flex-shrink-0">
                                    {{ $item['no'] }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 mb-0.5">{{ $item['nama'] }}</p>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $item['ket'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- CTA --}}
                <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-8 text-center">
                    <h3 class="text-xl font-bold text-white mb-2">Tunaikan Zakat Sekarang</h3>
                    <p class="text-emerald-100 text-sm mb-6 max-w-md mx-auto">
                        Pilih metode penerimaan yang paling mudah bagi Anda. Zakat langsung tersalurkan kepada yang berhak.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('hitung-zakat') }}"
                           class="inline-block bg-white text-emerald-700 font-bold px-6 py-3 rounded-xl hover:bg-emerald-50 transition-colors text-sm">
                            Hitung Zakat Saya
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-block bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-6 py-3 rounded-xl transition-colors text-sm">
                            Daftar dan Bayar Zakat
                        </a>
                    </div>
                </div>

            </main>
        </div>
    </div>

@endsection