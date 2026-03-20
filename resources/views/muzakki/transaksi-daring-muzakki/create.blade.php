@extends('layouts.app')

@section('title', 'Bayar Zakat')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 bg-white-50 border-b border-black-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-100 border border-primary-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">Bayar Zakat</h2>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $lembaga->nama }}</p>
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

            {{-- STEP 0: Pilih Metode --}}
            <div id="panelPilihMetode" class="p-4 sm:p-6">
                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293-1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
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
                    <button type="button" onclick="pilihMetode('daring')"
                        class="metode-card group flex flex-col items-center gap-3 p-6 rounded-2xl border-2 border-gray-200 hover:border-primary-400 hover:bg-primary-50/50 cursor-pointer transition-all text-center">
                        <div class="w-16 h-16 rounded-2xl bg-primary-100 group-hover:bg-primary-200 flex items-center justify-center transition-colors">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-900">Daring (Online)</p>
                            <p class="text-xs text-gray-500 mt-1">Bayar via Transfer Bank atau QRIS. Bukti dikirim ke amil untuk dikonfirmasi.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700">Direkomendasikan</span>
                    </button>
                    <button type="button" onclick="pilihMetode('dijemput')"
                        class="metode-card group flex flex-col items-center gap-3 p-6 rounded-2xl border-2 border-gray-200 hover:border-primary-400 hover:bg-primary-50/50 cursor-pointer transition-all text-center">
                        <div class="w-16 h-16 rounded-2xl bg-primary-100 group-hover:bg-primary-200 flex items-center justify-center transition-colors">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
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

            {{-- PANEL DARING --}}
            <div id="panelDaring" class="hidden">
                {{-- MODAL NIAT DOA --}}
                <div id="modalNiatDoa" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[10000] flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
                        <div class="px-6 py-4 bg-primary text-white flex-shrink-0 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"><span class="text-xl">&#9770;</span></div>
                                <div><h3 class="text-base font-bold">Niat Zakat</h3><p class="text-xs text-primary-100">Baca dengan khusyuk sebelum membayar</p></div>
                            </div>
                            <button type="button" onclick="tutupModalNiat()" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all ml-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="overflow-y-auto flex-1 px-6 py-5 space-y-6">
                            <div class="text-center py-2">
                                <p class="text-right text-xl leading-loose text-gray-800 font-arabic mb-3 px-2">خُذْ مِنْ أَمْوَالِهِمْ صَدَقَةً تُطَهِّرُهُمْ وَتُزَكِّيهِم بِهَا وَصَلِّ عَلَيْهِمْ ۖ إِنَّ صَلَاتَكَ سَكَنٌ لَّهُمْ ۗ وَاللَّهُ سَمِيعٌ عَلِيمٌ</p>
                                <p class="text-xs text-gray-500 italic leading-relaxed">"Ambillah zakat dari sebagian harta mereka, dengan zakat itu kamu membersihkan dan mensucikan mereka." </p>
                                <p class="text-xs font-semibold text-primary-600 mt-1.5">(QS. At-Taubah: 103)</p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wider mb-3">Niat Zakat Fitrah — Diri Sendiri</h4>
                                <p class="text-right text-lg leading-loose text-gray-800 font-arabic mb-2">ﻧَﻮَﻳْﺖُ أَﻥْ أُﺧْﺮِﺝَ ﺯَﻛَﺎﺓَ ﺍﻟْﻔِﻄْﺮِ ﻋَﻦْ ﻧَﻔْسيْ ﻓَﺮْﺿًﺎ ﻟﻠﻪِ ﺗَﻌَﺎﻟَﻰ</p>
                                <p class="text-xs text-gray-500 italic">"Nawaitu an ukhrija zakaatal fithri 'an nafsii fardhan lillaahi ta'aalaa."</p>
                                <p class="text-xs text-gray-400 mt-1">Artinya: <em>"Aku niat mengeluarkan zakat fitrah untuk diriku sendiri, fardu karena Allah Ta'ala."</em></p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wider mb-3">Niat Zakat Fitrah — Seluruh Keluarga</h4>
                                <p class="text-right text-lg leading-loose text-gray-800 font-arabic mb-2">ﻧَﻮَﻳْﺖُ ﺃَﻥْ ﺃُﺧْﺮِﺝَ ﺯَﻛَﺎﺓَ ﺍﻟْﻔِﻄْﺮِ ﻋَنِّيْ ﻭَﻋَﻦْ ﺟَﻤِﻴْﻊِ ﻣَﺎ ﻳَﻠْﺰَﻣُنِيْ ﻧَﻔَﻘَﺎﺗُﻬُﻢْ ﺷَﺮْﻋًﺎ ﻓَﺮْﺿًﺎ ﻟﻠﻪِ ﺗَﻌَﺎﻟَﻰ</p>
                                <p class="text-xs text-gray-500 italic">"Nawaitu an ukhrija zakaata al-fithri 'anni wa 'an jami'i ma ya'lunihi fardhan lillahi ta'ala."</p>
                                <p class="text-xs text-gray-400 mt-1">Artinya: <em>"Aku niat mengeluarkan zakat fitrah untuk diriku dan seluruh yang nafkahnya menjadi tanggunganku."</em></p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wider mb-3">Niat Zakat Mal — Diri Sendiri</h4>
                                <p class="text-right text-lg leading-loose text-gray-800 font-arabic mb-2">ﻧَﻮَﻳْﺖُ أَﻥْ أُﺧْﺮِﺝَ ﺯَﻛَﺎﺓَ ﺍﻟْﻤَﺎﻝِ ﻓَﺮْﺿًﺎ ﻟﻠﻪِ ﺗَﻌَﺎﻟَﻰ</p>
                                <p class="text-xs text-gray-500 italic">"Nawaitu an ukhrija zakaata maali fardhan lillaahi ta'aala."</p>
                                <p class="text-xs text-gray-400 mt-1">Artinya: <em>"Saya niat mengeluarkan zakat harta dari diri sendiri karena Allah Ta'ala."</em></p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wider mb-3">Niat Zakat Mal — Diri Sendiri &amp; Keluarga</h4>
                                <p class="text-right text-lg leading-loose text-gray-800 font-arabic mb-2">نَوَيْتُ أَنْ أُخْرِجَ زَكَاةَ مَالِي عَنِّيْ وَعَنْ جَمِيْعِ مَا يَلْزَمُنِيْ نَفَقَاتُهُمْ فَرْضًا لِلَّهِ تَعَالَى</p>
                                <p class="text-xs text-gray-500 italic">"Nawaitu an ukhrija zakaata maali 'anni wa 'an jami'i ma ya'lunihi fardhan lillaahi ta'aala."</p>
                                <p class="text-xs text-gray-400 mt-1">Artinya: <em>"Aku niat mengeluarkan zakat harta dari diriku dan seluruh yang nafkahnya menjadi tanggunganku."</em></p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wider mb-2">Keutamaan Berzakat</h4>
                                <div class="space-y-1.5 text-xs text-gray-600">
                                    <p>&#8226; <strong>Membersihkan harta</strong> — Zakat mensucikan harta dari hal-hal yang syubhat</p>
                                    <p>&#8226; <strong>Mendapat keberkahan</strong> — Allah melipatgandakan pahala orang yang berzakat</p>
                                    <p>&#8226; <strong>Menghapus dosa</strong> — Zakat dapat menjadi kafarat dosa-dosa kecil</p>
                                    <p>&#8226; <strong>Menolong sesama</strong> — Membantu saudara yang membutuhkan</p>
                                </div>
                            </div>
                            <div class="h-2"></div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex-shrink-0">
                            <div class="mb-3 flex items-start gap-2.5">
                                <input type="checkbox" id="chkSudahBaca" class="w-4 h-4 text-primary-600 border-gray-300 rounded mt-0.5 cursor-pointer">
                                <label for="chkSudahBaca" class="text-xs text-gray-700 cursor-pointer leading-relaxed">Saya telah membaca niat zakat di atas dan berniat menunaikan kewajiban zakat dengan ikhlas karena Allah Ta'ala.</label>
                            </div>
                            <button type="button" id="btnSudahBaca" disabled onclick="konfirmasiSudahBaca()" class="w-full py-3 rounded-xl text-sm font-bold text-white transition-all bg-gray-300 cursor-not-allowed opacity-60">Sudah Membaca — Lanjut Isi Form</button>
                            <p class="text-xs text-gray-400 text-center mt-2">Centang kotak di atas untuk mengaktifkan tombol</p>
                        </div>
                    </div>
                </div>

                {{-- MODAL DOA SETELAH ZAKAT --}}
                <div id="modalDoaSetelahZakat" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[10000] hidden items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden">
                        <div class="px-6 py-4 bg-primary text-white flex-shrink-0 flex items-center justify-between">
                            <div><h3 class="text-base font-bold">Doa Setelah Zakat</h3><p class="text-xs text-primary-100">Baca doa ini setelah menunaikan zakat</p></div>
                            <button type="button" onclick="tutupModalDoaSetelah()" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all ml-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="overflow-y-auto flex-1 px-6 py-5 space-y-5">
                            <div>
                                <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wider mb-3">Doa Setelah Berzakat</h4>
                                <p class="text-right text-xl leading-loose text-gray-800 font-arabic mb-3">اَللَّهُمَّ اجْعَلْهَا مَغْنَمًا وَلاَ تَجْعَلْهَا مَغْرَمًا</p>
                                <p class="text-sm text-gray-500 italic text-center mb-2">"Allahummaj'alhaa maghnamaw walaa taj'alhaa maghraman."</p>
                                <p class="text-xs text-gray-400 text-center">Artinya: <em>"Ya Allah, jadikanlah ini sebagai keuntungan dan jangan jadikan kerugian."</em></p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wider mb-3">Doa dari Amil kepada Muzakki</h4>
                                <p class="text-right text-xl leading-loose text-gray-800 font-arabic mb-3">آجَرَكَ اللهُ فِيمَا أَعْطَيْتَ وَبَارَكَ فِيمَا أَبْقَيْتَ وَجَعَلَهُ لَكَ طَهُورًا</p>
                                <p class="text-sm text-gray-500 italic text-center mb-2">"Aajaraka Allaahu fiimaa a'thayta wa baaraka fiimaa abqayta wa ja'alahu laka thahuuran."</p>
                                <p class="text-xs text-gray-400 text-center">Artinya: <em>"Semoga Allah memberimu pahala atas apa yang engkau berikan dan memberkahi apa yang masih tersisa."</em></p>
                            </div>
                            <hr class="border-gray-100">
                            <p class="text-xs text-amber-700 bg-amber-50 rounded-lg px-3 py-2">&#128204; Transaksi zakat Anda akan segera diproses. Amil akan menghubungi Anda untuk konfirmasi pembayaran.</p>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex-shrink-0">
                            <div class="mb-3 flex items-start gap-2.5">
                                <input type="checkbox" id="chkSudahBacaDoa" class="w-4 h-4 text-primary-600 border-gray-300 rounded mt-0.5 cursor-pointer">
                                <label for="chkSudahBacaDoa" class="text-xs text-gray-700 cursor-pointer leading-relaxed">Saya telah membaca doa setelah zakat di atas dengan ikhlas karena Allah Ta'ala.</label>
                            </div>
                            <button type="button" id="btnKonfirmasiDanSimpan" disabled onclick="konfirmasiDoaLaluSimpan()" class="w-full py-3 rounded-xl text-sm font-bold text-white transition-all bg-gray-300 cursor-not-allowed opacity-60">
                                <span id="btnKonfirmasiText">Simpan Transaksi Zakat</span>
                            </button>
                            <p class="text-xs text-gray-400 text-center mt-2">Centang kotak di atas untuk mengaktifkan tombol simpan</p>
                        </div>
                    </div>
                </div>

                {{-- Form Daring --}}
                <form id="formDaring" action="{{ route('transaksi-daring-muzakki.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                    @csrf
                    <input type="hidden" name="metode_penerimaan" value="daring">
                    <input type="hidden" name="tanggal_transaksi" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="is_pembayaran_beras" id="hdnBerasDaring" value="0">

                    {{-- Progress Steps --}}
                    <div class="mb-7">
                        <div class="flex items-center max-w-lg mx-auto">
                            <div class="flex flex-col items-center flex-1">
                                <div id="dDot1" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold ring-4 ring-primary-500/20 bg-primary text-white">1</div>
                                <span class="text-xs mt-1 font-medium text-primary-600 text-center">Detail Zakat</span>
                            </div>
                            <div id="dLine12" class="flex-1 h-0.5 bg-gray-200 transition-colors duration-300"></div>
                            <div class="flex flex-col items-center flex-1">
                                <div id="dDot2" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">2</div>
                                <span class="text-xs mt-1 font-medium text-gray-500 text-center">Pembayaran</span>
                            </div>
                            <div id="dLine23" class="flex-1 h-0.5 bg-gray-200 transition-colors duration-300"></div>
                            <div class="flex flex-col items-center flex-1">
                                <div id="dDot3" class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500">3</div>
                                <span class="text-xs mt-1 font-medium text-gray-500 text-center">Konfirmasi</span>
                            </div>
                        </div>
                    </div>

                    {{-- STEP D1 --}}
                    <div id="dStep1" class="dstep-panel">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold">1</span>
                            Pilih Jenis Zakat
                        </h3>
                        <div class="mb-5">
                            <p class="text-xs font-bold text-gray-700 mb-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Data Muzakki
                            </p>
                            <p class="text-xs text-gray-400 mb-3 italic">Nama dan email tidak dapat diubah.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                                    <input type="text" name="muzakki_nama" value="{{ $muzakkiData['nama'] }}" readonly class="w-full px-3 py-2 text-sm border border-gray-200 bg-gray-50 rounded-lg cursor-not-allowed text-gray-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Email</label>
                                    <input type="email" name="muzakki_email" value="{{ $muzakkiData['email'] }}" readonly class="w-full px-3 py-2 text-sm border border-gray-200 bg-gray-50 rounded-lg cursor-not-allowed text-gray-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Telepon / WA <span class="text-red-500">*</span></label>
                                    <input type="text" name="muzakki_telepon" id="d1Telepon" value="{{ old('muzakki_telepon', $muzakkiData['telepon']) }}" oninput="checkStep1Required()" class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-500 transition-all" placeholder="Masukkan nomor telepon">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">NIK</label>
                                    <input type="text" name="muzakki_nik" value="{{ old('muzakki_nik', $muzakkiData['nik']) }}" class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-500 transition-all" placeholder="16 digit NIK" maxlength="16">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs text-gray-500 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                                    <textarea name="muzakki_alamat" id="d1Alamat" rows="2" oninput="checkStep1Required()" class="w-full px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-500 transition-all resize-none" placeholder="Masukkan alamat lengkap">{{ old('muzakki_alamat', $muzakkiData['alamat']) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Zakat <span class="text-red-500">*</span></label>
                                    <select name="jenis_zakat_id" id="dJenisId" onchange="checkStep1Required()" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach ($jenisZakatList as $jz)
                                            @php $isFidyah = stripos($jz->nama, 'fidyah') !== false; @endphp
                                            @if (!$isFidyah)
                                                <option value="{{ $jz->id }}" data-nama="{{ strtolower($jz->nama) }}">{{ $jz->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-400 mt-1">Fidyah tidak tersedia via daring. Gunakan metode <strong>Dijemput Amil</strong>.</p>
                                </div>
                                <div id="dWrapTipe" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Spesifik <span class="text-red-500">*</span></label>
                                    <select name="tipe_zakat_id" id="dTipeId" onchange="checkStep1Required()" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                        <option value="">-- Pilih Tipe --</option>
                                    </select>
                                    <p id="dInfoTipeFitrah" class="mt-1.5 text-xs text-primary-600 hidden">Metode daring hanya mendukung pembayaran tunai (transfer/QRIS).</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Program Zakat <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                <select name="program_zakat_id" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    <option value="">-- Tidak memilih program tertentu --</option>
                                    @foreach ($programZakatList as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->nama_program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Panel Fitrah --}}
                            <div id="dPanelFitrahTunai" class="hidden space-y-4">
                                <div class="flex gap-4">
                                    <div class="text-center"><p class="font-bold text-primary-700 text-sm">{{ $zakatFitrahInfo['beras_kg'] }} kg</p><p class="text-xs text-gray-400">per jiwa</p></div>
                                    <div class="text-center"><p class="font-bold text-primary-700 text-sm">{{ $zakatFitrahInfo['beras_liter'] }} ltr</p><p class="text-xs text-gray-400">per jiwa</p></div>
                                    <div class="text-center"><p class="font-bold text-primary-700 text-sm">Rp {{ number_format($zakatFitrahInfo['nominal_per_jiwa'], 0, ',', '.') }}</p><p class="text-xs text-gray-400">BAZNAS</p></div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Jiwa <span class="text-red-500">*</span></label>
                                        <input type="number" name="jumlah_jiwa" id="dJiwa" value="1" min="1" step="1" oninput="checkStep1Required()" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nominal/Jiwa (Rp) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                            <input type="number" name="nominal_per_jiwa" id="dNominalJiwa" value="{{ $zakatFitrahInfo['nominal_per_jiwa'] }}" min="1000" step="1000" oninput="checkStep1Required()" class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            Daftar Nama per Jiwa
                                        </p>
                                        <span class="text-xs text-gray-400">Opsional, tapi disarankan</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div id="dDaftarNama">
                                            <div class="flex items-center gap-2 nama-jiwa-row" data-index="0">
                                                <div class="flex-shrink-0 w-7 h-7 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center nama-jiwa-num">1</div>
                                                <input type="text" name="nama_jiwa[]" value="{{ $muzakkiData['nama'] }}" placeholder="Nama jiwa ke-1" class="flex-1 px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-400 transition-all">
                                                <div class="w-8"></div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="tambahNamaJiwa()" class="w-full py-2 border-2 border-dashed border-gray-200 rounded-lg text-xs font-medium text-gray-500 hover:text-primary-600 hover:border-primary-300 transition-all flex items-center justify-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                            Tambah Nama Jiwa
                                        </button>
                                        <p class="text-xs text-gray-400 italic">Jumlah baris nama akan otomatis menyesuaikan dengan jumlah jiwa di atas.</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-600 mb-0.5">Total Zakat Fitrah</p>
                                    <p class="text-2xl font-bold text-primary-700" id="dTotalFitrah">Rp 0</p>
                                    <input type="hidden" name="jumlah" id="dHdnJumlahFitrah" value="0">
                                </div>
                            </div>
                            {{-- Panel Mal --}}
                            <div id="dPanelMal" class="hidden space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Total Nilai Harta (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" name="nilai_harta" id="dHarta" min="0" step="1000" placeholder="Total harta yang wajib dizakatkan" oninput="checkStep1Required()" class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nisab (Rp) <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                            <input type="number" name="nisab_saat_ini" min="0" step="1000" class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Persentase Zakat (%)</label>
                                        <input type="number" name="persentase_zakat" id="dPersen" value="2.5" min="0" max="100" step="0.1" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-600 mb-0.5">Total Zakat Mal</p>
                                    <p class="text-2xl font-bold text-primary-700" id="dTotalMal">Rp 0</p>
                                    <input type="hidden" name="jumlah" id="dHdnJumlahMal" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-5 pt-4 border-t border-gray-100">
                            <button type="button" id="btnStep1Next" onclick="dGoStep(2)" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-nz transition-all hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none">
                                Selanjutnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>
                    </div>

                    {{-- STEP D2 --}}
                    <div id="dStep2" class="dstep-panel hidden">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold">2</span>
                            Metode Pembayaran
                        </h3>
                        <div class="space-y-5">
                            <p class="text-xs text-gray-500">Pilih metode pembayaran. Bukti transfer akan dikonfirmasi oleh amil.</p>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <select name="metode_pembayaran" id="dMetodePembayaran" onchange="onMetodePembayaranChange(); checkStep2Required()" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                            <div id="dInfoTransfer" class="hidden space-y-3">
                                @if ($rekeningLembagaList->isNotEmpty())
                                    @foreach ($rekeningLembagaList as $rek)
                                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                            <div>
                                                <p class="text-xs font-bold text-gray-700">{{ $rek->nama_bank }}</p>
                                                <p class="text-sm font-mono font-bold text-gray-900 tracking-wider mt-0.5">{{ $rek->nomor_rekening }}</p>
                                                <p class="text-xs text-gray-400">a.n. {{ $rek->nama_pemilik }}</p>
                                            </div>
                                            <button type="button" onclick="salin('{{ $rek->nomor_rekening }}')" class="text-xs text-primary-600 hover:bg-primary-50 px-2.5 py-1.5 rounded-lg transition-all font-semibold">Salin</button>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-xs text-gray-400 italic">Belum ada rekening aktif. Hubungi pengurus lembaga.</p>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Screenshot Bukti Pembayaran <span class="text-red-500">*</span></label>
                                    <div id="dPrvTransfer" class="h-48 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden cursor-pointer hover:border-primary-400 transition-all" onclick="document.getElementById('dInpTransfer').click()">
                                        <div class="text-center pointer-events-none">
                                            <svg class="w-7 h-7 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <p class="text-xs text-gray-400">Klik untuk upload bukti transfer</p>
                                        </div>
                                    </div>
                                    <input type="file" name="bukti_transfer" id="dInpTransfer" accept="image/*" class="hidden" onchange="prvBuktiD(this,'dPrvTransfer'); checkStep2Required()">
                                </div>
                            </div>
                            <div id="dInfoQris" class="hidden space-y-3">
                                @if ($konfigurasiQris && !empty($konfigurasiQris->qris_image_path))
                                    <div class="flex justify-center">
                                        <div class="bg-white p-4 rounded-2xl border-2 border-primary-200 shadow-sm w-72 sm:w-80">
                                            <img src="{{ $konfigurasiQris->qris_image_url }}" class="w-full object-contain rounded-lg" alt="QRIS Lembaga">
                                            <p class="text-center text-xs text-primary-600 font-semibold mt-3">Scan untuk Membayar Zakat</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 italic">Hubungi pengurus lembaga untuk mendapatkan kode QRIS.</p>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Screenshot Bukti Pembayaran <span class="text-red-500">*</span></label>
                                    <div id="dPrvQris" class="h-48 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center mb-2 overflow-hidden cursor-pointer hover:border-primary-400 transition-all" onclick="document.getElementById('dInpQris').click()">
                                        <div class="text-center pointer-events-none">
                                            <svg class="w-7 h-7 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <p class="text-xs text-gray-400">Klik untuk upload bukti QRIS</p>
                                        </div>
                                    </div>
                                    <input type="file" name="bukti_qris" id="dInpQris" accept="image/*" class="hidden" onchange="prvBuktiD(this,'dPrvQris'); checkStep2Required()">
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Dibayar (Rp) <span class="text-red-500">*</span> <span class="text-xs text-gray-400 font-normal ml-1">— minimal = jumlah zakat</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="text" inputmode="numeric" name="jumlah_dibayar" id="dJmlDibayar" placeholder="Masukkan jumlah yang Anda bayarkan" oninput="formatJumlahDibayar(this); hitungInfaq(); checkStep2Required()" class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all">
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Jumlah zakat: <strong id="infoJumlahZakatStep2">Rp 0</strong></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Infaq <span class="text-xs text-gray-400 font-normal ml-1">— otomatis dihitung (kelebihan bayar)</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                        <input type="number" id="dJmlInfaqDisplay" readonly value="0" class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 bg-gray-50 rounded-xl cursor-not-allowed text-gray-500">
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Bayar lebih dari zakat &#8594; kelebihan otomatis dicatat sebagai <strong>infaq</strong></p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan <span class="text-xs text-gray-400 font-normal">(opsional)</span></label>
                                <textarea name="keterangan" rows="2" placeholder="Untuk program tertentu, atas nama keluarga, dll." class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-500 transition-all resize-none"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                            <button type="button" onclick="dGoStep(1)" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                Kembali
                            </button>
                            <button type="button" id="btnStep2Next" onclick="dGoStep(3)" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-nz transition-all hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none">
                                Selanjutnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>
                    </div>

                    {{-- STEP D3 --}}
                    <div id="dStep3" class="dstep-panel hidden">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                            <span class="inline-flex w-6 h-6 rounded-full bg-primary text-white text-xs items-center justify-center font-bold">3</span>
                            Konfirmasi Transaksi
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Ringkasan Pembayaran</p>
                                <table class="w-full text-sm">
                                    <tr class="border-b border-gray-100"><td class="text-gray-500 py-2 w-1/2">Jenis Zakat</td><td class="font-semibold text-gray-900" id="dRingJenis">-</td></tr>
                                    <tr class="border-b border-gray-100"><td class="text-gray-500 py-2">Jumlah Zakat</td><td class="font-bold text-primary-700 text-base" id="dRingJumlah">-</td></tr>
                                    <tr class="border-b border-gray-100"><td class="text-gray-500 py-2">Jumlah Dibayar</td><td class="font-semibold text-gray-900" id="dRingDibayar">-</td></tr>
                                    <tr class="border-b border-gray-100" id="dRingInfaqRow"><td class="text-gray-500 py-2">Infaq</td><td class="font-semibold text-green-600" id="dRingInfaq">-</td></tr>
                                    <tr class="border-b border-gray-100"><td class="text-gray-500 py-2">Metode Bayar</td><td class="font-semibold text-gray-900" id="dRingMetode">-</td></tr>
                                    <tr class="border-b border-gray-100" id="dRingJiwaRow"><td class="text-gray-500 py-2">Jumlah Jiwa</td><td class="font-semibold text-gray-900" id="dRingJiwa">-</td></tr>
                                    <tr><td class="text-gray-500 py-2">Status</td><td class="font-semibold text-primary-600">Menunggu konfirmasi amil</td></tr>
                                </table>
                            </div>
                            <div id="dRingNamaWrap" class="hidden">
                                <p class="text-xs font-bold text-gray-500 mb-2">Daftar Nama Jiwa</p>
                                <ul id="dRingNamaList" class="space-y-1 text-sm text-gray-700"></ul>
                            </div>
                            <p class="text-xs text-gray-400">Amil akan mendapat notifikasi dan segera memverifikasi pembayaran Anda.</p>
                        </div>
                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                            <button type="button" onclick="dGoStep(2)" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                Kembali
                            </button>
                            <button type="button" id="btnSimpanDaring" onclick="bukaTampilDoaSetelahZakat()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-nz hover:shadow-nz-lg hover:bg-primary-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Kirim Transaksi Zakat
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- PANEL DIJEMPUT --}}
            <div id="panelDijemput" class="hidden">
                <form id="formDijemput" action="{{ route('transaksi-daring-muzakki.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                    @csrf
                    <input type="hidden" name="metode_penerimaan" value="dijemput">
                    <input type="hidden" name="tanggal_transaksi" value="{{ now()->format('Y-m-d') }}">

                    <p class="text-xs text-gray-500 mb-5"><strong>Metode Dijemput:</strong> Amil akan datang ke lokasi Anda untuk mengambil zakat. Detail jenis dan jumlah zakat akan dilengkapi oleh amil saat menjemput.</p>

                    <div class="space-y-5">
                        {{-- Data diri --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Data Diri
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="muzakki_nama" value="{{ $muzakkiData['nama'] }}" readonly class="w-full px-4 py-2.5 text-sm border border-gray-200 bg-gray-50 rounded-xl cursor-not-allowed text-gray-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Email</label>
                                    <input type="email" name="muzakki_email" value="{{ $muzakkiData['email'] }}" readonly class="w-full px-4 py-2.5 text-sm border border-gray-200 bg-gray-50 rounded-xl cursor-not-allowed text-gray-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Telepon / WA <span class="text-red-500">*</span></label>
                                    <input type="text" name="muzakki_telepon" id="djTelepon" value="{{ old('muzakki_telepon', $muzakkiData['telepon']) }}" oninput="checkDijemputBtn()" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-400 transition-all" placeholder="Masukkan nomor telepon">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">NIK</label>
                                    <input type="text" name="muzakki_nik" value="{{ old('muzakki_nik', $muzakkiData['nik']) }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-400 transition-all" placeholder="16 digit NIK" maxlength="16">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Alamat Lengkap Penjemputan <span class="text-red-500">*</span></label>
                                    <textarea name="muzakki_alamat" id="djAlamat" rows="2" oninput="checkDijemputBtn()" class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-400 transition-all resize-none" placeholder="Masukkan alamat lengkap untuk penjemputan">{{ old('muzakki_alamat', $muzakkiData['alamat']) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Pilih Amil --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                Pilih Amil Penjemput <span class="text-red-500 ml-0.5">*</span>
                            </h4>
                            @if ($amilList->isNotEmpty())
                                <script>
                                    window.AMIL_LIST = {!! json_encode(
                                        $amilList->map(fn($a) => [
                                            'id'       => $a->id,
                                            'username' => $a->pengguna->username ?? 'Amil',
                                            'email'    => $a->pengguna->email ?? '',
                                        ])->values()->all()
                                    ) !!};
                                </script>
                                <div id="amilGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3 min-h-[80px]"></div>
                                <div id="amilPagination" class="flex items-center justify-between mt-3">
                                    <p class="text-xs text-gray-400" id="amilPaginationInfo"></p>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" id="amilPrevBtn" onclick="amilChangePage(-1)" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                            Prev
                                        </button>
                                        <div id="amilPageNumbers" class="flex items-center gap-1"></div>
                                        <button type="button" id="amilNextBtn" onclick="amilChangePage(1)" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                                            Next
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Belum ada amil aktif. Silakan pilih metode daring.</p>
                            @endif
                        </div>

                        {{-- Keterangan WAJIB dengan counter karakter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Keterangan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="keterangan" id="djKeterangan" rows="3" maxlength="5000"
                                placeholder="Patokan lokasi, waktu yang tersedia, dll."
                                oninput="updateKeteranganCounter(); checkDijemputBtn()"
                                class="w-full px-4 py-2.5 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary-400 transition-all resize-none"></textarea>

                            {{-- Row: hint kiri, counter kanan --}}
                            <div class="flex items-start justify-between mt-1.5 gap-3">
                                <div id="djKeteranganHint" class="flex items-center gap-1 text-xs text-gray-400">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Minimal <strong>5 karakter</strong>. Isi patokan lokasi, waktu tersedia, atau catatan untuk amil.</span>
                                </div>
                                <span id="djKeteranganCounter" class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0 font-mono">0/5000</span>
                            </div>

                            {{-- Error min karakter --}}
                            <p id="djKeteranganError" class="hidden text-xs text-red-500 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Keterangan minimal 5 karakter.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <button type="button" onclick="kembaliPilihMetode()" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            Kembali
                        </button>
                        {{-- Button disabled by default, enable via JS --}}
                        <button type="submit" id="btnSimpanDijemput" disabled
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-nz transition-all hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
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

    // Konstanta validasi
    const DJ_KETERANGAN_MIN = 5;
    const DJ_KETERANGAN_MAX = 5000;

    function fmt(n) { return new Intl.NumberFormat('id-ID').format(Math.round(n || 0)); }
    function getJmlDibayarRaw() { return parseFloat(document.getElementById('dJmlDibayar').value.replace(/\./g, '')) || 0; }
    function formatJumlahDibayar(el) {
        let raw = el.value.replace(/\./g, '').replace(/[^0-9]/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    }

    // ── Counter karakter keterangan + state validasi ──
    function updateKeteranganCounter() {
        const ta      = document.getElementById('djKeterangan');
        const counter = document.getElementById('djKeteranganCounter');
        const errEl   = document.getElementById('djKeteranganError');
        const hint    = document.getElementById('djKeteranganHint');
        if (!ta || !counter) return;

        const len     = ta.value.length;
        const isValid = len >= DJ_KETERANGAN_MIN;
        const isEmpty = len === 0;

        counter.textContent = len + '/' + DJ_KETERANGAN_MAX;
        counter.className   = 'text-xs whitespace-nowrap flex-shrink-0 font-mono ' +
            (isEmpty ? 'text-gray-400' : isValid ? 'text-primary-600' : 'text-red-500');

        if (!isEmpty && !isValid) {
            errEl?.classList.remove('hidden');
            ta.classList.add('border-red-400');
            ta.classList.remove('border-gray-300');
        } else {
            errEl?.classList.add('hidden');
            ta.classList.remove('border-red-400');
            ta.classList.add('border-gray-300');
        }

        if (hint) hint.style.display = isValid ? 'none' : 'flex';
    }

    // ── Enable/disable tombol Request Penjemputan ──
    function checkDijemputBtn() {
        const telepon    = document.getElementById('djTelepon')?.value.trim() || '';
        const alamat     = document.getElementById('djAlamat')?.value.trim() || '';
        const keterangan = document.getElementById('djKeterangan')?.value.trim() || '';
        const amilOk     = amilSelectedId !== null;

        const valid = telepon.length > 0
            && alamat.length > 0
            && keterangan.length >= DJ_KETERANGAN_MIN
            && amilOk;

        const btn = document.getElementById('btnSimpanDijemput');
        if (btn) btn.disabled = !valid;
    }

    function pilihMetode(metode) {
        document.getElementById('panelPilihMetode').classList.add('hidden');
        if (metode === 'daring') {
            document.getElementById('panelDaring').classList.remove('hidden');
            document.getElementById('formDaring').classList.add('hidden');
            const modal = document.getElementById('modalNiatDoa');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            document.getElementById('panelDijemput').classList.remove('hidden');
            checkDijemputBtn();
        }
    }

    function tutupModalNiat() { window.location.href = "{{ route('transaksi-daring-muzakki.index') }}"; }

    function tutupModalDoaSetelah() {
        const modal = document.getElementById('modalDoaSetelahZakat');
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function kembaliPilihMetode() {
        document.getElementById('panelDaring').classList.add('hidden');
        document.getElementById('panelDijemput').classList.add('hidden');
        document.getElementById('panelPilihMetode').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const chk = document.getElementById('chkSudahBaca');
        const btn = document.getElementById('btnSudahBaca');
        if (chk && btn) {
            chk.addEventListener('change', function() {
                btn.disabled = !this.checked;
                btn.classList.toggle('bg-gray-300', !this.checked);
                btn.classList.toggle('cursor-not-allowed', !this.checked);
                btn.classList.toggle('opacity-60', !this.checked);
                btn.classList.toggle('bg-primary', this.checked);
                btn.classList.toggle('hover:bg-primary-600', this.checked);
                btn.classList.toggle('cursor-pointer', this.checked);
            });
        }
        const chkDoa = document.getElementById('chkSudahBacaDoa');
        const btnDoa = document.getElementById('btnKonfirmasiDanSimpan');
        if (chkDoa && btnDoa) {
            chkDoa.addEventListener('change', function() {
                btnDoa.disabled = !this.checked;
                btnDoa.classList.toggle('bg-gray-300', !this.checked);
                btnDoa.classList.toggle('cursor-not-allowed', !this.checked);
                btnDoa.classList.toggle('opacity-60', !this.checked);
                btnDoa.classList.toggle('bg-primary', this.checked);
                btnDoa.classList.toggle('hover:bg-primary-600', this.checked);
                btnDoa.classList.toggle('cursor-pointer', this.checked);
            });
        }

        document.getElementById('dJenisId').addEventListener('change', function() {
            const jenisId   = this.value;
            const jenisNama = (this.options[this.selectedIndex]?.dataset.nama || '').toLowerCase();
            const tipeEl    = document.getElementById('dTipeId');
            const wrapTipe  = document.getElementById('dWrapTipe');
            const infoFitrah = document.getElementById('dInfoTipeFitrah');
            tipeEl.innerHTML = '<option value="">-- Pilih Tipe --</option>';
            dResetPanelZakat();
            infoFitrah.classList.add('hidden');
            if (!jenisId) { wrapTipe.classList.add('hidden'); checkStep1Required(); return; }
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
            } else { wrapTipe.classList.add('hidden'); }
            checkStep1Required();
        });

        document.getElementById('dTipeId').addEventListener('change', function() {
            const jenisEl  = document.getElementById('dJenisId');
            const namaJenis = (jenisEl.options[jenisEl.selectedIndex]?.dataset.nama || '').toLowerCase();
            dResetPanelZakat();
            if (!this.value) { checkStep1Required(); return; }
            if (namaJenis.includes('fitrah')) dTampilFitrah();
            else if (namaJenis.includes('mal')) dTampilMal(this.options[this.selectedIndex]);
            checkStep1Required();
        });

        document.getElementById('dJiwa')?.addEventListener('input', function() { hitungFitrahD(); sinkronisasiNamaJiwa(); checkStep1Required(); });
        document.getElementById('dNominalJiwa')?.addEventListener('input', function() { hitungFitrahD(); checkStep1Required(); });
        document.getElementById('dHarta')?.addEventListener('input', function() { hitungMalD(); checkStep1Required(); });
        document.getElementById('dPersen')?.addEventListener('input', hitungMalD);

        checkStep1Required();
        updateKeteranganCounter();
        if (window.AMIL_LIST) amilRender();
    });

    function konfirmasiSudahBaca() {
        const modal = document.getElementById('modalNiatDoa');
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('formDaring').classList.remove('hidden');
    }

    function bukaTampilDoaSetelahZakat() {
        const telepon = document.querySelector('#formDaring [name="muzakki_telepon"]').value.trim();
        const alamat  = document.querySelector('#formDaring [name="muzakki_alamat"]').value.trim();
        if (!telepon) { alert('Nomor telepon wajib diisi.'); dGoStep(1); return; }
        if (!alamat)  { alert('Alamat wajib diisi.'); dGoStep(1); return; }
        if (!document.getElementById('dJenisId').value) { alert('Pilih jenis zakat.'); dGoStep(1); return; }
        if (!document.getElementById('dTipeId').value)  { alert('Pilih tipe zakat.'); dGoStep(1); return; }
        if (getJumlahZakatD() <= 0) { alert('Jumlah zakat tidak valid.'); dGoStep(1); return; }
        if (!document.getElementById('dMetodePembayaran').value) { alert('Pilih metode pembayaran.'); dGoStep(2); return; }
        const metode = document.getElementById('dMetodePembayaran').value;
        if (metode === 'transfer' && !document.getElementById('dInpTransfer').files.length) { alert('Upload bukti transfer terlebih dahulu.'); dGoStep(2); return; }
        if (metode === 'qris'     && !document.getElementById('dInpQris').files.length)     { alert('Upload bukti QRIS terlebih dahulu.'); dGoStep(2); return; }
        if (getJmlDibayarRaw() <= 0) { alert('Jumlah dibayar wajib diisi.'); dGoStep(2); return; }

        const chkDoa = document.getElementById('chkSudahBacaDoa');
        const btnDoa = document.getElementById('btnKonfirmasiDanSimpan');
        chkDoa.checked = false;
        btnDoa.disabled = true;
        btnDoa.classList.add('bg-gray-300', 'cursor-not-allowed', 'opacity-60');
        btnDoa.classList.remove('bg-primary', 'hover:bg-primary-600', 'cursor-pointer');

        const modal = document.getElementById('modalDoaSetelahZakat');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function konfirmasiDoaLaluSimpan() {
        const modal = document.getElementById('modalDoaSetelahZakat');
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        const jmlEl = document.getElementById('dJmlDibayar');
        jmlEl.value = jmlEl.value.replace(/\./g, '');
        const btnText = document.getElementById('btnKonfirmasiText');
        if (btnText) btnText.innerHTML = '<svg class="animate-spin w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...';
        document.getElementById('formDaring').submit();
    }

    function dResetPanelZakat() {
        document.getElementById('dPanelFitrahTunai').classList.add('hidden');
        document.getElementById('dPanelMal').classList.add('hidden');
        dActivePanelZ = null;
    }
    function dTampilFitrah() { dActivePanelZ = 'fitrah'; document.getElementById('dPanelFitrahTunai').classList.remove('hidden'); hitungFitrahD(); sinkronisasiNamaJiwa(); }
    function dTampilMal(tipeOpt) { dActivePanelZ = 'mal'; document.getElementById('dPanelMal').classList.remove('hidden'); document.getElementById('dPersen').value = tipeOpt.dataset.persentase || 2.5; hitungMalD(); }

    function hitungFitrahD() {
        const jiwa  = parseFloat(document.getElementById('dJiwa').value) || 0;
        const nom   = parseFloat(document.getElementById('dNominalJiwa').value) || 0;
        const total = jiwa * nom;
        document.getElementById('dTotalFitrah').textContent = 'Rp ' + fmt(total);
        document.getElementById('dHdnJumlahFitrah').value = Math.round(total);
        updateInfoZakatStep2();
    }
    function hitungMalD() {
        const h = parseFloat(document.getElementById('dHarta').value) || 0;
        const p = parseFloat(document.getElementById('dPersen').value) || 2.5;
        const t = h * (p / 100);
        document.getElementById('dTotalMal').textContent = 'Rp ' + fmt(t);
        document.getElementById('dHdnJumlahMal').value = Math.round(t);
        updateInfoZakatStep2();
    }
    function getJumlahZakatD() {
        if (dActivePanelZ === 'fitrah') return parseFloat(document.getElementById('dHdnJumlahFitrah').value) || 0;
        if (dActivePanelZ === 'mal')    return parseFloat(document.getElementById('dHdnJumlahMal').value) || 0;
        return 0;
    }
    function updateInfoZakatStep2() {
        const jumlah = getJumlahZakatD();
        const el = document.getElementById('infoJumlahZakatStep2');
        if (el) el.textContent = 'Rp ' + fmt(jumlah);
        hitungInfaq();
    }
    function hitungInfaq() {
        const infaq = Math.max(0, getJmlDibayarRaw() - getJumlahZakatD());
        document.getElementById('dJmlInfaqDisplay').value = Math.round(infaq);
        checkStep2Required();
    }
    function onMetodePembayaranChange() {
        const val = document.getElementById('dMetodePembayaran').value;
        document.getElementById('dInfoTransfer').classList.add('hidden');
        document.getElementById('dInfoQris').classList.add('hidden');
        if (val === 'transfer') document.getElementById('dInfoTransfer').classList.remove('hidden');
        if (val === 'qris')     document.getElementById('dInfoQris').classList.remove('hidden');
    }
    function checkStep1Required() {
        const telepon = document.getElementById('d1Telepon')?.value.trim() || '';
        const alamat  = document.getElementById('d1Alamat')?.value.trim() || '';
        const jenisId = document.getElementById('dJenisId').value;
        const tipeId  = document.getElementById('dTipeId').value;
        let panelOk = false;
        if (dActivePanelZ === 'fitrah') {
            panelOk = (parseFloat(document.getElementById('dJiwa').value) || 0) > 0
                   && (parseFloat(document.getElementById('dNominalJiwa').value) || 0) > 0;
        } else if (dActivePanelZ === 'mal') {
            panelOk = (parseFloat(document.getElementById('dHarta').value) || 0) > 0;
        }
        const btn = document.getElementById('btnStep1Next');
        if (btn) btn.disabled = !(telepon && alamat && jenisId && tipeId && panelOk);
    }
    function checkStep2Required() {
        const metode = document.getElementById('dMetodePembayaran').value;
        if (!metode) { setStep2Btn(false); return; }
        let buktiOk = false;
        if (metode === 'transfer') buktiOk = document.getElementById('dInpTransfer').files.length > 0;
        if (metode === 'qris')     buktiOk = document.getElementById('dInpQris').files.length > 0;
        setStep2Btn(buktiOk && getJmlDibayarRaw() > 0);
    }
    function setStep2Btn(valid) {
        const btn = document.getElementById('btnStep2Next');
        if (btn) btn.disabled = !valid;
    }

    function tambahNamaJiwa() {
        const container = document.getElementById('dDaftarNama');
        const rows = container.querySelectorAll('.nama-jiwa-row');
        const jumlahJiwa = parseInt(document.getElementById('dJiwa').value) || 1;
        if (rows.length >= jumlahJiwa) { document.getElementById('dJiwa').value = rows.length + 1; hitungFitrahD(); }
        const idx = rows.length;
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center gap-2 nama-jiwa-row';
        newRow.dataset.index = idx;
        newRow.innerHTML = `<div class="flex-shrink-0 w-7 h-7 rounded-full bg-primary-100 border border-primary-300 text-primary-700 text-xs font-bold flex items-center justify-center nama-jiwa-num">${idx+1}</div><input type="text" name="nama_jiwa[]" placeholder="Nama jiwa ke-${idx+1}" class="flex-1 px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-400 transition-all"><button type="button" onclick="hapusNamaJiwa(this)" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-all flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>`;
        container.appendChild(newRow);
        const totalRows = container.querySelectorAll('.nama-jiwa-row').length;
        if (parseInt(document.getElementById('dJiwa').value) < totalRows) { document.getElementById('dJiwa').value = totalRows; hitungFitrahD(); }
    }
    function hapusNamaJiwa(btn) {
        const row = btn.closest('.nama-jiwa-row');
        const container = document.getElementById('dDaftarNama');
        if (container.querySelectorAll('.nama-jiwa-row').length <= 1) { alert('Minimal harus ada 1 jiwa.'); return; }
        row.remove();
        const remaining = container.querySelectorAll('.nama-jiwa-row');
        remaining.forEach((r, i) => {
            r.dataset.index = i;
            const numEl = r.querySelector('.nama-jiwa-num');
            if (numEl) numEl.textContent = i + 1;
            const inp = r.querySelector('input[name="nama_jiwa[]"]');
            if (inp && !inp.value) inp.placeholder = `Nama jiwa ke-${i+1}`;
        });
        document.getElementById('dJiwa').value = remaining.length;
        hitungFitrahD();
    }
    function sinkronisasiNamaJiwa() {
        const jumlahJiwa = parseInt(document.getElementById('dJiwa').value) || 1;
        const container  = document.getElementById('dDaftarNama');
        const rows = container.querySelectorAll('.nama-jiwa-row');
        const current = rows.length;
        if (jumlahJiwa > current) {
            for (let i = current; i < jumlahJiwa; i++) {
                const newRow = document.createElement('div');
                newRow.className = 'flex items-center gap-2 nama-jiwa-row';
                newRow.dataset.index = i;
                newRow.innerHTML = `<div class="flex-shrink-0 w-7 h-7 rounded-full bg-primary-100 border border-primary-300 text-primary-700 text-xs font-bold flex items-center justify-center nama-jiwa-num">${i+1}</div><input type="text" name="nama_jiwa[]" placeholder="Nama jiwa ke-${i+1}" class="flex-1 px-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:border-primary-400 transition-all"><button type="button" onclick="hapusNamaJiwa(this)" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-all flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>`;
                container.appendChild(newRow);
            }
        } else if (jumlahJiwa < current) {
            const allRows = container.querySelectorAll('.nama-jiwa-row');
            for (let i = current - 1; i >= jumlahJiwa; i--) { if (i > 0) allRows[i].remove(); }
        }
    }

    function dGoStep(n) {
        if (n > dActiveStep) { if (!dValidateStep(dActiveStep)) return; }
        document.querySelectorAll('.dstep-panel').forEach(p => p.classList.add('hidden'));
        document.getElementById('dStep' + n).classList.remove('hidden');
        dActiveStep = n;
        dRefreshDots(n);
        if (n === 2) { updateInfoZakatStep2(); checkStep2Required(); }
        if (n === 3) dUpdateRingkasan();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function dRefreshDots(active) {
        const checkIcon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
        [1, 2, 3].forEach(i => {
            const d = document.getElementById('dDot' + i);
            if (!d) return;
            d.className = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300';
            if (i < active) {
                d.classList.add('bg-primary-500', 'text-white');
                d.innerHTML = checkIcon;
            } else if (i === active) {
                d.classList.add('bg-primary', 'text-white', 'ring-4', 'ring-primary-500/20');
                d.innerHTML = i;
            } else {
                d.classList.add('bg-gray-200', 'text-gray-500');
                d.innerHTML = i;
            }
            const ln = document.getElementById(i === 1 ? 'dLine12' : 'dLine23');
            if (ln) { ln.classList.toggle('bg-primary-500', i < active); ln.classList.toggle('bg-gray-200', i >= active); }
        });
    }
    function dValidateStep(step) {
        if (step === 1) {
            if (!document.getElementById('d1Telepon')?.value.trim()) { alert('Nomor telepon wajib diisi.'); return false; }
            if (!document.getElementById('d1Alamat')?.value.trim())  { alert('Alamat wajib diisi.'); return false; }
            if (!document.getElementById('dJenisId').value) { alert('Pilih jenis zakat terlebih dahulu.'); return false; }
            if (!document.getElementById('dTipeId').value)  { alert('Pilih tipe zakat terlebih dahulu.'); return false; }
            if (getJumlahZakatD() <= 0) { alert('Jumlah zakat tidak valid.'); return false; }
            return true;
        }
        if (step === 2) {
            const metode = document.getElementById('dMetodePembayaran').value;
            if (!metode) { alert('Pilih metode pembayaran (Transfer atau QRIS).'); return false; }
            if (metode === 'transfer' && !document.getElementById('dInpTransfer').files.length) { alert('Upload bukti transfer terlebih dahulu.'); return false; }
            if (metode === 'qris'     && !document.getElementById('dInpQris').files.length)     { alert('Upload bukti QRIS terlebih dahulu.'); return false; }
            if (getJmlDibayarRaw() <= 0) { alert('Jumlah dibayar wajib diisi.'); return false; }
            return true;
        }
        return true;
    }
    function dUpdateRingkasan() {
        const jenisEl  = document.getElementById('dJenisId');
        const jenis    = jenisEl.options[jenisEl.selectedIndex]?.text || '-';
        const jumlah   = getJumlahZakatD();
        const jmlDibayar = getJmlDibayarRaw() || jumlah;
        const infaq    = Math.max(0, jmlDibayar - jumlah);
        const metode   = document.getElementById('dMetodePembayaran').value || '-';
        const jiwa     = document.getElementById('dJiwa')?.value || '-';

        document.getElementById('dRingJenis').textContent   = jenis;
        document.getElementById('dRingJumlah').textContent  = 'Rp ' + fmt(jumlah);
        document.getElementById('dRingDibayar').textContent = 'Rp ' + fmt(jmlDibayar);
        document.getElementById('dRingMetode').textContent  = metode === 'transfer' ? 'Transfer Bank' : (metode === 'qris' ? 'QRIS' : '-');

        const infaqRow = document.getElementById('dRingInfaqRow');
        if (infaq > 0) { document.getElementById('dRingInfaq').textContent = 'Rp ' + fmt(infaq); infaqRow.style.display = ''; }
        else { infaqRow.style.display = 'none'; }

        const jiwaRow = document.getElementById('dRingJiwaRow');
        if (dActivePanelZ === 'fitrah') { document.getElementById('dRingJiwa').textContent = jiwa + ' jiwa'; jiwaRow.style.display = ''; }
        else { jiwaRow.style.display = 'none'; }

        const namaWrap = document.getElementById('dRingNamaWrap');
        const namaList = document.getElementById('dRingNamaList');
        if (dActivePanelZ === 'fitrah') {
            const namaInputs = document.querySelectorAll('#dDaftarNama input[name="nama_jiwa[]"]');
            const namaAda    = Array.from(namaInputs).filter(i => i.value.trim());
            if (namaAda.length > 0) {
                namaList.innerHTML = namaAda.map((inp, idx) =>
                    `<li class="flex items-center gap-2 text-xs text-gray-600"><span class="w-5 h-5 rounded-full bg-primary-100 text-primary-700 text-xs font-bold flex items-center justify-center flex-shrink-0">${idx+1}</span>${inp.value.trim()}</li>`
                ).join('');
                namaWrap.classList.remove('hidden');
            } else { namaWrap.classList.add('hidden'); }
        } else { namaWrap.classList.add('hidden'); }
    }

    // Submit form dijemput
    document.getElementById('formDijemput')?.addEventListener('submit', function(e) {
        const nama       = this.querySelector('[name="muzakki_nama"]').value.trim();
        const telepon    = this.querySelector('[name="muzakki_telepon"]').value.trim();
        const alamat     = this.querySelector('[name="muzakki_alamat"]').value.trim();
        const amil       = document.querySelector('.amil-radio:checked');
        const keterangan = document.getElementById('djKeterangan')?.value.trim();

        if (!nama)     { e.preventDefault(); alert('Nama wajib diisi.'); return; }
        if (!telepon)  { e.preventDefault(); alert('Nomor telepon wajib diisi.'); return; }
        if (!alamat)   { e.preventDefault(); alert('Alamat penjemputan wajib diisi.'); return; }
        if (!amil)     { e.preventDefault(); alert('Pilih amil penjemput terlebih dahulu.'); return; }
        if (!keterangan || keterangan.length < DJ_KETERANGAN_MIN) {
            e.preventDefault();
            alert('Keterangan wajib diisi minimal ' + DJ_KETERANGAN_MIN + ' karakter.');
            return;
        }

        const btn = document.getElementById('btnSimpanDijemput');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...';
    });

    // Preview bukti
    function prvBuktiD(input, previewId) {
        const el   = document.getElementById(previewId);
        const file = input.files?.[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) { alert('Ukuran file maks 5MB.'); input.value = ''; return; }
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const MAX_SIZE = 1280;
                let w = img.width, h = img.height;
                if (w > MAX_SIZE || h > MAX_SIZE) {
                    if (w > h) { h = Math.round(h * MAX_SIZE / w); w = MAX_SIZE; }
                    else       { w = Math.round(w * MAX_SIZE / h); h = MAX_SIZE; }
                }
                canvas.width = w; canvas.height = h;
                canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                const originalKb = Math.round(file.size / 1024);
                canvas.toBlob(function(blob) {
                    const compressedKb = Math.round(blob.size / 1024);
                    const compressedFile = new File([blob], file.name.replace(/\.[^.]+$/, '.webp'), { type: 'image/webp' });
                    const dt = new DataTransfer();
                    dt.items.add(compressedFile);
                    input.files = dt.files;
                    const url = URL.createObjectURL(blob);
                    el.innerHTML = `<img src="${url}" style="max-height:160px; max-width:100%; object-fit:contain; display:block; margin:0 auto; padding:12px;">`;
                    const infoId = previewId + 'Info';
                    let infoEl = document.getElementById(infoId);
                    if (!infoEl) {
                        infoEl = document.createElement('p');
                        infoEl.id = infoId;
                        infoEl.className = 'text-xs font-semibold text-green-600 mt-1 flex items-center gap-1';
                        el.closest('div').parentElement.appendChild(infoEl);
                    }
                    infoEl.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Dikompresi: ${originalKb}KB &#8594; ${compressedKb}KB (WebP)`;
                    checkStep2Required();
                }, 'image/webp', 0.82);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
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

    // Pagination Amil
    const AMIL_PER_PAGE = 6;
    let amilCurrentPage = 1;
    let amilSelectedId  = null;

    function amilRender() {
        const list  = window.AMIL_LIST || [];
        const total = list.length;
        const pages = Math.ceil(total / AMIL_PER_PAGE);
        const start = (amilCurrentPage - 1) * AMIL_PER_PAGE;
        const slice = list.slice(start, start + AMIL_PER_PAGE);
        const grid  = document.getElementById('amilGrid');
        if (!grid) return;

        grid.innerHTML = slice.map(a => {
            const initial  = (a.username.charAt(0) || 'A').toUpperCase();
            const checked  = amilSelectedId == a.id ? 'checked' : '';
            const selected = amilSelectedId == a.id
                ? 'border-primary-500 bg-primary-50/60'
                : 'border-gray-200 hover:border-primary-400 hover:bg-primary-50/50';
            return `<label class="amil-card flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all ${selected}" onclick="amilSelect(${a.id}, this)">
                <input type="radio" name="amil_id" value="${a.id}" ${checked} class="w-4 h-4 text-primary-500 border-gray-300 amil-radio">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">${a.username}</p>
                    <p class="text-xs text-gray-500 truncate">${a.email}</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-primary-700">${initial}</span>
                </div>
            </label>`;
        }).join('');

        const infoEl = document.getElementById('amilPaginationInfo');
        if (infoEl) {
            const from = total === 0 ? 0 : start + 1;
            const to   = Math.min(start + AMIL_PER_PAGE, total);
            infoEl.textContent = `Menampilkan ${from}\u2013${to} dari ${total} amil`;
        }

        const prevBtn = document.getElementById('amilPrevBtn');
        const nextBtn = document.getElementById('amilNextBtn');
        if (prevBtn) prevBtn.disabled = amilCurrentPage <= 1;
        if (nextBtn) nextBtn.disabled = amilCurrentPage >= pages;

        const numWrap = document.getElementById('amilPageNumbers');
        if (numWrap) {
            numWrap.innerHTML = '';
            for (let i = 1; i <= pages; i++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = i;
                btn.onclick = () => { amilCurrentPage = i; amilRender(); };
                btn.className = i === amilCurrentPage
                    ? 'w-7 h-7 rounded-lg bg-primary text-white text-xs font-bold transition-all'
                    : 'w-7 h-7 rounded-lg border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-50 transition-all';
                numWrap.appendChild(btn);
            }
        }

        const pag = document.getElementById('amilPagination');
        if (pag) pag.style.display = pages <= 1 ? 'none' : 'flex';
    }

    function amilChangePage(delta) {
        const total = window.AMIL_LIST?.length || 0;
        const pages = Math.ceil(total / AMIL_PER_PAGE);
        amilCurrentPage = Math.min(Math.max(1, amilCurrentPage + delta), pages);
        amilRender();
    }

    function amilSelect(id, labelEl) {
        amilSelectedId = id;
        document.querySelectorAll('.amil-card').forEach(el => {
            el.classList.remove('border-primary-500', 'bg-primary-50/60');
            el.classList.add('border-gray-200');
        });
        labelEl.classList.remove('border-gray-200');
        labelEl.classList.add('border-primary-500', 'bg-primary-50/60');
        // Update tombol saat amil dipilih
        checkDijemputBtn();
    }
</script>
@endpush