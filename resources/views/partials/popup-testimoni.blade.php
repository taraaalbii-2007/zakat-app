{{-- resources/views/partials/popup-testimoni.blade.php --}}
@auth
    @if(auth()->user()->peran === 'muzakki')
    <div id="popupTestimoni"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" id="popupTestimoniBox">

            {{-- Header compact --}}
            <div class="px-5 py-4 bg-primary text-white relative">
                <button type="button" onclick="tutupPopupTestimoni()"
                    class="absolute top-3 right-3 w-7 h-7 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="pr-8">
                    <h3 class="text-sm font-bold leading-tight">Bagikan Pengalaman Anda</h3>
                    <p class="text-xs text-primary-100 mt-0.5">Bantu muzakki lain dengan cerita Anda</p>
                </div>
            </div>

            {{-- Form compact --}}
            <div id="popupFormWrapper" class="p-5 space-y-3.5">

                {{-- Nama + Pekerjaan dalam satu baris --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama <span class="text-red-400">*</span></label>
                        <input type="text" id="popupNama"
                            value="{{ auth()->user()?->muzakki?->nama }}"
                            class="w-full px-3 py-2 text-xs border border-gray-200 bg-gray-50 rounded-lg focus:outline-none focus:border-primary-500 focus:bg-white transition-all"
                            placeholder="Nama Anda">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Pekerjaan <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" id="popupPekerjaan"
                            class="w-full px-3 py-2 text-xs border border-gray-200 bg-gray-50 rounded-lg focus:outline-none focus:border-primary-500 focus:bg-white transition-all"
                            placeholder="Misal: Guru">
                    </div>
                </div>

                {{-- Rating bintang interaktif --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Rating <span class="text-red-400">*</span></label>
                    <div class="flex items-center gap-1.5">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}"
                                class="popup-star-btn group focus:outline-none transition-transform hover:scale-110 active:scale-95"
                                onclick="setPopupRating({{ $i }})">
                                <svg class="w-8 h-8 popup-star-icon text-gray-200 fill-current drop-shadow-sm transition-all duration-150" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        @endfor
                        <span id="popupRatingLabel" class="ml-2 text-xs font-semibold text-primary-600 min-w-16"></span>
                    </div>
                    <input type="hidden" id="popupRatingInput" value="">
                </div>

                {{-- Cerita --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-gray-600">Cerita Singkat <span class="text-red-400">*</span></label>
                        <span class="text-xs text-gray-400"><span id="popupCharCount">0</span>/300</span>
                    </div>
                    <textarea id="popupIsi" rows="3" maxlength="300"
                        class="w-full px-3 py-2.5 text-xs border border-gray-200 bg-gray-50 rounded-lg focus:outline-none focus:border-primary-500 focus:bg-white transition-all resize-none leading-relaxed"
                        placeholder="Ceritakan pengalaman Anda berzakat di sini..."></textarea>
                </div>

                <p id="popupErrMsg" class="hidden text-xs text-red-500 bg-red-50 px-3 py-2 rounded-lg border border-red-200"></p>

                {{-- Tombol --}}
                <div class="flex items-center gap-2.5 pt-1">
                    <button type="button" onclick="tutupPopupTestimoni()"
                        class="flex-1 py-2.5 border border-gray-200 text-gray-500 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-all">
                        Nanti Saja
                    </button>
                    <button type="button" onclick="submitPopupTestimoni()"
                        id="popupSubmitBtn"
                        class="flex-[2] inline-flex items-center justify-center gap-1.5 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-600 active:scale-95 transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Kirim Testimoni
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var STORAGE_KEY = 'nz_testimoni_popup_dismissed_{{ auth()->id() }}';
        if (localStorage.getItem(STORAGE_KEY)) return;

        document.addEventListener('DOMContentLoaded', function () {
            fetch('{{ route("muzakki.testimoni.check-popup") }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.show_popup) setTimeout(bukaPopupTestimoni, 1500);
            })
            .catch(function() {});
        });
    })();

    var popupCurrentRating = 0;
    var popupRatingLabels  = ['', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik', 'Luar Biasa!'];

    function bukaPopupTestimoni() {
        var el = document.getElementById('popupTestimoni');
        el.classList.remove('hidden');
        el.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function tutupPopupTestimoni() {
        var el = document.getElementById('popupTestimoni');
        el.style.display = 'none';
        el.classList.add('hidden');
        document.body.style.overflow = '';
        localStorage.setItem('nz_testimoni_popup_dismissed_{{ auth()->id() }}', '1');
    }

    function setPopupRating(val) {
        popupCurrentRating = val;
        document.getElementById('popupRatingInput').value = val;
        document.getElementById('popupRatingLabel').textContent = popupRatingLabels[val] || '';
        renderPopupStars(val);
    }

    function renderPopupStars(val) {
        document.querySelectorAll('.popup-star-icon').forEach(function (star, idx) {
            if (idx < val) {
                star.classList.remove('text-gray-200');
                star.classList.add('text-yellow-400');
                star.style.filter = 'drop-shadow(0 0 3px rgba(250,204,21,0.6))';
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-200');
                star.style.filter = '';
            }
        });
    }

    document.querySelectorAll('.popup-star-btn').forEach(function (btn) {
        btn.addEventListener('mouseenter', function () { renderPopupStars(parseInt(btn.dataset.value)); });
        btn.addEventListener('mouseleave', function () { renderPopupStars(popupCurrentRating); });
    });

    var popupTextarea = document.getElementById('popupIsi');
    var popupCounter  = document.getElementById('popupCharCount');
    if (popupTextarea) {
        popupTextarea.addEventListener('input', function () {
            popupCounter.textContent = popupTextarea.value.length;
        });
    }

    function submitPopupTestimoni() {
        var nama   = document.getElementById('popupNama').value.trim();
        var isi    = document.getElementById('popupIsi').value.trim();
        var rating = document.getElementById('popupRatingInput').value;
        var errEl  = document.getElementById('popupErrMsg');

        errEl.classList.add('hidden');
        if (!nama)           { errEl.textContent = 'Nama wajib diisi.'; errEl.classList.remove('hidden'); return; }
        if (!rating)         { errEl.textContent = 'Pilih rating bintang terlebih dahulu.'; errEl.classList.remove('hidden'); return; }
        if (isi.length < 10) { errEl.textContent = 'Cerita minimal 10 karakter.'; errEl.classList.remove('hidden'); return; }

        var btn = document.getElementById('popupSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Mengirim...';

        var formData = new FormData();
        formData.append('nama_pengirim', nama);
        formData.append('pekerjaan', document.getElementById('popupPekerjaan').value.trim());
        formData.append('isi_testimoni', isi);
        formData.append('rating', rating);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

        fetch('{{ route("muzakki.testimoni.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                document.getElementById('popupFormWrapper').innerHTML =
                    '<div class="py-8 text-center">' +
                        '<div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">' +
                            '<svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>' +
                            '</svg>' +
                        '</div>' +
                        '<p class="text-sm font-bold text-gray-900 mb-1">Terima Kasih!</p>' +
                        '<p class="text-xs text-gray-500">Testimoni Anda berhasil dikirim.</p>' +
                    '</div>';
                localStorage.setItem('nz_testimoni_popup_dismissed_{{ auth()->id() }}', '1');
                setTimeout(tutupPopupTestimoni, 2000);
            } else {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Kirim Testimoni';
                errEl.textContent = data.message || 'Terjadi kesalahan. Coba lagi.';
                errEl.classList.remove('hidden');
            }
        })
        .catch(function() {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Kirim Testimoni';
            errEl.textContent = 'Gagal terhubung ke server. Coba lagi.';
            errEl.classList.remove('hidden');
        });
    }
    </script>
    @endif
@endauth