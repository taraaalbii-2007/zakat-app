@extends('layouts.app')

@section('title', 'Edit Penyaluran - ' . $transaksi->no_transaksi)

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- HEADER --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">
                        Edit Transaksi Penyaluran
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        {{ $transaksi->no_transaksi }} &mdash; Perbarui data mustahik, detail penyaluran, dan dokumentasi
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('transaksi-penyaluran.show', $transaksi->uuid) }}"
                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                        Kembali
                    </a>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                        Draft
                    </span>
                </div>
            </div>
        </div>

        <form id="formPenyaluran"
              action="{{ route('transaksi-penyaluran.update', $transaksi->uuid) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-4 sm:p-6">
            @csrf
            @method('PUT')

            {{-- Error Summary --}}
            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-start gap-3">
                <div>
                    <p class="font-medium text-sm">Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- ==================== STEP 1: MUSTAHIK ==================== --}}
            <div class="mb-8">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">1</span>
                    Data Mustahik
                </h3>

                <div class="space-y-4">
                    {{-- Mustahik Preview --}}
                    <div id="mustahikPreview" class="{{ $transaksi->mustahik_id ? '' : 'hidden' }} bg-primary-50 border border-primary-200 rounded-xl p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 bg-primary-200 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span id="mustahikInisial" class="text-primary-800 font-bold text-sm">
                                        {{ strtoupper(substr($transaksi->mustahik->nama_lengkap ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p id="mustahikNama" class="text-sm font-semibold text-primary-900 truncate">
                                        {{ $transaksi->mustahik->nama_lengkap ?? '' }}
                                    </p>
                                    <p id="mustahikKategori" class="text-xs text-primary-700">
                                        {{ $transaksi->kategoriMustahik->nama ?? '' }}
                                    </p>
                                    @if($transaksi->mustahik?->nik)
                                    <p id="mustahikNik" class="text-xs text-gray-500 mt-0.5">NIK: {{ $transaksi->mustahik->nik }}</p>
                                    @else
                                    <p id="mustahikNik" class="text-xs text-gray-500 mt-0.5 hidden"></p>
                                    @endif
                                </div>
                            </div>
                            <button type="button" onclick="openMustahikModal()"
                                class="flex-shrink-0 text-xs text-primary-600 hover:text-primary-800 font-medium underline underline-offset-2">
                                Ganti
                            </button>
                        </div>
                    </div>

                    {{-- Tombol pilih mustahik (tampil jika belum ada) --}}
                    <div id="mustahikEmptyState" class="{{ $transaksi->mustahik_id ? 'hidden' : '' }}">
                        <button type="button" onclick="openMustahikModal()"
                            class="w-full flex items-center justify-center gap-2 px-4 py-4 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-500 hover:border-primary hover:text-primary hover:bg-primary-50 transition-all">
                            Pilih Mustahik
                        </button>
                    </div>

                    {{-- Hidden input untuk ID mustahik --}}
                    <input type="hidden" name="mustahik_id" id="mustahikIdSelected"
                           value="{{ old('mustahik_id', $transaksi->mustahik_id) }}">

                    {{-- Kategori Mustahik (Snapshot) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori Mustahik <span class="text-gray-400 font-normal">(Snapshot)</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori_mustahik_id" id="kategoriMustahikId"
                            class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('kategori_mustahik_id') border-red-500 @enderror">
                            @foreach($kategoriMustahikList as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_mustahik_id', $transaksi->kategori_mustahik_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('kategori_mustahik_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Kategori saat penyaluran dilakukan — disimpan sebagai histori dan tidak akan berubah jika kategori mustahik diperbarui.</p>
                    </div>
                </div>
            </div>

            {{-- ==================== STEP 2: DETAIL PENYALURAN ==================== --}}
            <div class="mb-8">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">2</span>
                    Detail Penyaluran
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Penyaluran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_penyaluran"
                                value="{{ old('tanggal_penyaluran', $transaksi->tanggal_penyaluran->format('Y-m-d')) }}"
                                required
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('tanggal_penyaluran') border-red-500 @enderror">
                            @error('tanggal_penyaluran')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Penyaluran</label>
                            <input type="time" name="waktu_penyaluran"
                                value="{{ old('waktu_penyaluran', $transaksi->waktu_penyaluran ? \Carbon\Carbon::parse($transaksi->waktu_penyaluran)->format('H:i') : '') }}"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Periode Zakat</label>
                            <input type="month" name="periode"
                                value="{{ old('periode', $transaksi->periode) }}"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('periode') border-red-500 @enderror">
                            @error('periode')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Format: YYYY-MM, untuk zakat fitrah atau zakat periodik.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Zakat</label>
                            <select name="jenis_zakat_id"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('jenis_zakat_id') border-red-500 @enderror">
                                <option value="">-- Pilih Jenis Zakat --</option>
                                @foreach($jenisZakatList as $jz)
                                <option value="{{ $jz->id }}" {{ old('jenis_zakat_id', $transaksi->jenis_zakat_id) == $jz->id ? 'selected' : '' }}>
                                    {{ $jz->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('jenis_zakat_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program Zakat</label>
                            <select name="program_zakat_id"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all">
                                <option value="">-- Tanpa Program --</option>
                                @foreach($programZakatList as $prog)
                                <option value="{{ $prog->id }}" {{ old('program_zakat_id', $transaksi->program_zakat_id) == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->nama_program }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amil Penyalur</label>
                            <select name="amil_id"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('amil_id') border-red-500 @enderror">
                                <option value="">-- Pilih Amil --</option>
                                @foreach($amilList as $amil)
                                <option value="{{ $amil->id }}" {{ old('amil_id', $transaksi->amil_id) == $amil->id ? 'selected' : '' }}>
                                    {{ $amil->nama_lengkap }}
                                </option>
                                @endforeach
                            </select>
                            @error('amil_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== STEP 3: METODE & NOMINAL ==================== --}}
            <div class="mb-8">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">3</span>
                    Metode &amp; Nominal
                </h3>

                <div class="space-y-5">
                    {{-- Metode Cards --}}
                    @php $currentMetode = old('metode_penyaluran', $transaksi->metode_penyaluran); @endphp
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Metode Penyaluran <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_penyaluran" value="tunai"
                                    class="hidden peer"
                                    {{ $currentMetode === 'tunai' ? 'checked' : '' }}
                                    onchange="onMetodeChange('tunai')">
                                <div class="border-2 rounded-xl p-3 sm:p-4 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-50 border-gray-200 hover:border-green-300">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-700 peer-checked:text-green-700">Tunai</p>
                                    <p class="text-xs text-gray-400">Cash</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_penyaluran" value="transfer"
                                    class="hidden peer"
                                    {{ $currentMetode === 'transfer' ? 'checked' : '' }}
                                    onchange="onMetodeChange('transfer')">
                                <div class="border-2 rounded-xl p-3 sm:p-4 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 border-gray-200 hover:border-blue-300">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-700 peer-checked:text-blue-700">Transfer</p>
                                    <p class="text-xs text-gray-400">Bank</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_penyaluran" value="barang"
                                    class="hidden peer"
                                    {{ $currentMetode === 'barang' ? 'checked' : '' }}
                                    onchange="onMetodeChange('barang')">
                                <div class="border-2 rounded-xl p-3 sm:p-4 text-center transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 border-gray-200 hover:border-orange-300">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-700 peer-checked:text-orange-700">Barang</p>
                                    <p class="text-xs text-gray-400">In-Kind</p>
                                </div>
                            </label>
                        </div>
                        @error('metode_penyaluran')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nominal (tunai/transfer) --}}
                    <div id="sectionNominal" class="{{ $currentMetode === 'barang' ? 'hidden' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium select-none">Rp</span>
                            <input type="number" name="jumlah" id="jumlah"
                                min="0" step="1000"
                                value="{{ old('jumlah', $transaksi->jumlah) }}"
                                class="block w-full pl-12 pr-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('jumlah') border-red-500 @enderror"
                                placeholder="0">
                        </div>
                        @error('jumlah')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Nilai penuh yang diterima mustahik — tanpa potongan apapun.</p>
                    </div>

                    {{-- Barang Fields --}}
                    <div id="sectionBarang" class="{{ $currentMetode === 'barang' ? '' : 'hidden' }} space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Detail Barang <span class="text-red-500">*</span>
                            </label>
                            <textarea name="detail_barang" rows="3"
                                class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all resize-none @error('detail_barang') border-red-500 @enderror"
                                placeholder="Contoh: Beras 10kg, Minyak Goreng 2L, Gula Pasir 1kg">{{ old('detail_barang', $transaksi->detail_barang) }}</textarea>
                            @error('detail_barang')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Estimasi Barang</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium select-none">Rp</span>
                                <input type="number" name="nilai_barang"
                                    min="0" step="1000"
                                    value="{{ old('nilai_barang', $transaksi->nilai_barang) }}"
                                    class="block w-full pl-12 pr-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all @error('nilai_barang') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('nilai_barang')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-400">Estimasi nilai barang dalam rupiah untuk pencatatan.</p>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            class="block w-full px-4 py-3 text-sm border border-gray-300 bg-white rounded-2xl focus:outline-none focus:border-primary focus:ring-0 transition-all resize-none"
                            placeholder="Catatan tambahan (opsional)...">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ==================== STEP 4: DOKUMENTASI ==================== --}}
            <div class="mb-8">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">4</span>
                    Dokumentasi
                    <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                </h3>

                <div class="space-y-6">
                    {{-- Foto Bukti Existing --}}
                    @if($transaksi->foto_bukti)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">Foto Bukti Saat Ini</p>
                        <img src="{{ Storage::url($transaksi->foto_bukti) }}" alt="Foto Bukti"
                             class="w-28 h-28 object-cover rounded-lg border border-gray-200 mb-2">
                        <p class="text-xs text-gray-500">Upload foto baru di bawah untuk mengganti foto ini.</p>
                    </div>
                    @endif

                    {{-- Upload Foto Bukti --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $transaksi->foto_bukti ? 'Ganti Foto Bukti' : 'Foto Bukti Penyaluran' }}
                        </label>
                        <div id="dropzoneFotoBukti"
                             class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-primary transition-colors cursor-pointer"
                             onclick="document.getElementById('fotoBukti').click()">
                            <input type="file" name="foto_bukti" id="fotoBukti"
                                   accept="image/*" class="hidden"
                                   onchange="previewFoto(this, 'previewFotoBukti')">
                            <img id="previewFotoBuktiImg" src="" alt=""
                                 class="hidden mx-auto max-h-32 rounded-lg mb-3 object-cover">
                            <div id="dropzoneFotoBuktiPlaceholder">
                                <p class="text-sm text-gray-500">Klik untuk memilih foto</p>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP &bull; Maks. 2MB</p>
                            </div>
                        </div>
                        @error('foto_bukti')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanda Tangan Existing --}}
                    @if($transaksi->path_tanda_tangan)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">Tanda Tangan Saat Ini</p>
                        <div class="bg-white border border-gray-200 rounded-lg p-3 inline-block mb-2">
                            <img src="{{ Storage::url($transaksi->path_tanda_tangan) }}" alt="Tanda Tangan"
                                 class="h-14 object-contain">
                        </div>
                        <p class="text-xs text-gray-500">Upload tanda tangan baru di bawah untuk mengganti.</p>
                    </div>
                    @endif

                    {{-- Upload Tanda Tangan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $transaksi->path_tanda_tangan ? 'Ganti Tanda Tangan' : 'Tanda Tangan Digital Mustahik' }}
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-primary transition-colors cursor-pointer"
                             onclick="document.getElementById('tandaTangan').click()">
                            <input type="file" name="tanda_tangan" id="tandaTangan"
                                   accept="image/*" class="hidden"
                                   onchange="previewFoto(this, 'previewTandaTangan')">
                            <img id="previewTandaTanganImg" src="" alt=""
                                 class="hidden mx-auto max-h-20 mb-3 object-contain">
                            <div id="dropzoneTtdPlaceholder">
                                <p class="text-sm text-gray-500">Upload tanda tangan mustahik</p>
                                <p class="text-xs text-gray-400 mt-1">Format gambar &bull; Maks. 2MB</p>
                            </div>
                        </div>
                        @error('tanda_tangan')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto Dokumentasi Existing --}}
                    @if($transaksi->dokumentasi->count() > 0)
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-3">
                            Foto Dokumentasi Saat Ini
                            <span class="text-xs text-gray-400 font-normal">(hover untuk hapus)</span>
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($transaksi->dokumentasi as $dok)
                            <div class="relative group">
                                <img src="{{ Storage::url($dok->path_foto) }}" alt="Dokumentasi"
                                     class="w-full aspect-square object-cover rounded-xl border border-gray-200">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-xl flex items-center justify-center">
                                    <form action="{{ route('transaksi-penyaluran.dokumentasi.destroy', $dok->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus foto dokumentasi ini?')"
                                          class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white rounded-full w-9 h-9 flex items-center justify-center shadow-lg transition-colors text-xs font-bold">
                                            X
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Upload Foto Dokumentasi Tambahan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Foto Dokumentasi</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-primary transition-colors cursor-pointer"
                             onclick="document.getElementById('fotoDokumentasi').click()">
                            <input type="file" name="foto_dokumentasi[]" id="fotoDokumentasi"
                                   accept="image/*" multiple class="hidden"
                                   onchange="previewDokumentasi(this)">
                            <div id="dokPlaceholder">
                                <p class="text-sm text-gray-500">Tambah foto dokumentasi</p>
                                <p class="text-xs text-gray-400 mt-1">Pilih beberapa foto sekaligus &bull; Maks. 2MB/foto</p>
                            </div>
                            <div id="previewDokGrid" class="hidden mt-3 grid grid-cols-3 gap-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col-reverse sm:flex-row gap-3 justify-end pt-2 border-t border-gray-100">
                <a href="{{ route('transaksi-penyaluran.show', $transaksi->uuid) }}"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium text-center">
                    Batal
                </a>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-primary/20 disabled:opacity-60 disabled:cursor-not-allowed">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL PILIH MUSTAHIK ==================== --}}
<div id="mustahikModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Overlay --}}
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" id="mustahikModalOverlay"></div>

    {{-- Panel --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                <h3 class="text-base font-semibold text-gray-900">Pilih Mustahik</h3>
                <button type="button" id="closeMustahikModal"
                    class="text-gray-400 hover:text-gray-600 transition-colors rounded-lg p-1 hover:bg-gray-100">
                    &times;
                </button>
            </div>

            {{-- Search --}}
            <div class="px-6 py-3 border-b border-gray-100 flex-shrink-0">
                <div class="relative">
                    <input type="text" id="searchMustahik"
                        placeholder="Cari nama atau NIK mustahik..."
                        class="w-full pl-4 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-0">
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-y-auto flex-1">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">NIK</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($mustahikList as $mustahik)
                        <tr class="hover:bg-primary-50 mustahik-row transition-colors"
                            data-nama="{{ strtolower($mustahik->nama_lengkap) }}"
                            data-nik="{{ $mustahik->nik ?? '' }}">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $mustahik->nama_lengkap }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $mustahik->nik ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $mustahik->kategoriMustahik->nama ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <button type="button"
                                    onclick="selectMustahik(
                                        {{ $mustahik->id }},
                                        '{{ addslashes($mustahik->nama_lengkap) }}',
                                        '{{ addslashes($mustahik->kategoriMustahik->nama ?? '') }}',
                                        '{{ $mustahik->kategori_mustahik_id ?? '' }}',
                                        '{{ $mustahik->nik ?? '' }}'
                                    )"
                                    class="px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-600 transition-colors">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400">
                                Belum ada mustahik terdaftar di masjid ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div id="noMustahikResult" class="hidden py-8 text-center text-sm text-gray-400">
                    Tidak ada mustahik yang sesuai dengan pencarian.
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex-shrink-0 text-right">
                <button type="button" id="closeMustahikModalFooter"
                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    'use strict';

    // ─── Modal Mustahik ───────────────────────────────────────────────────
    function openMustahikModal() {
        document.getElementById('mustahikModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('searchMustahik').focus();
    }

    function closeMustahikModal() {
        document.getElementById('mustahikModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Expose globally (dipanggil dari onclick inline)
    window.openMustahikModal = openMustahikModal;

    document.getElementById('closeMustahikModal').addEventListener('click', closeMustahikModal);
    document.getElementById('closeMustahikModalFooter').addEventListener('click', closeMustahikModal);
    document.getElementById('mustahikModalOverlay').addEventListener('click', closeMustahikModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMustahikModal();
    });

    // ─── Pilih Mustahik ───────────────────────────────────────────────────
    window.selectMustahik = function (id, nama, kategori, kategoriId, nik) {
        document.getElementById('mustahikInisial').textContent = nama.charAt(0).toUpperCase();
        document.getElementById('mustahikNama').textContent = nama;
        document.getElementById('mustahikKategori').textContent = kategori;
        document.getElementById('mustahikIdSelected').value = id;

        var nikEl = document.getElementById('mustahikNik');
        if (nik) {
            nikEl.textContent = 'NIK: ' + nik;
            nikEl.classList.remove('hidden');
        } else {
            nikEl.textContent = '';
            nikEl.classList.add('hidden');
        }

        var kategoriSelect = document.getElementById('kategoriMustahikId');
        for (var i = 0; i < kategoriSelect.options.length; i++) {
            if (kategoriSelect.options[i].value == kategoriId) {
                kategoriSelect.options[i].selected = true;
                break;
            }
        }

        document.getElementById('mustahikPreview').classList.remove('hidden');
        document.getElementById('mustahikEmptyState').classList.add('hidden');
        closeMustahikModal();
    };

    // ─── Search Mustahik ──────────────────────────────────────────────────
    document.getElementById('searchMustahik').addEventListener('input', function () {
        var term = this.value.toLowerCase().trim();
        var rows = document.querySelectorAll('.mustahik-row');
        var visibleCount = 0;

        rows.forEach(function (row) {
            var nama = row.dataset.nama || '';
            var nik  = row.dataset.nik  || '';
            var match = !term || nama.includes(term) || nik.includes(term);
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        document.getElementById('noMustahikResult').classList.toggle('hidden', visibleCount > 0);
    });

    // ─── Metode Penyaluran ────────────────────────────────────────────────
    window.onMetodeChange = function (val) {
        document.getElementById('sectionNominal').classList.toggle('hidden', val === 'barang');
        document.getElementById('sectionBarang').classList.toggle('hidden', val !== 'barang');
    };

    // ─── Preview Foto (tunggal) ───────────────────────────────────────────
    window.previewFoto = function (input, previewId) {
        var file = input.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB.');
            input.value = '';
            return;
        }

        var img = document.getElementById(previewId + 'Img');
        if (!img) return;

        var reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
            img.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    };

    // ─── Preview Dokumentasi (multiple) ──────────────────────────────────
    window.previewDokumentasi = function (input) {
        var grid = document.getElementById('previewDokGrid');
        var placeholder = document.getElementById('dokPlaceholder');
        if (!grid) return;

        grid.innerHTML = '';

        if (!input.files.length) {
            grid.classList.add('hidden');
            placeholder.classList.remove('hidden');
            return;
        }

        for (var i = 0; i < input.files.length; i++) {
            if (input.files[i].size > 2 * 1024 * 1024) {
                alert('Ukuran setiap foto maksimal 2MB.');
                input.value = '';
                grid.classList.add('hidden');
                placeholder.classList.remove('hidden');
                return;
            }
        }

        placeholder.classList.add('hidden');
        grid.classList.remove('hidden');

        Array.from(input.files).forEach(function (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full aspect-square object-cover rounded-lg border border-gray-200';
                grid.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    };

    // ─── Validasi & Submit ────────────────────────────────────────────────
    document.getElementById('formPenyaluran').addEventListener('submit', function (e) {
        var mustahikId = document.getElementById('mustahikIdSelected').value;
        if (!mustahikId) {
            e.preventDefault();
            alert('Mustahik harus dipilih terlebih dahulu.');
            return;
        }

        var metode = document.querySelector('input[name="metode_penyaluran"]:checked');
        if (!metode) {
            e.preventDefault();
            alert('Metode penyaluran harus dipilih.');
            return;
        }

        if (metode.value !== 'barang') {
            var jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
            if (jumlah <= 0) {
                e.preventDefault();
                alert('Jumlah nominal harus diisi dan lebih dari 0.');
                document.getElementById('jumlah').focus();
                return;
            }
        } else {
            var detail = document.querySelector('textarea[name="detail_barang"]');
            if (!detail || !detail.value.trim()) {
                e.preventDefault();
                alert('Detail barang harus diisi untuk metode penyaluran barang.');
                detail.focus();
                return;
            }
        }

        // Disable tombol agar tidak double-submit
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Menyimpan...';
    });

    // ─── Init metode saat halaman dimuat ──────────────────────────────────
    var checkedMetode = document.querySelector('input[name="metode_penyaluran"]:checked');
    if (checkedMetode) {
        window.onMetodeChange(checkedMetode.value);
    }

})();
</script>
@endpush
@endsection