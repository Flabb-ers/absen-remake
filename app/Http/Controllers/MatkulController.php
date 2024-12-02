<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatkulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Jadwal::all();
        $prodis = Prodi::all();
        $semesters = Semester::all();
        $matkuls = Matkul::with('prodi', 'semester')->latest()->paginate(6);
        return view('pages.data-master.data-matkul', compact('matkuls', 'kelasAll', 'prodis', 'semesters'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'nama_matkul' => [
                'required',
                'string',
                'max:255',
                Rule::unique('matkuls')->where(function ($query) use ($request) {
                    return $query->where('semester_id', $request->semester_id)
                                 ->where('prodi_id', $request->prodi_id);
                })
            ],
            'alias' => [
                'required',
                'string',
                'max:255',
                Rule::unique('matkuls')->where(function ($query) use ($request) {
                    return $query->where('semester_id', $request->semester_id)
                                 ->where('prodi_id', $request->prodi_id);
                })
            ],
            'kode' => [
                'required',
                Rule::unique('matkuls')->where(function ($query) use ($request) {
                    return $query->where('semester_id', $request->semester_id)
                                 ->where('prodi_id', $request->prodi_id);
                })
            ],
            'prodi_id' => 'required|exists:prodi,id',
            'semester_id' => 'required|exists:semesters,id',
        ], [
            'nama_matkul.required' => 'Nama mata kuliah harus diisi',
            'nama_matkul.unique' => 'Nama mata kuliah sudah terdaftar untuk kombinasi semester dan program studi yang sama',
            'alias.required' => 'Nama mata kuliah harus diisi',
            'alias.unique' => 'Nama mata kuliah sudah terdaftar untuk kombinasi semester dan program studi yang sama',
            'kode.required' => 'Kode mata kuliah harus diisi',
            'kode.unique' => 'Kode mata kuliah sudah terdaftar untuk kombinasi semester dan program studi yang sama',
            'prodi_id.required' => 'Prodi harus dipilih',
            'prodi_id.exists' => 'Prodi yang dipilih tidak valid',
            'semester_id.required' => 'Semester harus dipilih',
            'semester_id.exists' => 'Semester yang dipilih tidak valid',
        ]);
        
        Matkul::create([
            'nama_matkul' => $request->nama_matkul,
            'alias' => $request->alias,
            'kode' => $request->kode,
            'semester_id' => $request->semester_id,
            'prodi_id' => $request->prodi_id,
            'praktek' => $request->praktek,
            'teori' => $request->teori,
        ]);
        

        return response()->json(['success' => 'Data mata kuliah berhasil ditambahkan']);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_matkul' => [
                'required',
                'string',
                'max:255',
                Rule::unique('matkuls', 'nama_matkul')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('semester_id', $request->semester_id)
                                 ->where('prodi_id', $request->prodi_id);
                }),
            ],
            'alias' => [
                'required',
                'string',
                'max:255',
                Rule::unique('matkuls', 'alias')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('semester_id', $request->semester_id)
                                 ->where('prodi_id', $request->prodi_id);
                }),
            ],
            'kode' => [
                'required',
                Rule::unique('matkuls', 'kode')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('semester_id', $request->semester_id)
                                 ->where('prodi_id', $request->prodi_id);
                }),
            ],
            'prodi_id' => 'required|exists:prodi,id',
            'semester_id' => 'required|exists:semesters,id',
        ], [
            'nama_matkul.required' => 'Nama mata kuliah harus diisi',
            'nama_matkul.unique' => 'Nama mata kuliah sudah terdaftar untuk kombinasi semester dan program studi yang sama',
            'alias.required' => 'Nama mata kuliah harus diisi',
            'alias.unique' => 'Nama mata kuliah sudah terdaftar untuk kombinasi semester dan program studi yang sama',
            'kode.required' => 'Kode harus diisi',
            'kode.unique' => 'Kode mata kuliah sudah terdaftar untuk kombinasi semester dan program studi yang sama',
            'prodi_id.required' => 'Prodi harus dipilih',
            'prodi_id.exists' => 'Prodi yang dipilih tidak valid',
            'semester_id.required' => 'Semester harus dipilih',
            'semester_id.exists' => 'Semester yang dipilih tidak valid',
        ]);
    
        $matkul = Matkul::findOrFail($id);
    
        $matkul->update([
            'nama_matkul' => $request->nama_matkul,
            'alias' => $request->alias,
            'kode' => $request->kode,
            'semester_id' => $request->semester_id,
            'prodi_id' => $request->prodi_id,
            'praktek' => $request->praktek,
            'teori' => $request->teori,
        ]);

        return response()->json(['success' => 'Data mata kuliah berhasil diupdate']);
    }
    




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $matkul = Matkul::findOrFail($id);
        Jadwal::where('matkuls_id', $id)->forceDelete();
        $matkul->delete();
        return response()->json(['success' => 'Mata kuliah berhasil dihapus']);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $matkuls = Matkul::with('prodi','semester')->where('nama_matkul', 'LIKE', "%$search%")
            ->orWhere('kode', 'LIKE', "%$search%")
            ->paginate(6);

        return response()->json($matkuls);
    }
}
