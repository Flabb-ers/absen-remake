<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Direktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DirekturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Kelas::all();
        $direkturs = Direktur::latest()->get();
        $dosens = Dosen::all();
        return view('pages.data-master.data-direktur', compact('direkturs', 'dosens','kelasAll'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:direkturs,nama',
            'email' => 'required|unique:direkturs,email',
            'password' => 'required',
            'no_telephone'=>'required|unique:direkturs,no_telephone'
        ], [
            'nama.required' => 'Direktur harus diisi',
            'nama.unique' => 'Dosen sudah menjadi direktur',
            'no_telephone.unique' => 'Nomor WhatsApp sudah terdaftar',
            'no_telephone.required' => 'Nomor WhatsApp harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi'
        ]);

        Direktur::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'status' => 1,
            'no_telephone'=>$request->no_telephone,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => 'Direktur berhasil ditambahkan']);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, $id)
{
    $direktur = Direktur::findOrFail($id);

    // Validasi data yang diterima
    $request->validate([
        'nama' => 'required|string|max:255|unique:direkturs,nama,' . $direktur->id,
        'email' => 'required|email|unique:direkturs,email,' . $direktur->id,
        'no_telephone' => 'required|unique:direkturs,no_telephone,' . $direktur->id,
        'status' => 'required|boolean'
    ], [
        'nama.required' => 'Nama wajib diisi',
        'nama.unique' => 'Nama sudah digunakan',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah digunakan',
        'no_telephone.required' => 'Nomor WhatsApp wajib diisi',
        'no_telephone.unique' => 'Nomor WhatsApp sudah terdaftar',
        'status.required' => 'Status wajib dipilih',
        'status.boolean' => 'Status harus berupa nilai boolean'
    ]);

    // Update data jika ada perubahan
    if ($direktur->nama !== $request->nama) {
        $direktur->nama = $request->nama;
    }

    if ($direktur->email !== $request->email) {
        $direktur->email = $request->email;
    }

    if ($direktur->no_telephone !== $request->no_telephone) {
        $direktur->no_telephone = $request->no_telephone;
    }

    $direktur->status = $request->status;
    $direktur->save();

    return response()->json(['success' => 'Data direktur berhasil diperbarui']);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $direktur = Direktur::findOrFail($id);

        $direktur->delete();
        return response()->json(['success' => 'Data direktur berhasil dihapus.']);
    }
}
