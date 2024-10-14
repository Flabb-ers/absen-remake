<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Jadwal::all();
        $dosens = Dosen::latest()->get();
        return view('pages.data-master.data-dosen', compact('dosens', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nama' => 'required',
            'nidn' => [
                'nullable',
                'numeric',
                'digits:10',
                Rule::unique('dosens')->whereNull('deleted_at'),
            ],
            'jenis_kelamin' => 'required',
            'pembimbing_akademik' => 'required',
            'no_telephone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('dosens')->whereNull('deleted_at'),
            ],
            'agama' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('dosens')->whereNull('deleted_at'),
            ],
            'password' => 'required'
        ], [
            'nama.required' => 'Nama Dosen harus diisi',
            'nidn.numeric' => 'NIDN harus berupa angka',
            'nidn.digits' => 'NIDN harus terdiri dari 10 digit',
            'nidn.unique' => 'NIDN sudah terdaftar',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'pembimbing_akademik.required' => 'Status pembimbing akademik harus dipilih',
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
            'pembimbing_akademik' => $validateData['pembimbing_akademik']
        ]);

        return response()->json(['success' => 'Data dosen berhasil ditambahkan!'], 200);
    }





    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => [
                'nullable',
                'numeric',
                'digits:10',
                Rule::unique('dosens')->ignore($dosen->id)->whereNull('deleted_at'),
            ],
            'jenis_kelamin' => 'required|string',
            'pembimbing_akademik' => 'required',
            'no_telephone' => [
                'required',
                'string',
                'max:15',
                Rule::unique('dosens')->ignore($dosen->id)->whereNull('deleted_at'),
            ],
            'agama' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'email' => [
                'required',
                'email',
                Rule::unique('dosens')->ignore($dosen->id)->whereNull('deleted_at'),
            ],
        ], [
            'nama.required' => 'Nama dosen harus diisi',
            'nidn.numeric' => 'NIDN harus angka',
            'nidn.digits' => 'NIDN harus terdiri 10 digit',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'pembimbing_akademik.required' => 'Status pembimbing akademik harus dipilih',
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
            if ($request->$kolom !== null && $dosen->$kolom !== $request->$kolom) {
                $dosen->$kolom = $request->$kolom;
            }
        }

        $dosen->status = $request->status;
        $dosen->pembimbing_akademik = $request->pembimbing_akademik;
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
