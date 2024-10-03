<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosens = Dosen::all();
        return view('pages.data-master.data-dosen', compact('dosens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nama' => 'required',
            'nidn' => 'required|numeric|unique:dosens,nidn',
            'jenis_kelamin' => 'required',
            'no_telephone' => 'required|string|max:15|unique:dosens,no_telephone',
            'agama' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email',
            'password' => 'required'
        ], [
            'nama.required' => 'Nama Dosen harus diisi',
            'nidn.required' => 'NIDN harus diisi',
            'nidn.unique' => 'NIDN sudah terdaftar',
            'nidn.numeric' => 'NIDN harus angka',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'no_telephone.required' => 'Nomor WhatsApp harus diisi',
            'no_telephone.unique' => 'Nomor WhatsApp sudah terdaftar',
            'agama.required' => 'Agama harus dipilih',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi'
        ]);

        Dosen::create([
            'nama' => $validateData['nama'],
            'nidn' => $validateData['nidn'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'no_telephone' => $validateData['no_telephone'],
            'agama' => $validateData['agama'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'email' => $validateData['email'],
            'status' => 1,
            'password' => Hash::make($validateData['password']),
        ]);

        return response()->json(['success' => 'Data dosen berhasil ditambahkan!'], 200);
    }



    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'required|integer|unique:dosens,nidn,' . $dosen->id,
            'jenis_kelamin' => 'required|string',
            'no_telephone' => 'required|string|max:15|unique:dosens,no_telephone,' . $dosen->id,
            'agama' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
        ], [
            'nama.required' => 'Nama dosen harus diisi',
            'nidn.required' => 'NIDN harus diisi',
            'nidn.unique' => 'NIDN sudah terdaftar',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'no_telephone.required' => 'Nomor WhatsApp harus diisi',
            'no_telephone.unique' => 'Nomor WhatsApp sudah terdaftar',
            'agama.required' => 'Agama harus dipilih',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'status.required' => 'Status harus dipilih',
        ]);

        $kolomUpdate = [
            'nama',
            'nidn',
            'jenis_kelamin',
            'no_telephone',
            'agama',
            'tanggal_lahir',
            'tempat_lahir',
            'email'
        ];
        
        foreach ($kolomUpdate as $kolom) {
            if ($dosen->$kolom !== $request->$kolom) {
                $dosen->$kolom = $request->$kolom;
            }
        }

        $dosen->status = $request->status;
        $dosen->save();

        return response()->json(['success' => 'Data dosen berhasil diperbarui']);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return response()->json(['success' => 'Dosen berhasil dihapus.']);
    }
}
