<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($kelas_id)
    {
        $kelasAll = Jadwal::all();
        $jadwals = Jadwal::with('kelas.mahasiswa','kelas')->where('kelas_id',$kelas_id)->get(); 
        return view('pages.dosen.data-nilai.index',compact('kelasAll','jadwals'));
    }

    public function detail($kelas_id,$matkul_id,$jadwal_id){
        $kelasAll = Jadwal::all();
        $jadwal = Jadwal::where('kelas_id',$kelas_id)
                        ->where('matkuls_id',$matkul_id)
                        ->where('id', $jadwal_id)
                        ->first();

        return view('pages.dosen.data-nilai.detail',compact('kelas_id','matkul_id','kelasAll','jadwal','jadwal_id'));
    }
}