<?php

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\PengajuanRekapkontrak;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\WadirController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\DirekturController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\PengajuanRekapBeritaController;
use App\Http\Controllers\PengajuanRekapkontrakController;
use App\Http\Controllers\PengajuanRekapPresensiController;

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

Route::get('/', function () {
    return redirect('/presensi/dashboard');
});

Route::prefix('/presensi')->group(function () {
    // DASHBOARD
    Route::get('/dashboard', function () {
        return view('pages.dashboard.index');
    });
    // Data Master
    Route::prefix('/data-master')->group(function () {
        Route::resource('/data-kelas', KelasController::class)->except(['show']);
        Route::resource('/data-matkul', MatkulController::class)->except(['show']);
        Route::resource('/data-prodi', ProdiController::class)->except(['show']);
        Route::resource('/data-semester', SemesterController::class)->except(['show']);
        Route::put('/status', [SemesterController::class, 'gantiStatus'])->name('status.update');
        Route::resource('/data-ruangan', RuanganController::class)->except(['show']);
        Route::resource('/data-tahun-akademik', TahunAkademikController::class)->except(['show']);
        Route::resource('/data-direktur', DirekturController::class)->except(['show']);
        Route::resource('/data-dosen', DosenController::class)->except(['show']);
        Route::resource('/data-kaprodi', KaprodiController::class)->except(['show']);;
        Route::resource('/data-wadir', WadirController::class)->except(['show']);
    });

    // MAHASISWA
    Route::resource('/data-mahasiswa', MahasiswaController::class)->except(['show']);
    Route::get('/data-mahasiswa/{nama_kelas}', [MahasiswaController::class, 'kelas']);

    // JADWAL
    Route::resource('/jadwal-mengajar', JadwalController::class);

    // PRESENSI
    Route::resource('/data-presensi', PresensiController::class);
    Route::get('/data-presensi/isi-presensi/{id}', [PresensiController::class, 'absen']);
    Route::get('/data-presensi/edit/{id}/{matkuls_id}/{kelas_id}/{jadwal_id}', [PresensiController::class, 'edit']);
    Route::get('/data-presensi/rekap/1-7/{matkuls_id}/{kelas_id}/{jadwal_id}', [PresensiController::class, 'rekap1to7']);
    Route::get('/data-presensi/rekap/8-14/{matkuls_id}/{kelas_id}/{jadwal_id}', [PresensiController::class, 'rekap8to14']);

    // BERITA ACARA
    Route::get('/data-presensi/rekap/berita-acara-perkuliahan/1-7/{matkuls_id}/{kelas_id}/{jadwal_id}', [PresensiController::class, 'berita1to7']);
    Route::get('/data-presensi/rekap/berita-acara-perkuliahan/8-14/{matkuls_id}/{kelas_id}/{jadwal_id}', [PresensiController::class, 'berita8to14']);

    // KONTRAK
    Route::resource('/data-kontrak', KontrakController::class);
    Route::get('/data-kontrak/isi-kontrak/{id}', [KontrakController::class, 'create']);
    Route::get('/data-kontrak/rekap/{matkuls_id}/{kelas_id}/{jadwals_id}', [KontrakController::class, 'rekap']);



    // PENGAJUAN PRESENSI
    Route::resource('/pengajuan-konfirmasi/rekap-presensi', PengajuanRekapPresensiController::class);
    Route::get('/pengajuan-konfirmasi/presensi-disetujui', [PengajuanRekapPresensiController::class, 'confirm']);
    Route::get('/pengajuan-konfirmasi/rekap-presensi/{pertemuan}/{matkul_id}/{kelas_id}/{jadwal_id}', [PengajuanRekapPresensiController::class, 'edit']);
    route::put('/pengajuan-konfirmasi/rekap-presensi/{pertemuan}/{matkul_id}/{kelas_id}/{jadwal_id}', [PengajuanRekapPresensiController::class, 'update']);



    // PENGAJUAN BERITA
    Route::resource('/pengajuan-konfirmasi/rekap-berita', PengajuanRekapBeritaController::class);
    Route::get('/pengajuan-konfirmasi/rekap-berita/{pertemuan}/{matkul_id}/{kelas_id}/{jadwal_id}', [PengajuanRekapBeritaController::class, 'edit']);
    Route::put('/pengajuan-konfirmasi/rekap-berita/{pertemuan}/{matkul_id}/{kelas_id}/{jadwal_id}', [PengajuanRekapBeritaController::class, 'update']);
    Route::get('/pengajuan-konfirmasi/berita-disetujui', [PengajuanRekapBeritaController::class, 'confirm']);


    // PENGAJUAN KONTRAK
    Route::resource('/pengajuan-konfirmasi/rekap-kontrak', PengajuanRekapkontrakController::class);
    Route::get('/pengajuan-konfirmasi/rekap-kontrak/{jadwal_id}/{matkul_id}/{kelas_id}', [PengajuanRekapkontrakController::class, 'edit']);
    Route::put('/pengajuan-konfirmasi/rekap-kontrak/{jadwal_id}/{matkul_id}/{kelas_id}', [PengajuanRekapkontrakController::class, 'update']);
    Route::get('/pengajuan-konfirmasi/kontrak-disetujui', [PengajuanRekapKontrakController::class, 'confirm']);

    // NILAI
    Route::get('/data-nilai/{kelas_id}', [NilaiController::class, 'index']);
    Route::get('/data-nilai/{kelas_id}/{matkul_id}/detail', [NilaiController::class, 'detail']);

    // TUGAS
    Route::get('/data-nilai/{kelas_id}/{matkul_id}/tugas', [TugasController::class, 'index']);
    Route::get('/data-nilai/{kelas_id}/{matkul_id}/tugas/create', [TugasController::class, 'create']);
    Route::post('/data-nilai/{kelas_id}/{matkul_id}/tugas/', [TugasController::class, 'store']);
    Route::get('/data-nilai/{kelas_id}/{matkul_id}/tugas/{tugas_ke}/edit', [TugasController::class, 'edit'])->name('tugas.edit');
    Route::put('/data-nilai/{kelas_id}/{matkul_id}/tugas/{tugas_ke}', [TugasController::class, 'update'])->name('tugas.update');
});
