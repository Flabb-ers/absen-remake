<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = Prodi::all();
        return view('pages.data-master.data-prodi',compact('prodis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
        
    $validateData = $request->validate([
        'kode_prodi' => 'required|unique:prodis,kode_prodi',
        'nama_prodi' => 'required',
        'singkatan' => 'required',
        'jenjang' => 'required'
    ], [
        'kode_prodi.required' => 'Kode prodi harus diisi',
        'kode_prodi.unique'=>'Kode prodi sudah digunakan',
        'nama_prodi.required' => 'Nama prodi harus diisi',
        'singkatan.required' => 'Singkatan harus diisi',
        'jenjang.required' => 'Jenjang harus diisi'
    ]);

    $prodi = Prodi::create([
        'kode_prodi' => $validateData['kode_prodi'],
        'nama_prodi' => $validateData['nama_prodi'],
        'singkatan' => $validateData['singkatan'],
        'jenjang' => $validateData['jenjang']
    ]);

    return response()->json(['success' => 'Program studi berhasil ditambahkan', 'prodi' => $prodi]);
}



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $edit = Prodi::findOrFail($id);

    $uniqueKodeProdiRule = 'required';
    if ($request->kode_prodi !== $edit->kode_prodi) {
        $uniqueKodeProdiRule .= '|unique:prodis,kode_prodi';
    }

    $validateData = $request->validate([
        'kode_prodi' => $uniqueKodeProdiRule,
        'nama_prodi' => 'required',
        'singkatan' => 'required',
        'jenjang' => 'required',
    ], [
        'kode_prodi.required' => 'Kode prodi harus diisi',
        'kode_prodi.unique' => 'Kode prodi sudah terdaftar',
        'nama_prodi.required' => 'Nama prodi harus diisi',
        'singkatan.required' => 'Singkatan harus diisi',
        'jenjang.required' => 'Jenjang harus diisi',
    ]);

    $edit->update($validateData);

    $prodi = Prodi::find($id);

    return response()->json(['success' => 'Program studi berhasil diupdate', 'prodi' => $prodi]);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {   $prodi = Prodi::findOrFail($id);
        $prodi->delete();
        return response()->json(['success' => 'Program studi berhasil dihapus!']);
    }
}
