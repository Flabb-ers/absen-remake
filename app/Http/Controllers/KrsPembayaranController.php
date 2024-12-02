<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Mahasiswa;
use App\Models\NilaiHuruf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KrsPembayaranController extends Controller
{

    protected $userId, $kelasId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userId = Session::get("user.id");
            $this->kelasId = Session::get("user.kelasId");
            return $next($request);
        });
    }
    public function index()
    {
        $kelas = Kelas::where('id',$this->kelasId)->first();
        $matkulKrs = Matkul::where('semester_id',$kelas->id_semester)
                ->where('prodi_id',$kelas->id_prodi)
                ->get();
        $cekStatus = Mahasiswa::where('id',$this->userId)->first();
        $semesters = NilaiHuruf::where('mahasiswa_id', $this->userId)
            ->select('semester_id')
            ->with('semester')
            ->groupBy('semester_id')
            ->get();
        return view('pages.mahasiswa.krs_pembayaran.index',compact('semesters','cekStatus','matkulKrs'));
    }
}
