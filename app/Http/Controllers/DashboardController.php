<?php

namespace App\Http\Controllers;

use App\Models\PengajuanRekapBerita;
use App\Models\PengajuanRekapkontrak;
use Carbon\Carbon;
use App\Models\Absen;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Mahasiswa;
use App\Models\NilaiHuruf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanRekapPresensi;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $userId;
    protected $prodiId;
    protected $role;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userId = Session::get('user.id');
            $this->prodiId = Session::get('user.prodiId');
            $this->role = Session::get('user.role');
            return $next($request);
        });
    }
    public function index()
    {
        Carbon::setLocale('id');
        $prodiId = $this->prodiId;
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        $semesters = NilaiHuruf::with('semester')
            ->where('mahasiswa_id', $this->userId)
            ->select('semester_id')
            ->with('semester')
            ->groupBy('semester_id')
            ->get();

        // KAPRODI
        if (Auth::guard('kaprodi')->check()) {
            $prodi = Prodi::findOrFail($this->prodiId);

            $mahasiswa = Mahasiswa::with('kelas.prodi', 'kelas')->whereHas('kelas', function ($query) use ($prodiId) {
                $query->where('id_prodi', $prodiId);
            })->count();

            $tanggal = Carbon::now();
            $hariIni = $tanggal->isoFormat('dddd');

            $jadwals = Jadwal::with('kelas', 'dosen', 'ruangan', 'matkul')
                ->whereHas('kelas', function ($query) use ($prodiId) {
                    $query->where('id_prodi', $prodiId);
                })
                ->where('hari', $hariIni)
                ->get();

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
                },
            ])
                ->whereHas('jadwal.absen', function ($query) {
                    $query->where('setuju_kaprodi', 0);
                })
                ->count();

            $kontrak = PengajuanRekapkontrak::with([
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
                },
            ])
                ->whereHas('jadwal.absen', function ($query) {
                    $query->where('setuju_kaprodi', 0);
                })
                ->count();

            $resume = PengajuanRekapBerita::with([
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
                },
            ])
                ->whereHas('jadwal.absen', function ($query) {
                    $query->where('setuju_kaprodi', 0);
                })
                ->count();

            return view('pages.dashboard.index', compact('mahasiswa', 'prodi', 'jadwals', 'resume', 'kontrak', 'presensis'));

            // MAHASISWA
        } elseif (Auth::guard('mahasiswa')->check()) {

            $mahasiswaUser = Mahasiswa::findOrFail($this->userId);
            $kelas = Kelas::findOrFail($mahasiswaUser->kelas_id);

            $totalKehadiran  = Absen::where('mahasiswas_id', $this->userId)
                ->where('kelas_id', $kelas->id)
                ->whereIn('status',['H','T'])
                ->count();

            $totalMatakuliah = Matkul::with('prodi', 'semester')
                ->where('prodi_id', $kelas->id_prodi)
                ->where('semester_id', $kelas->id_semester)
                ->count();

            $tanggal = Carbon::now();
            $hariIni = $tanggal->isoFormat('dddd');
            $jadwalsMahasiswa = Jadwal::where('kelas_id', $kelas->id)
                ->where('hari', $hariIni)
                ->get();

            $absensHariIni = Absen::whereIn('jadwals_id', $jadwalsMahasiswa->pluck('id'))
                ->whereDate('created_at', $tanggal->toDateString()) 
                ->where('mahasiswas_id', auth()->user()->id) 
                ->get();

            $semesters = NilaiHuruf::where('mahasiswa_id', $this->userId)
                ->select('semester_id')
                ->with('semester')
                ->groupBy('semester_id')
                ->get();



            return view('pages.dashboard.index', compact('totalKehadiran', 'totalMatakuliah', 'jadwalsMahasiswa', 'semesters','absensHariIni'));


            // DOSEN
        } elseif (Auth::guard('dosen')->check()) {
            $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
            return view('pages.dashboard.index', compact('kelasAll'));

            // ADMIN
        } elseif (Auth::guard('admin')->check()) {
            $totalMahaiswa = Mahasiswa::all()->count();

            $totalKelas = Kelas::with('semester')
                ->whereHas('semester', function ($query) {
                    $query->where('status', 1);
                })->count();

            $totalDosen = Dosen::where('status', 1)->count();


            $totalHadir = Absen::whereDate('created_at', Carbon::today())
                ->whereIn('status', ['H', 'T'])
                ->count();

            $totalTidakHadir = Absen::whereDate('created_at', Carbon::today())
                ->whereIn('status', ['A', 'C', 'S', 'I'])
                ->count();

            $programStudi = Prodi::all();

            $data = $programStudi->map(function ($prodi) {
                $totalHadir = Absen::where('prodis_id', $prodi->id)
                    ->whereDate('created_at', Carbon::today())
                    ->whereIn('status', ['H', 'T'])
                    ->count();

                $totalTidakHadir = Absen::where('prodis_id', $prodi->id)
                    ->whereDate('created_at', Carbon::today())
                    ->whereIn('status', ['A', 'C', 'S', 'I'])
                    ->count();

                return [
                    'nama_prodi' => $prodi->nama_prodi,
                    'total_hadir' => $totalHadir,
                    'total_tidak_hadir' => $totalTidakHadir,
                ];
            });
            return view('pages.dashboard.index', compact('totalMahaiswa', 'totalKelas', 'totalDosen', 'totalHadir', 'totalTidakHadir', 'data'));

            // WADIR DAN DIREKTUR
        } elseif (Auth::guard('wakil_direktur')->check() || Auth::guard('direktur')->check()) {
            $today = Carbon::today();

            $totalMahaiswa = Mahasiswa::all()->count();

            $totalDosen = Dosen::where('status', 1)->count();

            $totalMahasiswaHariIni = Absen::whereDate('created_at', $today)->count();

            $totalHadirHariIni = Absen::whereDate('created_at', $today)
                ->whereIn('status', ['H', 'T'])
                ->count();

            $persentaseKehadiran = $totalMahasiswaHariIni > 0
                ? ($totalHadirHariIni / $totalMahasiswaHariIni) * 100
                : 0;

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
                },
            ])
                ->whereHas('jadwal.absen', function ($query) {
                    $query->where('setuju_wadir', 0);
                })
                ->count();

            $kontrak = PengajuanRekapkontrak::with([
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
                },
            ])
                ->whereHas('jadwal.absen', function ($query) {
                    $query->where('setuju_wadir', 0);
                })
                ->count();

            $resume = PengajuanRekapBerita::with([
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
                },
            ])
                ->whereHas('jadwal.absen', function ($query) {
                    $query->where('setuju_wadir', 0);
                })
                ->count();
            return view('pages.dashboard.index', compact('persentaseKehadiran', 'totalDosen', 'totalMahaiswa', 'presensis', 'kontrak', 'resume'));
        } else {
            return view('pages.dashboard.index');
        }
    }
}
