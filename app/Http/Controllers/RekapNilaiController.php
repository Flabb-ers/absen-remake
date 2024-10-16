<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Aktif;
use App\Models\Etika;
use App\Models\Absen;
use App\Models\Uas;
use App\Models\Uts;
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

        $absens = Absen::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->get();

        $dataAbsensi = $absens->groupBy('mahasiswas_id');

        $totalPertemuan = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->max('pertemuan');

        $dataAbsensi = $dataAbsensi->map(function ($absensiGroup, $mahasiswaId) use ($totalPertemuan) {
            $totalKehadiran = $absensiGroup->whereIn('status', ['H', 'T'])->count();
            $persentaseKehadiran = $totalPertemuan > 0 ? ($totalKehadiran / $totalPertemuan) * 15 : 0;

            return [
                'total_kegiatan' => $totalKehadiran,
                'persentase_kehadiran' => number_format($persentaseKehadiran, 2),
                'absensi' => $absensiGroup,
            ];
        });


        $utss = Uts::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        $uass = Uas::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

            
        return view('pages.dosen.data-nilai.rekap.index', compact('tugass', 'jumlahTugas', 'dataAktif', 'dataEtika', 'dataAbsensi', 'totalPertemuan', 'utss','uass'));
    }
}
