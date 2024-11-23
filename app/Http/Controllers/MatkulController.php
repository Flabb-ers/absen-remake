<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Matkul;
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
        $kelass = Kelas::all();
        $matkuls = Matkul::with('kelas.semester')->latest()->paginate(6);
        return view('pages.data-master.data-matkul', compact('matkuls','kelasAll','kelass'));
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
                return $query->where('kelas_id', $request->kelas_id)->whereNull('deleted_at');
            }),
        ],
            'kode' => [
                'required',
                Rule::unique('matkuls')->where(function ($query) use ($request) {
                    return $query->where('kelas_id', $request->kelas_id)->whereNull('deleted_at');
                }),
            ],
            'kelas_id' => 'required|exists:kelas,id',
        ], [
            'nama_matkul.required' => 'Nama mata kuliah harus diisi',
            'nama_matkul.unique' => 'Nama mata kuliah sudah terdaftar di kelas ini',
            'kode.required' => 'Kode harus diisi',
            'kode.unique' => 'Kode mata kuliah sudah terdaftar di kelas ini',
            'kelas_id.required' => 'Kelas harus dipilih',
        ]);
    
        Matkul::create([
            'nama_matkul' => $request->nama_matkul,
            'kode' => $request->kode,
            'kelas_id' => $request->kelas_id,
            'praktek' => $request->praktek,
            'teori' => $request->teori,
            'tahun' => $request->tahun,
        ]);
    
        return response()->json(['success' => 'Data mata kuliah berhasil ditambahkan']);
    }
    



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $matkul = Matkul::findOrFail($id);
    
        $request->validate([
            'nama_matkul' => [
                'required',
                'string',
                'max:255',
                Rule::unique('matkuls')->ignore($matkul->id)->where(function ($query) use ($request) {
                    return $query->where('kelas_id', $request->kelas_id)->whereNull('deleted_at');
                }),
            ],
            'kode' => [
                'required',
                Rule::unique('matkuls')->ignore($matkul->id)->where(function ($query) use ($request) {
                    return $query->where('kelas_id', $request->kelas_id)->whereNull('deleted_at');
                }),
            ],
            'kelas_id' => 'required',
        ], [
            'nama_matkul.required' => 'Nama mata kuliah wajib diisi',
            'nama_matkul.unique' => 'Nama mata kuliah sudah terdaftar di kelas ini',
            'kode.required' => 'Kode harus diisi',
            'kode.unique' => 'Kode sudah terdaftar untuk kelas ini',
            'kelas_id.required' => 'Kelas harus dipilih',
        ]);
    
        $matkul->update([
            'nama_matkul' => $request->nama_matkul,
            'kode' => $request->kode,
            'praktek' => $request->praktek,
            'teori' => $request->teori,
        ]);
    
        return response()->json(['success' => 'Mata kuliah berhasil diperbarui']);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $matkul = Matkul::findOrFail($id);
        Jadwal::where('matkuls_id',$id)->forceDelete();
        $matkul->delete();
        return response()->json(['success' => 'Mata kuliah berhasil dihapus']);
    }

    public function search(Request $request)
{
    $search = $request->input('search');

    $matkuls = Matkul::where('nama_matkul', 'LIKE', "%$search%")
                    ->orWhere('kode', 'LIKE', "%$search%")
                    ->paginate(6);

    return response()->json($matkuls);
}

}
