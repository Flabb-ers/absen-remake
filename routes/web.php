<?php

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\PengajuanRekapkontrak;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UasController;
use App\Http\Controllers\UtsController;
use App\Http\Controllers\AktifController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\EtikaController;
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
use App\Http\Controllers\RekapNilaiController;
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
        $kelasAll = Jadwal::all();
        return view('pages.dashboard.index', compact('kelasAll'));
    });
    // Data Master
    Route::prefix('/data-master')->group(function () {
        Route::resource('/data-kelas', KelasController::class)->except(['show']);
        Route::get('/data-matkul/search', [MatkulController::class, 'search'])->name('data-matkul.search');
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
    Route::get('/data-mahasiswa/{id}', [MahasiswaController::class, 'kelas']);
    Route::post('/data-mahasiswa/move',[MahasiswaController::class,'pindahKelas']);

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

    // PENGAJUAN PRESENSI sini
    Route::prefix('/pengajuan-konfirmasi')->group(function () {
        Route::resource('/rekap-presensi', PengajuanRekapPresensiController::class);
        Route::get('/presensi-disetujui', [PengajuanRekapPresensiController::class, 'confirm']);
        Route::get('/rekap-presensi/{pertemuan}/{matkul_id}/{kelas_id}/{jadwal_id}', [PengajuanRekapPresensiController::class, 'edit']);
        route::put('/rekap-presensi/{pertemuan}/{matkul_id}/{kelas_id}/{jadwal_id}', [PengajuanRekapPresensiController::class, 'update']);



        // PENGAJUAN BERITA
        Route::resource('/rekap-berita', PengajuanRekapBeritaController::class);
        Route::get('/rekap-berita/{pertemuan}/{matkul_id}/{kelas_id}/{jadwal_id}', [PengajuanRekapBeritaController::class, 'edit']);
        Route::put('/rekap-berita/{pertemuan}/{matkul_id}/{kelas_id}/{jadwal_id}', [PengajuanRekapBeritaController::class, 'update']);
        Route::get('/berita-disetujui', [PengajuanRekapBeritaController::class, 'confirm']);


        // PENGAJUAN KONTRAK
        Route::resource('/rekap-kontrak', PengajuanRekapkontrakController::class);
        Route::get('/rekap-kontrak/{jadwal_id}/{matkul_id}/{kelas_id}', [PengajuanRekapkontrakController::class, 'edit']);
        Route::put('/rekap-kontrak/{jadwal_id}/{matkul_id}/{kelas_id}', [PengajuanRekapkontrakController::class, 'update']);
        Route::get('/kontrak-disetujui', [PengajuanRekapKontrakController::class, 'confirm']);
    });


    Route::prefix('/data-nilai')->group(function () {
        // NILAI
        Route::get('/{kelas_id}', [NilaiController::class, 'index']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/detail', [NilaiController::class, 'detail']);

        // TUGAS
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/tugas', [TugasController::class, 'index']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/tugas/create', [TugasController::class, 'create']);
        Route::post('/{kelas_id}/{matkul_id}/{jadwal_id}/tugas/', [TugasController::class, 'store']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/tugas/{tugas_ke}/edit', [TugasController::class, 'edit'])->name('tugas.edit');
        Route::put('/{kelas_id}/{matkul_id}/{jadwal_id}/tugas/{tugas_ke}', [TugasController::class, 'update'])->name('tugas.update');
        Route::delete('/{kelas_id}/{matkul_id}/{jadwal_id}/tugas/{tugas_ke}/delete', [TugasController::class, 'destroy']);

        // UAS
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/uas', [UasController::class, 'index']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/uas/create', [UasController::class, 'create']);
        Route::Post('/{kelas_id}/{matkul_id}/{jadwal_id}/uas', [UasController::class, 'store']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/uas/edit', [UasController::class, 'edit']);
        Route::put('/{kelas_id}/{matkul_id}/{jadwal_id}/uas', [UasController::class, 'update']);
        Route::delete('/{kelas_id}/{matkul_id}/{jadwal_id}/uas/', [UasController::class, 'destroy']);


        // UTS
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/uts', [UtsController::class, 'index']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/uts/create', [UtsController::class, 'create']);
        Route::Post('/{kelas_id}/{matkul_id}/{jadwal_id}/uts', [UtsController::class, 'store']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/uts/edit', [UtsController::class, 'edit']);
        Route::put('/{kelas_id}/{matkul_id}/{jadwal_id}/uts', [UtsController::class, 'update']);
        Route::delete('/{kelas_id}/{matkul_id}/{jadwal_id}/uts', [UtsController::class, 'destroy']);


        // ETIKA
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/etika', [EtikaController::class, 'index']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/etika/create', [EtikaController::class, 'create']);
        Route::Post('/{kelas_id}/{matkul_id}/{jadwal_id}/etika', [EtikaController::class, 'store']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/etika/edit', [EtikaController::class, 'edit']);
        Route::put('/{kelas_id}/{matkul_id}/{jadwal_id}/etika', [EtikaController::class, 'update']);
        Route::delete('/{kelas_id}/{matkul_id}/{jadwal_id}/etika', [EtikaController::class, 'destroy']);


        // AKTIF
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/aktif', [AktifController::class, 'index']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/aktif/create', [AktifController::class, 'create']);
        Route::Post('/{kelas_id}/{matkul_id}/{jadwal_id}/aktif', [AktifController::class, 'store']);
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/aktif/edit', [AktifController::class, 'edit']);
        Route::put('/{kelas_id}/{matkul_id}/{jadwal_id}/aktif', [AktifController::class, 'update']);
        Route::delete('/{kelas_id}/{matkul_id}/{jadwal_id}/aktif', action: [AktifController::class, 'destroy']);

        // REKAP NILAI
        Route::get('/{kelas_id}/{matkul_id}/{jadwal_id}/rekap',[RekapNilaiController::class,'index']);
        Route::post('/rekap',[RekapNilaiController::class,'store']);
        Route::get('/pengajuan/rekap-nilai',[RekapNilaiController::class,'pengajuan']);
        Route::get('/pengajuan/rekap-nilai/{kelas_id}/{matkul_id}/{jadwal_id}',[RekapNilaiController::class,'diajukan']);
        Route::put('/pengajuan/rekap-nilai/{kelas_id}/{matkul_id}/{jadwal_id}',[RekapNilaiController::class,'update']);
    });
});
