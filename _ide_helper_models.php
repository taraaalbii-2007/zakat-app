<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int|null $pengguna_id
 * @property int $masjid_id
 * @property string $nama_lengkap
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property string $alamat
 * @property string $telepon
 * @property string $email
 * @property string|null $foto
 * @property string|null $tanda_tangan Path file gambar tanda tangan amil di storage/public/amil/ttd
 * @property string $kode_amil
 * @property \Illuminate\Support\Carbon $tanggal_mulai_tugas
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai_tugas
 * @property string $status
 * @property string|null $keterangan
 * @property string|null $wilayah_tugas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $foto_url
 * @property-read string $initial
 * @property-read string $jenis_kelamin_label
 * @property-read mixed $masa_tugas
 * @property-read string|null $tanda_tangan_url
 * @property-read mixed $umur
 * @property-read \App\Models\Masjid $masjid
 * @property-read \App\Models\Pengguna|null $pengguna
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaksiPenerimaan> $transaksiPenerimaan
 * @property-read int|null $transaksi_penerimaan_count
 * @method static \Illuminate\Database\Eloquent\Builder|Amil aktif()
 * @method static \Illuminate\Database\Eloquent\Builder|Amil byMasjid($masjidId = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Amil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Amil query()
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereKodeAmil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil wherePenggunaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereTandaTangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereTanggalMulaiTugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereTanggalSelesaiTugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Amil whereWilayahTugas($value)
 */
	class Amil extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $transaksi_penyaluran_id
 * @property string $path_foto Path file foto dokumentasi
 * @property string|null $keterangan_foto Deskripsi singkat foto ini
 * @property int $urutan Urutan tampil foto, 0 = pertama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $foto_url
 * @property-read \App\Models\TransaksiPenyaluran $transaksi
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran query()
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran whereKeteranganFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran wherePathFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran whereTransaksiPenyaluranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DokumentasiPenyaluran whereUrutan($value)
 */
	class DokumentasiPenyaluran extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $GOOGLE_CLIENT_ID
 * @property string|null $GOOGLE_CLIENT_SECRET
 * @property string|null $GOOGLE_REDIRECT_URI
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KonfigurasiAplikasi|null $konfigurasiAplikasi
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig whereGOOGLECLIENTID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig whereGOOGLECLIENTSECRET($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig whereGOOGLEREDIRECTURI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoogleConfig whereUpdatedAt($value)
 */
	class GoogleConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $harga_emas_pergram
 * @property string $harga_perak_pergram
 * @property string|null $sumber
 * @property string|null $keterangan
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_emas
 * @property-read mixed $formatted_perak
 * @property-read mixed $formatted_tanggal
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak active()
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak query()
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereHargaEmasPergram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereHargaPerakPergram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereSumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HargaEmasPerak whereUuid($value)
 */
	class HargaEmasPerak extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat active()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat query()
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JenisZakat whereUuid($value)
 */
	class JenisZakat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $amil_id
 * @property int $masjid_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $saldo_awal
 * @property string $total_penerimaan
 * @property string $total_penyaluran
 * @property string $saldo_akhir
 * @property int $jumlah_transaksi_masuk
 * @property int $jumlah_transaksi_keluar
 * @property int $jumlah_penjemputan
 * @property int $jumlah_datang_langsung
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $closed_at
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Amil $amil
 * @property-read bool $is_closed
 * @property-read bool $is_open
 * @property-read string $saldo_akhir_formatted
 * @property-read string $saldo_awal_formatted
 * @property-read string $status_badge
 * @property-read string $total_penerimaan_formatted
 * @property-read string $total_penyaluran_formatted
 * @property-read \App\Models\Masjid $masjid
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil byAmil(int $amilId)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil byMasjid(int $masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil byPeriode(string $start, string $end)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil byStatus(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil byTanggal($tanggal)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil closed()
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil open()
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil query()
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereAmilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereJumlahDatangLangsung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereJumlahPenjemputan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereJumlahTransaksiKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereJumlahTransaksiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereSaldoAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereSaldoAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereTotalPenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereTotalPenyaluran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KasHarianAmil whereUuid($value)
 */
	class KasHarianAmil extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $nama
 * @property string|null $kriteria
 * @property float|null $persentase_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $persentase_formatted
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik query()
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik whereKriteria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik wherePersentaseDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriMustahik whereUuid($value)
 */
	class KategoriMustahik extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $nama_aplikasi
 * @property string|null $tagline
 * @property string|null $deskripsi_aplikasi
 * @property string $versi
 * @property string|null $logo_aplikasi
 * @property string|null $favicon
 * @property string|null $email_admin
 * @property string|null $telepon_admin
 * @property string|null $alamat_kantor
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $twitter_url
 * @property string|null $youtube_url
 * @property string|null $whatsapp_support
 * @property bool $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $telepon_formatted
 * @property-read mixed $whatsapp_link
 * @property-read \App\Models\GoogleConfig|null $googleConfig
 * @property-read \App\Models\MailConfig|null $mailConfig
 * @property-read \App\Models\RecaptchaConfig|null $recaptchaConfig
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi query()
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereAlamatKantor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereDeskripsiAplikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereEmailAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereFacebookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereFavicon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereInstagramUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereLogoAplikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereNamaAplikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereTagline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereTeleponAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereTwitterUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereVersi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereWhatsappSupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiAplikasi whereYoutubeUrl($value)
 */
	class KonfigurasiAplikasi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $masjid_id
 * @property string|null $api_key API Key WhatsApp
 * @property string|null $nomor_pengirim Nomor WhatsApp Pengirim
 * @property string $api_url URL API WhatsApp
 * @property string|null $nomor_tujuan_default Nomor WhatsApp tujuan default untuk notifikasi
 * @property bool $is_active Status aktif WhatsApp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_nomor_pengirim
 * @property-read mixed $status_badge_class
 * @property-read mixed $status_label
 * @property-read \App\Models\Masjid $masjid
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp active()
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp byMasjid($masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp query()
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereApiUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereNomorPengirim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereNomorTujuanDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KonfigurasiWhatsapp whereUpdatedAt($value)
 */
	class KonfigurasiWhatsapp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $amil_id
 * @property int $mustahik_id
 * @property \Illuminate\Support\Carbon $tanggal_kunjungan
 * @property string|null $waktu_mulai
 * @property string|null $waktu_selesai
 * @property string $tujuan
 * @property string|null $hasil_kunjungan
 * @property array|null $foto_dokumentasi
 * @property string $status
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Amil $amil
 * @property-read array $foto_dokumentasi_urls
 * @property-read string $status_badge
 * @property-read string $status_color
 * @property-read string $tujuan_color
 * @property-read string $tujuan_label
 * @property-read string $waktu_format
 * @property-read \App\Models\Mustahik $mustahik
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik byAmil($amilId)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik byTanggal($dari, $sampai = null)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik byTujuan($tujuan)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik direncanakan()
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik query()
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik selesai()
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereAmilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereFotoDokumentasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereHasilKunjungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereMustahikId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereTanggalKunjungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KunjunganMustahik whereWaktuSelesai($value)
 */
	class KunjunganMustahik extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $masjid_id
 * @property int $tahun
 * @property int $bulan
 * @property \Illuminate\Support\Carbon $periode_mulai
 * @property \Illuminate\Support\Carbon $periode_selesai
 * @property string $saldo_awal
 * @property string $total_penerimaan
 * @property string $total_penyaluran
 * @property string $saldo_akhir
 * @property array|null $detail_penerimaan
 * @property array|null $detail_penyaluran
 * @property int $jumlah_muzakki
 * @property int $jumlah_mustahik
 * @property int $jumlah_transaksi_masuk
 * @property int $jumlah_transaksi_keluar
 * @property string $status
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pengguna|null $creator
 * @property-read bool $can_generate
 * @property-read bool $can_publish
 * @property-read string $nama_bulan
 * @property-read string $periode
 * @property-read string $status_badge
 * @property-read \App\Models\Masjid $masjid
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid filterTahun($tahun = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid forMasjid($masjidId = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid published()
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid query()
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereDetailPenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereDetailPenyaluran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereJumlahMustahik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereJumlahMuzakki($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereJumlahTransaksiKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereJumlahTransaksiMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid wherePeriodeMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid wherePeriodeSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereSaldoAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereSaldoAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereTotalPenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereTotalPenyaluran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaporanKeuanganMasjid whereUuid($value)
 */
	class LaporanKeuanganMasjid extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int|null $pengguna_id
 * @property string $peran
 * @property string $aktivitas
 * @property string $modul
 * @property string|null $deskripsi
 * @property array|null $data_lama
 * @property array|null $data_baru
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $badge_color
 * @property-read mixed $email_pengguna
 * @property-read mixed $formatted_aktivitas
 * @property-read mixed $formatted_modul
 * @property-read mixed $nama_pengguna
 * @property-read \App\Models\Pengguna|null $pengguna
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas byAktivitas($aktivitas)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas byModul($modul)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas byPeran($peran)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas byTanggal($tanggal)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas query()
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas search($search)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereAktivitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereDataBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereDataLama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereModul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas wherePenggunaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas wherePeran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogAktivitas whereUuid($value)
 */
	class LogAktivitas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $MAIL_MAILER
 * @property string|null $MAIL_HOST
 * @property string $MAIL_PORT
 * @property string|null $MAIL_USERNAME
 * @property string|null $MAIL_PASSWORD
 * @property string|null $MAIL_ENCRYPTION
 * @property string|null $MAIL_FROM_ADDRESS
 * @property string|null $MAIL_FROM_NAME
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $mail_password
 * @property-read \App\Models\KonfigurasiAplikasi|null $konfigurasiAplikasi
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereMAILENCRYPTION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereMAILFROMADDRESS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereMAILFROMNAME($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereMAILHOST($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereMAILMAILER($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereMAILPASSWORD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereMAILPORT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereMAILUSERNAME($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailConfig whereUpdatedAt($value)
 */
	class MailConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string|null $admin_nama
 * @property string|null $admin_telepon
 * @property string|null $admin_email
 * @property string|null $admin_foto
 * @property string|null $sejarah
 * @property int|null $tahun_berdiri
 * @property string|null $pendiri
 * @property int|null $kapasitas_jamaah
 * @property string $nama
 * @property string $kode_masjid
 * @property string $alamat
 * @property string|null $provinsi_kode
 * @property string|null $kota_kode
 * @property string|null $kecamatan_kode
 * @property string|null $kelurahan_kode
 * @property string|null $provinsi_nama
 * @property string|null $kota_nama
 * @property string|null $kecamatan_nama
 * @property string|null $kelurahan_nama
 * @property string|null $kode_pos
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $deskripsi
 * @property array|null $foto
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Amil> $amils
 * @property-read int|null $amils_count
 * @property-read \Laravolt\Indonesia\Models\City|null $city
 * @property-read \Laravolt\Indonesia\Models\District|null $district
 * @property-read mixed $admin_foto_url
 * @property-read mixed $alamat_lengkap
 * @property-read mixed $foto_count
 * @property-read mixed $foto_urls
 * @property-read mixed $foto_utama_url
 * @property-read mixed $tahun_berdiri_formatted
 * @property-read mixed $usia_masjid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mustahik> $mustahiks
 * @property-read int|null $mustahiks_count
 * @property-read \Laravolt\Indonesia\Models\Province|null $province
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaksiPenerimaan> $transaksiPenerimaan
 * @property-read int|null $transaksi_penerimaan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaksiPenyaluran> $transaksiPenyaluran
 * @property-read int|null $transaksi_penyaluran_count
 * @property-read \Laravolt\Indonesia\Models\Village|null $village
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid aktif()
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid berdiriSebelum($tahun)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid berdiriSetelah($tahun)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid byKota($kotaKode)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid byProvinsi($provinsiKode)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid byTahunBerdiri($tahun)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid query()
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid search($term)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereAdminEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereAdminFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereAdminNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereAdminTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKapasitasJamaah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKecamatanKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKecamatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKelurahanKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKelurahanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKodeMasjid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKotaKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereKotaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid wherePendiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereProvinsiKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereProvinsiNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereSejarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereTahunBerdiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Masjid whereUuid($value)
 */
	class Masjid extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $masjid_id
 * @property int $kategori_mustahik_id
 * @property string $no_registrasi
 * @property string|null $nik
 * @property string|null $kk
 * @property string $nama_lengkap
 * @property string $jenis_kelamin
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string|null $tempat_lahir
 * @property string|null $telepon
 * @property string $alamat
 * @property string|null $provinsi_kode
 * @property string|null $kota_kode
 * @property string|null $kecamatan_kode
 * @property string|null $kelurahan_kode
 * @property string|null $rt_rw
 * @property string|null $kode_pos
 * @property string|null $pekerjaan
 * @property string|null $penghasilan_perbulan
 * @property int $jumlah_tanggungan
 * @property string|null $status_rumah
 * @property string|null $kondisi_kesehatan
 * @property string|null $catatan
 * @property string|null $foto_ktp
 * @property string|null $foto_kk
 * @property string|null $foto_rumah
 * @property array|null $dokumen_lainnya
 * @property string $status_verifikasi
 * @property string|null $alasan_penolakan
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $tanggal_registrasi
 * @property \Illuminate\Support\Carbon|null $tanggal_nonaktif
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pengguna|null $creator
 * @property-read mixed $actions
 * @property-read mixed $active_badge
 * @property-read mixed $gender_label
 * @property-read mixed $status_badge
 * @property-read mixed $status_rumah_label
 * @property-read \App\Models\KategoriMustahik $kategoriMustahik
 * @property-read \App\Models\Masjid $masjid
 * @property-read \App\Models\Pengguna|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik active()
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik byKategori($kategoriId)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik byMasjid($masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik search($term)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereAlasanPenolakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereDokumenLainnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereFotoKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereFotoKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereFotoRumah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereJumlahTanggungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereKategoriMustahikId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereKecamatanKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereKelurahanKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereKondisiKesehatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereKotaKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereNoRegistrasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik wherePekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik wherePenghasilanPerbulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereProvinsiKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereRtRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereStatusRumah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereStatusVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereTanggalNonaktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereTanggalRegistrasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mustahik whereVerifiedBy($value)
 */
	class Mustahik extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $peran
 * @property int|null $masjid_id
 * @property string|null $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $google_id
 * @property string|null $google_token
 * @property string|null $refresh_token
 * @property bool $is_active
 * @property string|null $verification_token
 * @property \Illuminate\Support\Carbon|null $verification_token_expires_at
 * @property string|null $password_reset_token
 * @property \Illuminate\Support\Carbon|null $password_reset_token_expires_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Amil|null $amil
 * @property-read string $display_name
 * @property-read bool $is_email_verified
 * @property-read bool $is_google_user
 * @property-read string|null $masjid_name
 * @property-read string $nama
 * @property-read string $nama_lengkap
 * @property-read string $role_name
 * @property-read string $status_badge
 * @property-read string $status_text
 * @property-read \App\Models\Masjid|null $masjid
 * @property-read \App\Models\Masjid|null $masjidAsAmil
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna active()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna adminMasjids()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna amils()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna byMasjid($masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna googleUsers()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna nonGoogleUsers()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna role(string $role)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna search(?string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna superadmins()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna unverified()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna verified()
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereGoogleToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna wherePasswordResetToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna wherePasswordResetTokenExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna wherePeran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pengguna whereVerificationTokenExpiresAt($value)
 */
	class Pengguna extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $masjid_id
 * @property string $nama_program
 * @property string $kode_program
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai
 * @property string|null $target_dana
 * @property int|null $target_mustahik
 * @property float $realisasi_dana
 * @property int $realisasi_mustahik
 * @property string $status
 * @property string|null $catatan
 * @property array|null $foto_kegiatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $progress_dana
 * @property-read float $progress_mustahik
 * @property-read string $status_badge
 * @property-read \App\Models\Masjid $masjid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaksiPenerimaan> $transaksiPenerimaan
 * @property-read int|null $transaksi_penerimaan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaksiPenyaluran> $transaksiPenyaluran
 * @property-read int|null $transaksi_penyaluran_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat aktif()
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat byMasjid($masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat search($search)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereFotoKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereKodeProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereNamaProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereRealisasiDana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereRealisasiMustahik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereTargetDana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereTargetMustahik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProgramZakat whereUuid($value)
 */
	class ProgramZakat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $RECAPTCHA_SITE_KEY
 * @property string|null $RECAPTCHA_SECRET_KEY
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KonfigurasiAplikasi|null $konfigurasiAplikasi
 * @method static \Illuminate\Database\Eloquent\Builder|RecaptchaConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecaptchaConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecaptchaConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecaptchaConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecaptchaConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecaptchaConfig whereRECAPTCHASECRETKEY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecaptchaConfig whereRECAPTCHASITEKEY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecaptchaConfig whereUpdatedAt($value)
 */
	class RecaptchaConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $masjid_id
 * @property string $nama_bank
 * @property string $nomor_rekening
 * @property string $nama_pemilik
 * @property bool $is_primary
 * @property bool $is_active
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read array $actions
 * @property-read string $display_name
 * @property-read string $primary_badge
 * @property-read string $status_badge
 * @property-read \App\Models\Masjid $masjid
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid active()
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid byMasjid($masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid primary()
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid query()
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereNamaBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereNamaPemilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RekeningMasjid whereUuid($value)
 */
	class RekeningMasjid extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $no_setor
 * @property \Illuminate\Support\Carbon $tanggal_setor
 * @property \Illuminate\Support\Carbon $periode_dari
 * @property \Illuminate\Support\Carbon $periode_sampai
 * @property int $amil_id
 * @property int $masjid_id
 * @property int|null $diterima_oleh
 * @property string $jumlah_disetor
 * @property string $jumlah_dari_datang_langsung
 * @property string $jumlah_dari_dijemput
 * @property string|null $bukti_foto
 * @property string|null $tanda_tangan_amil
 * @property string|null $tanda_tangan_penerima
 * @property string|null $keterangan
 * @property string $status
 * @property string|null $alasan_penolakan
 * @property \Illuminate\Support\Carbon|null $diterima_at
 * @property string|null $jumlah_dihitung_fisik
 * @property \Illuminate\Support\Carbon|null $ditolak_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Amil $amil
 * @property-read bool $bisa_diedit
 * @property-read bool $bisa_dihapus
 * @property-read bool $bisa_diterima
 * @property-read string|null $bukti_foto_url
 * @property-read string $jumlah_dari_datang_langsung_formatted
 * @property-read string $jumlah_dari_dijemput_formatted
 * @property-read string $jumlah_dihitung_fisik_formatted
 * @property-read string $jumlah_disetor_formatted
 * @property-read string $periode_formatted
 * @property-read float|null $selisih_jumlah
 * @property-read string $status_badge
 * @property-read string $status_color
 * @property-read string|null $tanda_tangan_amil_url
 * @property-read string|null $tanda_tangan_penerima_url
 * @property-read \App\Models\Masjid $masjid
 * @property-read \App\Models\Pengguna|null $penerimaSetoran
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas byAmil(int $amilId)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas byMasjid(int $masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas byPeriode(string $dari, string $sampai)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas byStatus(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas diterima()
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas ditolak()
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas pending()
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas query()
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas search(?string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereAlasanPenolakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereAmilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereBuktiFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereDiterimaAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereDiterimaOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereDitolakAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereJumlahDariDatangLangsung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereJumlahDariDijemput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereJumlahDihitungFisik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereJumlahDisetor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereNoSetor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas wherePeriodeDari($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas wherePeriodeSampai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereTandaTanganAmil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereTandaTanganPenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereTanggalSetor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SetorKas withoutTrashed()
 */
	class SetorKas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $jenis_zakat_id
 * @property string $nama
 * @property string|null $nisab_emas_gram
 * @property string|null $nisab_perak_gram
 * @property string|null $nisab_pertanian_kg
 * @property int|null $nisab_kambing_min
 * @property int|null $nisab_sapi_min
 * @property int|null $nisab_unta_min
 * @property string|null $persentase_zakat
 * @property string|null $persentase_alternatif
 * @property string|null $keterangan_persentase
 * @property bool $requires_haul
 * @property string|null $ketentuan_khusus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $active_nisab_types
 * @property-read mixed $formatted_persentase
 * @property-read \App\Models\JenisZakat $jenisZakat
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat byJenisZakat($jenisZakatId)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat requiresHaul()
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereJenisZakatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereKetentuanKhusus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereKeteranganPersentase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereNisabEmasGram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereNisabKambingMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereNisabPerakGram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereNisabPertanianKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereNisabSapiMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereNisabUntaMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat wherePersentaseAlternatif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat wherePersentaseZakat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereRequiresHaul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipeZakat whereUuid($value)
 */
	class TipeZakat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property string $no_transaksi
 * @property \Illuminate\Support\Carbon $tanggal_transaksi
 * @property \Illuminate\Support\Carbon|null $waktu_transaksi
 * @property int $masjid_id
 * @property int|null $jenis_zakat_id
 * @property int|null $tipe_zakat_id
 * @property int|null $program_zakat_id
 * @property string $muzakki_nama
 * @property string|null $muzakki_telepon
 * @property string|null $muzakki_email
 * @property string|null $muzakki_alamat
 * @property string|null $muzakki_nik
 * @property string $metode_penerimaan
 * @property int|null $amil_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $status_penjemputan
 * @property \Illuminate\Support\Carbon|null $waktu_request
 * @property \Illuminate\Support\Carbon|null $waktu_diterima_amil
 * @property \Illuminate\Support\Carbon|null $waktu_berangkat
 * @property \Illuminate\Support\Carbon|null $waktu_sampai
 * @property \Illuminate\Support\Carbon|null $waktu_selesai
 * @property string|null $jumlah
 * @property string|null $metode_pembayaran
 * @property string|null $konfirmasi_status
 * @property string|null $no_referensi_transfer
 * @property int|null $dikonfirmasi_oleh
 * @property \Illuminate\Support\Carbon|null $konfirmasi_at
 * @property string|null $catatan_konfirmasi
 * @property int|null $jumlah_jiwa
 * @property string|null $nominal_per_jiwa
 * @property string|null $jumlah_beras_kg
 * @property string|null $harga_beras_per_kg
 * @property string|null $nilai_harta
 * @property string|null $nisab_saat_ini
 * @property bool|null $sudah_haul
 * @property \Illuminate\Support\Carbon|null $tanggal_mulai_haul
 * @property string|null $no_kwitansi
 * @property string|null $bukti_transfer
 * @property array|null $foto_dokumentasi
 * @property string|null $keterangan
 * @property string $status
 * @property string|null $alasan_penolakan
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Amil|null $amil
 * @property-read \App\Models\Pengguna|null $dikonfirmasiOleh
 * @property-read mixed $bisa_dikonfirmasi
 * @property-read mixed $bisa_ditolak
 * @property-read mixed $bisa_diupdate_penjemputan
 * @property-read mixed $bisa_diverifikasi
 * @property-read mixed $detail_zakat_fitrah
 * @property-read mixed $detail_zakat_mal
 * @property-read mixed $is_bayar_beras
 * @property-read mixed $is_draft
 * @property-read mixed $is_lengkap
 * @property-read mixed $is_non_tunai
 * @property-read mixed $is_pembayaran_dikonfirmasi
 * @property-read mixed $is_tunai
 * @property-read mixed $is_zakat_fitrah
 * @property-read mixed $is_zakat_mal
 * @property-read mixed $jumlah_formatted
 * @property-read mixed $konfirmasi_status_badge
 * @property-read mixed $metode_pembayaran_badge
 * @property-read mixed $metode_penerimaan_badge
 * @property-read mixed $status_badge
 * @property-read mixed $status_penjemputan_badge
 * @property-read \App\Models\JenisZakat|null $jenisZakat
 * @property-read \App\Models\Masjid $masjid
 * @property-read \App\Models\ProgramZakat|null $programZakat
 * @property-read \App\Models\TipeZakat|null $tipeZakat
 * @property-read \App\Models\Pengguna|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byAmil($id)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byJenisZakat($id)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byKonfirmasiStatus($s)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byMasjid($id)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byMetodePembayaran($m)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byMetodePenerimaan($m)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byPeriode($start, $end)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byStatus($s)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byStatusPenjemputan($s)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan byTanggal($tgl)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan menungguKonfirmasi()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan pending()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan rejected()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan search($search)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan verified()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereAlasanPenolakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereAmilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereBuktiTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereCatatanKonfirmasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereDikonfirmasiOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereFotoDokumentasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereHargaBerasPerKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereJenisZakatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereJumlahBerasKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereJumlahJiwa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereKonfirmasiAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereKonfirmasiStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereMetodePembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereMetodePenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereMuzakkiAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereMuzakkiEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereMuzakkiNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereMuzakkiNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereMuzakkiTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereNilaiHarta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereNisabSaatIni($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereNoKwitansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereNoReferensiTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereNoTransaksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereNominalPerJiwa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereProgramZakatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereStatusPenjemputan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereSudahHaul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereTanggalMulaiHaul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereTanggalTransaksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereTipeZakatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereVerifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereWaktuBerangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereWaktuDiterimaAmil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereWaktuRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereWaktuSampai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereWaktuSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenerimaan whereWaktuTransaksi($value)
 */
	class TransaksiPenerimaan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid UUID untuk ekspos ke API/publik, menghindari enumerable ID
 * @property string $no_transaksi Nomor transaksi auto-generate, human-readable
 * @property string|null $no_kwitansi Nomor kwitansi fisik, bisa berbeda dari no_transaksi
 * @property \Illuminate\Support\Carbon $tanggal_penyaluran Tanggal realisasi penyaluran
 * @property string|null $waktu_penyaluran Waktu penyaluran jika dicatat
 * @property string|null $periode Periode zakat format YYYY-MM, untuk zakat fitrah/periodik. Contoh: 2024-03
 * @property int $masjid_id
 * @property int $mustahik_id
 * @property int $kategori_mustahik_id
 * @property int|null $jenis_zakat_id
 * @property int|null $program_zakat_id
 * @property int|null $amil_id
 * @property string $jumlah Jumlah uang yang disalurkan (untuk metode tunai/transfer). Nilai bersih TANPA potongan apapun sesuai syariah.
 * @property string $metode_penyaluran Metode penyaluran: tunai = uang cash, transfer = bank/e-wallet, barang = in-kind
 * @property string|null $detail_barang Deskripsi detail barang yang disalurkan. Wajib diisi jika metode_penyaluran = barang
 * @property string|null $nilai_barang Nilai estimasi barang dalam rupiah. Wajib diisi jika metode_penyaluran = barang
 * @property string|null $foto_bukti Path file foto utama bukti penyerahan
 * @property string|null $path_tanda_tangan Path file tanda tangan digital mustahik (PNG/SVG). BUKAN base64 — simpan file, kolom hanya path
 * @property string|null $keterangan Catatan tambahan terkait penyaluran
 * @property string $status 
 *                     Status workflow transaksi penyaluran.
 *                     draft      = Amil sudah input, menunggu review Admin Masjid.
 *                     disetujui  = Admin Masjid menyetujui, siap disalurkan ke mustahik.
 *                     disalurkan = Amil konfirmasi sudah diserahkan ke mustahik (final).
 *                     dibatalkan = Admin Masjid menolak / transaksi dibatalkan, wajib ada alasan_pembatalan.
 * @property string|null $alasan_pembatalan 
 *                     Wajib diisi saat status = dibatalkan (reject oleh Admin Masjid).
 *                     Validasi: required_if:status,dibatalkan.
 *                     Contoh isi: "Data mustahik tidak valid", "Nominal melebihi plafon program".
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at Timestamp saat Admin Masjid menyetujui. Digunakan untuk timeline & laporan kecepatan approval.
 * @property int|null $disalurkan_oleh
 * @property \Illuminate\Support\Carbon|null $disalurkan_at Timestamp saat Amil konfirmasi penyaluran selesai. Digunakan untuk timeline, laporan harian & bulanan.
 * @property int|null $dibatalkan_oleh
 * @property \Illuminate\Support\Carbon|null $dibatalkan_at Timestamp saat Admin Masjid menolak transaksi. Wajib ada alasan_pembatalan jika kolom ini terisi.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at Soft delete — data historis keuangan tidak boleh dihapus permanen
 * @property-read \App\Models\Amil|null $amil
 * @property-read \App\Models\Pengguna|null $approvedBy
 * @property-read \App\Models\Pengguna|null $dibatalkanOleh
 * @property-read \App\Models\Pengguna|null $disalurkanOleh
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DokumentasiPenyaluran> $dokumentasi
 * @property-read int|null $dokumentasi_count
 * @property-read bool $bisa_diedit
 * @property-read bool $bisa_dihapus
 * @property-read bool $bisa_disalurkan
 * @property-read string $jumlah_formatted
 * @property-read string $metode_penyaluran_badge
 * @property-read string $status_badge
 * @property-read \App\Models\JenisZakat|null $jenisZakat
 * @property-read \App\Models\KategoriMustahik $kategoriMustahik
 * @property-read \App\Models\Masjid $masjid
 * @property-read \App\Models\Mustahik $mustahik
 * @property-read \App\Models\ProgramZakat|null $programZakat
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran byMasjid(int $masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran byPeriode(string $periode)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran byStatus(string $status)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran menungguApproval()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereAlasanPembatalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereAmilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereDetailBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereDibatalkanAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereDibatalkanOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereDisalurkanAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereDisalurkanOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereFotoBukti($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereJenisZakatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereKategoriMustahikId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereMetodePenyaluran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereMustahikId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereNilaiBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereNoKwitansi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereNoTransaksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran wherePathTandaTangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran wherePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereProgramZakatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereTanggalPenyaluran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran whereWaktuPenyaluran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TransaksiPenyaluran withoutTrashed()
 */
	class TransaksiPenyaluran extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $uuid
 * @property int $masjid_id
 * @property int $tahun
 * @property int $bulan
 * @property string $total_penerimaan
 * @property string $total_penyaluran
 * @property string $saldo_akhir
 * @property int $jumlah_muzakki
 * @property int $jumlah_mustahik
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $bulan_nama
 * @property-read mixed $periode
 * @property-read mixed $saldo_akhir_formatted
 * @property-read mixed $total_penerimaan_formatted
 * @property-read mixed $total_penyaluran_formatted
 * @property-read \App\Models\Masjid $masjid
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi byBulan($bulan)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi byMasjid($masjidId)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi byPeriode($tahun, $bulan)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi byTahun($tahun)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi orderByPeriode($direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi query()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereJumlahMustahik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereJumlahMuzakki($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereMasjidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereSaldoAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereTotalPenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereTotalPenyaluran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewLaporanKonsolidasi whereUuid($value)
 */
	class ViewLaporanKonsolidasi extends \Eloquent {}
}

