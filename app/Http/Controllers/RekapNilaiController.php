<?php

namespace App\Http\Controllers;

use App\Models\Aktif;
use App\Models\Etika;
use App\Models\Kelas;
use App\Models\Tugas;
use Illuminate\Http\Request;

class RekapNilaiController extends Controller
{
    public function index($kelas_id, $matkul_id, $jadwal_id)
    {
        $tugass = Tugas::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();
    
        $jumlahTugas = $tugass->pluck('tugas_ke')->unique()->count();
    
        $aktifs = Aktif::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();
    
        $dataAktif = $aktifs->keyBy('mahasiswa_id');
    
        $etikas = Etika::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();
    
        $dataEtika = $etikas->keyBy('mahasiswa_id');
    
        return view('pages.dosen.data-nilai.rekap.index', compact('tugass', 'jumlahTugas', 'dataAktif', 'dataEtika'));
    }
    
}
