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

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($kelas_id, $matkul_id)
    {
        $kelasAll = Kelas::all();
        $jadwal = Jadwal::where('kelas_id', $kelas_id)
                        ->where('matkuls_id', $matkul_id)
                        ->first();

        $tugas = Tugas::select('tugas_ke', 'jadwal_id', DB::raw('MIN(id) as id')) 
                        ->where('kelas_id', $kelas_id)
                        ->where('matkul_id', $matkul_id)
                        ->where('jadwal_id', $jadwal->id)
                        ->groupBy('tugas_ke', 'jadwal_id') 
                        ->get();
    
        return view('pages.dosen.data-nilai.tugas.index', compact('kelasAll', 'tugas', 'kelas_id', 'matkul_id'));
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create($kelas_id, $matkul_id)
    {
        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)
        ->orderBy('nim', 'asc') 
        ->get();

        $matkul = Matkul::where('id', $matkul_id)->first();
        $kelasAll = Kelas::all();

        $lastTugas = Tugas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->orderBy('tugas_ke', 'desc')
            ->first();

        $nextTugasKe = $lastTugas ? $lastTugas->tugas_ke + 1 : 1;
        $jadwal = Jadwal::where('matkuls_id', $matkul_id)
            ->where('kelas_id', $kelas_id)
            ->first();

        return view('pages.dosen.data-nilai.tugas.create', compact('mahasiswas', 'matkul', 'nextTugasKe', 'kelasAll', 'jadwal', 'kelas_id', 'matkul_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $kelas_id, $matkul_id)
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
        $jadwal_id = $request->jadwal_id;

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

        return redirect()->back()->with('success', 'Data tugas berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kelas_id, $matkul_id, $tugas_ke)
    {

        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)
            ->orderBy('nim')
            ->get();
    
        $tugas = Tugas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('tugas_ke', $tugas_ke)
            ->get();
        $kelasAll = Kelas::all();
        return view('pages.dosen.data-nilai.tugas.edit', compact('mahasiswas', 'tugas', 'kelas_id', 'matkul_id', 'tugas_ke','kelasAll'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kelas_id, $matkul_id, $tugas_ke)
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
                    'matkul_id' => $matkul_id,
                    'tugas_ke' => $tugas_ke,
                ],
                [
                    'nilai' => $request->nilai[$index],
                ]
            );
        }
    
        // Redirect back dengan pesan sukses
        return redirect()->back()->with('success', 'Data nilai berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nilai)
    {
        //
    }
}
