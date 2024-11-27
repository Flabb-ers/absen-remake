<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\PengajuanRekapPresensi;
use Illuminate\Support\Facades\Session;


class PengajuanRekapPresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $prodiId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->prodiId = Session::get('user.prodiId');
            return $next($request);
        });
    }

    public function index()
    {
        $prodiId = $this->prodiId;
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
            ->when($prodiId, function ($query) use ($prodiId) {
                return $query->whereHas('kelas.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
                });
            })
            ->latest()
            ->get();

        $kelasAll = Jadwal::all();

        return view('pages.pengajuanRekapPresensi.index', compact('presensis', 'kelasAll'));
    }

    public function confirm()
    {
        $prodiId = $this->prodiId;
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
            ->when($prodiId, function ($query) use ($prodiId) {
                return $query->whereHas('kelas.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
                });
            })
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
        $prodiId = $this->prodiId;
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
            ->when($prodiId, function ($query) use ($prodiId) {
                return $query->whereHas('kelas.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
                });
            })
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

            $allKaprodiApproved = true;
            $allWadirApproved = true;

            foreach ($absenRecords as $absen) {
                $setujuKaprodi = $request->has('kaprodi') ? true : false;
                $setujuWadir = $request->has('wakil_direktur') ? true : false;

                if ($setujuKaprodi && !$absen->setuju_kaprodi) {
                    $absen->setuju_kaprodi = true;
                }
                if ($setujuWadir && !$absen->setuju_wadir) {
                    $absen->setuju_wadir = true;
                }

                if (!$absen->setuju_kaprodi) {
                    $allKaprodiApproved = false;
                }
                if (!$absen->setuju_wadir) {
                    $allWadirApproved = false;
                }

                $absen->save();
            }
            $statusPresensi = ($allKaprodiApproved && $allWadirApproved) ? 1 : 0;

            $presensi = PengajuanRekapPresensi::where('matkul_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->where('pertemuan', $pertemuan)
                ->first();

            if ($presensi) {
                $presensi->update(['status' => $statusPresensi]);
            }

            return redirect()->back()->with('success', 'Status persetujuan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status persetujuan: ' . $e->getMessage());
        }
    }
}
