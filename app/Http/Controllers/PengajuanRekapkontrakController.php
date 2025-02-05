<?php

namespace App\Http\Controllers;


use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Wadir;
use App\Models\Jadwal;
use App\Models\Kaprodi;
use App\Models\Kontrak;
use App\Models\Direktur;
use Illuminate\Http\Request;
use App\Models\PengajuanRekapkontrak;
use Illuminate\Support\Facades\Session;
use App\Notifications\PengajuanKontrakNotification;

class PengajuanRekapkontrakController extends Controller
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
        $prodiId = $this->prodiId;
        if ($this->role == 'kaprodi') {
            $kontraks = PengajuanRekapkontrak::with([
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
                },
                'matkul' => function ($query) {
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
            $kontraks = PengajuanRekapkontrak::with([
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
                },
                'matkul' => function ($query) {
                    $query->withTrashed();
                }
            ])
                ->where('status', 0)
                ->latest()
                ->get();
        }

        $kelasAll = Jadwal::all();
        return view('pages.pengajuanRekapKontrak.index', compact('kontraks', 'kelasAll'));
    }


    public function confirm()
    {
        if ($this->role == 'kaprodi') {
            $prodiId = $this->prodiId;
            $kontraks = PengajuanRekapkontrak::with([
                'kelas' => function ($query) {
                    $query->withTrashed();
                },
                'kelas.prodi' => function ($query) {
                    $query->withTrashed();
                },
                'jadwal' => function ($query) {
                    $query->withTrashed();
                },
                'jadwal.dosen' => function ($query) {
                    $query->withTrashed();
                },
                'matkul' => function ($query) {
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
            $kontraks = PengajuanRekapkontrak::with([
                'kelas' => function ($query) {
                    $query->withTrashed();
                },
                'kelas.prodi' => function ($query) {
                    $query->withTrashed();
                },
                'jadwal' => function ($query) {
                    $query->withTrashed();
                },
                'jadwal.dosen' => function ($query) {
                    $query->withTrashed();
                },
                'matkul' => function ($query) {
                    $query->withTrashed();
                }
            ])
                ->where('status', 1)
                ->latest()
                ->get();
        }

        $kelasAll = Jadwal::all();
        return view('pages.pengajuanRekapKontrak.disetujui', compact('kontraks', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kelas = Kelas::findOrFail($request->kelas_id);
        $wadirs = Wadir::all();
        $direkturs = Direktur::all();
        $kaprodi = Kaprodi::where('prodis_id', $kelas->id_prodi)->first();
        $validateData = $request->validate([
            'jadwal_id' => 'required',
            'kelas_id' => 'required',
            'matkul_id' => 'required'
        ]);

        $kontrak = PengajuanRekapkontrak::create([
            'jadwal_id' => $validateData['jadwal_id'],
            'kelas_id' => $validateData['kelas_id'],
            'matkul_id' => $validateData['matkul_id']
        ]);
        foreach ($wadirs as $wadir) {
            $wadir->notify(new PengajuanKontrakNotification($kontrak));
        }
        foreach ($direkturs as $direktur) {
            $direktur->notify(new PengajuanKontrakNotification($kontrak));
        }
        $kaprodi->notify(new PengajuanKontrakNotification($kontrak));
        return redirect()->back()->with('success', 'Pengajuan Kontrak Kuliah Berhasil');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jadwal_id, $matkul_id, $kelas_id)
    {

        $prodiId = $this->prodiId;
        $kontraks = Kontrak::with('matkul', 'kelas.semester', 'kelas.prodi', 'jadwal.dosen')
            ->where('matkuls_id', $matkul_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwal_id)
            ->when($prodiId, function ($query) use ($prodiId) {
                return $query->whereHas('kelas.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
                });
            })
            ->get();
        $kelas = Kelas::with('prodi')->where('id', $kelas_id)->first();
        $kaprodi = Kaprodi::where('prodis_id', $kelas->id_prodi)->first();
        $wadir = Wadir::where('no', 1)->first();
        return view('pages.pengajuanRekapKontrak.rekap', compact('kontraks', 'kaprodi', 'wadir'));
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

            $allKaprodiApproved = true;
            $allWadirApproved = true;

            foreach ($kontraks as $kontrak) {
                if ($request->has('kaprodi')) {
                    $kontrak->setuju_kaprodi = true;
                }

                if ($request->has('wakil_direktur')) {
                    $kontrak->setuju_wadir = true;
                }

                if ($request->has('uncheck_kaprodi')) {
                    $kontrak->setuju_kaprodi = false;
                }

                if ($request->has('uncheck_wakil_direktur')) {
                    $kontrak->setuju_wadir = false;
                }

                if (!$kontrak->setuju_kaprodi) {
                    $allKaprodiApproved = false;
                }
                if (!$kontrak->setuju_wadir) {
                    $allWadirApproved = false;
                }

                $kontrak->save();
            }

            $statusKontrak = ($allKaprodiApproved && $allWadirApproved) ? true : false;

            foreach ($kontraks as $kontrak) {
                $kontrak->update(['status' => $statusKontrak]);
            }

            $pengajuan = PengajuanRekapKontrak::where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->first();

            if ($statusKontrak) {
                $dosen = Dosen::findOrFail($kontraks->first()->jadwal->dosens_id);
                $dosen->notify(new PengajuanKontrakNotification($pengajuan));
            }

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
