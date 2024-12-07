<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Wadir;
use App\Models\Jadwal;
use App\Models\Kaprodi;
use App\Models\Kontrak;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\PengajuanRekapkontrak;
use Illuminate\Support\Facades\Session;


class KontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $userId;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userId = session::get('user.id');
            return $next($request);
        });
    }


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
        ])
            ->where('dosens_id', $this->userId)
            ->latest()
            ->get();

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
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();

        return view('pages.dosen.data-kontrak.index', compact('jadwals', 'pertemuanCounts', 'rekapKontrakStatus', 'kelasAll'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $jadwal = Jadwal::with('dosen', 'matkul', 'kelas', 'ruangan')
            ->where('id', $id)
            ->where('dosens_id',  $this->userId)
            ->first();
        $pertemuan = Absen::where('jadwals_id', $id)->max('pertemuan');
        $mahasiswas = Mahasiswa::where('kelas_id', $jadwal->kelas->id)->get();
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        $tahun = TahunAkademik::where('status', 1)->first();
        return view('pages.dosen.data-kontrak.kontrak', compact('jadwal', 'mahasiswas', 'pertemuan', 'tahun', 'kelasAll'));
    }



    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     Kontrak::create([
    //         'tahun' => $request->tahun,
    //         'pertemuan' => $request->pertemuan,
    //         'matkuls_id' => $request->matkuls_id,
    //         'kelas_id' => $request->kelas_id,
    //         'materi' => $request->materiKontrak,
    //         'pustaka' => $request->pustakaKontrak,
    //         'jadwals_id' => $request->jadwals_id
    //     ]);

    //     return redirect()->back()->with('success', 'Kontrak berhasil ditambahkan');
    // }

    public function store(Request $request)
    {
        $kontrakData = [];
        foreach ($request->materiKontrak as $pertemuan => $materi) {
            $kontrakData[] = [
                'tahun' => $request->tahun,
                'pertemuan' => $pertemuan,
                'matkuls_id' => $request->matkuls_id,
                'kelas_id' => $request->kelas_id,
                'materi' => $materi ?? null,
                'pustaka' => $request->pustakaKontrak[$pertemuan] ?? null,
                'jadwals_id' => $request->jadwals_id,
            ];
        }

        Kontrak::insert($kontrakData);

        return redirect()->back()->with('success', 'Kontrak berhasil ditambahkan');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kontrak = Kontrak::with(['matkul', 'kelas.prodi', 'jadwal.dosen'])->whereHas('jadwal', function ($query) use ($id) {
            $query->where('id', $id);
        })->get();
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        return view('pages.dosen.data-kontrak.edit', compact('kontrak', 'kelasAll'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $kontrak = Kontrak::where('jadwals_id',$id)->get();

    //     $validateData = $request->validate([
    //         'pustakaKontrak' => 'required',
    //         'materiKontrak' => 'required'
    //     ]);

    //     $kontrak->update([
    //         'pustaka' => $validateData['pustakaKontrak'],
    //         'materi' => $validateData['materiKontrak']
    //     ]);

    //     return redirect()->back()->with('success', 'Data kontrak berhasil diupdate');
    // }

    public function update(Request $request, $id)
    {
        $kontrak = Kontrak::where('jadwals_id', $id)->get();

        $validateData = $request->validate([
            'pustakaKontrak' => 'required|array', 
            'materiKontrak' => 'required|array', 
        ]);

        foreach ($kontrak as $index => $item) {
            $item->update([
                'materi' => $validateData['materiKontrak'][$index] ?? null,
                'pustaka' => $validateData['pustakaKontrak'][$index] ?? null,
            ]);
        }
        return redirect()->back()->with('success', 'Data kontrak berhasil ditambahkan');
    }



    public function rekap($matkuls_id, $kelas_id, $jadwals_id)
    {
        $kontraks = Kontrak::with('matkul', 'kelas', 'kelas.semester', 'kelas.prodi', 'jadwal.dosen')
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwals_id)
            ->get();
        $kaprodi = Kaprodi::where('prodis_id', $kontraks->first()->kelas->prodi->id)->first();

        $wadir = Wadir::where('status', 1)
            ->where('no', 1)
            ->first();

        return view('pages.dosen.data-kontrak.rekap', compact('kontraks', 'kaprodi', 'wadir'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kontrak $kontrak) {}
}
