<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $kelass = Kelas::with([
            'prodi' => function ($query) {
                $query->withTrashed();
            },
            'semester' => function ($query) {
                $query->withTrashed();
            },
            'mahasiswa'
        ])
            ->get();
        $kelasAll = Jadwal::all();
        return view('pages.data-mahasiswa.index', compact('kelass', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nama_lengkap' => 'required',
            'nim' => 'required|unique:mahasiswas,nim',
            'nisn' => 'required|unique:mahasiswas,nisn',
            'nik' => 'required|unique:mahasiswas,nik',
            'email' => 'required|email|unique:mahasiswas,email',
            'alamat' => 'required',
            'password' => 'required',
            'pembimbing_akademik' => 'required',
            'no_telephone' => 'required|unique:mahasiswas,no_telephone',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'nama_ibu' => 'required',
            'jenis_kelamin' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nim.required' => 'NIM harus diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'nisn.required' => 'NISN harus diisi',
            'nisn.unique' => 'NISN sudah terdaftar',
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'alamat.required' => 'Alamat harus diisi',
            'password.required' => 'Password harus diisi',
            'pembimbing_akademik.required' => 'Dosen pembimbing akademik harus diisi',
            'no_telephone.required' => 'Nomor telepone harus diisi',
            'no_telephone.unique' => 'Nomor telephone sudah tedaftar',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'nama_ibu.required' => 'Nama Ibu harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
            'kelas_id.required' => 'Kelas harus dipilih',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid',
        ]);

        Mahasiswa::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nim' => $request->nim,
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'no_telephone' => $request->no_telephone,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'nama_ibu' => $request->nama_ibu,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,
            'dosen_pembimbing_id' => $request->pembimbing_akademik,
            'is_first_login' => true
        ]);

        return response()->json(['success' => 'Mahasiswa berhasil ditambahkan']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'dosen_pembimbing_id' => 'required',
            'nim' => 'required|numeric',
            'nisn' => 'required|numeric',
            'nik' => 'required|numeric',
            'email' => 'required|email|max:255',
            'alamat' => 'required|string',
            'no_telephone' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
        ];

        if ($request->nim !== $mahasiswa->nim) {
            $rules['nim'] .= '|unique:mahasiswas,nim';
        }
        if ($request->nisn !== $mahasiswa->nisn) {
            $rules['nisn'] .= '|unique:mahasiswas,nisn';
        }
        if ($request->nik !== $mahasiswa->nik) {
            $rules['nik'] .= '|unique:mahasiswas,nik';
        }
        if ($request->email !== $mahasiswa->email) {
            $rules['email'] .= '|unique:mahasiswas,email';
        }
        if ($request->no_telephone !== $mahasiswa->no_telephone) {
            $rules['no_telephone'] .= '|unique:mahasiswas,no_telephone';
        }

        $request->validate($rules, [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nim.required' => 'NIM harus diisi',
            'nim.unique' => 'NIM sudah terdaftar',
            'nim.numeric' => 'NIM harus berupa angka',
            'nisn.required' => 'NISN harus diisi',
            'nisn.unique' => 'NISN sudah terdaftar',
            'nisn.numeric' => 'NISN harus berupa angka',
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'nik.numeric' => 'NIK harus berupa angka',
            'email.required' => 'Email harus diisi',
            'dosen_pembimbing_id.required' => 'Dosen pembimbing akademik harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'alamat.required' => 'Alamat harus diisi',
            'no_telephone.required' => 'Nomor telepon harus diisi',
            'no_telephone.unique' => 'Nomor telepon sudah terdaftar',
            'no_telephone.numeric' => 'Nomor telepon harus berupa angka',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'nama_ibu.required' => 'Nama Ibu harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'kelas_id.required' => 'Kelas harus dipilih',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid',
        ]);

        $updateData = $request->except('password');

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $mahasiswa->update($updateData);

        return response()->json(['success' => 'Data mahasiswa berhasil diperbarui'], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();
        return response()->json(['message' => 'Data mahasiswa berhasil dihapus'], 200);
    }


    public function kelas($id)
    {
        $namaKelas = Kelas::where('id', $id)->first();
        $mahasiswas = Mahasiswa::with('kelas.semester', 'kelas')
            ->where('kelas_id', $namaKelas->id)
            ->orderBy('nim', 'asc')
            ->get();

        $kelass = Kelas::with('prodi', 'semester')->get();
        $dosens = Dosen::where('pembimbing_akademik', 1)
            ->where('status', 1)
            ->get();
        $kelasId = Kelas::where('id', $id);
        $kelasAll = Jadwal::all();
        $kelasSem = Kelas::where('id_prodi', $namaKelas->id_prodi)
            ->where('jenis_kelas', $namaKelas->jenis_kelas)
            ->get();
        $kelasAlls = Kelas::where('id_prodi', $namaKelas->id_prodi)->first();
        return view('pages.data-mahasiswa.detail', compact('mahasiswas', 'kelass', 'namaKelas', 'dosens', 'kelasAlls', 'kelasAll', 'kelasSem', 'kelasId'));
    }


    public function pindahKelas(Request $request)
    {
        $request->validate([
            'mahasiswa_ids' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $mahasiswaIds = explode(',', $request->input('mahasiswa_ids'));
        $newKelasId = $request->input('kelas_id');

        Mahasiswa::whereIn('id', $mahasiswaIds)->each(function ($mahasiswa) use ($newKelasId) {
            $mahasiswa->update([
                'kelas_id' => $newKelasId,
                'status_krs' => 0,
            ]);
        });

        return redirect('/presensi/data-mahasiswa')->with('success', 'Kelas mahasiswa berhasil diperbarui!');
    }

    public function deleteCheck(Request $request)
    {
        $mahasiswaIds = explode(',', $request->mahasiswa_ids);
        Mahasiswa::whereIn('id', $mahasiswaIds)->delete();

        return response()->json(['status' => 'success']);
    }



    public function search(Request $request)
    {
        $search = $request->search;
        $kelasId = $request->kelas_id;

        $mahasiswas = Mahasiswa::with(['kelas', 'kelas.semester', 'kelas.prodi'])
            ->where('kelas_id', $kelasId)
            ->where(function ($query) use ($search) {
                $query->where('nama_lengkap', 'LIKE', "%$search%")
                    ->orWhere('nim', 'LIKE', "%$search%");
            })
            ->get();

        return response()->json(
            $mahasiswas
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'kelas_id' => 'required|exists:kelas,id',
        ],['file.mimes'=>'Format file tidak sesuai']);

        try {
            DB::beginTransaction();

            Excel::import(new MahasiswaImport($request->kelas_id), $request->file('file'));

            DB::commit();

            return response()->json([
                'success' => 'Data mahasiswa berhasil diimpor'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Format data Excel tidak sesuai',
                'errors' => $e->failures()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Terjadi kesalahan saat mengimpor data'
            ], 500);
        }
    }
}
