<?php

use App\Http\Controllers\Admin\Autentikasi\AdminLevelController;
use App\Http\Controllers\Admin\Autentikasi\AdminUserController;
use App\Http\Controllers\Admin\Berita\AdminBeritaController;
use App\Http\Controllers\Admin\Kehadiran\AdminCutiMelahirkanController;
use App\Http\Controllers\Admin\Kehadiran\AdminCutiMenikahController;
use App\Http\Controllers\Admin\Kehadiran\AdminCutiTahunanController;
use App\Http\Controllers\Admin\Kehadiran\AdminIzinKeluarController;
use App\Http\Controllers\Admin\Kehadiran\AdminIzinTerlambatController;
use App\Http\Controllers\Admin\Kehadiran\AdminKegiatanHarianController;
use App\Http\Controllers\Admin\Kehadiran\AdminLemburController;
use App\Http\Controllers\Admin\Kehadiran\AdminPengajuanCutiController;
use App\Http\Controllers\Admin\Kehadiran\AdminPotongGajiController;
use App\Http\Controllers\Admin\Kehadiran\AdminPulangCepatController;
use App\Http\Controllers\Admin\Kehadiran\AdminSakitController;
use App\Http\Controllers\Admin\Master\AdminDokterController;
use App\Http\Controllers\Admin\Master\AdminJabatanController;
use App\Http\Controllers\Admin\Master\AdminKategoriBeritaController;
use App\Http\Controllers\Admin\Master\AdminSpesialisController;
use App\Http\Controllers\Autentikasi\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturCutiMelahirkanController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturCutiMenikahController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturCutiTahunanController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturIzinKeluarController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturIzinTerlambatController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturKegiatanHarianController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturLemburController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturPotongGajiController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturPulangCepatController;
use App\Http\Controllers\Direktur\Kehadiran\DirekturSakitController;
use App\Http\Controllers\Karyawan\Berita\KaryawanBeritaController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanCutiMelahirkanController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanCutiMenikahController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanCutiTahunanController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanIzinKeluarController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanIzinTerlambatController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanKegiatanHarianController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanLemburController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanPotongGajiController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanPulangCepatController;
use App\Http\Controllers\Karyawan\Kehadiran\KaryawanSakitController;
use App\Http\Controllers\Karyawan\Master\KaryawanDokterController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaCutiMelahirkanController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaCutiMenikahController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaCutiTahunanController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaIzinKeluarController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaIzinTerlambatController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaKegiatanHarianController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaLemburController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaPotongGajiController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaPulangCepatController;
use App\Http\Controllers\Karyawan\Periksa\KaryawanPeriksaSakitController;
use App\Http\Controllers\Landing\LandingController;
use App\Http\Controllers\Operator\Autentikasi\OperatorJabatanController;
use App\Http\Controllers\Operator\Autentikasi\OperatorLevelController;
use App\Http\Controllers\Operator\Autentikasi\OperatorUserController;
use App\Http\Controllers\Operator\Kehadiran\OperatorCutiMelahirkanController;
use App\Http\Controllers\Operator\Kehadiran\OperatorCutiMenikahController;
use App\Http\Controllers\Operator\Kehadiran\OperatorCutiTahunanController;
use App\Http\Controllers\Operator\Kehadiran\OperatorIzinKeluarController;
use App\Http\Controllers\Operator\Kehadiran\OperatorIzinTerlambatController;
use App\Http\Controllers\Operator\Kehadiran\OperatorKegiatanHarianController;
use App\Http\Controllers\Operator\Kehadiran\OperatorLemburController;
use App\Http\Controllers\Operator\Kehadiran\OperatorPotongGajiController;
use App\Http\Controllers\Operator\Kehadiran\OperatorPulangCepatController;
use App\Http\Controllers\Operator\Kehadiran\OperatorSakitController;
use App\Http\Middleware\CekLevel;
use Illuminate\Support\Facades\Route;















































/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/profil/singkat', [LandingController::class, 'profilsingkat'])->name('landing.profilsingkat');
Route::get('/sejarah/rumah-sakit', [LandingController::class, 'sejarahrumahsakit'])->name('landing.sejarahrumahsakit');
Route::get('/visi-misi', [LandingController::class, 'visimisi'])->name('landing.visimisi');
Route::get('/struktur-organisasi', [LandingController::class, 'strukturorganisasi'])->name('landing.strukturorganisasi');
Route::get('/dokter-spesialis', [LandingController::class, 'dokterspesialis'])->name('landing.dokterspesialis');
Route::get('/profil/dokter/{id}', [LandingController::class, 'profildokter'])->name('landing.profildokter');
Route::get('/tenaga-medis', [LandingController::class, 'tenagamedis'])->name('landing.tenagamedis');
Route::get('/layanan-unggulan', [LandingController::class, 'layananunggulan'])->name('landing.layananunggulan');
Route::get('/layanan-umum', [LandingController::class, 'layananumum'])->name('landing.layananumum');
Route::get('/ruang-operasi', [LandingController::class, 'ruangoperasi'])->name('landing.ruangoperasi');
Route::get('/berita', [LandingController::class, 'berita'])->name('landing.berita');
Route::get('/show/berita/{id}', [LandingController::class, 'showberita'])->name('landing.showberita');
Route::get('/tata-tertib', [LandingController::class, 'tatatertib'])->name('landing.tatatertib');
Route::get('/kerjasama-asuransi', [LandingController::class, 'kerjasamaasuransi'])->name('landing.kerjasamaasuransi');
Route::get('/kontak-kami', [LandingController::class, 'kontak'])->name('landing.kontak');

// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

