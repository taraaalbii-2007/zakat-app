/**
 * Wilayah Indonesia Cascading Dropdown
 * Untuk form complete profile dan form masjid
 */

class WilayahIndonesia {
    constructor(config = {}) {
        this.selectors = {
            provinsi: config.provinsiSelector || '#provinsi_kode',
            kota: config.kotaSelector || '#kota_kode',
            kecamatan: config.kecamatanSelector || '#kecamatan_kode',
            kelurahan: config.kelurahanSelector || '#kelurahan_kode',
            kodePos: config.kodePosSelector || '#kode_pos'
        };

        this.elements = {
            provinsi: document.querySelector(this.selectors.provinsi),
            kota: document.querySelector(this.selectors.kota),
            kecamatan: document.querySelector(this.selectors.kecamatan),
            kelurahan: document.querySelector(this.selectors.kelurahan),
            kodePos: document.querySelector(this.selectors.kodePos)
        };

        this.apiUrls = {
            cities: '/api/cities/',
            districts: '/api/districts/',
            villages: '/api/villages/',
            postalCode: '/api/postal-code/'
        };

        this.init();
    }

    init() {
        if (!this.elements.provinsi) {
            console.error('Provinsi select not found');
            return;
        }

        this.attachEventListeners();
        console.log('✅ Wilayah Indonesia initialized');
    }

    attachEventListeners() {
        // Event: Provinsi berubah
        this.elements.provinsi.addEventListener('change', (e) => {
            const provinceCode = e.target.value;
            console.log('Provinsi changed:', provinceCode);

            if (provinceCode) {
                this.loadCities(provinceCode);
            } else {
                this.resetKota();
                this.resetKecamatan();
                this.resetKelurahan();
                this.resetKodePos();
            }
        });

        // Event: Kota berubah
        this.elements.kota.addEventListener('change', (e) => {
            const cityCode = e.target.value;
            console.log('Kota changed:', cityCode);

            if (cityCode) {
                this.loadDistricts(cityCode);
            } else {
                this.resetKecamatan();
                this.resetKelurahan();
                this.resetKodePos();
            }
        });

        // Event: Kecamatan berubah
        this.elements.kecamatan.addEventListener('change', (e) => {
            const districtCode = e.target.value;
            console.log('Kecamatan changed:', districtCode);

            if (districtCode) {
                this.loadVillages(districtCode);
            } else {
                this.resetKelurahan();
                this.resetKodePos();
            }
        });

        // Event: Kelurahan berubah
        this.elements.kelurahan.addEventListener('change', (e) => {
            const villageCode = e.target.value;
            console.log('Kelurahan changed:', villageCode);

            if (villageCode && this.elements.kodePos) {
                this.loadPostalCode(villageCode);
            }
        });
    }

    async loadCities(provinceCode) {
        try {
            this.setLoading(this.elements.kota, true);
            this.resetKota();
            this.resetKecamatan();
            this.resetKelurahan();
            this.resetKodePos();

            const response = await fetch(`${this.apiUrls.cities}${provinceCode}`);
            const result = await response.json();

            if (result.success && result.data) {
                this.populateSelect(this.elements.kota, result.data, 'Pilih Kota/Kabupaten');
                this.setLoading(this.elements.kota, false);
                console.log(`✅ Loaded ${result.data.length} cities`);
            } else {
                throw new Error(result.message || 'Failed to load cities');
            }
        } catch (error) {
            console.error('Load cities error:', error);
            this.setLoading(this.elements.kota, false);
            this.showError('Gagal memuat data kota/kabupaten');
        }
    }

    async loadDistricts(cityCode) {
        try {
            this.setLoading(this.elements.kecamatan, true);
            this.resetKecamatan();
            this.resetKelurahan();
            this.resetKodePos();

            const response = await fetch(`${this.apiUrls.districts}${cityCode}`);
            const result = await response.json();

            if (result.success && result.data) {
                this.populateSelect(this.elements.kecamatan, result.data, 'Pilih Kecamatan');
                this.setLoading(this.elements.kecamatan, false);
                console.log(`✅ Loaded ${result.data.length} districts`);
            } else {
                throw new Error(result.message || 'Failed to load districts');
            }
        } catch (error) {
            console.error('Load districts error:', error);
            this.setLoading(this.elements.kecamatan, false);
            this.showError('Gagal memuat data kecamatan');
        }
    }

    async loadVillages(districtCode) {
        try {
            this.setLoading(this.elements.kelurahan, true);
            this.resetKelurahan();
            this.resetKodePos();

            const response = await fetch(`${this.apiUrls.villages}${districtCode}`);
            const result = await response.json();

            if (result.success && result.data) {
                this.populateSelect(this.elements.kelurahan, result.data, 'Pilih Kelurahan/Desa');
                this.setLoading(this.elements.kelurahan, false);
                console.log(`✅ Loaded ${result.data.length} villages`);
            } else {
                throw new Error(result.message || 'Failed to load villages');
            }
        } catch (error) {
            console.error('Load villages error:', error);
            this.setLoading(this.elements.kelurahan, false);
            this.showError('Gagal memuat data kelurahan/desa');
        }
    }

    async loadPostalCode(villageCode) {
        if (!this.elements.kodePos) return;

        try {
            const response = await fetch(`${this.apiUrls.postalCode}${villageCode}`);
            const result = await response.json();

            if (result.success && result.data && result.data.postal_code) {
                this.elements.kodePos.value = result.data.postal_code;
                console.log(`✅ Postal code: ${result.data.postal_code}`);
            } else {
                this.elements.kodePos.value = '';
            }
        } catch (error) {
            console.error('Load postal code error:', error);
        }
    }

    populateSelect(selectElement, data, placeholder = 'Pilih...') {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.code;
            option.textContent = item.name;
            selectElement.appendChild(option);
        });

        selectElement.disabled = false;
    }

    resetKota() {
        this.elements.kota.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        this.elements.kota.disabled = true;
    }

    resetKecamatan() {
        this.elements.kecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
        this.elements.kecamatan.disabled = true;
    }

    resetKelurahan() {
        this.elements.kelurahan.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        this.elements.kelurahan.disabled = true;
    }

    resetKodePos() {
        if (this.elements.kodePos) {
            this.elements.kodePos.value = '';
        }
    }

    setLoading(selectElement, isLoading) {
        if (isLoading) {
            selectElement.disabled = true;
            selectElement.innerHTML = '<option value="">Loading...</option>';
        }
    }

    showError(message) {
        alert(message);
        // Atau gunakan notification library seperti SweetAlert, Toastr, dll
    }
}

// Auto-initialize saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah ada form dengan wilayah indonesia
    const provinsiSelect = document.querySelector('#provinsi_kode');
    
    if (provinsiSelect) {
        window.wilayahIndonesia = new WilayahIndonesia();
    }
});