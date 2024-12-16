<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Prodi;
use App\Models\Jadwal;
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
        } else {
            return view('pages.dashboard.index');
        }
    }
}