// Dashboard
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Admin
    Route::group(['middleware' => [CekLevel::class . ':1']], function () {

        // Berita
        Route::get('/admin-berita/index', [AdminBeritaController::class, 'index'])->name('admin-berita.index');
        Route::get('/admin-berita/create', [AdminBeritaController::class, 'create'])->name('admin-berita.create');
        Route::get('/admin-berita/edit/{id}', [AdminBeritaController::class, 'edit'])->name('admin-berita.edit');
        Route::post('/admin-berita/store', [AdminBeritaController::class, 'store'])->name('admin-berita.store');
        Route::post('/admin-berita/update/{id}', [AdminBeritaController::class, 'update'])->name('admin-berita.update');
        Route::post('/admin-berita/destroy/{id}', [AdminBeritaController::class, 'destroy'])->name('admin-berita.destroy');

        // Kategori Berita
        Route::get('/admin-kategori/berita/index', [AdminKategoriBeritaController::class, 'index'])->name('admin-kategoriberita.index');
        Route::get('/admin-kategori/berita/generatepdf', [AdminKategoriBeritaController::class, 'generatepdf'])->name('admin-kategoriberita.generatepdf');
        Route::get('/admin-kategori/berita/generateexcel', [AdminKategoriBeritaController::class, 'generateexcel'])->name('admin-kategoriberita.generateexcel');
        Route::get('/admin-kategori/berita/create', [AdminKategoriBeritaController::class, 'create'])->name('admin-kategoriberita.create');
        Route::get('/admin-kategori/berita/edit/{id}', [AdminKategoriBeritaController::class, 'edit'])->name('admin-kategoriberita.edit');
        Route::get('/admin-kategori/berita/getUser', [AdminKategoriBeritaController::class, 'getUser'])->name('admin-kategoriberita.getUser');
        Route::post('/admin-kategori/berita/store', [AdminKategoriBeritaController::class, 'store'])->name('admin-kategoriberita.store');
        Route::post('/admin-kategori/berita/update/{id}', [AdminKategoriBeritaController::class, 'update'])->name('admin-kategoriberita.update');
        Route::post('/admin-kategori/berita/destroy/{id}', [AdminKategoriBeritaController::class, 'destroy'])->name('admin-kategoriberita.destroy');

        // Cuti Menikah
        Route::get('/admin-cuti/menikah/index', [AdminCutiMenikahController::class, 'index'])->name('admin-cutimenikah.index');
        Route::get('/admin-cuti/menikah/generatepdf', [AdminCutiMenikahController::class, 'generatepdf'])->name('admin-cutimenikah.generatepdf');
        Route::get('/admin-cuti/menikah/generateexcel', [AdminCutiMenikahController::class, 'generateexcel'])->name('admin-cutimenikah.generateexcel');
        Route::get('/admin-cuti/menikah/create', [AdminCutiMenikahController::class, 'create'])->name('admin-cutimenikah.create');
        Route::get('/admin-cuti/menikah/edit/{id}', [AdminCutiMenikahController::class, 'edit'])->name('admin-cutimenikah.edit');
        Route::get('/admin-cuti/menikah/getUser', [AdminCutiMenikahController::class, 'getUser'])->name('admin-cutimenikah.getUser');
        Route::post('/admin-cuti/menikah/store', [AdminCutiMenikahController::class, 'store'])->name('admin-cutimenikah.store');
        Route::post('/admin-cuti/menikah/update/{id}', [AdminCutiMenikahController::class, 'update'])->name('admin-cutimenikah.update');
        Route::post('/admin-cuti/menikah/destroy/{id}', [AdminCutiMenikahController::class, 'destroy'])->name('admin-cutimenikah.destroy');

        // Pulang Cepat
        Route::get('/admin-pulang/cepat/index', [AdminPulangCepatController::class, 'index'])->name('admin-pulangcepat.index');
        Route::get('/admin-pulang/cepat/generatepdf', [AdminPulangCepatController::class, 'generatepdf'])->name('admin-pulangcepat.generatepdf');
        Route::get('/admin-pulang/cepat/generateexcel', [AdminPulangCepatController::class, 'generateexcel'])->name('admin-pulangcepat.generateexcel');
        Route::get('/admin-pulang/cepat/create', [AdminPulangCepatController::class, 'create'])->name('admin-pulangcepat.create');
        Route::get('/admin-pulang/cepat/edit/{id}', [AdminPulangCepatController::class, 'edit'])->name('admin-pulangcepat.edit');
        Route::get('/admin-pulang/cepat/getUser', [AdminPulangCepatController::class, 'getUser'])->name('admin-pulangcepat.getUser');
        Route::post('/admin-pulang/cepat/store', [AdminPulangCepatController::class, 'store'])->name('admin-pulangcepat.store');
        Route::post('/admin-pulang/cepat/update/{id}', [AdminPulangCepatController::class, 'update'])->name('admin-pulangcepat.update');
        Route::post('/admin-pulang/cepat/destroy/{id}', [AdminPulangCepatController::class, 'destroy'])->name('admin-pulangcepat.destroy');

        // Potong Gaji
        Route::get('/admin-potong/gaji/index', [AdminPotongGajiController::class, 'index'])->name('admin-potonggaji.index');
        Route::get('/admin-potong/gaji/generatepdf', [AdminPotongGajiController::class, 'generatepdf'])->name('admin-potonggaji.generatepdf');
        Route::get('/admin-potong/gaji/generateexcel', [AdminPotongGajiController::class, 'generateexcel'])->name('admin-potonggaji.generateexcel');
        Route::get('/admin-potong/gaji/create', [AdminPotongGajiController::class, 'create'])->name('admin-potonggaji.create');
        Route::get('/admin-potong/gaji/edit/{id}', [AdminPotongGajiController::class, 'edit'])->name('admin-potonggaji.edit');
        Route::get('/admin-potong/gaji/getUser', [AdminPotongGajiController::class, 'getUser'])->name('admin-potonggaji.getUser');
        Route::post('/admin-potong/gaji/store', [AdminPotongGajiController::class, 'store'])->name('admin-potonggaji.store');
        Route::post('/admin-potong/gaji/update/{id}', [AdminPotongGajiController::class, 'update'])->name('admin-potonggaji.update');
        Route::post('/admin-potong/gaji/destroy/{id}', [AdminPotongGajiController::class, 'destroy'])->name('admin-potonggaji.destroy');

        // Izin Keluar
        Route::get('/admin-izin/keluar/index', [AdminIzinKeluarController::class, 'index'])->name('admin-izinkeluar.index');
        Route::get('/admin-izin/keluar/generatepdf', [AdminIzinKeluarController::class, 'generatepdf'])->name('admin-izinkeluar.generatepdf');
        Route::get('/admin-izin/keluar/generateexcel', [AdminIzinKeluarController::class, 'generateexcel'])->name('admin-izinkeluar.generateexcel');
        Route::get('/admin-izin/keluar/create', [AdminIzinKeluarController::class, 'create'])->name('admin-izinkeluar.create');
        Route::get('/admin-izin/keluar/edit/{id}', [AdminIzinKeluarController::class, 'edit'])->name('admin-izinkeluar.edit');
        Route::get('/admin-izin/keluar/getUser', [AdminIzinKeluarController::class, 'getUser'])->name('admin-izinkeluar.getUser');
        Route::post('/admin-izin/keluar/store', [AdminIzinKeluarController::class, 'store'])->name('admin-izinkeluar.store');
        Route::post('/admin-izin/keluar/update/{id}', [AdminIzinKeluarController::class, 'update'])->name('admin-izinkeluar.update');
        Route::post('/admin-izin/keluar/destroy/{id}', [AdminIzinKeluarController::class, 'destroy'])->name('admin-izinkeluar.destroy');

        // Cuti Melahirkan
        Route::get('/admin-cuti/melahirkan/index', [AdminCutiMelahirkanController::class, 'index'])->name('admin-cutimelahirkan.index');
        Route::get('/admin-cuti/melahirkan/generatepdf', [AdminCutiMelahirkanController::class, 'generatepdf'])->name('admin-cutimelahirkan.generatepdf');
        Route::get('/admin-cuti/melahirkan/generateexcel', [AdminCutiMelahirkanController::class, 'generateexcel'])->name('admin-cutimelahirkan.generateexcel');
        Route::get('/admin-cuti/melahirkan/create', [AdminCutiMelahirkanController::class, 'create'])->name('admin-cutimelahirkan.create');
        Route::get('/admin-cuti/melahirkan/edit/{id}', [AdminCutiMelahirkanController::class, 'edit'])->name('admin-cutimelahirkan.edit');
        Route::get('/admin-cuti/melahirkan/getUser', [AdminCutiMelahirkanController::class, 'getUser'])->name('admin-cutimelahirkan.getUser');
        Route::post('/admin-cuti/melahirkan/store', [AdminCutiMelahirkanController::class, 'store'])->name('admin-cutimelahirkan.store');
        Route::post('/admin-cuti/melahirkan/update/{id}', [AdminCutiMelahirkanController::class, 'update'])->name('admin-cutimelahirkan.update');
        Route::post('/admin-cuti/melahirkan/destroy/{id}', [AdminCutiMelahirkanController::class, 'destroy'])->name('admin-cutimelahirkan.destroy');

        // Sakit
        Route::get('/admin-sakit/index', [AdminSakitController::class, 'index'])->name('admin-sakit.index');
        Route::get('/admin-sakit/generatepdf', [AdminSakitController::class, 'generatepdf'])->name('admin-sakit.generatepdf');
        Route::get('/admin-sakit/generateexcel', [AdminSakitController::class, 'generateexcel'])->name('admin-sakit.generateexcel');
        Route::get('/admin-sakit/create', [AdminSakitController::class, 'create'])->name('admin-sakit.create');
        Route::get('/admin-sakit/edit/{id}', [AdminSakitController::class, 'edit'])->name('admin-sakit.edit');
        Route::get('/admin-sakit/getUser', [AdminSakitController::class, 'getUser'])->name('admin-sakit.getUser');
        Route::post('/admin-sakit/store', [AdminSakitController::class, 'store'])->name('admin-sakit.store');
        Route::post('/admin-sakit/update/{id}', [AdminSakitController::class, 'update'])->name('admin-sakit.update');
        Route::post('/admin-sakit/destroy/{id}', [AdminSakitController::class, 'destroy'])->name('admin-sakit.destroy');

        // Izin Terlambat
        Route::get('/admin-izin/terlambat/index', [AdminIzinTerlambatController::class, 'index'])->name('admin-izinterlambat.index');
        Route::get('/admin-izin/terlambat/generatepdf', [AdminIzinTerlambatController::class, 'generatepdf'])->name('admin-izinterlambat.generatepdf');
        Route::get('/admin-izin/terlambat/generateexcel', [AdminIzinTerlambatController::class, 'generateexcel'])->name('admin-izinterlambat.generateexcel');
        Route::get('/admin-izin/terlambat/create', [AdminIzinTerlambatController::class, 'create'])->name('admin-izinterlambat.create');
        Route::get('/admin-izin/terlambat/getUser', [AdminIzinTerlambatController::class, 'getUser'])->name('admin-izinterlambat.getUser');
        Route::get('/admin-izin/terlambat/edit/{id}', [AdminIzinTerlambatController::class, 'edit'])->name('admin-izinterlambat.edit');
        Route::post('/admin-izin/terlambat/store', [AdminIzinTerlambatController::class, 'store'])->name('admin-izinterlambat.store');
        Route::post('/admin-izin/terlambat/update/{id}', [AdminIzinTerlambatController::class, 'update'])->name('admin-izinterlambat.update');
        Route::post('/admin-izin/terlambat/destroy/{id}', [AdminIzinTerlambatController::class, 'destroy'])->name('admin-izinterlambat.destroy');

        // Cuti Tahunan
        Route::get('/admin-cuti/tahunan/index', [AdminCutiTahunanController::class, 'index'])->name('admin-cutitahunan.index');
        Route::get('/admin-cuti/tahunan/generatepdf', [AdminCutiTahunanController::class, 'generatepdf'])->name('admin-cutitahunan.generatepdf');
        Route::get('/admin-cuti/tahunan/generateexcel', [AdminCutiTahunanController::class, 'generateexcel'])->name('admin-cutitahunan.generateexcel');
        Route::get('/admin-cuti/tahunan/create', [AdminCutiTahunanController::class, 'create'])->name('admin-cutitahunan.create');
        Route::get('/admin-cuti/tahunan/edit/{id}', [AdminCutiTahunanController::class, 'edit'])->name('admin-cutitahunan.edit');
        Route::get('/admin-cuti/tahunan/getUser', [AdminCutiTahunanController::class, 'getUser'])->name('admin-cutitahunan.getUser');
        Route::post('/admin-cuti/tahunan/store', [AdminCutiTahunanController::class, 'store'])->name('admin-cutitahunan.store');
        Route::post('/admin-cuti/tahunan/update/{id}', [AdminCutiTahunanController::class, 'update'])->name('admin-cutitahunan.update');
        Route::post('/admin-cuti/tahunan/destroy/{id}', [AdminCutiTahunanController::class, 'destroy'])->name('admin-cutitahunan.destroy');

        // Kegiatan Harian
        Route::get('/admin-kegiatan/harian/index', [AdminKegiatanHarianController::class, 'index'])->name('admin-kegiatanharian.index');
        Route::get('/admin-kegiatan/harian/generatepdf', [AdminKegiatanHarianController::class, 'generatepdf'])->name('admin-kegiatanharian.generatepdf');
        Route::get('/admin-kegiatan/harian/generateexcel', [AdminKegiatanHarianController::class, 'generateexcel'])->name('admin-kegiatanharian.generateexcel');
        Route::get('/admin-kegiatan/harian/create', [AdminKegiatanHarianController::class, 'create'])->name('admin-kegiatanharian.create');
        Route::get('/admin-kegiatan/harian/edit/{id}', [AdminKegiatanHarianController::class, 'edit'])->name('admin-kegiatanharian.edit');
        Route::get('/admin-kegiatan/harian/getUser', [AdminKegiatanHarianController::class, 'getUser'])->name('admin-kegiatanharian.getUser');
        Route::post('/admin-kegiatan/harian/store', [AdminKegiatanHarianController::class, 'store'])->name('admin-kegiatanharian.store');
        Route::post('/admin-kegiatan/harian/update/{id}', [AdminKegiatanHarianController::class, 'update'])->name('admin-kegiatanharian.update');
        Route::post('/admin-kegiatan/harian/destroy/{id}', [AdminKegiatanHarianController::class, 'destroy'])->name('admin-kegiatanharian.destroy');

        // Lembur
        Route::get('/admin-lembur/index', [AdminLemburController::class, 'index'])->name('admin-lembur.index');
        Route::get('/admin-lembur/generatepdf', [AdminLemburController::class, 'generatepdf'])->name('admin-lembur.generatepdf');
        Route::get('/admin-lembur/generateexcel', [AdminLemburController::class, 'generateexcel'])->name('admin-lembur.generateexcel');
        Route::get('/admin-lembur/create', [AdminLemburController::class, 'create'])->name('admin-lembur.create');
        Route::get('/admin-lembur/edit/{id}', [AdminLemburController::class, 'edit'])->name('admin-lembur.edit');
        Route::get('/admin-lembur/getUser', [AdminLemburController::class, 'getUser'])->name('admin-lembur.getUser');
        Route::post('/admin-lembur/store', [AdminLemburController::class, 'store'])->name('admin-lembur.store');
        Route::post('/admin-lembur/update/{id}', [AdminLemburController::class, 'update'])->name('admin-lembur.update');
        Route::post('/admin-lembur/destroy/{id}', [AdminLemburController::class, 'destroy'])->name('admin-lembur.destroy');

        // Dokter
        Route::get('/admin-dokter/index', [AdminDokterController::class, 'index'])->name('admin-dokter.index');
        Route::get('/admin-dokter/generatepdf', [AdminDokterController::class, 'generatepdf'])->name('admin-dokter.generatepdf');
        Route::get('/admin-dokter/generateexcel', [AdminDokterController::class, 'generateexcel'])->name('admin-dokter.generateexcel');
        Route::get('/admin-dokter/jadwal/{id}', [AdminDokterController::class, 'jadwal'])->name('admin-dokter.jadwal');
        Route::get('/admin-dokter/cutidokter/{id}', [AdminDokterController::class, 'cutidokter'])->name('admin-dokter.cutidokter');
        Route::get('/admin-dokter/create', [AdminDokterController::class, 'create'])->name('admin-dokter.create');
        Route::get('/admin-dokter/edit/{id}', [AdminDokterController::class, 'edit'])->name('admin-dokter.edit');
        Route::post('/admin-dokter/store', [AdminDokterController::class, 'store'])->name('admin-dokter.store');
        Route::post('/admin-dokter/storecutidokter', [AdminDokterController::class, 'storecutidokter'])->name('admin-dokter.storecutidokter');
        Route::post('/admin-dokter/updatecutidokter/{id}', [AdminDokterController::class, 'updatecutidokter'])->name('admin-dokter.updatecutidokter');
        Route::post('/admin-dokter/destroycutidokter/{id}', [AdminDokterController::class, 'destroycutidokter'])->name('admin-dokter.destroycutidokter');
        Route::post('/admin-dokter/storejadwaldokter', [AdminDokterController::class, 'storejadwaldokter'])->name('admin-dokter.storejadwaldokter');
        Route::post('/admin-dokter/updatejadwaldokter/{id}', [AdminDokterController::class, 'updatejadwaldokter'])->name('admin-dokter.updatejadwaldokter');
        Route::post('/admin-dokter/destroyjadwaldokter/{id}', [AdminDokterController::class, 'destroyjadwaldokter'])->name('admin-dokter.destroyjadwaldokter');
        Route::post('/admin-dokter/update/{id}', [AdminDokterController::class, 'update'])->name('admin-dokter.update');
        Route::post('/admin-dokter/destroy/{id}', [AdminDokterController::class, 'destroy'])->name('admin-dokter.destroy');

        // Spesialis
        Route::get('/admin-spesialis/index', [AdminSpesialisController::class, 'index'])->name('admin-spesialis.index');
        Route::get('/admin-spesialis/generatepdf', [AdminSpesialisController::class, 'generatepdf'])->name('admin-spesialis.generatepdf');
        Route::get('/admin-spesialis/generateexcel', [AdminSpesialisController::class, 'generateexcel'])->name('admin-spesialis.generateexcel');
        Route::get('/admin-spesialis/create', [AdminSpesialisController::class, 'create'])->name('admin-spesialis.create');
        Route::get('/admin-spesialis/edit/{id}', [AdminSpesialisController::class, 'edit'])->name('admin-spesialis.edit');
        Route::post('/admin-spesialis/store', [AdminSpesialisController::class, 'store'])->name('admin-spesialis.store');
        Route::post('/admin-spesialis/update/{id}', [AdminSpesialisController::class, 'update'])->name('admin-spesialis.update');
        Route::post('/admin-spesialis/destroy/{id}', [AdminSpesialisController::class, 'destroy'])->name('admin-spesialis.destroy');

        // Jabatan
        Route::get('/admin-jabatan/index', [AdminJabatanController::class, 'index'])->name('admin-jabatan.index');
        Route::get('/admin-jabatan/generatepdf', [AdminJabatanController::class, 'generatepdf'])->name('admin-jabatan.generatepdf');
        Route::get('/admin-jabatan/generateexcel', [AdminJabatanController::class, 'generateexcel'])->name('admin-jabatan.generateexcel');
        Route::get('/admin-jabatan/create', [AdminJabatanController::class, 'create'])->name('admin-jabatan.create');
        Route::get('/admin-jabatan/edit/{id}', [AdminJabatanController::class, 'edit'])->name('admin-jabatan.edit');
        Route::post('/admin-jabatan/store', [AdminJabatanController::class, 'store'])->name('admin-jabatan.store');
        Route::post('/admin-jabatan/update/{id}', [AdminJabatanController::class, 'update'])->name('admin-jabatan.update');
        Route::post('/admin-jabatan/destroy/{id}', [AdminJabatanController::class, 'destroy'])->name('admin-jabatan.destroy');

        // Level
        Route::get('/admin-level/index', [AdminLevelController::class, 'index'])->name('admin-level.index');
        Route::get('/admin-level/create', [AdminLevelController::class, 'create'])->name('admin-level.create');
        Route::get('/admin-level/generatepdf', [AdminLevelController::class, 'generatepdf'])->name('admin-level.generatepdf');
        Route::get('/admin-level/generateexcel', [AdminLevelController::class, 'generateexcel'])->name('admin-level.generateexcel');
        Route::get('/admin-level/edit/{id}', [AdminLevelController::class, 'edit'])->name('admin-level.edit');
        Route::post('/admin-level/store', [AdminLevelController::class, 'store'])->name('admin-level.store');
        Route::post('/admin-level/update/{id}', [AdminLevelController::class, 'update'])->name('admin-level.update');
        Route::post('/admin-level/destroy/{id}', [AdminLevelController::class, 'destroy'])->name('admin-level.destroy');

        // User Registrasi
        Route::get('/admin-users/index', [AdminUserController::class, 'index'])->name('admin-users.index');
        Route::get('/admin-users/create', [AdminUserController::class, 'create'])->name('admin-users.create');
        Route::get('/admin-users/generatepdf', [AdminUserController::class, 'generatepdf'])->name('admin-users.generatepdf');
        Route::get('/admin-users/generateexcel', [AdminUserController::class, 'generateexcel'])->name('admin-users.generateexcel');
        Route::get('/admin-users/edit/{id}', [AdminUserController::class, 'edit'])->name('admin-users.edit');
        Route::post('/admin-users/store', [AdminUserController::class, 'store'])->name('admin-users.store');
        Route::post('/admin-users/update/{id}', [AdminUserController::class, 'update'])->name('admin-users.update');
        Route::post('/admin-users/destroy/{id}', [AdminUserController::class, 'destroy'])->name('admin-users.destroy');
    });

    // Karyawan
    Route::group(['middleware' => [CekLevel::class . ':2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25']], function () {

        // Cuti Menikah
        Route::get('/karyawan-periksa/cuti-menikah/index', [KaryawanPeriksaCutiMenikahController::class, 'index'])->name('karyawan-periksacutimenikah.index');
        Route::post('/karyawan-periksa/cuti-menikah/diterima/{id}', [KaryawanPeriksaCutiMenikahController::class, 'diterima'])->name('karyawan-periksacutimenikah.diterima');
        Route::post('/karyawan-periksa/cuti-menikah/ditolak/{id}', [KaryawanPeriksaCutiMenikahController::class, 'ditolak'])->name('karyawan-periksacutimenikah.ditolak');

        // Pulang Cepat
        Route::get('/karyawan-periksa/pulang-cepat/index', [KaryawanPeriksaPulangCepatController::class, 'index'])->name('karyawan-periksapulangcepat.index');
        Route::post('/karyawan-periksa/pulang-cepat/diterima/{id}', [KaryawanPeriksaPulangCepatController::class, 'diterima'])->name('karyawan-periksapulangcepat.diterima');
        Route::post('/karyawan-periksa/pulang-cepat/ditolak/{id}', [KaryawanPeriksaPulangCepatController::class, 'ditolak'])->name('karyawan-periksapulangcepat.ditolak');

        // Potong Gaji
        Route::get('/karyawan-periksa/potong-gaji/index', [KaryawanPeriksaPotongGajiController::class, 'index'])->name('karyawan-periksapotonggaji.index');
        Route::post('/karyawan-periksa/potong-gaji/diterima/{id}', [KaryawanPeriksaPotongGajiController::class, 'diterima'])->name('karyawan-periksapotonggaji.diterima');
        Route::post('/karyawan-periksa/potong-gaji/ditolak/{id}', [KaryawanPeriksaPotongGajiController::class, 'ditolak'])->name('karyawan-periksapotonggaji.ditolak');

        // Izin Keluar
        Route::get('/karyawan-periksa/izin-keluar/index', [KaryawanPeriksaIzinKeluarController::class, 'index'])->name('karyawan-periksaizinkeluar.index');
        Route::post('/karyawan-periksa/izin-keluar/diterima/{id}', [KaryawanPeriksaIzinKeluarController::class, 'diterima'])->name('karyawan-periksaizinkeluar.diterima');
        Route::post('/karyawan-periksa/izin-keluar/ditolak/{id}', [KaryawanPeriksaIzinKeluarController::class, 'ditolak'])->name('karyawan-periksaizinkeluar.ditolak');

        // Cuti Melahirkan
        Route::get('/karyawan-periksa/cuti-melahirkan/index', [KaryawanPeriksaCutiMelahirkanController::class, 'index'])->name('karyawan-periksacutimelahirkan.index');
        Route::post('/karyawan-periksa/cuti-melahirkan/diterima/{id}', [KaryawanPeriksaCutiMelahirkanController::class, 'diterima'])->name('karyawan-periksacutimelahirkan.diterima');
        Route::post('/karyawan-periksa/cuti-melahirkan/ditolak/{id}', [KaryawanPeriksaCutiMelahirkanController::class, 'ditolak'])->name('karyawan-periksacutimelahirkan.ditolak');

        // Sakit
        Route::get('/karyawan-periksa/sakit/index', [KaryawanPeriksaSakitController::class, 'index'])->name('karyawan-periksasakit.index');
        Route::post('/karyawan-periksa/sakit/diterima/{id}', [KaryawanPeriksaSakitController::class, 'diterima'])->name('karyawan-periksasakit.diterima');
        Route::post('/karyawan-periksa/sakit/ditolak/{id}', [KaryawanPeriksaSakitController::class, 'ditolak'])->name('karyawan-periksasakit.ditolak');

        // Izin Terlambat
        Route::get('/karyawan-periksa/izin-terlambat/index', [KaryawanPeriksaIzinTerlambatController::class, 'index'])->name('karyawan-periksaizinterlambat.index');
        Route::post('/karyawan-periksa/izin-terlambat/diterima/{id}', [KaryawanPeriksaIzinTerlambatController::class, 'diterima'])->name('karyawan-periksaizinterlambat.diterima');
        Route::post('/karyawan-periksa/izin-terlambat/ditolak/{id}', [KaryawanPeriksaIzinTerlambatController::class, 'ditolak'])->name('karyawan-periksaizinterlambat.ditolak');

        // Cuti Tahunan
        Route::get('/karyawan-periksa/cuti-tahunan/index', [KaryawanPeriksaCutiTahunanController::class, 'index'])->name('karyawan-periksacutitahunan.index');
        Route::post('/karyawan-periksa/cuti-tahunan/diterima/{id}', [KaryawanPeriksaCutiTahunanController::class, 'diterima'])->name('karyawan-periksacutitahunan.diterima');
        Route::post('/karyawan-periksa/cuti-tahunan/ditolak/{id}', [KaryawanPeriksaCutiTahunanController::class, 'ditolak'])->name('karyawan-periksacutitahunan.ditolak');

        // Kegiatan Harian
        Route::get('/karyawan-periksa/kegiatan-harian/index', [KaryawanPeriksaKegiatanHarianController::class, 'index'])->name('karyawan-periksakegiatanharian.index');
        Route::post('/karyawan-periksa/kegiatan-harian/diterima/{id}', [KaryawanPeriksaKegiatanHarianController::class, 'diterima'])->name('karyawan-periksakegiatanharian.diterima');
        Route::post('/karyawan-periksa/kegiatan-harian/ditolak/{id}', [KaryawanPeriksaKegiatanHarianController::class, 'ditolak'])->name('karyawan-periksakegiatanharian.ditolak');


        // Periksa Lembur
        Route::get('/karyawan-periksa/lembur/index', [KaryawanPeriksaLemburController::class, 'index'])->name('karyawan-periksalembur.index');
        Route::post('/karyawan-periksa/lembur/diterima/{id}', [KaryawanPeriksaLemburController::class, 'diterima'])->name('karyawan-periksalembur.diterima');
        Route::post('/karyawan-periksa/lembur/ditolak/{id}', [KaryawanPeriksaLemburController::class, 'ditolak'])->name('karyawan-periksalembur.ditolak');

        // ====================================================================================================================

        // Cuti Menikah
        Route::get('/karyawan-cuti/menikah/index', [KaryawanCutiMenikahController::class, 'index'])->name('karyawan-cutimenikah.index');
        Route::get('/karyawan-cuti/menikah/getUser', [KaryawanCutiMenikahController::class, 'getUser'])->name('karyawan-cutimenikah.getUser');
        Route::get('/karyawan-cuti/menikah/create', [KaryawanCutiMenikahController::class, 'create'])->name('karyawan-cutimenikah.create');
        Route::get('/karyawan-cuti/menikah/edit/{id}', [KaryawanCutiMenikahController::class, 'edit'])->name('karyawan-cutimenikah.edit');
        Route::post('/karyawan-cuti/menikah/store', [KaryawanCutiMenikahController::class, 'store'])->name('karyawan-cutimenikah.store');
        Route::post('/karyawan-cuti/menikah/update/{id}', [KaryawanCutiMenikahController::class, 'update'])->name('karyawan-cutimenikah.update');
        Route::post('/karyawan-cuti/menikah/destroy/{id}', [KaryawanCutiMenikahController::class, 'destroy'])->name('karyawan-cutimenikah.destroy');

        // Pulang Cepat
        Route::get('/karyawan-pulang/cepat/index', [KaryawanPulangCepatController::class, 'index'])->name('karyawan-pulangcepat.index');
        Route::get('/karyawan-pulang/cepat/getUser', [KaryawanPulangCepatController::class, 'getUser'])->name('karyawan-pulangcepat.getUser');
        Route::get('/karyawan-pulang/cepat/create', [KaryawanPulangCepatController::class, 'create'])->name('karyawan-pulangcepat.create');
        Route::get('/karyawan-pulang/cepat/edit/{id}', [KaryawanPulangCepatController::class, 'edit'])->name('karyawan-pulangcepat.edit');
        Route::post('/karyawan-pulang/cepat/store', [KaryawanPulangCepatController::class, 'store'])->name('karyawan-pulangcepat.store');
        Route::post('/karyawan-pulang/cepat/update/{id}', [KaryawanPulangCepatController::class, 'update'])->name('karyawan-pulangcepat.update');
        Route::post('/karyawan-pulang/cepat/destroy/{id}', [KaryawanPulangCepatController::class, 'destroy'])->name('karyawan-pulangcepat.destroy');


        // Potong Gaji
        Route::get('/karyawan-potong/gaji/index', [KaryawanPotongGajiController::class, 'index'])->name('karyawan-potonggaji.index');
        Route::get('/karyawan-potong/gaji/getUser', [KaryawanPotongGajiController::class, 'getUser'])->name('karyawan-potonggaji.getUser');
        Route::get('/karyawan-potong/gaji/create', [KaryawanPotongGajiController::class, 'create'])->name('karyawan-potonggaji.create');
        Route::get('/karyawan-potong/gaji/edit/{id}', [KaryawanPotongGajiController::class, 'edit'])->name('karyawan-potonggaji.edit');
        Route::post('/karyawan-potong/gaji/store', [KaryawanPotongGajiController::class, 'store'])->name('karyawan-potonggaji.store');
        Route::post('/karyawan-potong/gaji/update/{id}', [KaryawanPotongGajiController::class, 'update'])->name('karyawan-potonggaji.update');
        Route::post('/karyawan-potong/gaji/destroy/{id}', [KaryawanPotongGajiController::class, 'destroy'])->name('karyawan-potonggaji.destroy');

        // Izin Keluar
        Route::get('/karyawan-izin/keluar/index', [KaryawanIzinKeluarController::class, 'index'])->name('karyawan-izinkeluar.index');
        Route::get('/karyawan-izin/keluar/getUser', [KaryawanIzinKeluarController::class, 'getUser'])->name('karyawan-izinkeluar.getUser');
        Route::get('/karyawan-izin/keluar/create', [KaryawanIzinKeluarController::class, 'create'])->name('karyawan-izinkeluar.create');
        Route::get('/karyawan-izin/keluar/edit/{id}', [KaryawanIzinKeluarController::class, 'edit'])->name('karyawan-izinkeluar.edit');
        Route::post('/karyawan-izin/keluar/store', [KaryawanIzinKeluarController::class, 'store'])->name('karyawan-izinkeluar.store');
        Route::post('/karyawan-izin/keluar/update/{id}', [KaryawanIzinKeluarController::class, 'update'])->name('karyawan-izinkeluar.update');
        Route::post('/karyawan-izin/keluar/destroy/{id}', [KaryawanIzinKeluarController::class, 'destroy'])->name('karyawan-izinkeluar.destroy');

        // Cuti Melahirkan
        Route::get('/karyawan-cuti/melahirkan/index', [KaryawanCutiMelahirkanController::class, 'index'])->name('karyawan-cutimelahirkan.index');
        Route::get('/karyawan-cuti/melahirkan/getUser', [KaryawanCutiMelahirkanController::class, 'getUser'])->name('karyawan-cutimelahirkan.getUser');
        Route::get('/karyawan-cuti/melahirkan/create', [KaryawanCutiMelahirkanController::class, 'create'])->name('karyawan-cutimelahirkan.create');
        Route::get('/karyawan-cuti/melahirkan/edit/{id}', [KaryawanCutiMelahirkanController::class, 'edit'])->name('karyawan-cutimelahirkan.edit');
        Route::post('/karyawan-cuti/melahirkan/store', [KaryawanCutiMelahirkanController::class, 'store'])->name('karyawan-cutimelahirkan.store');
        Route::post('/karyawan-cuti/melahirkan/update/{id}', [KaryawanCutiMelahirkanController::class, 'update'])->name('karyawan-cutimelahirkan.update');
        Route::post('/karyawan-cuti/melahirkan/destroy/{id}', [KaryawanCutiMelahirkanController::class, 'destroy'])->name('karyawan-cutimelahirkan.destroy');

        // Sakit
        Route::get('/karyawan-sakit/index', [KaryawanSakitController::class, 'index'])->name('karyawan-sakit.index');
        Route::get('/karyawan-sakit/getUser', [KaryawanSakitController::class, 'getUser'])->name('karyawan-sakit.getUser');
        Route::get('/karyawan-sakit/create', [KaryawanSakitController::class, 'create'])->name('karyawan-sakit.create');
        Route::get('/karyawan-sakit/edit/{id}', [KaryawanSakitController::class, 'edit'])->name('karyawan-sakit.edit');
        Route::post('/karyawan-sakit/store', [KaryawanSakitController::class, 'store'])->name('karyawan-sakit.store');
        Route::post('/karyawan-sakit/update/{id}', [KaryawanSakitController::class, 'update'])->name('karyawan-sakit.update');
        Route::post('/karyawan-sakit/destroy/{id}', [KaryawanSakitController::class, 'destroy'])->name('karyawan-sakit.destroy');


        // Izin Terlambat
        Route::get('/karyawan-izin/terlambat/index', [KaryawanIzinTerlambatController::class, 'index'])->name('karyawan-izinterlambat.index');
        Route::get('/karyawan-izin/terlambat/getUser', [KaryawanIzinTerlambatController::class, 'getUser'])->name('karyawan-izinterlambat.getUser');
        Route::get('/karyawan-izin/terlambat/create', [KaryawanIzinTerlambatController::class, 'create'])->name('karyawan-izinterlambat.create');
        Route::get('/karyawan-izin/terlambat/edit/{id}', [KaryawanIzinTerlambatController::class, 'edit'])->name('karyawan-izinterlambat.edit');
        Route::post('/karyawan-izin/terlambat/store', [KaryawanIzinTerlambatController::class, 'store'])->name('karyawan-izinterlambat.store');
        Route::post('/karyawan-izin/terlambat/update/{id}', [KaryawanIzinTerlambatController::class, 'update'])->name('karyawan-izinterlambat.update');
        Route::post('/karyawan-izin/terlambat/destroy/{id}', [KaryawanIzinTerlambatController::class, 'destroy'])->name('karyawan-izinterlambat.destroy');


        // Cuti Tahunan
        Route::get('/karyawan-cuti/tahunan/index', [KaryawanCutiTahunanController::class, 'index'])->name('karyawan-cutitahunan.index');
        Route::get('/karyawan-cuti/tahunan/getUser', [KaryawanCutiTahunanController::class, 'getUser'])->name('karyawan-cutitahunan.getUser');
        Route::get('/karyawan-cuti/tahunan/create', [KaryawanCutiTahunanController::class, 'create'])->name('karyawan-cutitahunan.create');
        Route::get('/karyawan-cuti/tahunan/edit/{id}', [KaryawanCutiTahunanController::class, 'edit'])->name('karyawan-cutitahunan.edit');
        Route::post('/karyawan-cuti/tahunan/store', [KaryawanCutiTahunanController::class, 'store'])->name('karyawan-cutitahunan.store');
        Route::post('/karyawan-cuti/tahunan/update/{id}', [KaryawanCutiTahunanController::class, 'update'])->name('karyawan-cutitahunan.update');
        Route::post('/karyawan-cuti/tahunan/destroy/{id}', [KaryawanCutiTahunanController::class, 'destroy'])->name('karyawan-cutitahunan.destroy');

        // Kegiatan Harian
        Route::get('/karyawan-kegiatan/harian/index', [KaryawanKegiatanHarianController::class, 'index'])->name('karyawan-kegiatanharian.index');
        Route::get('/karyawan-kegiatan/harian/getUser', [KaryawanKegiatanHarianController::class, 'getUser'])->name('karyawan-kegiatanharian.getUser');
        Route::get('/karyawan-kegiatan/harian/create', [KaryawanKegiatanHarianController::class, 'create'])->name('karyawan-kegiatanharian.create');
        Route::get('/karyawan-kegiatan/harian/edit/{id}', [KaryawanKegiatanHarianController::class, 'edit'])->name('karyawan-kegiatanharian.edit');
        Route::post('/karyawan-kegiatan/harian/store', [KaryawanKegiatanHarianController::class, 'store'])->name('karyawan-kegiatanharian.store');
        Route::post('/karyawan-kegiatan/harian/update/{id}', [KaryawanKegiatanHarianController::class, 'update'])->name('karyawan-kegiatanharian.update');
        Route::post('/karyawan-kegiatan/harian/destroy/{id}', [KaryawanKegiatanHarianController::class, 'destroy'])->name('karyawan-kegiatanharian.destroy');

        // Lembur
        Route::get('/karyawan-lembur/index', [KaryawanLemburController::class, 'index'])->name('karyawan-lembur.index');
        Route::get('/karyawan-lembur/getUser', [KaryawanLemburController::class, 'getUser'])->name('karyawan-lembur.getUser');
        Route::get('/karyawan-lembur/create', [KaryawanLemburController::class, 'create'])->name('karyawan-lembur.create');
        Route::get('/karyawan-lembur/edit/{id}', [KaryawanLemburController::class, 'edit'])->name('karyawan-lembur.edit');
        Route::post('/karyawan-lembur/store', [KaryawanLemburController::class, 'store'])->name('karyawan-lembur.store');
        Route::post('/karyawan-lembur/update/{id}', [KaryawanLemburController::class, 'update'])->name('karyawan-lembur.update');
        Route::post('/karyawan-lembur/destroy/{id}', [KaryawanLemburController::class, 'destroy'])->name('karyawan-lembur.destroy');
    });

    // Customer Service
    Route::group(['middleware' => [CekLevel::class . ':3']], function () {

        // Dokter dan Jadwal
        Route::get('/customer-dokter/index', [KaryawanDokterController::class, 'index'])->name('customer-dokter.index');
        Route::get('/customer-dokter/jadwal/{id}', [KaryawanDokterController::class, 'jadwal'])->name('customer-dokter.jadwal');
        Route::get('/customer-dokter/cutidokter/{id}', [KaryawanDokterController::class, 'cutidokter'])->name('customer-dokter.cutidokter');
        Route::post('/customer-dokter/storecutidokter', [KaryawanDokterController::class, 'storecutidokter'])->name('customer-dokter.storecutidokter');
        Route::post('/customer-dokter/updatecutidokter/{id}', [KaryawanDokterController::class, 'updatecutidokter'])->name('customer-dokter.updatecutidokter');
        Route::post('/customer-dokter/destroycutidokter/{id}', [KaryawanDokterController::class, 'destroycutidokter'])->name('customer-dokter.destroycutidokter');
        Route::post('/customer-dokter/storejadwaldokter', [KaryawanDokterController::class, 'storejadwaldokter'])->name('customer-dokter.storejadwaldokter');
        Route::post('/customer-dokter/updatejadwaldokter/{id}', [KaryawanDokterController::class, 'updatejadwaldokter'])->name('customer-dokter.updatejadwaldokter');
        Route::post('/customer-dokter/destroyjadwaldokter/{id}', [KaryawanDokterController::class, 'destroyjadwaldokter'])->name('customer-dokter.destroyjadwaldokter');
    });

    // Humas Marketing
    Route::group(['middleware' => [CekLevel::class . ':4']], function () {

        // Berita
        Route::get('/humas-berita/index', [KaryawanBeritaController::class, 'index'])->name('humas-berita.index');
        Route::get('/humas-berita/create', [KaryawanBeritaController::class, 'create'])->name('humas-berita.create');
        Route::get('/humas-berita/edit/{id}', [KaryawanBeritaController::class, 'edit'])->name('humas-berita.edit');
        Route::post('/humas-berita/store', [KaryawanBeritaController::class, 'store'])->name('humas-berita.store');
        Route::post('/humas-berita/update/{id}', [KaryawanBeritaController::class, 'update'])->name('humas-berita.update');
        Route::post('/humas-berita/destroy/{id}', [KaryawanBeritaController::class, 'destroy'])->name('humas-berita.destroy');
    });


    // Direktur
    Route::group(['middleware' => [CekLevel::class . ':19']], function () {

        // Cuti Menikah
        Route::get('/direktur-cutimenikah/index', [DirekturCutiMenikahController::class, 'index'])->name('direktur-cutimenikah.index');
        Route::get('/direktur-cutimenikah/generatepdf', [DirekturCutiMenikahController::class, 'generatepdf'])->name('direktur-cutimenikah.generatepdf');
        Route::get('/direktur-cutimenikah/generateexcel', [DirekturCutiMenikahController::class, 'generateexcel'])->name('direktur-cutimenikah.generateexcel');
        Route::post('/direktur-cutimenikah/diterima/{id}', [DirekturCutiMenikahController::class, 'diterima'])->name('direktur-cutimenikah.diterima');
        Route::post('/direktur-cutimenikah/ditolak/{id}', [DirekturCutiMenikahController::class, 'ditolak'])->name('direktur-cutimenikah.ditolak');

        // Pulang Cepat
        Route::get('/direktur-pulangcepat/index', [DirekturPulangCepatController::class, 'index'])->name('direktur-pulangcepat.index');
        Route::get('/direktur-pulangcepat/generatepdf', [DirekturPulangCepatController::class, 'generatepdf'])->name('direktur-pulangcepat.generatepdf');
        Route::get('/direktur-pulangcepat/generateexcel', [DirekturPulangCepatController::class, 'generateexcel'])->name('direktur-pulangcepat.generateexcel');
        Route::post('/direktur-pulangcepat/diterima/{id}', [DirekturPulangCepatController::class, 'diterima'])->name('direktur-pulangcepat.diterima');
        Route::post('/direktur-pulangcepat/ditolak/{id}', [DirekturPulangCepatController::class, 'ditolak'])->name('direktur-pulangcepat.ditolak');

        // Potong Gaji
        Route::get('/direktur-potonggaji/index', [DirekturPotongGajiController::class, 'index'])->name('direktur-potonggaji.index');
        Route::get('/direktur-potonggaji/generatepdf', [DirekturPotongGajiController::class, 'generatepdf'])->name('direktur-potonggaji.generatepdf');
        Route::get('/direktur-potonggaji/generateexcel', [DirekturPotongGajiController::class, 'generateexcel'])->name('direktur-potonggaji.generateexcel');
        Route::post('/direktur-potonggaji/diterima/{id}', [DirekturPotongGajiController::class, 'diterima'])->name('direktur-potonggaji.diterima');
        Route::post('/direktur-potonggaji/ditolak/{id}', [DirekturPotongGajiController::class, 'ditolak'])->name('direktur-potonggaji.ditolak');

        // Izin Keluar
        Route::get('/direktur-izinkeluar/index', [DirekturIzinKeluarController::class, 'index'])->name('direktur-izinkeluar.index');
        Route::get('/direktur-izinkeluar/generatepdf', [DirekturIzinKeluarController::class, 'generatepdf'])->name('direktur-izinkeluar.generatepdf');
        Route::get('/direktur-izinkeluar/generateexcel', [DirekturIzinKeluarController::class, 'generateexcel'])->name('direktur-izinkeluar.generateexcel');
        Route::post('/direktur-izinkeluar/diterima/{id}', [DirekturIzinKeluarController::class, 'diterima'])->name('direktur-izinkeluar.diterima');
        Route::post('/direktur-izinkeluar/ditolak/{id}', [DirekturIzinKeluarController::class, 'ditolak'])->name('direktur-izinkeluar.ditolak');

        // Cuti Melahirkan
        Route::get('/direktur-cutimelahirkan/index', [DirekturCutiMelahirkanController::class, 'index'])->name('direktur-cutimelahirkan.index');
        Route::get('/direktur-cutimelahirkan/generatepdf', [DirekturCutiMelahirkanController::class, 'generatepdf'])->name('direktur-cutimelahirkan.generatepdf');
        Route::get('/direktur-cutimelahirkan/generateexcel', [DirekturCutiMelahirkanController::class, 'generateexcel'])->name('direktur-cutimelahirkan.generateexcel');
        Route::post('/direktur-cutimelahirkan/diterima/{id}', [DirekturCutiMelahirkanController::class, 'diterima'])->name('direktur-cutimelahirkan.diterima');
        Route::post('/direktur-cutimelahirkan/ditolak/{id}', [DirekturCutiMelahirkanController::class, 'ditolak'])->name('direktur-cutimelahirkan.ditolak');

        // Sakit
        Route::get('/direktur-sakit/index', [DirekturSakitController::class, 'index'])->name('direktur-sakit.index');
        Route::get('/direktur-sakit/generatepdf', [DirekturSakitController::class, 'generatepdf'])->name('direktur-sakit.generatepdf');
        Route::get('/direktur-sakit/generateexcel', [DirekturSakitController::class, 'generateexcel'])->name('direktur-sakit.generateexcel');
        Route::post('/direktur-sakit/diterima/{id}', [DirekturSakitController::class, 'diterima'])->name('direktur-sakit.diterima');
        Route::post('/direktur-sakit/ditolak/{id}', [DirekturSakitController::class, 'ditolak'])->name('direktur-sakit.ditolak');

        // Izin Terlambat
        Route::get('/direktur-izinterlambat/index', [DirekturIzinTerlambatController::class, 'index'])->name('direktur-izinterlambat.index');
        Route::get('/direktur-izinterlambat/generatepdf', [DirekturIzinTerlambatController::class, 'generatepdf'])->name('direktur-izinterlambat.generatepdf');
        Route::get('/direktur-izinterlambat/generateexcel', [DirekturIzinTerlambatController::class, 'generateexcel'])->name('direktur-izinterlambat.generateexcel');
        Route::post('/direktur-izinterlambat/diterima/{id}', [DirekturIzinTerlambatController::class, 'diterima'])->name('direktur-izinterlambat.diterima');
        Route::post('/direktur-izinterlambat/ditolak/{id}', [DirekturIzinTerlambatController::class, 'ditolak'])->name('direktur-izinterlambat.ditolak');

        // Cuti Tahunan
        Route::get('/direktur-cutitahunan/index', [DirekturCutiTahunanController::class, 'index'])->name('direktur-cutitahunan.index');
        Route::get('/direktur-cutitahunan/generatepdf', [DirekturCutiTahunanController::class, 'generatepdf'])->name('direktur-cutitahunan.generatepdf');
        Route::get('/direktur-cutitahunan/generateexcel', [DirekturCutiTahunanController::class, 'generateexcel'])->name('direktur-cutitahunan.generateexcel');
        Route::post('/direktur-cutitahunan/diterima/{id}', [DirekturCutiTahunanController::class, 'diterima'])->name('direktur-cutitahunan.diterima');
        Route::post('/direktur-cutitahunan/ditolak/{id}', [DirekturCutiTahunanController::class, 'ditolak'])->name('direktur-cutitahunan.ditolak');

        // Kegiatan Harian
        Route::get('/direktur-kegiatanharian/index', [DirekturKegiatanHarianController::class, 'index'])->name('direktur-kegiatanharian.index');
        Route::get('/direktur-kegiatanharian/generatepdf', [DirekturKegiatanHarianController::class, 'generatepdf'])->name('direktur-kegiatanharian.generatepdf');
        Route::get('/direktur-kegiatanharian/generateexcel', [DirekturKegiatanHarianController::class, 'generateexcel'])->name('direktur-kegiatanharian.generateexcel');
        Route::post('/direktur-kegiatanharian/diterima/{id}', [DirekturKegiatanHarianController::class, 'diterima'])->name('direktur-kegiatanharian.diterima');
        Route::post('/direktur-kegiatanharian/ditolak/{id}', [DirekturKegiatanHarianController::class, 'ditolak'])->name('direktur-kegiatanharian.ditolak');

        // Lembur
        Route::get('/direktur-lembur/index', [DirekturLemburController::class, 'index'])->name('direktur-lembur.index');
        Route::get('/direktur-lembur/generatepdf', [DirekturLemburController::class, 'generatepdf'])->name('direktur-lembur.generatepdf');
        Route::get('/direktur-lembur/generateexcel', [DirekturLemburController::class, 'generateexcel'])->name('direktur-lembur.generateexcel');
        Route::post('/direktur-lembur/diterima/{id}', [DirekturLemburController::class, 'diterima'])->name('direktur-lembur.diterima');
        Route::post('/direktur-lembur/ditolak/{id}', [DirekturLemburController::class, 'ditolak'])->name('direktur-lembur.ditolak');
    });

    // Operator
    Route::group(['middleware' => [CekLevel::class . ':20']], function () {

        // User Registrasi
        Route::get('/operator-users/index', [OperatorUserController::class, 'index'])->name('operator-users.index');
        Route::get('/operator-users/create', [OperatorUserController::class, 'create'])->name('operator-users.create');
        Route::get('/operator-users/generatepdf', [OperatorUserController::class, 'generatepdf'])->name('operator-users.generatepdf');
        Route::get('/operator-users/generateexcel', [OperatorUserController::class, 'generateexcel'])->name('operator-users.generateexcel');
        Route::get('/operator-users/edit/{id}', [OperatorUserController::class, 'edit'])->name('operator-users.edit');
        Route::post('/operator-users/store', [OperatorUserController::class, 'store'])->name('operator-users.store');
        Route::post('/operator-users/update/{id}', [OperatorUserController::class, 'update'])->name('operator-users.update');
        Route::post('/operator-users/destroy/{id}', [OperatorUserController::class, 'destroy'])->name('operator-users.destroy');

        // Level
        Route::get('/operator-level/index', [OperatorLevelController::class, 'index'])->name('operator-level.index');
        Route::get('/operator-level/create', [OperatorLevelController::class, 'create'])->name('operator-level.create');
        Route::get('/operator-level/generatepdf', [OperatorLevelController::class, 'generatepdf'])->name('operator-level.generatepdf');
        Route::get('/operator-level/generateexcel', [OperatorLevelController::class, 'generateexcel'])->name('operator-level.generateexcel');
        Route::get('/operator-level/edit/{id}', [OperatorLevelController::class, 'edit'])->name('operator-level.edit');
        Route::post('/operator-level/store', [OperatorLevelController::class, 'store'])->name('operator-level.store');
        Route::post('/operator-level/update/{id}', [OperatorLevelController::class, 'update'])->name('operator-level.update');
        Route::post('/operator-level/destroy/{id}', [OperatorLevelController::class, 'destroy'])->name('operator-level.destroy');

        // Jabatan
        Route::get('/operator-jabatan/index', [OperatorJabatanController::class, 'index'])->name('operator-jabatan.index');
        Route::get('/operator-jabatan/generatepdf', [OperatorJabatanController::class, 'generatepdf'])->name('operator-jabatan.generatepdf');
        Route::get('/operator-jabatan/generateexcel', [OperatorJabatanController::class, 'generateexcel'])->name('operator-jabatan.generateexcel');
        Route::get('/operator-jabatan/create', [OperatorJabatanController::class, 'create'])->name('operator-jabatan.create');
        Route::get('/operator-jabatan/edit/{id}', [OperatorJabatanController::class, 'edit'])->name('operator-jabatan.edit');
        Route::post('/operator-jabatan/store', [OperatorJabatanController::class, 'store'])->name('operator-jabatan.store');
        Route::post('/operator-jabatan/update/{id}', [OperatorJabatanController::class, 'update'])->name('operator-jabatan.update');
        Route::post('/operator-jabatan/destroy/{id}', [OperatorJabatanController::class, 'destroy'])->name('operator-jabatan.destroy');

        // ========================================================================================

        // Cuti Menikah
        Route::get('/operator-cutimenikah/index', [OperatorCutiMenikahController::class, 'index'])->name('operator-cutimenikah.index');
        Route::get('/operator-cutimenikah/generatepdf', [OperatorCutiMenikahController::class, 'generatepdf'])->name('operator-cutimenikah.generatepdf');
        Route::get('/operator-cutimenikah/generateexcel', [OperatorCutiMenikahController::class, 'generateexcel'])->name('operator-cutimenikah.generateexcel');
        Route::post('/operator-cutimenikah/diterima/{id}', [OperatorCutiMenikahController::class, 'diterima'])->name('operator-cutimenikah.diterima');
        Route::post('/operator-cutimenikah/ditolak/{id}', [OperatorCutiMenikahController::class, 'ditolak'])->name('operator-cutimenikah.ditolak');

        // Pulang Cepat
        Route::get('/operator-pulangcepat/index', [OperatorPulangCepatController::class, 'index'])->name('operator-pulangcepat.index');
        Route::get('/operator-pulangcepat/generatepdf', [OperatorPulangCepatController::class, 'generatepdf'])->name('operator-pulangcepat.generatepdf');
        Route::get('/operator-pulangcepat/generateexcel', [OperatorPulangCepatController::class, 'generateexcel'])->name('operator-pulangcepat.generateexcel');
        Route::post('/operator-pulangcepat/diterima/{id}', [OperatorPulangCepatController::class, 'diterima'])->name('operator-pulangcepat.diterima');
        Route::post('/operator-pulangcepat/ditolak/{id}', [OperatorPulangCepatController::class, 'ditolak'])->name('operator-pulangcepat.ditolak');

        // Potong Gaji
        Route::get('/operator-potonggaji/index', [OperatorPotongGajiController::class, 'index'])->name('operator-potonggaji.index');
        Route::get('/operator-potonggaji/generatepdf', [OperatorPotongGajiController::class, 'generatepdf'])->name('operator-potonggaji.generatepdf');
        Route::get('/operator-potonggaji/generateexcel', [OperatorPotongGajiController::class, 'generateexcel'])->name('operator-potonggaji.generateexcel');
        Route::post('/operator-potonggaji/diterima/{id}', [OperatorPotongGajiController::class, 'diterima'])->name('operator-potonggaji.diterima');
        Route::post('/operator-potonggaji/ditolak/{id}', [OperatorPotongGajiController::class, 'ditolak'])->name('operator-potonggaji.ditolak');

        // Izin Keluar
        Route::get('/operator-izinkeluar/index', [OperatorIzinKeluarController::class, 'index'])->name('operator-izinkeluar.index');
        Route::get('/operator-izinkeluar/generatepdf', [OperatorIzinKeluarController::class, 'generatepdf'])->name('operator-izinkeluar.generatepdf');
        Route::get('/operator-izinkeluar/generateexcel', [OperatorIzinKeluarController::class, 'generateexcel'])->name('operator-izinkeluar.generateexcel');
        Route::post('/operator-izinkeluar/diterima/{id}', [OperatorIzinKeluarController::class, 'diterima'])->name('operator-izinkeluar.diterima');
        Route::post('/operator-izinkeluar/ditolak/{id}', [OperatorIzinKeluarController::class, 'ditolak'])->name('operator-izinkeluar.ditolak');

        // Cuti Melahirkan
        Route::get('/operator-cutimelahirkan/index', [OperatorCutiMelahirkanController::class, 'index'])->name('operator-cutimelahirkan.index');
        Route::get('/operator-cutimelahirkan/generatepdf', [OperatorCutiMelahirkanController::class, 'generatepdf'])->name('operator-cutimelahirkan.generatepdf');
        Route::get('/operator-cutimelahirkan/generateexcel', [OperatorCutiMelahirkanController::class, 'generateexcel'])->name('operator-cutimelahirkan.generateexcel');
        Route::post('/operator-cutimelahirkan/diterima/{id}', [OperatorCutiMelahirkanController::class, 'diterima'])->name('operator-cutimelahirkan.diterima');
        Route::post('/operator-cutimelahirkan/ditolak/{id}', [OperatorCutiMelahirkanController::class, 'ditolak'])->name('operator-cutimelahirkan.ditolak');

        // Izin Sakit
        Route::get('/operator-sakit/index', [OperatorSakitController::class, 'index'])->name('operator-sakit.index');
        Route::get('/operator-sakit/generatepdf', [OperatorSakitController::class, 'generatepdf'])->name('operator-sakit.generatepdf');
        Route::get('/operator-sakit/generateexcel', [OperatorSakitController::class, 'generateexcel'])->name('operator-sakit.generateexcel');
        Route::post('/operator-sakit/diterima/{id}', [OperatorSakitController::class, 'diterima'])->name('operator-sakit.diterima');
        Route::post('/operator-sakit/ditolak/{id}', [OperatorSakitController::class, 'ditolak'])->name('operator-sakit.ditolak');

        // Izin Terlambat
        Route::get('/operator-izinterlambat/index', [OperatorIzinTerlambatController::class, 'index'])->name('operator-izinterlambat.index');
        Route::get('/operator-izinterlambat/generatepdf', [OperatorIzinTerlambatController::class, 'generatepdf'])->name('operator-izinterlambat.generatepdf');
        Route::get('/operator-izinterlambat/generateexcel', [OperatorIzinTerlambatController::class, 'generateexcel'])->name('operator-izinterlambat.generateexcel');
        Route::post('/operator-izinterlambat/diterima/{id}', [OperatorIzinTerlambatController::class, 'diterima'])->name('operator-izinterlambat.diterima');
        Route::post('/operator-izinterlambat/ditolak/{id}', [OperatorIzinTerlambatController::class, 'ditolak'])->name('operator-izinterlambat.ditolak');

        // Cuti Tahunan
        Route::get('/operator-cutitahunan/index', [OperatorCutiTahunanController::class, 'index'])->name('operator-cutitahunan.index');
        Route::get('/operator-cutitahunan/generatepdf', [OperatorCutiTahunanController::class, 'generatepdf'])->name('operator-cutitahunan.generatepdf');
        Route::get('/operator-cutitahunan/generateexcel', [OperatorCutiTahunanController::class, 'generateexcel'])->name('operator-cutitahunan.generateexcel');
        Route::post('/operator-cutitahunan/diterima/{id}', [OperatorCutiTahunanController::class, 'diterima'])->name('operator-cutitahunan.diterima');
        Route::post('/operator-cutitahunan/ditolak/{id}', [OperatorCutiTahunanController::class, 'ditolak'])->name('operator-cutitahunan.ditolak');

        // Kegiatan Harian
        Route::get('/operator-kegiatanharian/index', [OperatorKegiatanHarianController::class, 'index'])->name('operator-kegiatanharian.index');
        Route::get('/operator-kegiatanharian/generatepdf', [OperatorKegiatanHarianController::class, 'generatepdf'])->name('operator-kegiatanharian.generatepdf');
        Route::get('/operator-kegiatanharian/generateexcel', [OperatorKegiatanHarianController::class, 'generateexcel'])->name('operator-kegiatanharian.generateexcel');
        Route::post('/operator-kegiatanharian/diterima/{id}', [OperatorKegiatanHarianController::class, 'diterima'])->name('operator-kegiatanharian.diterima');
        Route::post('/operator-kegiatanharian/ditolak/{id}', [OperatorKegiatanHarianController::class, 'ditolak'])->name('operator-kegiatanharian.ditolak');

        // Lembur
        Route::get('/operator-lembur/index', [OperatorLemburController::class, 'index'])->name('operator-lembur.index');
        Route::get('/operator-lembur/generatepdf', [OperatorLemburController::class, 'generatepdf'])->name('operator-lembur.generatepdf');
        Route::get('/operator-lembur/generateexcel', [OperatorLemburController::class, 'generateexcel'])->name('operator-lembur.generateexcel');
        Route::post('/operator-lembur/diterima/{id}', [OperatorLemburController::class, 'diterima'])->name('operator-lembur.diterima');
        Route::post('/operator-lembur/ditolak/{id}', [OperatorLemburController::class, 'ditolak'])->name('operator-lembur.ditolak');
    });
});
