<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Semester;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = Prodi::all();
        $kelasAll = Jadwal::all();
        $semesters = Semester::orderBy('semester', 'asc')->get();
        $kelass = Kelas::with(['semester' => function ($query) {
            $query->withTrashed();
        }, 'prodi' => function ($query) {
            $query->withTrashed();
        }])
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(6);

        return view('pages.data-master.data-kelas', compact('prodis', 'semesters', 'kelass', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_prodi' => 'required',
            'id_semester' => 'required',
            'jenis_kelas' => 'required',
            'kode_kelas' => [
                'required',
                Rule::unique('kelas', 'kode_kelas')->whereNull('deleted_at')
            ]
        ], [
            'id_prodi.required' => 'Prodi harus dipilih',
            'id_semester.required' => 'Semester harus dipilih',
            'jenis_kelas.required' => 'Jenis kelas harus dipilih',
            'kode_kelas.required' => 'Kode kelas harus diisi',
            'kode_kelas.unique' => 'Kode kelas sudah terdaftar',
        ]);
        

        $prodi = Prodi::findOrFail($request->id_prodi);
        $semester = Semester::findOrFail($request->id_semester);
        $namaKelas = $prodi->singkatan . ' ' . $semester->semester . ($request->jenis_kelas === 'Reguler' ? 'A' : 'B');


        $kelas = Kelas::create([
            'id_prodi' => $request->id_prodi,
            'id_semester' => $request->id_semester,
            'jenis_kelas' => $request->jenis_kelas,
            'nama_kelas' => $namaKelas,
            'kode_kelas' => $request->kode_kelas
        ]);

        return response()->json([
            'success' => 'Kelas berhasil ditambahkan!',
            'kelas' => $kelas
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->kode_kelas != $request->kode_kelas) {
            $request->validate([
                'id_prodi' => 'required',
                'id_semester' => 'required',
                'jenis_kelas' => 'required',
                'kode_kelas' => 'required|unique:kelas,kode_kelas'
            ], [
                'id_prodi.required' => 'Prodi harus dipilih',
                'id_semester.required' => 'Semester harus dipilih',
                'jenis_kelas.required' => 'Jenis kelas harus dipilih',
                'kode_kelas.required' => 'Kode kelas harus diisi',
                'kode_kelas.unique' => 'Kode kelas sudah terdaftar',
            ]);
        } else {
            $request->validate([
                'id_prodi' => 'required',
                'id_semester' => 'required',
                'jenis_kelas' => 'required',
                'kode_kelas' => 'required'
            ], [
                'id_prodi.required' => 'Prodi harus dipilih',
                'id_semester.required' => 'Semester harus dipilih',
                'jenis_kelas.required' => 'Jenis kelas harus dipilih',
                'kode_kelas.required' => 'Kode kelas harus diisi',
            ]);
        }

        $prodi = Prodi::findOrFail($request->id_prodi);
        $semester = Semester::findOrFail($request->id_semester);

        $edited = $kelas->update([
            'id_prodi' => $request->id_prodi,
            'id_semester' => $request->id_semester,
            'jenis_kelas' => $request->jenis_kelas,
            'nama_kelas' => $prodi->singkatan . ' ' . $semester->semester . ($request->jenis_kelas === 'Reguler' ? 'A' : 'B'),
            'kode_kelas' => $request->kode_kelas
        ]);

        return response()->json([
            'success' => 'Kelas berhasil diperbarui!',
            'kelas' => $edited
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hapus = Kelas::findOrFail($id);
        Matkul::where('kelas_id', $id)->forceDelete();
        Mahasiswa::where('kelas_id', $id)->forceDelete();
        $hapus->delete();
        Jadwal::where('kelas_id',$id)->forceDelete();
        return response()->json([
            'success' => 'Kelas berhasil dihapus!',
        ]);
    }
}
