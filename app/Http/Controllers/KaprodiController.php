<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Kaprodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;

class KaprodiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Kelas::all();
        $kaprodis = Kaprodi::with('prodi')->latest()->get();
        $dosens = Dosen::all();
        $prodis = Prodi::all();
        return view('pages.data-master.data-kaprodi', compact('dosens', 'prodis', 'kaprodis','kelasAll'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:kaprodi,nama',
            'prodis_id' => 'required|unique:kaprodi,prodis_id',
            'no_telephone' => 'required|unique:kaprodi,no_telephone',
            'email' => 'required|unique:kaprodi,email',
            'password' => 'required'
        ], [
            'nama.required' => 'Dosen harus dipilih',
            'nama.unique' => 'Dosen sudah menjadi kaprodi',
            'prodis_id.required' => 'Prodi harus dipilih',
            'no_telephone.unique' => 'Nomor WhatsApp sudah terdaftar',
            'no_telephone.required' => 'Nomor WhatsApp haris diisi',
            'prodis_id.unique' => 'Prodi ini sudah memiliki kaprodi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
        ]);

        Kaprodi::create([
            'nama' => $request->nama,
            'prodis_id' => $request->prodis_id,
            'no_telephone' => $request->no_telephone,
            'email' => $request->email,
            'status' => 1,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => 'Kaprodi berhasil ditambahkan!',
        ]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $kaprodi = Kaprodi::findOrFail($id);

    // Validasi data yang diterima
    $request->validate([
        'nama' => 'required|unique:kaprodi,nama,' . $kaprodi->id,
        'prodis_id' => 'required|unique:kaprodi,prodis_id,'.$kaprodi->id,
        'no_telephone' => 'required|unique:kaprodi,no_telephone,' . $kaprodi->id,
        'email' => 'required|email|unique:kaprodi,email,' . $kaprodi->id,
        'status' => 'required|in:0,1'
    ], [
        'nama.required' => 'Dosen harus dipilih',
        'nama.unique' => 'Dosen sudah menjadi kaprodi',
        'prodis_id.required' => 'Program studi harus diisi',
        'prodis_id.unique' => 'Program studi ini sudah memiliki kaprodi',
        'no_telephone.required' => 'Nomor WhatsApp harus diisi',
        'no_telephone.unique' => 'Nomor WhatsApp sudah terdaftar',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah digunakan',
        'status.required' => 'Status harus diisi',
        'status.in' => 'Status harus 0 atau 1',
    ]);

    // Update data jika ada perubahan
    if ($kaprodi->nama !== $request->nama) {
        $kaprodi->nama = $request->nama;
    }

    if ($kaprodi->no_telephone !== $request->no_telephone) {
        $kaprodi->no_telephone = $request->no_telephone;
    }

    if ($kaprodi->email !== $request->email) {
        $kaprodi->email = $request->email;
    }

    if ($kaprodi->prodis_id !== $request->prodis_id) {
        $kaprodi->prodis_id = $request->prodis_id;
    }

    $kaprodi->status = $request->status;
    $kaprodi->save();

    return response()->json(['success' => 'Kaprodi berhasil diupdate!']);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kaprodi = Kaprodi::findOrFail($id);
        $kaprodi->delete();
        return response()->json(['success' => 'Kaprodi berhasil dihapus']);
    }
}
