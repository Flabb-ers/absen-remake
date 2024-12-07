<?php

namespace App\Http\Controllers;

use App\Models\Uts;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UtsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $userId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userId = Session::get("user.id");
            return $next($request);
        });
    }
    public function index($kelas_id, $matkul_id, $jadwal_id)
    {
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        $utss = Uts::where('kelas_id', $kelas_id)
            ->where('jadwal_id', $jadwal_id)
            ->where('matkul_id', $matkul_id)
            ->select('matkul_id', 'kelas_id', 'jadwal_id', DB::raw('GROUP_CONCAT(mahasiswa_id) as mahasiswa_ids, GROUP_CONCAT(nilai) as nilai_total'))
            ->groupBy('matkul_id', 'kelas_id', 'jadwal_id')
            ->get();

        return  view('pages.dosen.data-nilai.uts.index', compact('kelasAll', 'kelas_id', 'matkul_id', 'jadwal_id', 'utss'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($kelas_id, $matkul_id, $jadwal_id)
    {
        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)
            ->where('status_krs', 1)
            ->orderBy('nim', 'asc')
            ->get();

        $matkul = Matkul::where('id', $matkul_id)->first();
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();

        $jadwal = Jadwal::where('matkuls_id', $matkul_id)
            ->where('kelas_id', $kelas_id)
            ->where('id', $jadwal_id)
            ->first();
        return view('pages.dosen.data-nilai.uts.create', compact('mahasiswas', 'matkul', 'kelasAll', 'jadwal', 'kelas_id', 'matkul_id', 'jadwal_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $kelas_id, $matkul_id, $jadwal_id)
    {
        $request->validate([
            'mahasiswas_id' => 'required|array',
            'mahasiswas_id.*' => 'exists:mahasiswas,id',
            'nilai' => 'required|array',
            'nilai.*' => 'numeric|min:0|max:100',
            'jadwal_id' => 'required|exists:jadwals,id'
        ]);

        $mahasiswas_id = $request->mahasiswas_id;
        $nilais = $request->nilai;

        foreach ($mahasiswas_id as $index => $mahasiswa_id) {
            Uts::create([
                'mahasiswa_id' => $mahasiswa_id,
                'matkul_id' => $matkul_id,
                'kelas_id' => $kelas_id,
                'jadwal_id' => $jadwal_id,
                'nilai' => $nilais[$index],
            ]);
        }

        session()->flash('success', 'Data nilai berhasil disimpan!');
        session()->flash('tab', 'uts');
        session()->flash('kelas_id', $kelas_id);
        session()->flash('matkul_id', $matkul_id);
        session()->flash('jadwal_id', $jadwal_id);
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kelas_id, $matkul_id, $jadwal_id)
    {

        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)
            ->where('status_krs', 1)
            ->orderBy('nim')
            ->get();

        $uts = Uts::with('jadwal.dosen', 'matkul', 'kelas.prodi')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        return view('pages.dosen.data-nilai.uts.edit', compact('mahasiswas', 'uts', 'kelas_id', 'matkul_id', 'kelasAll', 'jadwal_id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kelas_id, $matkul_id, $jadwal_id)
    {
        $request->validate([
            'mahasiswas_id' => 'required|array',
            'nilai' => 'required|array',
            'nilai.*' => 'numeric|min:0|max:100',
        ]);

        foreach ($request->mahasiswas_id as $index => $mahasiswa_id) {
            Uts::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa_id,
                    'kelas_id' => $kelas_id,
                    'jadwal_id' => $jadwal_id,
                    'matkul_id' => $matkul_id,
                ],
                [
                    'nilai' => $request->nilai[$index],
                ]
            );
        }


        session()->flash('success', 'Data nilai uas berhasil diperbarui.');
        session()->flash('tab', 'uts');
        session()->flash('kelas_id', $kelas_id);
        session()->flash('matkul_id', $matkul_id);
        session()->flash('jadwal_id', $jadwal_id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kelas_id, $matkul_id, $jadwal_id)
    {
        $uasList = Uts::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        foreach ($uasList as $uas) {
            $uas->delete();
        }

        session()->flash('success', 'Data nilai berhasil dihapus.');
        session()->flash('tab', 'uts');
        session()->flash('kelas_id', $kelas_id);
        session()->flash('matkul_id', $matkul_id);
        session()->flash('jadwal_id', $jadwal_id);
        return redirect()->back();
    }
}
