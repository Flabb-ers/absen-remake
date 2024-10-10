<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Resume;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\PengajuanRekapBerita;

class PengajuanRekapBeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beritas = PengajuanRekapBerita::with(['matkul', 'kelas', 'jadwal' => function ($query) {
            $query->withTrashed();
        }])
        ->where('status', 0)
        ->latest()
        ->get();
    
        $kelasAll = Kelas::all();
    
        return view('pages.pengajuanRekapBerita.index', compact('beritas', 'kelasAll'));
    }
    
    public function confirm()
    {
        $beritas = PengajuanRekapBerita::with(['matkul', 'kelas', 'jadwal' => function ($query) {
            $query->withTrashed();
        }])
        ->where('status', 1)
        ->latest()
        ->get();
    
        $kelasAll = Kelas::all();
    
        return view('pages.pengajuanRekapBerita.disetujui', compact('beritas', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            "matkul_id" => "required",
            "kelas_id" => "required",
            "jadwal_id" => "required",
            "rentang" => 'required|in:1-7,8-14',
            "dosen_id" => 'required'
        ]);

        PengajuanRekapBerita::create([
            'matkuls_id' => $validateData['matkul_id'],
            'kelas_id' => $validateData['kelas_id'],
            'jadwal_id' => $validateData['jadwal_id'],
            'pertemuan' => $validateData['rentang'],
            'dosens_id' => $validateData['dosen_id'],
        ]);

        return redirect()->back()->with('success', 'Pengajuan Rekap Berita Acara Perkuliahan Berhasil');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($pertemuan, $matkul_id, $kelas_id,$jadwal_id)
    {
        $range = [];
        if ($pertemuan == '1-7') {
            $range = range(1, 7);
        } elseif ($pertemuan == '8-14') {
            $range = range(8, 14);
        }

        $beritas = Resume::with('dosen', 'matkul', 'kelas.prodi')
            ->where('matkuls_id', $matkul_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id',$jadwal_id)
            ->whereIn('pertemuan', $range)
            ->get();

        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $sem = ($semester->semester % 2 == 0) ? "GENAP" : "GANJIL";
        }
        $tahunAkademik = TahunAkademik::where('status', 1)->first();
        return view('pages.pengajuanRekapBerita.rekap', compact('beritas', 'tahunAkademik', 'sem', 'range'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $pertemuan, $matkul_id, $kelas_id,$jadwal_id)
    {
        $rentang = [];
        if ($pertemuan == '1-7') {
            $rentang = range(1, 7);
        } elseif ($pertemuan == '8-14') {
            $rentang = range(8, 14);
        }

        try {
            $resumeRecords = Resume::where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->where('jadwals_id',$jadwal_id)
                ->whereIn('pertemuan', $rentang)
                ->get();

            $updateData = [];

            if ($request->has('kaprodi')) {
                $updateData['setuju_kaprodi'] = true;
            } else {
                $updateData['setuju_kaprodi'] = false;
            }

            if ($request->has('wakil_direktur')) {
                $updateData['setuju_wadir'] = true;
            } else {
                $updateData['setuju_wadir'] = false;
            }

            Resume::where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->whereIn('pertemuan', $rentang)
                ->where('jadwals_id',$jadwal_id)
                ->update($updateData);

            if ($updateData['setuju_kaprodi'] && $updateData['setuju_wadir']) {
                $statusBerita = 1;
            } else {
                $statusBerita = 0;
            }

            $pengajuan = PengajuanRekapBerita::where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->where('pertemuan', $pertemuan)
                ->where('jadwal_id',$jadwal_id)
                ->first();

            if ($pengajuan) {
                $pengajuan->update(['status' => $statusBerita]);
            }

            return redirect()->back()->with('success', 'Status persetujuan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status persetujuan');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanRekapBerita $pengajuanRekapBerita)
    {
        //
    }
}
