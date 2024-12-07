<?php

namespace App\Http\Controllers;

use App\Models\Uas;
use App\Models\Uts;
use App\Models\Absen;
use App\Models\Aktif;
use App\Models\Dosen;
use App\Models\Etika;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Tugas;
use App\Models\Wadir;
use App\Models\Jadwal;
use App\Models\Kaprodi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $userId;
    protected $role;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userId = Session::get("user.id");
            $this->role = Session::get("user.role");
            return $next($request);
        });
    }
    public function index($kelas_id)
    {
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        $jadwals = Jadwal::with('kelas.mahasiswa', 'kelas')
            ->where('kelas_id', $kelas_id)
            ->where('dosens_id', $this->userId)
            ->get();
        return view('pages.dosen.data-nilai.index', compact('kelasAll', 'jadwals'));
    }

    public function detail($kelas_id, $matkul_id, $jadwal_id)
    {
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        $jadwal = Jadwal::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('dosens_id', $this->userId)
            ->where('id', $jadwal_id)
            ->first();

        return view('pages.dosen.data-nilai.detail', compact('kelas_id', 'matkul_id', 'kelasAll', 'jadwal', 'jadwal_id'));
    }

    public function kategori()
    {
        $jadwals = Jadwal::select('dosens_id')->distinct()->get();

        $getDosen = Dosen::whereIn('id', $jadwals->pluck('dosens_id'))->get();

        if ($this->role == 'kaprodi') {
            $kaprodi = Kaprodi::where('id', $this->userId)->first();
            $prodiId = $kaprodi->prodis_id;
            $dosenMatkulCount = Jadwal::whereIn('dosens_id', $getDosen->pluck('id'))
                ->whereHas('matkul.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
                })
                ->groupBy('dosens_id')
                ->selectRaw('dosens_id, COUNT(*) as total')
                ->pluck('total', 'dosens_id');
        } else {
            $dosenMatkulCount = Jadwal::whereIn('dosens_id', $getDosen->pluck('id'))
                ->groupBy('dosens_id')
                ->selectRaw('dosens_id, COUNT(*) as total')
                ->pluck('total', 'dosens_id');
        }
        return view('pages.data-nilai.index', compact('getDosen', 'dosenMatkulCount'));
    }


    public function detailMatkul($id){

        if ($this->role == 'kaprodi') {
            $kaprodi = Kaprodi::where('id', $this->userId)->first();
            $prodiId = $kaprodi->prodis_id;
            $jadwals = Jadwal::with('matkul', 'matkul.prodi', 'matkul.semester')
                ->where('dosens_id', $id)
                ->whereHas('matkul.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
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
        return view('pages.data-nilai.matkul', compact('jadwals', 'pertemuanCounts'));
    }

    public function cekNilai($kelas_id, $matkul_id, $jadwal_id)
    {
        $mahasiswa_ids = collect();

        $tugas_mahasiswa_ids = Tugas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $aktif_mahasiswa_ids = Aktif::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $etika_mahasiswa_ids = Etika::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $absen_mahasiswa_ids = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->pluck('mahasiswas_id');

        $uts_mahasiswa_ids = Uts::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $uas_mahasiswa_ids = Uas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $mahasiswa_ids = $mahasiswa_ids->concat($tugas_mahasiswa_ids)
            ->concat($aktif_mahasiswa_ids)
            ->concat($etika_mahasiswa_ids)
            ->concat($absen_mahasiswa_ids)
            ->concat($uts_mahasiswa_ids)
            ->concat($uas_mahasiswa_ids)
            ->unique();


        $mahasiswas = Mahasiswa::withTrashed()
            ->whereIn('id', $mahasiswa_ids)
            ->orderBy('nim', 'asc')
            ->get();

        $tugass = Tugas::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $groupedTugas = $tugass->groupBy('mahasiswa_id');
        $jumlahTugas = max(1, $tugass->pluck('tugas_ke')->unique()->count());

        $aktifs = Aktif::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $dataAktif = $aktifs->keyBy('mahasiswa_id');

        $etikas = Etika::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $dataEtika = $etikas->keyBy('mahasiswa_id');

        $absens = Absen::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->get();


        $dataAbsensi = $absens->groupBy('mahasiswas_id');

        $totalPertemuan = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->max('pertemuan');

        $jadwals = Jadwal::with([
            'dosen' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'kelas.semester' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->withTrashed()
            ->where('id', $jadwal_id)
            ->first();


        $dataAbsensi = $dataAbsensi->map(function ($absensiGroup, $mahasiswaId) use ($totalPertemuan) {
            $totalKehadiran = $absensiGroup->whereIn('status', ['H', 'T'])->count();
            $persentaseKehadiran = $totalPertemuan > 0 ? ($totalKehadiran / $totalPertemuan) * 15 : 0;

            return [
                'total_kegiatan' => $totalKehadiran,
                'persentase_kehadiran' => number_format($persentaseKehadiran, 2),
                'absensi' => $absensiGroup,
            ];
        });

        $utss = Uts::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        $uass = Uas::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        if ($jadwals && $jadwals->kelas && $jadwals->kelas->prodi) {
            $kaprodi = Kaprodi::where('prodis_id', $jadwals->kelas->prodi->id)
                ->first();
        }

        $wadir = Wadir::where('no', 1)->first();

        return view('pages.data-nilai.rekap',
            compact(
                'mahasiswas',
                'groupedTugas',
                'jumlahTugas',
                'dataAktif',
                'dataEtika',
                'kaprodi',
                'dataAbsensi',
                'totalPertemuan',
                'utss',
                'uass',
                'jadwals',
                'wadir'
            )
        );
    }

}
