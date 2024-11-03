<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Wadir;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WadirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wadirs = Wadir::latest()->get();
        $dosens = Dosen::all();
        $kelasAll = Jadwal::all();
        return view('pages.data-master.data-wadir', compact('wadirs', 'dosens', 'kelasAll'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:wadirs,nama',
            'no_telephone' => 'required|unique:wadirs,no_telephone',
            'email' => 'required|unique:wadirs,email',
            'password' => 'required',
            'no' => 'required'
        ], [
            'nama.unique' => 'Dosen sudah menjadi wakil direktur',
            'nama.required' => 'Dosen harus diisi',
            'no_telephone.required' => 'Nomor WhatsApp harus diisi',
            'no_telephone.unique' => 'Nomor WhatsApp sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'no.required' => 'Posisi harus diisi'
        ]);

        if (in_array($request->no, [1, 2]) && Wadir::where('no', $request->no)->exists()) {
            return response()->json([
                'errors' => [
                    'no' => ['Wadir dengan nomor ' . $request->no . ' sudah ada, tidak bisa menambah lagi']
                ]
            ], 400);
        }


        Wadir::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telephone' => $request->no_telephone,
            'status' => 1,
            'no' => $request->no,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => 'Wakil direktur berhasil ditambahkan']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $wadir = Wadir::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:wadirs,nama,' . $wadir->id,
            'email' => 'required|email|unique:wadirs,email,' . $wadir->id,
            'no_telephone' => 'required|unique:wadirs,no_telephone,' . $wadir->id,
            'status' => 'required|boolean',
            'no' => 'required'
        ], [
            'nama.required' => 'Nama wakil direktur wajib diisi',
            'nama.unique' => 'Nama sudah menjadi wakil direktur',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'no_telephone.required' => 'Nomor WhatsApp wajib diisi',
            'no_telephone.unique' => 'Nomor  WhatsApp sudah terdaftar',
            'status.required' => 'Status wajib dipilih',
            'status.boolean' => 'Status harus berupa nilai boolean',
            'no.required' => 'Posisi wajib diisi'
        ]);

        if ($wadir->nama !== $request->nama) {
            $wadir->nama = $request->nama;
        }

        if ($wadir->email !== $request->email) {
            $wadir->email = $request->email;
        }

        if ($wadir->no_telephone !== $request->no_telephone) {
            $wadir->no_telephone = $request->no_telephone;
        }
        if ($wadir->no !== $request->no) {
            $wadir->no = $request->no;
        }

        $wadir->status = $request->status;
        $wadir->save();

        return response()->json(['success' => 'Data Wadir berhasil diperbarui']);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $wadir = Wadir::findOrFail($id);
        $wadir->delete();
        return response()->json(['success' => 'Wadir berhasil dihapus.']);
    }
}
