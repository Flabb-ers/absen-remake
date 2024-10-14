<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Kontrak;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\PengajuanRekapkontrak;

class KontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwals = Jadwal::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'ruangan' => function ($query) {
                $query->withTrashed();
            }
        ])->latest()->get();

        $pertemuanCounts = [];
        $rekapKontrakStatus = [];

        foreach ($jadwals as $jadwal) {
            $pertemuan = Kontrak::where('jadwals_id', $jadwal->id)->max('pertemuan');
            $pertemuanCounts[$jadwal->id] = $pertemuan ?? 0;

            $status = PengajuanRekapkontrak::where('jadwal_id', $jadwal->id)
                ->where('kelas_id', $jadwal->kelas->id)
                ->where('matkul_id', $jadwal->matkul->id)
                ->pluck('status')
                ->first();

            $rekapKontrakStatus[$jadwal->id] = $status;
        }
        $kelasAll = Jadwal::all();

        return view('pages.dosen.data-kontrak.index', compact('jadwals', 'pertemuanCounts', 'rekapKontrakStatus', 'kelasAll'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $jadwal = Jadwal::with('dosen', 'matkul', 'kelas', 'ruangan')
            ->where('id', $id)
            ->first();
        $pertemuan = Absen::where('jadwals_id', $id)->max('pertemuan');
        $mahasiswas = Mahasiswa::where('kelas_id', $jadwal->kelas->id)->get();
        $kelasAll = Jadwal::all();
        $tahun = TahunAkademik::where('status', 1)->first();
        return view('pages.dosen.data-kontrak.kontrak', compact('jadwal', 'mahasiswas', 'pertemuan', 'tahun', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Kontrak::create([
            'tahun' => $request->tahun,
            'pertemuan' => $request->pertemuan,
            'matkuls_id' => $request->matkuls_id,
            'kelas_id' => $request->kelas_id,
            'materi' => $request->materiKontrak,
            'pustaka' => $request->pustakaKontrak,
            'jadwals_id' => $request->jadwals_id
        ]);

        return redirect()->back()->with('success', 'Kontrak berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kontrak = Kontrak::with(['matkul', 'kelas.prodi', 'jadwal.dosen'])
            ->findOrFail($id);
        $kelasAll = Jadwal::all();
        return view('pages.dosen.data-kontrak.edit', compact('kontrak', 'kelasAll'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kontrak = Kontrak::findOrFail($id);

        $validateData = $request->validate([
            'pustakaKontrak' => 'required',
            'materiKontrak' => 'required'
        ]);

        $kontrak->update([
            'pustaka' => $validateData['pustakaKontrak'],
            'materi' => $validateData['materiKontrak']
        ]);

        return redirect()->back()->with('success', 'Data kontrak berhasil diupdate');
    }


    public function rekap($matkuls_id, $kelas_id, $jadwals_id)
    {

        $kontraks = Kontrak::with('matkul', 'kelas.semester', 'kelas.prodi')
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwals_id)
            ->get();

        return view('pages.dosen.data-kontrak.rekap', compact('kontraks'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kontrak $kontrak) {}
}
