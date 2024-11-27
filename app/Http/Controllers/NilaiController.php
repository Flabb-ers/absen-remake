<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $userId;

     public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->userId = Session::get("user.id");
            return $next($request);
        });
     }
    public function index($kelas_id)
    {
        $kelasAll = Jadwal::where('dosens_id',$this->userId)->get();
        $jadwals = Jadwal::with('kelas.mahasiswa','kelas')
                    ->where('kelas_id',$kelas_id)
                    ->where('dosens_id',$this->userId)
                    ->get(); 
        return view('pages.dosen.data-nilai.index',compact('kelasAll','jadwals'));
    }

    public function detail($kelas_id,$matkul_id,$jadwal_id){
        $kelasAll = Jadwal::where('dosens_id',$this->userId)->get();
        $jadwal = Jadwal::where('kelas_id',$kelas_id)
                        ->where('matkuls_id',$matkul_id)
                        ->where('dosens_id',$this->userId)
                        ->where('id', $jadwal_id)
                        ->first();

        return view('pages.dosen.data-nilai.detail',compact('kelas_id','matkul_id','kelasAll','jadwal','jadwal_id'));
    }
}