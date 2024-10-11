<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Uas;
use Illuminate\Http\Request;

class UasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($kelas_id, $matkul_id, $jadwal_id)
    {
        $kelasAll = Jadwal::all();
        $uass = Uas::where('kelas_id', $kelas_id)
            ->where('jadwal_id', $jadwal_id)
            ->where('matkul_id', $matkul_id)
            ->get();

        return  view('pages.dosen.data-nilai.uas.index', compact('kelasAll', 'kelas_id', 'matkul_id', 'jadwal_id', 'uass'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Uas $uas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Uas $uas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Uas $uas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uas $uas)
    {
        //
    }
}
