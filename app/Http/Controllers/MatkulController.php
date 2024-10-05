<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class MatkulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matkuls = Matkul::with('kelas.semester', 'dosen', 'ruangan')->latest()->paginate(6);
        return view('pages.data-master.data-matkul', compact('matkuls'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_matkul' => 'required|string|max:255',
            'kode' => 'required|unique:matkuls,kode',
        ], [
            'nama_matkul.required' => 'Nama mata kuliah harus diisi',
            'kode.required' => 'Kode haris diisi',
            'kode.unique' => 'Kode sudah terdaftar'
        ]);

        Matkul::create([
            'nama_matkul' => $request->nama_matkul,
            'kode' => $request->kode,
            'praktek' => $request->praktek,
            'teori' => $request->teori,
            'tahun'=>$request->tahun
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
        'nama_matkul' => 'required|string|max:255',
        'kode' => 'required|unique:matkuls,kode,' . $matkul->id,
    ], [
        'nama_matkul.required' => 'Nama mata kuliah wajib diisi',
        'kode.required' => 'Kode harus diisi',
        'kode.unique' => 'Kode sudah terdaftar',
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
        $matkul->delete();
        return response()->json(['success' => 'Mata kuliah berhasil dihapus']);
    }
}
