<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $userId;
    protected $prodiId;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userId = Session::get('user.id');
            $this->prodiId = Session::get('user.prodiId');
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

            return view('pages.dashboard.index', compact('mahasiswa', 'prodi', 'jadwals'));

            // MAHASISWA
        } elseif (Auth::guard('mahasiswa')->check()) {

            $mahasiswaUser = Mahasiswa::findOrFail($this->userId);
            $kelas = Kelas::findOrFail($mahasiswaUser->kelas_id);

            $totalKehadiran  = Absen::where('mahasiswas_id', $this->userId)
                ->where('kelas_id', $kelas->id)
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

            $semesters = NilaiHuruf::where('mahasiswa_id', $this->userId)
                ->select('semester_id')
                ->with('semester')
                ->groupBy('semester_id')
                ->get();

            return view('pages.dashboard.index', compact('totalKehadiran', 'totalMatakuliah', 'jadwalsMahasiswa', 'semesters'));


            // DOSEN
        } elseif (Auth::guard('dosen')->check()) {
            $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
            return view('pages.dashboard.index', compact('kelasAll'));

            // ADMIN
        } elseif (Auth::guard('admin')) {
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
        } else {
            return view('pages.dashboard.index');
        }
    }
}
