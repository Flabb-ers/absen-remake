<?php

namespace App\Http\Controllers;

use App\Models\Uas;
use App\Models\Uts;
use App\Models\Absen;
use App\Models\Aktif;
use App\Models\Etika;
use App\Models\Tugas;
use App\Models\Jadwal;
use App\Models\Kaprodi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\PengajuanRekapNilai;

class RekapNilaiController extends Controller
{
    public function index($kelas_id, $matkul_id, $jadwal_id)
    {
        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)->orderBy('nim','asc')->get();

        $tugass = Tugas::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $groupedTugas = $tugass->groupBy('mahasiswa_id');

        $jumlahTugas = max(1, $tugass->pluck('tugas_ke')->unique()->count());

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


        $jadwals = Jadwal::where('id',$jadwal_id)->first();


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

        return view(
            'pages.dosen.data-nilai.rekap.index',
            compact(
                'mahasiswas',
                'groupedTugas',
                'jumlahTugas',
                'dataAktif',
                'dataEtika',
                'dataAbsensi',
                'totalPertemuan',
                'utss',
                'uass',
                'jadwals'
            )
        );
    }


    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kelas_id' => 'required',
            'jadwal_id' => 'required',
            'matkul_id' => 'required'
        ]);

        $tahun = TahunAkademik::where('status', 1)->first();

        PengajuanRekapNilai::create([
            'kelas_id' => $validateData['kelas_id'],
            'matkul_id' => $validateData['matkul_id'],
            'jadwal_id' => $validateData['jadwal_id'],
            'tahun' => $tahun->tahun_akademik
        ]);

        session()->flash('success', 'Pengajuan verifikasi nilai berhasil');
        session()->flash('tab', 'rekap');
        session()->flash('kelas_id', $validateData['kelas_id']);
        session()->flash('matkul_id', $validateData['matkul_id']);
        session()->flash('jadwal_id', $validateData['jadwal_id']);
        return redirect()->back();
    }


    public function pengajuan(){
        

        $pengajuans = PengajuanRekapNilai::latest()->get();
        $kelasAll = Jadwal::all();
        return view('pages.pengajuanRekapNilai.index',compact('pengajuans','kelasAll'));
    }

    public function diajukan($kelas_id, $matkul_id, $jadwal_id)
    {
        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)->orderBy('nim','asc')->get();

        $tugass = Tugas::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $groupedTugas = $tugass->groupBy('mahasiswa_id');

        $jumlahTugas = max(1, $tugass->pluck('tugas_ke')->unique()->count());

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


        $jadwals = Jadwal::where('id',$jadwal_id)->first();


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

        $kaprodi = Kaprodi::Where('prodis_id',$jadwals->first()->kelas->prodi->id)->first();

        return view(
            'pages.pengajuanRekapNilai.diajukan',
            compact(
                'mahasiswas',
                'groupedTugas',
                'jumlahTugas',
                'dataAktif',
                'dataEtika',
                'kaprodi',
                'dataAbsensi',
                'totalPertemuan',
                'utss',
                'uass',
                'jadwals'
            )
        );
    }
}
