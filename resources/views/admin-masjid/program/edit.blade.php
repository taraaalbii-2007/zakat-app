{{-- resources/views/admin-masjid/program/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Program Zakat')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-white">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Program Zakat</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi program zakat</p>
                
                {{-- Status Badge --}}
                <div class="mt-2">
                    {!! $program->status_badge !!}
                </div>
            </div>

            <form action="{{ route('program-zakat.update', $program->uuid) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                @csrf
                @method('PUT')

                {{-- Section 1 - Informasi Program --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">1</span>
                        Informasi Program
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- Kode Program (Read-only) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Program
                            </label>
                            <input type="text" value="{{ $program->kode_program }}" disabled
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl bg-gray-50 cursor-not-allowed shadow-inner">
                        </div>

                        {{-- Nama Program --}}
                        <div>
                            <label for="nama_program" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Program <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_program" id="nama_program" value="{{ old('nama_program', $program->nama_program) }}"
                                placeholder="Contoh: Program Zakat Fitrah Ramadhan 1446 H"
                                maxlength="255"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('nama_program') border-red-500 @enderror">
                            @error('nama_program')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Program
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                placeholder="Jelaskan tujuan dan detail program zakat ini..."
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $program->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Periode Program --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $program->tanggal_mulai?->format('Y-m-d')) }}"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_mulai') border-red-500 @enderror">
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Selesai (Opsional)
                                </label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $program->tanggal_selesai?->format('Y-m-d')) }}"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_selesai') border-red-500 @enderror">
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2 - Target Program --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">2</span>
                        Target Program
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            {{-- Target Dana --}}
                            <div>
                                <label for="target_dana" class="block text-sm font-medium text-gray-700 mb-2">
                                    Target Dana (Rp)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                    <input type="number" name="target_dana" id="target_dana" value="{{ old('target_dana', $program->target_dana) }}"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                        class="block w-full pl-10 pr-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('target_dana') border-red-500 @enderror">
                                </div>
                                @error('target_dana')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Target Mustahik --}}
                            <div>
                                <label for="target_mustahik" class="block text-sm font-medium text-gray-700 mb-2">
                                    Target Jumlah Mustahik
                                </label>
                                <input type="number" name="target_mustahik" id="target_mustahik" value="{{ old('target_mustahik', $program->target_mustahik) }}"
                                    placeholder="0"
                                    min="0"
                                    class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('target_mustahik') border-red-500 @enderror">
                                @error('target_mustahik')
                                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Progress Info --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <h4 class="text-sm font-medium text-blue-900 mb-3">Progress Saat Ini</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-blue-700 mb-1">Realisasi Dana</p>
                                    <p class="text-lg font-semibold text-blue-900">Rp {{ number_format($program->realisasi_dana, 0, ',', '.') }}</p>
                                    @if($program->target_dana)
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between text-xs text-blue-700 mb-1">
                                                <span>Progress</span>
                                                <span class="font-medium">{{ $program->progress_dana }}%</span>
                                            </div>
                                            <div class="w-full bg-blue-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $program->progress_dana }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-blue-700 mb-1">Realisasi Mustahik</p>
                                    <p class="text-lg font-semibold text-blue-900">{{ $program->realisasi_mustahik }} Orang</p>
                                    @if($program->target_mustahik)
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between text-xs text-blue-700 mb-1">
                                                <span>Progress</span>
                                                <span class="font-medium">{{ $program->progress_mustahik }}%</span>
                                            </div>
                                            <div class="w-full bg-blue-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $program->progress_mustahik }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3 - Foto Kegiatan --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">3</span>
                        Foto Kegiatan
                    </h3>
                    
                    {{-- Existing Photos --}}
                    @if($program->foto_kegiatan && count($program->foto_kegiatan) > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Foto yang Ada</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($program->foto_kegiatan as $index => $foto)
                                    <div class="relative group" id="foto-{{ $index }}">
                                        <img src="{{ Storage::url($foto) }}" alt="Foto {{ $index + 1 }}" 
                                            class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                        <button type="button" onclick="deleteFoto('{{ $program->uuid }}', {{ $index }})"
                                            class="absolute top-2 right-2 p-1.5 bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upload New Photos --}}
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Upload Foto Tambahan
                            </div>
                        </label>
                        <div class="space-y-3">
                            <div class="min-h-[120px] p-4 bg-white rounded-lg border-2 border-dashed border-gray-300 hover:border-primary/50 transition-colors">
                                <input type="file" name="foto_kegiatan[]" id="foto_kegiatan" accept="image/jpeg,image/png,image/jpg"
                                    multiple class="hidden" onchange="handleMultipleFiles(this)">
                                <label for="foto_kegiatan"
                                    class="flex flex-col items-center justify-center cursor-pointer py-4">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="text-sm text-gray-600 font-medium">Klik untuk upload</span>
                                    <span class="text-xs text-gray-500 mt-1">atau drag & drop</span>
                                </label>
                                <div id="foto-list" class="mt-3 space-y-2"></div>
                            </div>
                            <p class="text-xs text-gray-500">JPG, PNG. Maks 2MB/file. Maksimal 10 foto total</p>
                            @error('foto_kegiatan')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            @error('foto_kegiatan.*')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 4 - Catatan & Status --}}
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs mr-2">4</span>
                        Catatan & Status
                    </h3>
                    <div class="space-y-4 sm:space-y-6">
                        {{-- Catatan --}}
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Pelaksanaan
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                placeholder="Catatan tambahan mengenai pelaksanaan program..."
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('catatan') border-red-500 @enderror">{{ old('catatan', $program->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Program <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="block w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('status') border-red-500 @enderror">
                                <option value="draft" {{ old('status', $program->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="aktif" {{ old('status', $program->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ old('status', $program->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ old('status', $program->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                    <a href="{{ route('program-zakat.show', $program->uuid) }}"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');

            tanggalMulai.addEventListener('change', function() {
                tanggalSelesai.min = this.value;
                if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                    tanggalSelesai.value = this.value;
                }
            });

            // Set initial min value
            if (tanggalMulai.value) {
                tanggalSelesai.min = tanggalMulai.value;
            }
        });

        function handleMultipleFiles(input) {
            const container = document.getElementById('foto-list');
            container.innerHTML = '';
            
            const existingCount = {{ $program->foto_kegiatan ? count($program->foto_kegiatan) : 0 }};
            const maxTotal = 10;
            
            if (input.files.length + existingCount > maxTotal) {
                alert(`Maksimal ${maxTotal} foto total. Anda sudah memiliki ${existingCount} foto.`);
                input.value = '';
                return;
            }
            
            Array.from(input.files).forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} melebihi 2MB`);
                    input.value = '';
                    container.innerHTML = '';
                    return;
                }
                
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-200';
                div.innerHTML = `
                    <div class="flex items-center space-x-2 flex-1 min-w-0">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-gray-700 truncate">${file.name}</span>
                    </div>
                    <span class="text-xs text-gray-500 ml-2 flex-shrink-0">${(file.size / 1024).toFixed(1)} KB</span>
                `;
                container.appendChild(div);
            });
        }

        function deleteFoto(uuid, index) {
            if (!confirm('Hapus foto ini?')) return;

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch(`/program-zakat/${uuid}/foto/${index}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`foto-${index}`).remove();
                    showToast('Foto berhasil dihapus', 'success');
                } else {
                    showToast('Gagal menghapus foto', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            });
        }

        function showToast(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
            };
            
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 ${colors[type]} text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slide-in-right`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endpush