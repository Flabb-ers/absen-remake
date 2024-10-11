<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Kontrak;
use Illuminate\Http\Request;
use App\Models\PengajuanRekapkontrak;

class PengajuanRekapkontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kontraks = PengajuanRekapkontrak::with('kelas', 'jadwal', 'matkul')
            ->where('status', 0)
            ->latest()
            ->get();
            $kelasAll = Jadwal::all();
        return view('pages.pengajuanRekapKontrak.index', compact('kontraks','kelasAll'));
    }


    public function confirm()
    {
        $kontraks = PengajuanRekapkontrak::with('kelas', 'jadwal', 'matkul')
            ->where('status', 1)
            ->latest()
            ->get();
        $kelasAll = Jadwal::all();
        return view('pages.pengajuanRekapKontrak.disetujui', compact('kontraks','kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'jadwal_id' => 'required',
            'kelas_id' => 'required',
            'matkul_id' => 'required'
        ]);

        PengajuanRekapkontrak::create([
            'jadwal_id' => $validateData['jadwal_id'],
            'kelas_id' => $validateData['kelas_id'],
            'matkul_id' => $validateData['matkul_id']
        ]);

        return redirect()->back()->with('success', 'Pengajuan Kontrak Kuliah Berhasil');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jadwal_id, $matkul_id, $kelas_id)
    {
        $kontraks = Kontrak::with('matkul', 'kelas.semester', 'kelas.prodi')
            ->where('matkuls_id', $matkul_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwal_id)
            ->get();


        return view('pages.pengajuanRekapKontrak.rekap', compact('kontraks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $jadwal_id, $matkul_id, $kelas_id)
    {
        try {
            $kontraks = Kontrak::where('jadwals_id', $jadwal_id)
                ->where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->get();

            if ($kontraks->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada kontrak ditemukan.');
            }

            $updateData = [
                'setuju_kaprodi' => $request->has('kaprodi') ? true : false,
                'setuju_wadir' => $request->has('wakil_direktur') ? true : false,
            ];

            foreach ($kontraks as $kontrak) {
                $kontrak->update($updateData);
            }

            $statusKontrak = ($updateData['setuju_kaprodi'] && $updateData['setuju_wadir']) ? true : false;

            foreach ($kontraks as $kontrak) {
                $kontrak->update(['status' => $statusKontrak]);
            }

            $pengajuan = PengajuanRekapKontrak::where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->first();

            if ($pengajuan) {
                $pengajuan->update(['status' => $statusKontrak]);
            }

            return redirect()->back()->with('success', 'Status persetujuan kontrak berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status persetujuan kontrak: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanRekapkontrak $pengajuanRekapkontrak)
    {
        //
    }
}
