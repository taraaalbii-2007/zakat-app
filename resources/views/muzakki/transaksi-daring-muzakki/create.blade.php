@extends('layouts.app')

@section('title', 'Bayar Zakat')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- ── Header ── --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 bg-primary-50 border-b border-primary-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-100 border border-primary-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">Bayar Zakat</h2>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $masjid->nama }}</p>
                        </div>
                    </div>
                    <a href="{{ route('transaksi-daring-muzakki.index') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all self-start sm:self-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- ── STEP 0: Pilih Metode ── --}}
            <div id="panelPilihMetode" class="p-4 sm:p-6">

                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293-1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 mt-1 space-y-0.5">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <h3 class="text-sm font-bold text-gray-800 mb-5 text-center">Pilih Cara Bayar Zakat</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl mx-auto">
                    {{-- Card Daring --}}
                    <button type="button" onclick="pilihMetode('daring')"
                        class="metode-card group flex flex-col items-center gap-3 p-6 rounded-2xl border-2 border-gray-200 hover:border-primary-400 hover:bg-primary-50/50 cursor-pointer transition-all text-center">
                        <div class="w-16 h-16 rounded-2xl bg-primary-100 group-hover:bg-primary-200 flex items-center justify-center transition-colors">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-900">Daring (Online)</p>
                            <p class="text-xs text-gray-500 mt-1">Bayar via Transfer Bank atau QRIS. Bukti dikirim ke amil untuk dikonfirmasi.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700">Direkomendasikan</span>
                    </button>

                    {{-- Card Dijemput --}}
                    <button type="button" onclick="pilihMetode('dijemput')"
                        class="metode-card group flex flex-col items-center gap-3 p-6 rounded-2xl border-2 border-gray-200 hover:border-primary-400 hover:bg-primary-50/50 cursor-pointer transition-all text-center">
                        <div class="w-16 h-16 rounded-2xl bg-primary-100 group-hover:bg-primary-200 flex items-center justify-center transition-colors">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-900">Dijemput Amil</p>
                            <p class="text-xs text-gray-500 mt-1">Amil datang ke lokasi Anda. Zakat bisa dibayar tunai, transfer, atau beras.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700">Amil ke lokasi Anda</span>
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════
         PANEL DARING
    ══════════════════════════════════════════════════════════ --}}
            <div id="panelDaring" class="hidden">

                {{-- ===================================================
                     MODAL NIAT DOA ZAKAT (muncul saat pilih daring)
                     HANYA berisi niat zakat fitrah & mal
                ====================================================== --}}
                <div id="modalNiatDoa"
                    class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[10000] flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">

                        {{-- Header Modal --}}
                        <div class="px-6 py-4 bg-primary text-white flex-shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                    <span class="text-xl">☪</span>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold">Niat Zakat</h3>
                                    <p class="text-xs text-primary-100">Baca dengan khusyuk sebelum membayar</p>
                                </div>
                            </div>
                        </div>

                        {{-- Konten Doa (scrollable) --}}
                        <div class="overflow-y-auto flex-1 px-6 py-5 space-y-5" id="doaScrollArea">

                            {{-- ── Niat Zakat Fitrah untuk Diri Sendiri ── --}}
                            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-3">Niat Zakat Fitrah — Diri Sendiri</h4>
                                <p class="text-right text-lg leading-loose text-gray-800 font-arabic mb-2">
                                    ﻧَﻮَﻳْﺖُ أَﻥْ أُﺧْﺮِﺝَ ﺯَﻛَﺎﺓَ ﺍﻟْﻔِﻄْﺮِ ﻋَﻦْ ﻧَﻔْسيْ ﻓَﺮْﺿًﺎ ﻟﻠﻪِ ﺗَﻌَﺎﻟَﻰ
                                </p>
                                <p class="text-xs text-gray-600 italic">
                                    "Nawaitu an ukhrija zakaatal fithri 'an nafsii fardhan lillaahi ta'aalaa."
                                </p>
                                <p class="text-xs text-gray-500 mt-1.5">
                                    Artinya: <em>"Aku niat mengeluarkan zakat fitrah untuk diriku sendiri, fardu karena Allah Ta'ala."</em>
                                </p>
                            </div>

                            {{-- ── Niat Zakat Fitrah untuk Seluruh Keluarga ── --}}
                            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-3">Niat Zakat Fitrah — Seluruh Keluarga</h4>
                                <p class="text-right text-lg leading-loose text-gray-800 font-arabic mb-2">
                                    ﻧَﻮَﻳْﺖُ ﺃَﻥْ ﺃُﺧْﺮِﺝَ ﺯَﻛَﺎﺓَ ﺍﻟْﻔِﻄْﺮِ ﻋَنِّيْ ﻭَﻋَﻦْ ﺟَﻤِﻴْﻊِ ﻣَﺎ ﻳَﻠْﺰَﻣُنِيْ ﻧَﻔَﻘَﺎﺗُﻬُﻢْ ﺷَﺮْﻋًﺎ ﻓَﺮْﺿًﺎ ﻟﻠﻪِ ﺗَﻌَﺎﻟَﻰ
                                </p>
                                <p class="text-xs text-gray-600 italic">
                                    "Nawaitu an ukhrija zakaata al-fithri 'anni wa 'an jami'i ma ya'lunihi fardhan lillahi ta'ala."
                                </p>
                                <p class="text-xs text-gray-500 mt-1.5">
                                    Artinya: <em>"Aku niat mengeluarkan zakat fitrah untuk diriku dan seluruh orang yang nafkahnya menjadi tanggunganku, fardu karena Allah Ta'ala."</em>
                                </p>
                            </div>

                            {{-- ── Niat Zakat Mal — Diri Sendiri (Umum) ── --}}
                            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-3">Niat Zakat Mal — Diri Sendiri</h4>
                                <p class="text-right text-lg leading-loose text-gray-800 font-arabic mb-2">
                                    ﻧَﻮَﻳْﺖُ أَﻥْ أُﺧْﺮِﺝَ ﺯَﻛَﺎﺓَ ﺍﻟْﻤَﺎﻝِ ﻓَﺮْﺿًﺎ ﻟﻠﻪِ ﺗَﻌَﺎﻟَﻰ
                                </p>
                                <p class="text-xs text-gray-600 italic">
                                    "Nawaitu an ukhrija zakaata maali fardhan lillaahi ta'aala."
                                </p>
                                <p class="text-xs text-gray-500 mt-1.5">
                                    Artinya: <em>"Saya niat mengeluarkan zakat harta dari diri sendiri karena Allah Ta'ala."</em>
                                </p>
                            </div>

                            {{-- ── Niat Zakat Mal — Diri Sendiri & Keluarga ── --}}
                            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-3">Niat Zakat Mal — Diri Sendiri & Keluarga</h4>
                                <p class="text-right text-lg leading-loose text-gray-800 font-arabic mb-2">
                                    نَوَيْتُ أَنْ أُخْرِجَ زَكَاةَ مَالِي عَنِّيْ وَعَنْ جَمِيْعِ مَا يَلْزَمُنِيْ نَفَقَاتُهُمْ فَرْضًا لِلَّهِ تَعَالَى
                                </p>
                                <p class="text-xs text-gray-600 italic">
                                    "Nawaitu an ukhrija zakaata maali 'anni wa 'an jami'i ma ya'lunihi fardhan lillaahi ta'aala."
                                </p>
                                <p class="text-xs text-gray-500 mt-1.5">
                                    Artinya: <em>"Aku niat mengeluarkan zakat harta dari diriku dan seluruh orang yang nafkahnya menjadi tanggunganku, fardu karena Allah Ta'ala."</em>
                                </p>
                            </div>

                            {{-- Keutamaan Zakat --}}
                            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">Keutamaan Berzakat</h4>
                                <div class="space-y-2 text-xs text-primary-800">
                                    <p>• <strong>Membersihkan harta</strong> — Zakat mensucikan harta dari hal-hal yang syubhat</p>
                                    <p>• <strong>Mendapat keberkahan</strong> — Allah melipatgandakan pahala orang yang berzakat</p>
                                    <p>• <strong>Menghapus dosa</strong> — Zakat dapat menjadi kafarat (penghapus) dosa-dosa kecil</p>
                                    <p>• <strong>Menolong sesama</strong> — Membantu saudara yang membutuhkan</p>
                                </div>
                                <div class="mt-3 p-2.5 bg-white/70 rounded-lg border border-primary-200">
                                    <p class="text-xs text-primary-700 italic text-center">
                                        "Ambillah zakat dari sebagian harta mereka, dengan zakat itu kamu membersihkan dan mensucikan mereka."
                                        <br><span class="font-semibold not-italic">(QS. At-Taubah: 103)</span>
                                    </p>
                                </div>
                            </div>

                            <div class="h-4"></div>
                        </div>

                        {{-- Footer Modal --}}
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                            <div class="mb-3 flex items-start gap-2 p-3 bg-primary-50 border border-primary-200 rounded-xl">
                                <input type="checkbox" id="chkSudahBaca"
                                    class="w-4 h-4 text-primary-600 border-gray-300 rounded mt-0.5 cursor-pointer">
                                <label for="chkSudahBaca" class="text-xs text-primary-800 cursor-pointer leading-relaxed">
                                    Saya telah membaca niat zakat di atas dan berniat menunaikan kewajiban zakat dengan ikhlas karena Allah Ta'ala.
                                </label>
                            </div>
                            <button type="button" id="btnSudahBaca" disabled onclick="konfirmasiSudahBaca()"
                                class="w-full py-3 rounded-xl text-sm font-bold text-white transition-all bg-gray-300 cursor-not-allowed opacity-60">
                                Sudah Membaca — Lanjut Isi Form
                            </button>
                            <p class="text-xs text-gray-400 text-center mt-2">Centang kotak di atas untuk mengaktifkan tombol</p>
                        </div>
                    </div>
                </div>

                {{-- ===================================================
                     MODAL DOA SETELAH ZAKAT (muncul saat klik "Kirim Transaksi Zakat")
                ====================================================== --}}
                <div id="modalDoaSetelahZakat"
                    class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[10000] hidden items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden">

                        {{-- Header --}}
                        <div class="px-6 py-4 bg-primary text-white flex-shrink-0">
                            <div class="flex items-center gap-3">
                                <div>
                                    <h3 class="text-base font-bold">Doa Setelah Zakat</h3>
                                    <p class="text-xs text-primary-100">Baca doa ini setelah menunaikan zakat</p>
                                </div>
                            </div>
                        </div>

                        {{-- Konten --}}
                        <div class="overflow-y-auto flex-1 px-6 py-5 space-y-4">

                            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-3">Doa Setelah Berzakat</h4>
                                <p class="text-right text-xl leading-loose text-gray-800 font-arabic mb-3">
                                    اَللَّهُمَّ اجْعَلْهَا مَغْنَمًا وَلاَ تَجْعَلْهَا مَغْرَمًا
                                </p>
                                <p class="text-sm text-gray-600 italic text-center mb-2">
                                    "Allahummaj'alhaa maghnamaw walaa taj'alhaa maghraman."
                                </p>
                                <p class="text-xs text-gray-500 text-center">
                                    Artinya: <em>"Ya Allah, jadikanlah ini sebagai keuntungan dan jangan jadikan kerugian."</em>
                                </p>
                            </div>

                            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-3">Doa dari Amil kepada Muzakki</h4>
                                <p class="text-right text-xl leading-loose text-gray-800 font-arabic mb-3">
                                    آجَرَكَ اللهُ فِيمَا أَعْطَيْتَ وَبَارَكَ فِيمَا أَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُورًا
                                </p>
                                <p class="text-sm text-gray-600 italic text-center mb-2">
                                    "Aajaraka Allaahu fiimaa a'thayta wa baaraka fiimaa abqayta wa ja'alahu laka thahuuran."
                                </p>
                                <p class="text-xs text-gray-500 text-center">
                                    Artinya: <em>"Semoga Allah memberimu pahala atas apa yang engkau berikan, memberkahi apa yang masih tersisa, dan menjadikannya sebagai penyuci bagimu."</em>
                                </p>
                            </div>

                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800">
                                <p class="font-semibold mb-1">📌 Catatan</p>
                                <p>Transaksi zakat Anda akan segera diproses. Amil akan menghubungi Anda untuk konfirmasi pembayaran.</p>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                            <div class="mb-3 flex items-start gap-2 p-3 bg-primary-50 border border-primary-200 rounded-xl">
                                <input type="checkbox" id="chkSudahBacaDoa"
                                    class="w-4 h-4 text-primary-600 border-gray-300 rounded mt-0.5 cursor-pointer">
                                <label for="chkSudahBacaDoa" class="text-xs text-primary-800 cursor-pointer leading-relaxed">
                                    Saya telah membaca doa setelah zakat di atas dengan ikhlas karena Allah Ta'ala.
                                </label>
                            </div>
                            <button type="button" id="btnKonfirmasiDanSimpan" disabled onclick="konfirmasiDoaLaluSimpan()"
                                class="w-full py-3 rounded-xl text-sm font-bold text-white transition-all bg-gray-300 cursor-not-allowed opacity-60">
                                <span id="btnKonfirmasiText">Simpan Transaksi Zakat</span>
                            </button>
                            <p class="text-xs text-gray-400 text-center mt-2">Centang kotak di atas untuk mengaktifkan tombol simpan</p>
                        </div>
                    </div>
                </div>

                {{-- Form Daring (multistep) --}}
                <form id="formDaring" action="{{ route('transaksi-daring-muzakki.store') }}" method="POST"
                    enctype="multipart/form-data" class="p-4 sm:p-6">
                    @csrf
                    <input type="hidden" name="metode_penerimaan" value="daring">
                    <input type="hidden" name="tanggal_transaksi" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="is_pembayaran_beras" id="hdnBerasDaring" value="0">

                    {{-- Progress Steps --}}
                    <div class="mb-7">
                        <div class="flex items-center max-w-lg mx-auto">
                            <div class="flex flex-col items-center flex-1">
                                <div id="dDot1"
                                    class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold ring-4 ring-primary-500/20 bg-primary text-white">
                                    1</div>
                                <span class="text-xs mt-1 font-medium text-primary-600 text-center">Detail Zakat</span>
                            </div>
                            <div id="dLine12" class="flex-1 h-0.5 bg-gray-200 transition-colors duration-300"></div>
                            <div class="flex flex-col items-center flex-1">
                                <div id="dDot2"
                                    class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">
                                    2</div>
                                <span class="text-xs mt-1 font-medium text-gray-500 text-center">Pembayaran</span>
                            </div>
                            <div id="dLine23" class="flex-1 h-0.5 bg-gray-200 transition-colors duration-300"></div>
                            <div class="flex flex-col items-center flex-1">
                                <div id="dDot3"
                                    class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">
                                    3</div>
                                <span class="text-xs mt-1 font-medium text-gray-500 text-center">Konfirmasi</span>
                            </div>
                        </div>
                    </div>

                    {{-- ===== STEP D1: Detail Zakat ===== --}}
                    <div id="dStep1" class="dstep-panel">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold">1</span>
                            Pilih Jenis Zakat
                        </h3>

                        {{-- DATA MUZAKKI --}}
                        <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 mb-5">
                            <p class="text-xs font-bold text-primary-800 mb-2 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Data Muzakki
                            </p>
                            <p class="text-xs text-gray-500 mb-3 italic">Field yang dicetak tebal tidak dapat diubah.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                                    <input type="text" name="muzakki_nama" value="{{ $muzakkiData['nama'] }}" readonly
                                        class="w-full px-3 py-2 text-sm border border-gray-200 bg-gray-100 rounded-lg cursor-not-allowed text-gray-600 select-none">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Email</label>
                                    <input type="email" name="muzakki_email" value="{{ $muzakkiData['email'] }}" readonly
                                        class="w-full px-3 py-2 text-sm border border-gray-200 bg-gray-100 rounded-lg cursor-not-allowed text-gray-600 select-none">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Telepon / WA <span class="text-red-500">*</span></label>
                                    <input type="text" name="muzakki_telepon"
                                        value="{{ old('muzakki_telepon', $muzakkiData['telepon']) }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-500 transition-all"
                                        placeholder="Masukkan nomor telepon">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">NIK</label>
                                    <input type="text" name="muzakki_nik"
                                        value="{{ old('muzakki_nik', $muzakkiData['nik']) }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-500 transition-all"
                                        placeholder="16 digit NIK" maxlength="16">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs text-gray-500 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                                    <textarea name="muzakki_alamat" rows="2"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-500 transition-all resize-none"
                                        placeholder="Masukkan alamat lengkap">{{ old('muzakki_alamat', $muzakkiData['alamat']) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Zakat <span class="text-red-500">*</span></label>
                                    <select name="jenis_zakat_id" id="dJenisId"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach ($jenisZakatList as $jz)
                                            @php
                                                // Sembunyikan fidyah dari pilihan metode daring
                                                $isFidyah = stripos($jz->nama, 'fidyah') !== false;
                                            @endphp
                                            @if (!$isFidyah)
                                                <option value="{{ $jz->id }}" data-nama="{{ strtolower($jz->nama) }}">{{ $jz->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <svg class="w-3.5 h-3.5 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Fidyah tidak tersedia via daring. Gunakan metode <strong>Dijemput Amil</strong>.
                                    </p>
                                </div>
                                <div id="dWrapTipe" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Spesifik <span class="text-red-500">*</span></label>
                                    <select name="tipe_zakat_id" id="dTipeId"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                        <option value="">-- Pilih Tipe --</option>
                                    </select>
                                    <p id="dInfoTipeFitrah" class="mt-1.5 text-xs text-primary-700 hidden">
                                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Metode daring hanya mendukung pembayaran tunai (transfer/QRIS).
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Program Zakat <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                <select name="program_zakat_id"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    <option value="">-- Tidak memilih program tertentu --</option>
                                    @foreach ($programZakatList as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->nama_program }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Panel Fitrah Tunai --}}
                            <div id="dPanelFitrahTunai" class="hidden space-y-4">
                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-3">
                                    <p class="text-xs font-bold text-primary-800 mb-2">Zakat Fitrah — Nominal</p>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="bg-white rounded-lg border border-primary-200 p-2 text-center">
                                            <p class="font-bold text-primary-800 text-sm">{{ $zakatFitrahInfo['beras_kg'] }} kg</p>
                                            <p class="text-xs text-primary-600">per jiwa</p>
                                        </div>
                                        <div class="bg-white rounded-lg border border-primary-200 p-2 text-center">
                                            <p class="font-bold text-primary-800 text-sm">{{ $zakatFitrahInfo['beras_liter'] }} ltr</p>
                                            <p class="text-xs text-primary-600">per jiwa</p>
                                        </div>
                                        <div class="bg-white rounded-lg border border-primary-200 p-2 text-center">
                                            <p class="font-bold text-primary-700 text-sm">Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'], 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">BAZNAS</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Jiwa <span class="text-red-500">*</span></label>
                                        <input type="number" name="jumlah_jiwa" id="dJiwa" value="1" min="1" step="1"
                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nominal/Jiwa (Rp) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                            <input type="number" name="nominal_per_jiwa" id="dNominalJiwa"
                                                value="{{ $zakatFitrahInfo['nominal_per_jiwa'] }}" min="1000" step="1000"
                                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                        </div>
                                    </div>
                                </div>

                                {{-- Daftar Nama Muzakki (per jiwa) --}}
                                <div class="border border-primary-200 rounded-xl overflow-hidden">
                                    <div class="bg-primary-50 px-4 py-2.5 flex items-center justify-between">
                                        <p class="text-xs font-bold text-primary-800 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Daftar Nama per Jiwa
                                        </p>
                                        <span class="text-xs text-primary-600">Opsional, tapi disarankan</span>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        <div id="dDaftarNama">
                                            <div class="flex items-center gap-2 nama-jiwa-row" data-index="0">
                                                <div class="flex-shrink-0 w-7 h-7 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center nama-jiwa-num">1</div>
                                                <input type="text" name="nama_jiwa[]"
                                                    value="{{ $muzakkiData['nama'] }}" placeholder="Nama jiwa ke-1"
                                                    class="flex-1 px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-400 transition-all">
                                                <div class="w-8"></div>
                                            </div>
                                        </div>

                                        <button type="button" id="btnTambahNama" onclick="tambahNamaJiwa()"
                                            class="w-full py-2 border-2 border-dashed border-primary-300 rounded-lg text-xs font-medium text-primary-600 hover:bg-primary-50 hover:border-primary-400 transition-all flex items-center justify-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Tambah Nama Jiwa
                                        </button>

                                        <p class="text-xs text-gray-400 italic">Jumlah baris nama akan otomatis menyesuaikan dengan jumlah jiwa di atas.</p>
                                    </div>
                                </div>

                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-3">
                                    <p class="text-xs font-bold text-primary-700 mb-1">Total Zakat Fitrah</p>
                                    <p class="text-xl font-bold text-primary-800" id="dTotalFitrah">Rp 0</p>
                                    <input type="hidden" name="jumlah" id="dHdnJumlahFitrah" value="0">
                                </div>
                            </div>

                            {{-- Panel Mal --}}
                            <div id="dPanelMal" class="hidden space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Total Nilai Harta (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="nilai_harta" id="dHarta" min="0" step="1000"
                                            placeholder="Total harta yang wajib dizakatkan"
                                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nisab (Rp) <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                            <input type="number" name="nisab_saat_ini" min="0" step="1000"
                                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Persentase Zakat (%)</label>
                                        <input type="number" name="persentase_zakat" id="dPersen" value="2.5" min="0" max="100" step="0.1"
                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    </div>
                                </div>
                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-3">
                                    <p class="text-xs font-bold text-primary-700 mb-1">Total Zakat Mal</p>
                                    <p class="text-xl font-bold text-primary-800" id="dTotalMal">Rp 0</p>
                                    <input type="hidden" name="jumlah" id="dHdnJumlahMal" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-5 pt-4 border-t border-gray-100">
                            <button type="button" onclick="dGoStep(2)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-nz transition-all hover:bg-primary-600">
                                Selanjutnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ===== STEP D2: Pembayaran ===== --}}
                    <div id="dStep2" class="dstep-panel hidden">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold">2</span>
                            Metode Pembayaran
                        </h3>
                        <div class="space-y-5">
                            <div class="bg-primary-50 border border-primary-200 rounded-xl px-4 py-3 flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-primary-800">Pilih metode pembayaran. Bukti transfer akan dikonfirmasi oleh amil.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <select name="metode_pembayaran" id="dMetodePembayaran"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            {{-- Info Transfer --}}
                            <div id="dInfoTransfer" class="hidden space-y-3">
                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                    <p class="text-sm font-bold text-primary-800 mb-3">Rekening Transfer Masjid</p>
                                    @if ($rekeningMasjidList->isNotEmpty())
                                        @foreach ($rekeningMasjidList as $rek)
                                            <div class="bg-white border border-primary-200 rounded-lg p-3 flex items-center justify-between mb-2 last:mb-0">
                                                <div>
                                                    <p class="text-xs font-bold text-gray-800">{{ $rek->nama_bank }}</p>
                                                    <p class="text-sm font-mono font-bold text-gray-900 tracking-wider mt-0.5">{{ $rek->nomor_rekening }}</p>
                                                    <p class="text-xs text-gray-500">a.n. {{ $rek->nama_pemilik }}</p>
                                                </div>
                                                <button type="button" onclick="salin('{{ $rek->nomor_rekening }}')"
                                                    class="text-xs text-primary-600 hover:bg-primary-100 px-2.5 py-1.5 rounded-lg transition-all font-semibold">Salin</button>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-xs text-gray-500 italic">Belum ada rekening aktif. Hubungi pengurus masjid.</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Screenshot Bukti Pembayaran <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                    <div id="dPrvTransfer"
                                        class="h-28 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden cursor-pointer hover:border-primary-400 transition-all"
                                        onclick="document.getElementById('dInpTransfer').click()">
                                        <div class="text-center">
                                            <svg class="w-7 h-7 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-xs text-gray-400">Klik untuk upload</p>
                                        </div>
                                    </div>
                                    <input type="file" name="bukti_transfer" id="dInpTransfer" accept="image/*" class="hidden" onchange="prvBuktiD(this,'dPrvTransfer')">
                                </div>
                            </div>

                            {{-- Info QRIS — DIPERBESAR --}}
                            <div id="dInfoQris" class="hidden space-y-3">
                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                    <p class="text-sm font-bold text-primary-800 mb-4">QRIS Masjid</p>
                                    @if ($konfigurasiQris && !empty($konfigurasiQris->qris_image_path))
                                        <div class="flex justify-center">
                                            {{-- PERUBAHAN: Diperbesar dari w-52 menjadi w-72 sm:w-80, padding lebih besar --}}
                                            <div class="bg-white p-4 rounded-2xl border-2 border-primary-300 shadow-md w-72 sm:w-80">
                                                <img src="{{ $konfigurasiQris->qris_image_url }}" class="w-full object-contain rounded-lg" alt="QRIS Masjid">
                                                <p class="text-center text-xs text-primary-700 font-semibold mt-3">Scan untuk Membayar Zakat</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-white border border-primary-200 rounded-lg p-4 text-center">
                                            <p class="text-xs text-gray-500">Hubungi pengurus masjid untuk mendapatkan kode QRIS.</p>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Screenshot Bukti Pembayaran <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                    <div id="dPrvQris"
                                        class="h-28 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden cursor-pointer hover:border-primary-400 transition-all"
                                        onclick="document.getElementById('dInpQris').click()">
                                        <div class="text-center">
                                            <svg class="w-7 h-7 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-xs text-gray-400">Klik untuk upload</p>
                                        </div>
                                    </div>
                                    <input type="file" name="bukti_qris" id="dInpQris" accept="image/*" class="hidden" onchange="prvBuktiD(this,'dPrvQris')">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Dibayar (Rp) <span class="text-xs text-gray-400">(opsional — lebih = infaq)</span></label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                    <input type="number" name="jumlah_dibayar" id="dJmlDibayar" min="0" step="1000"
                                        placeholder="Kosongkan = bayar pas sesuai zakat"
                                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Bayar lebih → kelebihan dicatat sebagai <strong>infaq</strong></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                <textarea name="keterangan" rows="2" placeholder="Untuk program tertentu, atas nama keluarga, dll."
                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all resize-none"></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                            <button type="button" onclick="dGoStep(1)"
                                class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </button>
                            <button type="button" onclick="dGoStep(3)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-nz transition-all hover:bg-primary-600">
                                Selanjutnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ===== STEP D3: Konfirmasi & Simpan ===== --}}
                    <div id="dStep3" class="dstep-panel hidden">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold">3</span>
                            Konfirmasi Transaksi
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <p class="text-xs font-bold text-primary-800 uppercase tracking-wide mb-3">Ringkasan Pembayaran</p>
                                <table class="w-full text-sm">
                                    <tr class="border-b border-primary-100">
                                        <td class="text-gray-500 py-1.5 w-1/2">Jenis Zakat</td>
                                        <td class="font-bold text-gray-900" id="dRingJenis">-</td>
                                    </tr>
                                    <tr class="border-b border-primary-100">
                                        <td class="text-gray-500 py-1.5">Jumlah Zakat</td>
                                        <td class="font-bold text-primary-700" id="dRingJumlah">-</td>
                                    </tr>
                                    <tr class="border-b border-primary-100">
                                        <td class="text-gray-500 py-1.5">Metode Bayar</td>
                                        <td class="font-bold text-gray-900" id="dRingMetode">-</td>
                                    </tr>
                                    <tr class="border-b border-primary-100" id="dRingJiwaRow">
                                        <td class="text-gray-500 py-1.5">Jumlah Jiwa</td>
                                        <td class="font-bold text-gray-900" id="dRingJiwa">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-500 py-1.5">Status</td>
                                        <td class="font-bold text-primary-600">Menunggu konfirmasi amil</td>
                                    </tr>
                                </table>
                            </div>

                            <div id="dRingNamaWrap" class="hidden bg-primary-50 border border-primary-200 rounded-xl p-4">
                                <p class="text-xs font-bold text-primary-800 mb-2">Daftar Nama Jiwa</p>
                                <ul id="dRingNamaList" class="space-y-1 text-sm text-gray-700"></ul>
                            </div>

                            <div class="bg-primary-50 border border-primary-200 rounded-xl px-4 py-3 flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <p class="text-xs text-primary-800">Amil akan mendapat notifikasi dan segera memverifikasi pembayaran Anda.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                            <button type="button" onclick="dGoStep(2)"
                                class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </button>
                            {{-- PERUBAHAN: Tombol ini sekarang membuka modal doa dulu --}}
                            <button type="button" id="btnSimpanDaring" onclick="bukaTampilDoaSetelahZakat()"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-nz hover:shadow-nz-lg hover:bg-primary-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Kirim Transaksi Zakat
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ══════════════════════════════════════════════════════════
         PANEL DIJEMPUT
    ══════════════════════════════════════════════════════════ --}}
            <div id="panelDijemput" class="hidden">
                <form id="formDijemput" action="{{ route('transaksi-daring-muzakki.store') }}" method="POST"
                    enctype="multipart/form-data" class="p-4 sm:p-6">
                    @csrf
                    <input type="hidden" name="metode_penerimaan" value="dijemput">
                    <input type="hidden" name="tanggal_transaksi" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="latitude" id="djLat" value="">
                    <input type="hidden" name="longitude" id="djLng" value="">

                    <div class="mb-5 bg-primary-50 border border-primary-200 rounded-xl px-4 py-3 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-primary-800"><strong>Metode Dijemput:</strong> Amil akan datang ke lokasi Anda untuk mengambil zakat. Detail jenis dan jumlah zakat akan dilengkapi oleh amil saat menjemput.</p>
                    </div>

                    <div class="space-y-5">
                        {{-- Data diri --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Data Diri
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span> <span class="text-xs font-bold text-gray-600">(readonly)</span></label>
                                    <input type="text" name="muzakki_nama" value="{{ $muzakkiData['nama'] }}" readonly
                                        class="w-full px-4 py-2.5 text-sm border border-gray-200 bg-gray-100 rounded-xl cursor-not-allowed text-gray-600">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Email <span class="text-xs font-bold text-gray-600">(readonly)</span></label>
                                    <input type="email" name="muzakki_email" value="{{ $muzakkiData['email'] }}" readonly
                                        class="w-full px-4 py-2.5 text-sm border border-gray-200 bg-gray-100 rounded-xl cursor-not-allowed text-gray-600">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Telepon / WA <span class="text-red-500">*</span></label>
                                    <input type="text" name="muzakki_telepon"
                                        value="{{ old('muzakki_telepon', $muzakkiData['telepon']) }}"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-400 transition-all"
                                        placeholder="Masukkan nomor telepon">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">NIK</label>
                                    <input type="text" name="muzakki_nik"
                                        value="{{ old('muzakki_nik', $muzakkiData['nik']) }}"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-400 transition-all"
                                        placeholder="16 digit NIK" maxlength="16">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Alamat Lengkap Penjemputan <span class="text-red-500">*</span></label>
                                    <textarea name="muzakki_alamat" id="djAlamat" rows="2"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-400 transition-all resize-none"
                                        placeholder="Masukkan alamat lengkap untuk penjemputan">{{ old('muzakki_alamat', $muzakkiData['alamat']) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Pilih Amil --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Pilih Amil Penjemput
                            </h4>
                            @if ($amilList->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($amilList as $amil)
                                        <label class="amil-card flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all border-gray-200 hover:border-primary-400 hover:bg-primary-50/50">
                                            <input type="radio" name="amil_id" value="{{ $amil->id }}"
                                                class="w-4 h-4 text-primary-500 border-gray-300 amil-radio">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900">{{ $amil->pengguna->username ?? 'Amil' }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $amil->pengguna->email ?? '' }}</p>
                                            </div>
                                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-bold text-primary-700">{{ strtoupper(substr($amil->pengguna->username ?? 'A', 0, 1)) }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 text-center">
                                    <p class="text-sm text-primary-800">Belum ada amil aktif. Silakan pilih metode daring.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Lokasi GPS --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lokasi Penjemputan
                            </h4>
                            <button type="button" onclick="getGPS()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-100 hover:bg-primary-200 text-primary-800 text-sm font-medium rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Dapatkan Lokasi GPS Saya
                            </button>
                            <div id="gpsStatus" class="mt-2 text-xs text-gray-500"></div>
                            <div id="gpsResult" class="hidden mt-2 p-3 bg-primary-50 border border-primary-200 rounded-xl">
                                <p class="text-xs font-medium text-primary-800">Lokasi berhasil didapatkan</p>
                                <p class="text-xs text-primary-700 mt-0.5" id="gpsCoord"></p>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                            <textarea name="keterangan" rows="2" placeholder="Patokan lokasi, waktu yang tersedia, dll."
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-400 transition-all resize-none"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <button type="button" onclick="kembaliPilihMetode()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </button>
                        <button type="submit" id="btnSimpanDijemput"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-nz hover:shadow-nz-lg hover:bg-primary-600 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Request Penjemputan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const BAZNAS = {
            nominalPerJiwa: {{ $zakatFitrahInfo['nominal_per_jiwa'] }},
            berasKg: {{ $zakatFitrahInfo['beras_kg'] }},
            berasLiter: {{ $zakatFitrahInfo['beras_liter'] }}
        };
        const TIPE_DATA = @json($tipeZakatList ?? []);
        let dActiveStep = 1;
        let dActivePanelZ = null;
        let sudahBacaDoa = false;

        function fmt(n) {
            return new Intl.NumberFormat('id-ID').format(Math.round(n || 0));
        }

        function pilihMetode(metode) {
            document.getElementById('panelPilihMetode').classList.add('hidden');
            if (metode === 'daring') {
                document.getElementById('panelDaring').classList.remove('hidden');
                // Tampilkan modal niat doa
                const modal = document.getElementById('modalNiatDoa');
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            } else {
                document.getElementById('panelDijemput').classList.remove('hidden');
            }
        }

        function kembaliPilihMetode() {
            document.getElementById('panelDaring').classList.add('hidden');
            document.getElementById('panelDijemput').classList.add('hidden');
            document.getElementById('panelPilihMetode').classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // ── Checkbox modal niat doa ──
            const chk = document.getElementById('chkSudahBaca');
            const btn = document.getElementById('btnSudahBaca');
            if (chk && btn) {
                chk.addEventListener('change', function() {
                    if (this.checked) {
                        btn.disabled = false;
                        btn.classList.remove('bg-gray-300', 'cursor-not-allowed', 'opacity-60');
                        btn.classList.add('bg-primary', 'hover:bg-primary-600', 'cursor-pointer');
                    } else {
                        btn.disabled = true;
                        btn.classList.add('bg-gray-300', 'cursor-not-allowed', 'opacity-60');
                        btn.classList.remove('bg-primary', 'hover:bg-primary-600', 'cursor-pointer');
                    }
                });
            }

            // ── Checkbox modal doa setelah zakat ──
            const chkDoa = document.getElementById('chkSudahBacaDoa');
            const btnDoa = document.getElementById('btnKonfirmasiDanSimpan');
            if (chkDoa && btnDoa) {
                chkDoa.addEventListener('change', function() {
                    if (this.checked) {
                        btnDoa.disabled = false;
                        btnDoa.classList.remove('bg-gray-300', 'cursor-not-allowed', 'opacity-60');
                        btnDoa.classList.add('bg-primary', 'hover:bg-primary-600', 'cursor-pointer');
                    } else {
                        btnDoa.disabled = true;
                        btnDoa.classList.add('bg-gray-300', 'cursor-not-allowed', 'opacity-60');
                        btnDoa.classList.remove('bg-primary', 'hover:bg-primary-600', 'cursor-pointer');
                    }
                });
            }

            document.getElementById('dJenisId').addEventListener('change', function() {
                const jenisId = this.value;
                const jenisNama = (this.options[this.selectedIndex]?.dataset.nama || '').toLowerCase();
                const tipeEl = document.getElementById('dTipeId');
                const wrapTipe = document.getElementById('dWrapTipe');
                const infoFitrah = document.getElementById('dInfoTipeFitrah');

                tipeEl.innerHTML = '<option value="">-- Pilih Tipe --</option>';
                dResetPanelZakat();
                infoFitrah.classList.add('hidden');

                if (!jenisId) { wrapTipe.classList.add('hidden'); return; }

                const list = TIPE_DATA[jenisId] || [];
                if (list.length > 0) {
                    const isFitrah = jenisNama.includes('fitrah');
                    if (isFitrah) {
                        const tunaiList = list.filter(t => t.nama.toLowerCase().includes('tunai'));
                        const displayList = tunaiList.length > 0 ? tunaiList : list;
                        displayList.forEach(t => {
                            const o = new Option(t.nama, t.uuid);
                            o.dataset.nama = t.nama.toLowerCase();
                            o.dataset.persentase = t.persentase_zakat || 2.5;
                            tipeEl.appendChild(o);
                        });
                        infoFitrah.classList.remove('hidden');
                    } else {
                        list.forEach(t => {
                            const o = new Option(t.nama, t.uuid);
                            o.dataset.nama = t.nama.toLowerCase();
                            o.dataset.persentase = t.persentase_zakat || 2.5;
                            tipeEl.appendChild(o);
                        });
                    }
                    wrapTipe.classList.remove('hidden');
                } else {
                    wrapTipe.classList.add('hidden');
                }
            });

            document.getElementById('dTipeId').addEventListener('change', function() {
                const jenisEl = document.getElementById('dJenisId');
                const namaJenis = (jenisEl.options[jenisEl.selectedIndex]?.dataset.nama || '').toLowerCase();
                dResetPanelZakat();
                if (!this.value) return;
                if (namaJenis.includes('fitrah')) dTampilFitrah();
                else if (namaJenis.includes('mal')) dTampilMal(this.options[this.selectedIndex]);
            });

            document.getElementById('dJiwa')?.addEventListener('input', function() {
                hitungFitrahD(); sinkronisasiNamaJiwa();
            });
            document.getElementById('dNominalJiwa')?.addEventListener('input', hitungFitrahD);
            document.getElementById('dHarta')?.addEventListener('input', hitungMalD);
            document.getElementById('dPersen')?.addEventListener('input', hitungMalD);

            document.getElementById('dMetodePembayaran')?.addEventListener('change', function() {
                document.getElementById('dInfoTransfer').classList.add('hidden');
                document.getElementById('dInfoQris').classList.add('hidden');
                if (this.value === 'transfer') document.getElementById('dInfoTransfer').classList.remove('hidden');
                if (this.value === 'qris') document.getElementById('dInfoQris').classList.remove('hidden');
            });
        });

        function konfirmasiSudahBaca() {
            sudahBacaDoa = true;
            const modal = document.getElementById('modalNiatDoa');
            modal.style.display = 'none';
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // ── Buka modal doa setelah zakat saat klik "Kirim Transaksi Zakat" ──
        function bukaTampilDoaSetelahZakat() {
            // Validasi ulang sebelum membuka modal
            const telepon = document.querySelector('#formDaring [name="muzakki_telepon"]').value.trim();
            const alamat = document.querySelector('#formDaring [name="muzakki_alamat"]').value.trim();
            if (!telepon) { alert('Nomor telepon wajib diisi.'); dGoStep(1); return; }
            if (!alamat) { alert('Alamat wajib diisi.'); dGoStep(1); return; }
            if (!document.getElementById('dJenisId').value) { alert('Pilih jenis zakat.'); dGoStep(1); return; }
            if (!document.getElementById('dTipeId').value) { alert('Pilih tipe zakat.'); dGoStep(1); return; }
            if (getJumlahZakatD() <= 0) { alert('Jumlah zakat tidak valid.'); dGoStep(1); return; }
            if (!document.getElementById('dMetodePembayaran').value) { alert('Pilih metode pembayaran.'); dGoStep(2); return; }

            // Reset checkbox dan tombol modal doa
            const chkDoa = document.getElementById('chkSudahBacaDoa');
            const btnDoa = document.getElementById('btnKonfirmasiDanSimpan');
            chkDoa.checked = false;
            btnDoa.disabled = true;
            btnDoa.classList.add('bg-gray-300', 'cursor-not-allowed', 'opacity-60');
            btnDoa.classList.remove('bg-primary', 'hover:bg-primary-600', 'cursor-pointer');

            // Tampilkan modal
            const modal = document.getElementById('modalDoaSetelahZakat');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        // ── Submit form setelah baca doa setelah zakat ──
        function konfirmasiDoaLaluSimpan() {
            const modal = document.getElementById('modalDoaSetelahZakat');
            modal.style.display = 'none';
            modal.classList.add('hidden');
            document.body.style.overflow = '';

            // Update tombol dan submit
            const btn = document.getElementById('btnKonfirmasiDanSimpan');
            btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...';

            document.getElementById('formDaring').submit();
        }

        function dResetPanelZakat() {
            document.getElementById('dPanelFitrahTunai').classList.add('hidden');
            document.getElementById('dPanelMal').classList.add('hidden');
            dActivePanelZ = null;
        }

        function dTampilFitrah() {
            dActivePanelZ = 'fitrah';
            document.getElementById('dPanelFitrahTunai').classList.remove('hidden');
            hitungFitrahD(); sinkronisasiNamaJiwa();
        }

        function dTampilMal(tipeOpt) {
            dActivePanelZ = 'mal';
            document.getElementById('dPanelMal').classList.remove('hidden');
            document.getElementById('dPersen').value = tipeOpt.dataset.persentase || 2.5;
            hitungMalD();
        }

        function hitungFitrahD() {
            const jiwa = parseFloat(document.getElementById('dJiwa').value) || 0;
            const nom = parseFloat(document.getElementById('dNominalJiwa').value) || 0;
            const total = jiwa * nom;
            document.getElementById('dTotalFitrah').textContent = 'Rp ' + fmt(total);
            document.getElementById('dHdnJumlahFitrah').value = Math.round(total);
        }

        function hitungMalD() {
            const h = parseFloat(document.getElementById('dHarta').value) || 0;
            const p = parseFloat(document.getElementById('dPersen').value) || 2.5;
            const t = h * (p / 100);
            document.getElementById('dTotalMal').textContent = 'Rp ' + fmt(t);
            document.getElementById('dHdnJumlahMal').value = Math.round(t);
        }

        function getJumlahZakatD() {
            if (dActivePanelZ === 'fitrah') return parseFloat(document.getElementById('dHdnJumlahFitrah').value) || 0;
            if (dActivePanelZ === 'mal') return parseFloat(document.getElementById('dHdnJumlahMal').value) || 0;
            return 0;
        }

        function tambahNamaJiwa() {
            const container = document.getElementById('dDaftarNama');
            const rows = container.querySelectorAll('.nama-jiwa-row');
            const jumlahJiwa = parseInt(document.getElementById('dJiwa').value) || 1;
            if (rows.length >= jumlahJiwa) {
                document.getElementById('dJiwa').value = rows.length + 1;
                hitungFitrahD();
            }
            const idx = rows.length;
            const newRow = document.createElement('div');
            newRow.className = 'flex items-center gap-2 nama-jiwa-row';
            newRow.dataset.index = idx;
            newRow.innerHTML = `
                <div class="flex-shrink-0 w-7 h-7 rounded-full bg-primary-100 border border-primary-300 text-primary-700 text-xs font-bold flex items-center justify-center nama-jiwa-num">${idx + 1}</div>
                <input type="text" name="nama_jiwa[]" placeholder="Nama jiwa ke-${idx + 1}"
                    class="flex-1 px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-400 transition-all">
                <button type="button" onclick="hapusNamaJiwa(this)"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-all flex-shrink-0" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>`;
            container.appendChild(newRow);
            const totalRows = container.querySelectorAll('.nama-jiwa-row').length;
            if (parseInt(document.getElementById('dJiwa').value) < totalRows) {
                document.getElementById('dJiwa').value = totalRows;
                hitungFitrahD();
            }
        }

        function hapusNamaJiwa(btn) {
            const row = btn.closest('.nama-jiwa-row');
            const container = document.getElementById('dDaftarNama');
            const rows = container.querySelectorAll('.nama-jiwa-row');
            if (rows.length <= 1) { alert('Minimal harus ada 1 jiwa.'); return; }
            row.remove();
            const remaining = container.querySelectorAll('.nama-jiwa-row');
            remaining.forEach((r, i) => {
                r.dataset.index = i;
                const numEl = r.querySelector('.nama-jiwa-num');
                if (numEl) numEl.textContent = i + 1;
                const inp = r.querySelector('input[name="nama_jiwa[]"]');
                if (inp && !inp.value) inp.placeholder = `Nama jiwa ke-${i + 1}`;
            });
            document.getElementById('dJiwa').value = remaining.length;
            hitungFitrahD();
        }

        function sinkronisasiNamaJiwa() {
            const jumlahJiwa = parseInt(document.getElementById('dJiwa').value) || 1;
            const container = document.getElementById('dDaftarNama');
            const rows = container.querySelectorAll('.nama-jiwa-row');
            const current = rows.length;
            if (jumlahJiwa > current) {
                for (let i = current; i < jumlahJiwa; i++) {
                    const newRow = document.createElement('div');
                    newRow.className = 'flex items-center gap-2 nama-jiwa-row';
                    newRow.dataset.index = i;
                    newRow.innerHTML = `
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-primary-100 border border-primary-300 text-primary-700 text-xs font-bold flex items-center justify-center nama-jiwa-num">${i + 1}</div>
                        <input type="text" name="nama_jiwa[]" placeholder="Nama jiwa ke-${i + 1}"
                            class="flex-1 px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-400 transition-all">
                        <button type="button" onclick="hapusNamaJiwa(this)"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-all flex-shrink-0" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>`;
                    container.appendChild(newRow);
                }
            } else if (jumlahJiwa < current) {
                const allRows = container.querySelectorAll('.nama-jiwa-row');
                for (let i = current - 1; i >= jumlahJiwa; i--) {
                    if (i > 0) allRows[i].remove();
                }
            }
        }

        function dGoStep(n) {
            if (n > dActiveStep) {
                if (!dValidateStep(dActiveStep)) return;
            }
            document.querySelectorAll('.dstep-panel').forEach(p => p.classList.add('hidden'));
            document.getElementById('dStep' + n).classList.remove('hidden');
            dActiveStep = n;
            dRefreshDots(n);
            if (n === 3) dUpdateRingkasan();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function dRefreshDots(active) {
            [1, 2, 3].forEach(i => {
                const d = document.getElementById('dDot' + i);
                if (!d) return;
                d.className = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold';
                if (i < active) {
                    d.classList.add('bg-primary-500', 'text-white');
                    d.textContent = '✓';
                } else if (i === active) {
                    d.classList.add('bg-primary', 'text-white', 'ring-4', 'ring-primary-500/20');
                    d.textContent = i;
                } else {
                    d.classList.add('bg-gray-200', 'text-gray-500');
                    d.textContent = i;
                }
                const ln = document.getElementById(i === 1 ? 'dLine12' : 'dLine23');
                if (ln) {
                    ln.classList.toggle('bg-primary-500', i < active);
                    ln.classList.toggle('bg-gray-200', i >= active);
                }
            });
        }

        function dValidateStep(step) {
            if (step === 1) {
                if (!document.getElementById('dJenisId').value) { alert('Pilih jenis zakat terlebih dahulu.'); return false; }
                if (!document.getElementById('dTipeId').value) { alert('Pilih tipe zakat terlebih dahulu.'); return false; }
                if (getJumlahZakatD() <= 0) { alert('Jumlah zakat tidak valid. Periksa kembali data yang diisi.'); return false; }
                return true;
            }
            if (step === 2) {
                if (!document.getElementById('dMetodePembayaran').value) { alert('Pilih metode pembayaran (Transfer atau QRIS).'); return false; }
                return true;
            }
            return true;
        }

        function dUpdateRingkasan() {
            const jenisEl = document.getElementById('dJenisId');
            const jenis = jenisEl.options[jenisEl.selectedIndex]?.text || '-';
            const jumlah = getJumlahZakatD();
            const metode = document.getElementById('dMetodePembayaran').value || '-';
            const jiwa = document.getElementById('dJiwa')?.value || '-';

            document.getElementById('dRingJenis').textContent = jenis;
            document.getElementById('dRingJumlah').textContent = 'Rp ' + fmt(jumlah);
            document.getElementById('dRingMetode').textContent = metode === 'transfer' ? 'Transfer Bank' : (metode === 'qris' ? 'QRIS' : '-');

            const jiwaRow = document.getElementById('dRingJiwaRow');
            if (dActivePanelZ === 'fitrah') {
                document.getElementById('dRingJiwa').textContent = jiwa + ' jiwa';
                jiwaRow.style.display = '';
            } else {
                jiwaRow.style.display = 'none';
            }

            const namaWrap = document.getElementById('dRingNamaWrap');
            const namaList = document.getElementById('dRingNamaList');
            if (dActivePanelZ === 'fitrah') {
                const namaInputs = document.querySelectorAll('#dDaftarNama input[name="nama_jiwa[]"]');
                const namaAda = Array.from(namaInputs).filter(i => i.value.trim());
                if (namaAda.length > 0) {
                    namaList.innerHTML = namaAda.map((inp, idx) =>
                        `<li class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-primary-200 text-primary-800 text-xs font-bold flex items-center justify-center flex-shrink-0">${idx+1}</span>${inp.value.trim()}</li>`
                    ).join('');
                    namaWrap.classList.remove('hidden');
                } else {
                    namaWrap.classList.add('hidden');
                }
            } else {
                namaWrap.classList.add('hidden');
            }
        }

        function getGPS() {
            const status = document.getElementById('gpsStatus');
            const result = document.getElementById('gpsResult');
            status.textContent = 'Mendapatkan lokasi...';
            if (!navigator.geolocation) { status.textContent = 'Browser tidak mendukung GPS.'; return; }
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    document.getElementById('djLat').value = lat;
                    document.getElementById('djLng').value = lng;
                    status.textContent = '';
                    result.classList.remove('hidden');
                    document.getElementById('gpsCoord').textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                },
                err => { status.textContent = 'Gagal: ' + err.message; }
            );
        }

        document.getElementById('formDijemput')?.addEventListener('submit', function(e) {
            const nama = this.querySelector('[name="muzakki_nama"]').value.trim();
            const telepon = this.querySelector('[name="muzakki_telepon"]').value.trim();
            const alamat = this.querySelector('[name="muzakki_alamat"]').value.trim();
            const lat = document.getElementById('djLat').value;
            const amil = document.querySelector('.amil-radio:checked');
            if (!nama) { e.preventDefault(); alert('Nama wajib diisi.'); return; }
            if (!telepon) { e.preventDefault(); alert('Nomor telepon wajib diisi.'); return; }
            if (!alamat) { e.preventDefault(); alert('Alamat penjemputan wajib diisi.'); return; }
            if (!lat) { e.preventDefault(); alert('Lokasi GPS wajib dideteksi. Klik tombol "Dapatkan Lokasi GPS Saya".'); return; }
            if (!amil) { e.preventDefault(); alert('Pilih amil penjemput terlebih dahulu.'); return; }
            const btn = document.getElementById('btnSimpanDijemput');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...';
        });

        function prvBuktiD(input, previewId) {
            const el = document.getElementById(previewId);
            if (input.files?.[0]) {
                if (input.files[0].size > 2097152) { alert('Ukuran file maks 2MB.'); input.value = ''; return; }
                const r = new FileReader();
                r.onload = e => { el.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-contain">`; };
                r.readAsDataURL(input.files[0]);
            }
        }

        function salin(teks) {
            navigator.clipboard.writeText(teks).then(() => {
                const el = document.createElement('div');
                el.textContent = teks + ' disalin!';
                el.className = 'fixed bottom-5 right-5 bg-gray-900 text-white text-xs px-4 py-2.5 rounded-xl shadow-xl z-50';
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 2000);
            });
        }
    </script>
@endpush