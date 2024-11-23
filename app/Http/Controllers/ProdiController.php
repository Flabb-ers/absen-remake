<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Jadwal;
use App\Models\Kaprodi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Jadwal::all();
        $prodis = Prodi::latest()->get();
        return view('pages.data-master.data-prodi', compact('prodis', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kode_prodi' => [
                'required',
                Rule::unique('prodi')->whereNull('deleted_at'),
            ],
            'nama_prodi' => 'required',
            'singkatan' => 'required',
            'jenjang' => 'required'
        ], [
            'kode_prodi.required' => 'Kode prodi harus diisi',
            'kode_prodi.unique' => 'Kode prodi sudah digunakan',
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

        $validateData = $request->validate([
            'kode_prodi' => [
                'required',
                Rule::unique('prodi')->ignore($edit->id)->whereNull('deleted_at'),
            ],
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

        return response()->json(['success' => 'Program studi berhasil diupdate', 'prodi' => $edit]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        Kelas::where('s', $prodi->id)->delete();
        Kaprodi::where('prodis_id',$prodi->id)->delete();
        $prodi->delete();
        return response()->json(['success' => 'Program studi berhasil dihapus!']);
    }
}
