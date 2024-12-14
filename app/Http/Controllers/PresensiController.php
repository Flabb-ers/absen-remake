<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Dire;
use App\Models\Absen;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Resume;
use App\Models\Kaprodi;
use App\Models\Kontrak;
use App\Models\Message;
use App\Models\Semester;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanRekapBerita;
use App\Models\PengajuanRekapPresensi;
use Illuminate\Support\Facades\Session;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $userid;
    protected $role;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userid = Session::get('user.id');
            $this->role = Session::get('user.role');
            return $next($request);
        });
    }
    public function index()
    {
        $kelasAll = Jadwal::where('dosens_id', $this->userid)->get();
        $jadwals = Jadwal::with('dosen', 'matkul', 'kelas.prodi', 'ruangan')
            ->where('dosens_id', $this->userid)
            ->latest()
            ->get();

        $pertemuanCounts = [];
        foreach ($jadwals as $jadwal) {
            $pertemuan = Absen::where('jadwals_id', $jadwal->id)->max('pertemuan');
            $pertemuanCounts[$jadwal->id] = $pertemuan ?? 0;
        }
        $pengajuan = PengajuanRekapPresensi::whereIn('jadwals_id', $jadwals->pluck('id'))->get()->groupBy('jadwals_id');

        $berita = PengajuanRekapBerita::whereIn('jadwal_id', $jadwals->pluck('id'))->get()->groupBy('jadwal_id');


        $pesans = Message::with('sender')
            ->where('receiver_id', $this->userid)
            ->get();

        $filteredPesans = $pesans->filter(function ($pesan) {
            return $pesan->sender_type != 'App\Models\Dosen'; 
        });

        $groupedPesans = $filteredPesans->groupBy(function ($pesan) {
            return $pesan->sender_type . '-' . $pesan->sender_id; 
        });

        $pesans = $groupedPesans->map(function (Collection $group) {
            return $group->first();
        })->values();

        return view('pages.dosen.data-presensi.index', compact('jadwals', 'pertemuanCounts', 'pengajuan', 'berita', 'kelasAll', 'pesans'));
    }



    public function absen($id)
    {
        $jadwal = Jadwal::with('dosen', 'matkul', 'kelas', 'ruangan')
            ->where('dosens_id', $this->userid)
            ->where('id', $id)
            ->first();
        $pertemuan = Absen::where('jadwals_id', $jadwal->id)->max('pertemuan');
        $pertemuan = $pertemuan ? $pertemuan + 1 : 1;
        $mahasiswas = Mahasiswa::where('kelas_id', $jadwal->kelas->id)
            ->where('status_krs', 1)
            ->get();
        $tahun = TahunAkademik::where('status', 1)->first();
        $kelasAll = Jadwal::where('dosens_id', $this->userid)->get();
        return view('pages.dosen.data-presensi.presensi', compact('jadwal', 'mahasiswas', 'pertemuan', 'tahun', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->mahasiswas_id as $mahasiswaId) {
                Absen::create([
                    'pertemuan' => $request->pertemuan,
                    'tanggal' => now(),
                    'jadwals_id' => $request->jadwals_id,
                    'matkuls_id' => $request->matkuls_id,
                    'dosens_id' => $request->dosens_id,
                    'prodis_id' => $request->prodis_id,
                    'kelas_id' => $request->kelas_id,
                    'mahasiswas_id' => $mahasiswaId,
                    'tahun' => $request->tahun,
                    'status' => $request->status[$mahasiswaId],
                ]);
            }
            Resume::create([
                'pertemuan' => $request->pertemuan,
                'tanggal' => now(),
                'waktu' => now(),
                'tahun' => $request->tahun,
                'materi' => $request->materiResume,
                'jadwals_id' => $request->jadwals_id,
                'dosens_id' => $request->dosens_id,
                'matkuls_id' => $request->matkuls_id,
                'prodis_id' => $request->prodis_id,
                'kelas_id' => $request->kelas_id,
                'tidak_hadir' => $request->jumlahTidakHadir,
                'hadir' => $request->jumlahHadir,
            ]);


            DB::commit();
            return redirect('/presensi/data-presensi')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal disimpan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $matkuls_id, $kelas_id, $jadwal_id)
    {

        $resume = Resume::where('pertemuan', $id)
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkuls_id)
            ->where('jadwals_id', $jadwal_id)
            ->first();

        $absens = Absen::with('prodi', 'matkul')
            ->where('pertemuan', $id)
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkuls_id)
            ->where('jadwals_id', $jadwal_id)
            ->get();

        $mahasiswas = Mahasiswa::where('kelas_id', $kelas_id)
            ->where('status_krs', 1)
            ->get();
        $kelasAll = Jadwal::where('dosens_id', $this->userid)->get();

        return view('pages.dosen.data-presensi.edit', compact('resume',  'absens', 'mahasiswas', 'id', 'kelasAll'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kelas_id)
    {
        $request->validate([
            'pertemuan' => 'required',
            'matkuls_id' => 'required',
            'kelas_id' => 'required',
            'mahasiswas_id' => 'required|array',
            'status' => 'required|array',
        ]);


        foreach ($request->mahasiswas_id as $index => $mahasiswa_id) {
            $absensi = Absen::where('kelas_id', $request->kelas_id)
                ->where('mahasiswas_id', $mahasiswa_id)
                ->where('pertemuan', $request->pertemuan)
                ->where('matkuls_id', $request->matkuls_id)
                ->where('jadwals_id', $request->jadwals_id)
                ->first();

            if ($absensi) {
                $absensi->update([
                    'status' => $request->status[$mahasiswa_id],
                ]);
            }
        }

        $resume = Resume::where('kelas_id', $request->kelas_id)
            ->where('matkuls_id', $request->matkuls_id)
            ->where('pertemuan', $request->pertemuan)
            ->first();

        if ($resume) {
            $resume->update([
                'materi' => $request->materiResume,
            ]);
        }
        return redirect()->back()
            ->with('success', 'Data presensi berhasil diperbarui.');
    }

    public function rekap1to7($matkuls_id, $kelas_id, $jadwal_id)
    {
        $absens = Absen::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
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
            ->where('jadwals_id', $jadwal_id)
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->whereIn('pertemuan', [1, 2, 3, 4, 5, 6, 7])
            ->get();

        $tahunAkademik = TahunAkademik::where('status', 1)->get();
        $dosenPengampu = Dosen::where('id', $absens->first()->dosens_id)->first();
        $kaprodi = Kaprodi::where('prodis_id', $absens->first()->prodis_id)->first();
        return view('pages.dosen.data-presensi.rekap.presensi1-7', compact('absens', 'tahunAkademik', 'dosenPengampu', 'kaprodi'));
    }

    public function rekap8to14($matkuls_id, $kelas_id, $jadwal_id)
    {
        $absens = Absen::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
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
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwal_id)
            ->whereIn('pertemuan', [8, 9, 10, 11, 12, 13, 14])
            ->get();

        $tahunAkademik = TahunAkademik::where('status', 1)->get();
        $dosenPengampu = Dosen::where('id', $absens->first()->dosens_id)->first();
        $kaprodi = Kaprodi::where('prodis_id', $absens->first()->prodis_id)->first();

        return view('pages.dosen.data-presensi.rekap.presensi8-14', compact('absens', 'tahunAkademik', 'dosenPengampu', 'kaprodi'));
    }


    public function berita1to7($matkuls_id, $kelas_id, $jadwal_id)
    {
        $beritas = Resume::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwal_id)
            ->whereBetween('pertemuan', [1, 7])
            ->get();

        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $sem = ($semester->semester % 2 == 0) ? "GENAP" : "GANJIL";
        }
        $tahunAkademik = TahunAkademik::where('status', 1)->get();
        return view('pages.dosen.data-presensi.rekap.berita1-7', compact('beritas', 'tahunAkademik', 'sem'));
    }

    public function berita8to14($matkuls_id, $kelas_id, $jadwal_id)
    {
        $beritas = Resume::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwal_id)
            ->whereBetween('pertemuan', [8, 14])
            ->get();

        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $sem = ($semester->semester % 2 == 0) ? "GENAP" : "GANJIL";
        }
        $tahunAkademik = TahunAkademik::where('status', 1)->get();
        return view('pages.dosen.data-presensi.rekap.berita8-14', compact('beritas', 'sem', 'tahunAkademik'));
    }

    public function kategoriInPresensi()
    {
        if ($this->role == 'kaprodi') {
            $kaprodi = Kaprodi::where('id', $this->userid)->first();

            $getDosen = Dosen::where('status', 1)
                ->whereHas('jadwal', function ($query) use ($kaprodi) {
                    $query->whereHas('kelas', function ($subQuery) use ($kaprodi) {
                        $subQuery->where('id_prodi', $kaprodi->prodis_id);
                    });
                })
                ->get();

            $dosenMatkulCount = Jadwal::whereHas('kelas', function ($query) use ($kaprodi) {
                $query->where('id_prodi', $kaprodi->prodis_id);
            })
                ->groupBy('dosens_id')
                ->selectRaw('dosens_id, COUNT(*) as total')
                ->pluck('total', 'dosens_id');
        } else {
            $getDosen = Dosen::where('status', 1)
                ->whereHas('jadwal')
                ->get();

            $dosenMatkulCount = Jadwal::groupBy('dosens_id')
                ->selectRaw('dosens_id, COUNT(*) as total')
                ->pluck('total', 'dosens_id');
        }


        return view('pages.data-presensi.index', compact('getDosen', 'dosenMatkulCount'));
    }

    public function detailMatkulPresensi($id)
    {
        if ($this->role == 'kaprodi') {
            $kaprodi = Kaprodi::where('id', $this->userid)->first();
            $prodiId = $kaprodi->prodis_id;

            $jadwals = Jadwal::with('matkul', 'matkul.prodi', 'matkul.semester')
                ->where('dosens_id', $id)
                ->whereHas('kelas', function ($query) use ($prodiId) {
                    $query->where('id_prodi', $prodiId);
                })
                ->orderBy(function ($query) {
                    $query->select('nama_matkul')
                        ->from('matkuls')
                        ->whereColumn('matkuls.id', 'jadwals.matkuls_id');
                }, 'asc')
                ->get();
        } else {
            $jadwals = Jadwal::with('matkul', 'matkul.prodi', 'matkul.semester')
                ->where('dosens_id', $id)
                ->orderBy(function ($query) {
                    $query->select('nama_matkul')
                        ->from('matkuls')
                        ->whereColumn('matkuls.id', 'jadwals.matkuls_id');
                }, 'asc')
                ->get();
        }
        $pertemuanCounts = [];
        foreach ($jadwals as $jadwal) {
            $pertemuan = Absen::where('jadwals_id', $jadwal->id)->max('pertemuan');
            $pertemuanCounts[$jadwal->id] = $pertemuan ?? 0;
        }
        return view('pages.data-presensi.matkul', compact('jadwals', 'pertemuanCounts'));
    }
    public function cekPresensi1to7($matkul_id, $kelas_id, $jadwal_id)
    {
        $absens = Absen::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
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
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('jadwals_id', $jadwal_id)
            ->where('matkuls_id', $matkul_id)
            ->where('kelas_id', $kelas_id)
            ->whereIn('pertemuan', [1, 2, 3, 4, 5, 6, 7])
            ->get();

        $tahunAkademik = TahunAkademik::where('status', 1)->get();
        $dosenPengampu = Dosen::where('id', $absens->first()->dosens_id)->first();
        $kaprodi = Kaprodi::where('prodis_id', $absens->first()->prodis_id)->first();
        return view('pages.data-presensi.cek1to7', compact('absens', 'tahunAkademik', 'dosenPengampu', 'kaprodi'));
    }

    public function cekPresensi8to14($matkul_id, $kelas_id, $jadwal_id)
    {
        $absens = Absen::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
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
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('jadwals_id', $jadwal_id)
            ->where('matkuls_id', $matkul_id)
            ->where('kelas_id', $kelas_id)
            ->whereIn('pertemuan', [8, 9, 10, 11, 12, 13, 14])
            ->get();

        $tahunAkademik = TahunAkademik::where('status', 1)->get();
        $dosenPengampu = Dosen::where('id', $absens->first()->dosens_id)->first();
        $kaprodi = Kaprodi::where('prodis_id', $absens->first()->prodis_id)->first();
        return view('pages.data-presensi.cek8to14', compact('absens', 'tahunAkademik', 'dosenPengampu', 'kaprodi'));
    }

    public function kategoriInResume()
    {
        if ($this->role == 'kaprodi') {
            $kaprodi = Kaprodi::where('id', $this->userid)->first();

            $getDosen = Dosen::where('status', 1)
                ->whereHas('jadwal', function ($query) use ($kaprodi) {
                    $query->whereHas('kelas', function ($subQuery) use ($kaprodi) {
                        $subQuery->where('id_prodi', $kaprodi->prodis_id);
                    });
                })
                ->get();

            $dosenMatkulCount = Jadwal::whereHas('kelas', function ($query) use ($kaprodi) {
                $query->where('id_prodi', $kaprodi->prodis_id);
            })
                ->groupBy('dosens_id')
                ->selectRaw('dosens_id, COUNT(*) as total')
                ->pluck('total', 'dosens_id');
        } else {
            $getDosen = Dosen::where('status', 1)
                ->whereHas('jadwal')
                ->get();

            $dosenMatkulCount = Jadwal::groupBy('dosens_id')
                ->selectRaw('dosens_id, COUNT(*) as total')
                ->pluck('total', 'dosens_id');
        }
        return view('pages.data-resume.index', compact('getDosen', 'dosenMatkulCount'));
    }

    public function detailMatkulResume($id)
    {
        if ($this->role == 'kaprodi') {
            $kaprodi = Kaprodi::where('id', $this->userid)->first();
            $prodiId = $kaprodi->prodis_id;

            $jadwals = Jadwal::with('matkul', 'matkul.prodi', 'matkul.semester')
                ->where('dosens_id', $id)
                ->whereHas('kelas', function ($query) use ($prodiId) {
                    $query->where('id_prodi', $prodiId);
                })
                ->orderBy(function ($query) {
                    $query->select('nama_matkul')
                        ->from('matkuls')
                        ->whereColumn('matkuls.id', 'jadwals.matkuls_id');
                }, 'asc')
                ->get();
        } else {
            $jadwals = Jadwal::with('matkul', 'matkul.prodi', 'matkul.semester')
                ->where('dosens_id', $id)
                ->orderBy(function ($query) {
                    $query->select('nama_matkul')
                        ->from('matkuls')
                        ->whereColumn('matkuls.id', 'jadwals.matkuls_id');
                }, 'asc')
                ->get();
        }

        $pertemuanCounts = [];
        foreach ($jadwals as $jadwal) {
            $pertemuan = Resume::where('jadwals_id', $jadwal->id)->max('pertemuan');
            $pertemuanCounts[$jadwal->id] = $pertemuan ?? 0;
        }
        return view('pages.data-resume.matkul', compact('jadwals', 'pertemuanCounts'));
    }
    public function cekResume1to7($matkuls_id, $kelas_id, $jadwal_id)
    {
        $beritas = Resume::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwal_id)
            ->whereBetween('pertemuan', [1, 7])
            ->get();

        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $sem = ($semester->semester % 2 == 0) ? "GENAP" : "GANJIL";
        }
        $tahunAkademik = TahunAkademik::where('status', 1)->get();
        return view('pages.data-resume.cek1to7', compact('beritas', 'tahunAkademik', 'sem'));
    }
    public function cekResume8to14($matkuls_id, $kelas_id, $jadwal_id)
    {
        $beritas = Resume::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('matkuls_id', $matkuls_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwal_id)
            ->whereBetween('pertemuan', [8, 14])
            ->get();

        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $sem = ($semester->semester % 2 == 0) ? "GENAP" : "GANJIL";
        }
        $tahunAkademik = TahunAkademik::where('status', 1)->get();
        return view('pages.data-resume.cek8to14', compact('beritas', 'tahunAkademik', 'sem'));
    }
}
