<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Tugas;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Session;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $userId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userId = Session::get('user.id');
            return $next($request);
        });
    }
    public function index($kelas_id, $matkul_id, $jadwal_id)
    {
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();

        $tugas = Tugas::select('tugas_ke', 'jadwal_id', DB::raw('MIN(id) as id'))
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->groupBy('tugas_ke', 'jadwal_id')
            ->get();

        return view('pages.dosen.data-nilai.tugas.index', compact('kelasAll', 'tugas', 'kelas_id', 'matkul_id', 'jadwal_id'));
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

        $lastTugas = Tugas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->orderBy('tugas_ke', 'desc')
            ->first();

        $nextTugasKe = $lastTugas ? $lastTugas->tugas_ke + 1 : 1;
        $jadwal = Jadwal::where('matkuls_id', $matkul_id)
            ->where('id', $jadwal_id)
            ->where('kelas_id', $kelas_id)
            ->first();

        return view('pages.dosen.data-nilai.tugas.create', compact('mahasiswas', 'matkul', 'nextTugasKe', 'kelasAll', 'jadwal', 'kelas_id', 'matkul_id', 'jadwal_id'));
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
            'tugas_ke' => 'required|integer',
            'jadwal_id' => 'required|exists:jadwals,id'
        ]);

        $mahasiswas_id = $request->mahasiswas_id;
        $nilais = $request->nilai;
        $tugas_ke = $request->tugas_ke;

        foreach ($mahasiswas_id as $index => $mahasiswa_id) {
            Tugas::create([
                'mahasiswa_id' => $mahasiswa_id,
                'matkul_id' => $matkul_id,
                'kelas_id' => $kelas_id,
                'jadwal_id' => $jadwal_id,
                'tugas_ke' => $tugas_ke,
                'nilai' => $nilais[$index],
            ]);
        }

        session()->flash('success', 'Data nilai berhasil ditambahkan.');
        session()->flash('tab', 'tugas');
        session()->flash('kelas_id', $kelas_id);
        session()->flash('matkul_id', $matkul_id);
        session()->flash('jadwal_id', $jadwal_id);
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kelas_id, $matkul_id, $jadwal_id, $tugas_ke)
    {

        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)
            ->where('status_krs', 1)
            ->orderBy('nim', 'asc')
            ->get();

        $tugas = Tugas::with('jadwal.dosen', 'matkul', 'kelas.prodi')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->where('tugas_ke', $tugas_ke)
            ->get();

        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        return view('pages.dosen.data-nilai.tugas.edit', compact('mahasiswas', 'tugas', 'kelas_id', 'matkul_id', 'tugas_ke', 'kelasAll', 'jadwal_id'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kelas_id, $matkul_id, $jadwal_id, $tugas_ke)
    {
        $request->validate([
            'mahasiswas_id' => 'required|array',
            'nilai' => 'required|array',
            'nilai.*' => 'numeric|min:0|max:100',
        ]);

        foreach ($request->mahasiswas_id as $index => $mahasiswa_id) {
            Tugas::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa_id,
                    'kelas_id' => $kelas_id,
                    'jadwal_id' => $jadwal_id,
                    'matkul_id' => $matkul_id,
                    'tugas_ke' => $tugas_ke,
                ],
                [
                    'nilai' => $request->nilai[$index],
                ]
            );
        }


        session()->flash('success', 'Data nilai berhasil diperbarui.');
        session()->flash('tab', 'tugas');
        session()->flash('kelas_id', $kelas_id);
        session()->flash('matkul_id', $matkul_id);
        session()->flash('jadwal_id', $jadwal_id);
        return redirect()->back();
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kelas_id, $matkul_id, $jadwal_id, $tugas_ke)
    {
        $tugasList = Tugas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('tugas_ke', $tugas_ke)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        foreach ($tugasList as $tugas) {
            $tugas->delete();
        }

        session()->flash('success', 'Data nilai berhasil dihapus.');
        session()->flash('tab', 'tugas');
        session()->flash('kelas_id', $kelas_id);
        session()->flash('matkul_id', $matkul_id);
        session()->flash('jadwal_id', $jadwal_id);
        return redirect()->back();
    }
}
