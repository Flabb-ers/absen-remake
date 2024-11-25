<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kaprodi;
use App\Models\Matkul;
use App\Models\NilaiHuruf;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class NilaiMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Jadwal::all();
        $matkuls = Matkul::where('kelas_id', 1)->get();

        $nilais = NilaiHuruf::with(['mahasiswa', 'kelas', 'matkul'])
            ->where('kelas_id', 1)
            ->where('mahasiswa_id', 1)
            ->get();

        $combinedData = $matkuls->map(function ($matkul) use ($nilais) {
            $nilai = $nilais->firstWhere('matkul_id', $matkul->id);
            return [
                'matkul' => $matkul,
                'nilai' => $nilai
            ];
        });

        $semesters = Matkul::with(['nilaiHuruf' => function ($query) {
            $query->where('mahasiswa_id', 2)
                ->whereHas('kelas.prodi', function ($q) {
                    $q->where('id', 3);
                })
                ->with(['kelas.prodi']);
        }])->get();

        return view("pages.mahasiswa.nilai.index", compact("kelasAll", "combinedData", "semesters"));
    }

    /**
     * Display the specified resource.
     */
    public function riwayat($kelas_id)
    {
        $kelasAll = Jadwal::all();
        $matkuls = Matkul::where('kelas_id', $kelas_id)->get();
        $nilais = NilaiHuruf::with(['mahasiswa', 'kelas', 'matkul'])
            ->where('kelas_id', $kelas_id)
            ->where('mahasiswa_id', 2)
            ->get();

        $combinedData = $matkuls->map(function ($matkul) use ($nilais) {
            $nilai = $nilais->firstWhere('matkul_id', $matkul->id);
            return [
                'matkul' => $matkul,
                'nilai' => $nilai
            ];
        });
        $semesters = Matkul::with(['nilaiHuruf' => function ($query) {
            $query->where('mahasiswa_id', 4)
                ->whereHas('kelas.prodi', function ($q) {
                    $q->where('id', 2);
                })
                ->with(['kelas.prodi']);
        }])->get();
        return view("pages.mahasiswa.nilai.riwayat", compact("kelasAll", "combinedData", "semesters"));
    }


    public function khs()
    {
        $ipks = NilaiHuruf::with(['matkul', 'kelas.prodi'])
            ->where('mahasiswa_id', 1)
            ->whereHas('kelas.prodi', function ($query) {
                $query->where('id', 1);
            })
            ->get();

        $ipss = NilaiHuruf::with(['matkul', 'kelas.prodi'])
            ->where('mahasiswa_id', 1)
            ->whereHas('kelas.prodi', function ($query) {
                $query->where('id', 1);
            })
            ->whereHas('kelas', function ($query) {
                $query->where('id_semester', 1);
            })
            ->get();

        $tahunAkademik = TahunAkademik::where('status', 1)->first();
        $tahunAkademikFormatted = str_replace('/', '-', $tahunAkademik->tahun_akademik); 
        $kaprodi = Kaprodi::where('prodis_id',1)->where('status',1)->first();


        return view('pages.mahasiswa.nilai.khs', compact('ipks', 'ipss', 'tahunAkademikFormatted','kaprodi'));
    }
}
