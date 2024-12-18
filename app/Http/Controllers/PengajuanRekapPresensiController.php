<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Wadir;
use App\Models\Jadwal;
use App\Models\Kaprodi;
use App\Models\Direktur;
use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Models\PengajuanRekapPresensi;
use App\Notifications\PengajuanPresensiNotification;
use Illuminate\Support\Facades\Session;


class PengajuanRekapPresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $prodiId;
    protected $role;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->prodiId = Session::get('user.prodiId');
            $this->role = Session::get('user.role');
            return $next($request);
        });
    }

    public function index()
    {
        if ($this->role == 'kaprodi') {
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
        } elseif ($this->role == 'direktur' || $this->role == 'wakil_direktur') {
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
        }

        $kelasAll = Jadwal::all();

        return view('pages.pengajuanRekapPresensi.index', compact('presensis', 'kelasAll'));
    }

    public function confirm()
    {
        if ($this->role == 'kaprodi') {
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

        } elseif ($this->role == 'direktur' || $this->role == 'wakil_direktur') {
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
        }

        $kelasAll = Jadwal::all();

        return view('pages.pengajuanRekapPresensi.disetujui', compact('presensis', 'kelasAll'));
    }


    public function store(Request $request)
    {
        $kelas = Kelas::findOrFail($request->kelas_id);
        $wadirs = Wadir::all();
        $direkturs = Direktur::all();
        $kaprodi = Kaprodi::where('prodis_id', $kelas->id_prodi)->first();
        $validateData = $request->validate([
            'kelas_id' => 'required',
            'rentang' => 'required',
            'matkul_id' => 'required',
            'jadwal_id' => 'required',
        ]);

        $presensi = PengajuanRekapPresensi::with('jadwal', 'jadwal.dosen', 'kelas', 'matkul')->create([
            'kelas_id' => $validateData['kelas_id'],
            'matkul_id' => $validateData['matkul_id'],
            'pertemuan' => $validateData['rentang'],
            'jadwals_id' => $validateData['jadwal_id']
        ]);
        foreach ($wadirs as $wadir) {
            $wadir->notify(new PengajuanPresensiNotification($presensi));
        }
        foreach ($direkturs as $direktur) {
            $direktur->notify(new PengajuanPresensiNotification($presensi));
        }
        $kaprodi->notify(new PengajuanPresensiNotification($presensi));
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

        $kaprodi = Kaprodi::where('prodis_id', $absens->first()->prodis_id)->first();
        return view('pages.pengajuanRekapPresensi.rekap', compact('absens', 'rentang', 'kaprodi'));
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
            $absenRecords = Absen::with('dosen')->where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->whereIn('pertemuan', $rentang)
                ->where('jadwals_id', $jadwal_id)
                ->get();

            $allKaprodiApproved = true;
            $allWadirApproved = true;

            foreach ($absenRecords as $absen) {
                if ($request->has('kaprodi')) {
                    $absen->setuju_kaprodi = true;
                }

                if ($request->has('wakil_direktur')) {
                    $absen->setuju_wadir = true;
                }

                if ($request->has('uncheck_kaprodi')) {
                    $absen->setuju_kaprodi = false;
                }

                if ($request->has('uncheck_wakil_direktur')) {
                    $absen->setuju_wadir = false;
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

            if ($statusPresensi) {
                $dosen = Dosen::findOrFail($absenRecords->first()->dosens_id);
                $dosen->notify(new PengajuanPresensiNotification($presensi));
            }

            return redirect()->back()->with('success', 'Status persetujuan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status persetujuan: ' . $e->getMessage());
        }
    }
}
