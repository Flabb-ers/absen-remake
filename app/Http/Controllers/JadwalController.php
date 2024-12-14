<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Message;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Jadwal::all();
        $dosens = Dosen::all();
        $kelass = Kelas::with('semester','prodi')->whereHas('semester', function ($query) {
            $query->where('status', 1);
        })->get();
        $matkuls = Matkul::with('semester','prodi')->orderBy('nama_matkul','asc')->get();
        $jadwals = Jadwal::with('dosen', 'kelas', 'matkul', 'ruangan')->latest()->get();
        $ruangans = Ruangan::all();
        $tahun = TahunAkademik::where('status','1')->first();
        return view('pages.jadwal-mengajar.index', compact('dosens', 'kelass', 'matkuls', 'jadwals', 'ruangans','tahun','kelasAll'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'matkul_id' => 'required|exists:matkuls,id',
        'dosen_id' => 'required|exists:dosens,id',
        'kelas_id' => 'required|exists:kelas,id',
        'ruangan_id' => 'required|exists:ruangans,id',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        'hari' => 'required',
    ], [
        'matkul_id.required' => 'Mata kuliah harus dipilih',
        'matkul_id.exists' => 'Mata kuliah tidak valid',
        'dosen_id.required' => 'Dosen harus dipilih',
        'dosen_id.exists' => 'Dosen tidak valid',
        'kelas_id.required' => 'Kelas harus dipilih',
        'kelas_id.exists' => 'Kelas tidak valid',
        'ruangan_id.required' => 'Ruangan harus dipilih',
        'ruangan_id.exists' => 'Ruangan tidak valid',
        'jam_mulai.required' => 'Jam mulai harus diisi',
        'jam_mulai.date_format' => 'Format jam mulai tidak valid',
        'jam_selesai.required' => 'Jam selesai harus diisi',
        'jam_selesai.date_format' => 'Format jam selesai tidak valid',
        'jam_selesai.after' => 'Jam selesai harus lebih dari jam mulai',
        'hari.required' => 'Hari harus dipilih',
    ]);

    $existingJadwal = Jadwal::where('matkuls_id', $request->matkul_id)
        ->where('kelas_id', $request->kelas_id)
        ->first();

    if ($existingJadwal) {
        return response()->json([
            'status' => 400,
            'error' => 'Jadwal sudah ada untuk kelas ini dan mata kuliah ini.'
        ], 400);
    }

    // Jika tidak ada, buat jadwal baru
    Jadwal::create([
        'dosens_id' => $request->dosen_id,
        'matkuls_id' => $request->matkul_id,
        'kelas_id' => $request->kelas_id,
        'tahun'=>$request->tahun,
        'ruangans_id' => $request->ruangan_id,
        'waktu_mulai' => $request->jam_mulai,
        'waktu_selesai' => $request->jam_selesai,
        'hari' => $request->hari,
    ]);

    return response()->json([
        'status' => 200,
        'success' => 'Jadwal berhasil ditambahkan!',
    ]);
}



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'matkul_id' => 'required|exists:matkuls,id',
            'dosen_id' => 'required|exists:dosens,id',
            'kelas_id' => 'required|exists:kelas,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'hari' => 'required|string'
        ], [
            'matkul_id.required' => 'Matkul harus dipilih',
            'matkul_id.exists' => 'Matkul tidak valid',
            'dosen_id.required' => 'Dosen harus dipilih',
            'dosen_id.exists' => 'Dosen tidak valid',
            'kelas_id.required' => 'Kelas harus dipilih',
            'kelas_id.exists' => 'Kelas tidak valid',
            'ruangan_id.required' => 'Ruangan harus dipilih',
            'ruangan_id.exists' => 'Ruangan tidak valid',
            'jam_mulai.required' => 'Jam mulai harus diisi',
            'jam_selesai.required' => 'Jam selesai harus diisi',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai',
            'hari.required' => 'Hari harus dipilih'
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->matkuls_id = $request->matkul_id;
        $jadwal->dosens_id = $request->dosen_id;
        $jadwal->kelas_id = $request->kelas_id;
        $jadwal->ruangans_id = $request->ruangan_id;
        $jadwal->waktu_mulai = $request->jam_mulai;
        $jadwal->waktu_selesai = $request->jam_selesai;
        $jadwal->hari = $request->hari;
        $jadwal->save();


        return response()->json(['success' => 'Jadwal berhasil diperbarui']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        Message::where('jadwal_id',$jadwal->id)->delete();

        return response()->json([
            'success' => 'Jadwal berhasil dihapus'
        ]);
    }
}
