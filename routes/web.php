<?php

use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\WadirController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\DirekturController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TahunAkademikController;

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
        Route::resource('/data-kelas', KelasController::class);
        Route::resource('/data-matkul', MatkulController::class);
        Route::resource('/data-prodi', ProdiController::class);
        Route::resource('/data-semester', SemesterController::class);
        Route::put('/status', [SemesterController::class, 'gantiStatus'])->name('status.update');
        Route::resource('/data-ruangan', RuanganController::class);
        Route::resource('/data-tahun-akademik', TahunAkademikController::class);
        Route::resource('/data-direktur', DirekturController::class);
        Route::resource('/data-dosen', DosenController::class);
        Route::resource('/data-kaprodi', KaprodiController::class);
        Route::resource('/data-wadir', WadirController::class);
    });

    // MAHASISWA
    Route::resource('/data-mahasiswa', MahasiswaController::class);

    // JADWAL
    Route::resource('/jadwal-mengajar', JadwalController::class);

    // ABSEN
    Route::resource('/data-presensi', PresensiController::class);
    Route::get('/data-presensi/isi-presensi/{id}', [PresensiController::class, 'absen']);
    Route::get('/data-presensi/edit/{id}/{matkuls_id}/{kelas_id}', [PresensiController::class, 'edit']);
    Route::get('/data-presensi/rekap/1-7/{matkuls_id}/{kelas_id}', [PresensiController::class, 'rekap1to7']);
    Route::get('/data-presensi/rekap/8-14/{matkuls_id}/{kelas_id}', [PresensiController::class, 'rekap8to14']);

    // BERITA ACARA
    Route::get('/data-presensi/rekap/berita-acara-perkuliahan/1-7/{matkuls_id}/{kelas_id}',[PresensiController::class,'berita1to7']);
    Route::get('/data-presensi/rekap/berita-acara-perkuliahan/8-14/{matkuls_id}/{kelas_id}',[PresensiController::class,'berita8to14']);

    // KONTRAK
    Route::resource('/data-kontrak', KontrakController::class);
    Route::get('/data-kontrak/isi-kontrak/{id}', [KontrakController::class,'create']);
    Route::get('/data-kontrak/rekap/{matkuls_id}/{kelas_id}/{jadwals_id}', [KontrakController::class,'rekap']);
});
