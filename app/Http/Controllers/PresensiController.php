<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Resume;
use App\Models\Kontrak;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $jadwals = Jadwal::with('dosen', 'matkul', 'kelas.prodi', 'ruangan')->latest()->get();
        $pertemuanCounts = [];
        foreach ($jadwals as $jadwal) {
            $pertemuan = Absen::where('jadwals_id', $jadwal->id)->max('pertemuan');
            $pertemuanCounts[$jadwal->id] = $pertemuan ?? 0;
        }
        return view('pages.dosen.data-presensi.index', compact('jadwals', 'pertemuanCounts'));
    }


    public function absen($id)
    {
        $jadwal = Jadwal::with('dosen', 'matkul', 'kelas', 'ruangan')
            ->where('id', $id)
            ->first();
        $pertemuan = Absen::where('jadwals_id', $jadwal->id)->max('pertemuan');
        $pertemuan = $pertemuan ? $pertemuan + 1 : 1;
        $mahasiswas = Mahasiswa::where('kelas_id', $jadwal->kelas->id)->get();
        $tahun = TahunAkademik::where('status',1)->first();
        return view('pages.dosen.data-presensi.presensi', compact('jadwal', 'mahasiswas', 'pertemuan','tahun'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->mahasiswas_id as $mahasiswaId) {
                Absen::create([
                    'pertemuan' => $request->pertemuan,
                    'tanggal' => now(),
                    'jadwals_id' => $request->jadwals_id,
                    'matkuls_id' => $request->matkuls_id,
                    'dosens_id' => $request->dosens_id,
                    'prodis_id' => $request->prodis_id,
                    'kelas_id' => $request->kelas_id,
                    'mahasiswas_id' => $mahasiswaId,
                    'tahun'=>$request->tahun,
                    'status' => $request->status[$mahasiswaId],
                ]);
            }
            Resume::create([
                'pertemuan' => $request->pertemuan,
                'tanggal' => now(),
                'waktu' => now(),
                'tahun'=>$request->tahun,
                'materi' => $request->materiResume,
                'jadwals_id' => $request->jadwals_id,
                'dosens_id' => $request->dosens_id,
                'matkuls_id' => $request->matkuls_id,
                'prodis_id' => $request->prodis_id,
                'kelas_id' => $request->kelas_id,
                'tidak_hadir' => $request->jumlahTidakHadir,
                'hadir' => $request->jumlahHadir,
            ]);


            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal disimpan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $matkuls_id, $kelas_id)
    {

        $resume = Resume::where('pertemuan', $id)
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkuls_id)
            ->first();

        $absens = Absen::with('prodi', 'matkul')
            ->where('pertemuan', $id)
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkuls_id)
            ->get();

        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)->get();

        return view('pages.dosen.data-presensi.edit', compact('resume',  'absens', 'mahasiswas', 'id'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kelas_id)
    {
        $request->validate([
            'pertemuan' => 'required',
            'matkuls_id' => 'required',
            'kelas_id' => 'required',
            'mahasiswas_id' => 'required|array',
            'status' => 'required|array',
        ]);


        foreach ($request->mahasiswas_id as $index => $mahasiswa_id) {
            $absensi = Absen::where('kelas_id', $request->kelas_id)
                ->where('mahasiswas_id', $mahasiswa_id)
                ->where('pertemuan', $request->pertemuan)
                ->where('matkuls_id', $request->matkuls_id)
                ->first();

            if ($absensi) {
                $absensi->update([
                    'status' => $request->status[$mahasiswa_id],
                ]);
            }
        }

        $resume = Resume::where('kelas_id', $request->kelas_id)
            ->where('matkuls_id', $request->matkuls_id)
            ->where('pertemuan', $request->pertemuan)
            ->first();

        if ($resume) {
            $resume->update([
                'materi' => $request->materiResume,
            ]);
        }
        return redirect()->back()
            ->with('success', 'Data presensi berhasil diperbarui.');
    }

    public function rekap1to7($matkuls_id, $kelas_id) {
        $absens = Absen::with('dosen', 'kelas', 'matkul', 'prodi', 'mahasiswa')
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->whereIn('pertemuan', [1, 2, 3, 4, 5, 6, 7]) 
            ->get();
    
        $tahunAkademik = TahunAkademik::first();
        
        return view('pages.dosen.data-presensi.rekap.presensi1-7', compact('absens', 'tahunAkademik'));
    }
    
    public function rekap8to14($matkuls_id, $kelas_id) {
        $absens = Absen::with('dosen', 'kelas', 'matkul', 'prodi', 'mahasiswa')
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->whereIn('pertemuan', [8, 9, 10, 11, 12, 13, 14])
            ->get();
    
        return view('pages.dosen.data-presensi.rekap.presensi8-14', compact('absens'));
    }

    public function berita1to7($matkuls_id, $kelas_id) {
        $beritas = Resume::with('dosen', 'matkul', 'kelas.prodi')
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->whereBetween('pertemuan', [1, 7]) 
            ->get();
    
        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $sem = ($semester->semester % 2 == 0) ? "GENAP" : "GANJIL";
        }
    
        return view('pages.dosen.data-presensi.rekap.berita1-7', compact('beritas', 'sem'));
    }
    
    public function berita8to14($matkuls_id, $kelas_id) {
        $beritas = Resume::with('dosen', 'matkul', 'kelas.prodi')
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->whereBetween('pertemuan', [8, 14])
            ->get();

        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $sem = ($semester->semester % 2 == 0) ? "GENAP" : "GANJIL";
        }
    
        return view('pages.dosen.data-presensi.rekap.berita8-14', compact('beritas', 'sem'));
    }
    
}
