<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\PengajuanRekapPresensi;

class PengajuanRekapPresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presensis = PengajuanRekapPresensi::with([
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            },
            'jadwal.dosen' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('status', 0)
            ->latest()
            ->get();

        $kelasAll = Jadwal::all();

        return view('pages.pengajuanRekapPresensi.index', compact('presensis', 'kelasAll'));
    }

    public function confirm()
    {
        $presensis = PengajuanRekapPresensi::with([
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            },
            'jadwal.dosen' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('status', 1)
            ->latest()
            ->get();

        $kelasAll = Jadwal::all();

        return view('pages.pengajuanRekapPresensi.disetujui', compact('presensis', 'kelasAll'));
    }


    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kelas_id' => 'required',
            'rentang' => 'required',
            'matkul_id' => 'required',
            'jadwal_id' => 'required',
        ]);

        PengajuanRekapPresensi::create([
            'kelas_id' => $validateData['kelas_id'],
            'matkul_id' => $validateData['matkul_id'],
            'pertemuan' => $validateData['rentang'],
            'jadwals_id' => $validateData['jadwal_id']
        ]);

        return redirect()->back()->with('success', 'Pengajuan Rekap Presensi Berhasil');
    }

    public function edit($pertemuan, $matkul_id, $kelas_id, $jadwal_id)
    {
        $rentang = [];

        if ($pertemuan == '1-7') {
            $rentang = range(1, 7);
        } elseif ($pertemuan == '8-14') {
            $rentang = range(8, 14);
        }

        $absens = Absen::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'prodi' => function ($query) {
                $query->withTrashed();
            },
            'mahasiswa' => function ($query) {
                $query->withTrashed();
            }
        ])
        ->where('matkuls_id', $matkul_id)
        ->where('kelas_id', $kelas_id)
        ->where('jadwals_id', $jadwal_id)
        ->whereIn('pertemuan', $rentang)
        ->get();
        

        return view('pages.pengajuanRekapPresensi.rekap', compact('absens', 'rentang'));
    }

    public function update(Request $request, $pertemuan, $matkul_id, $kelas_id, $jadwal_id)
    {
        $rentang = [];
        if ($pertemuan == '1-7') {
            $rentang = range(1, 7);
        } elseif ($pertemuan == '8-14') {
            $rentang = range(8, 14);
        }

        try {
            $absenRecords = Absen::where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->whereIn('pertemuan', $rentang)
                ->where('jadwals_id', $jadwal_id)
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


            Absen::where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->whereIn('pertemuan', $rentang)
                ->where('jadwals_id', $jadwal_id)
                ->update($updateData);


            if ($updateData['setuju_kaprodi'] && $updateData['setuju_wadir']) {
                $statusPresensi = 1;
            } else {
                $statusPresensi = 0;
            }

            $presensi = PengajuanRekapPresensi::where('matkul_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->where('pertemuan', $pertemuan)
                ->first();

            $presensi->update(['status' => $statusPresensi]);

            return redirect()->back()->with('success', 'Status persetujuan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status persetujuan');
        }
    }
}
