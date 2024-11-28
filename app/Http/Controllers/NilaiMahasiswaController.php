<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Kaprodi;
use App\Models\Semester;
use App\Models\Mahasiswa;
use App\Models\NilaiHuruf;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Session;

class NilaiMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        // INI BIMBANG MUNGKIN REVISI
        $matkuls = Matkul::where('kelas_id', $this->kelasId)->get();
        $kelas = Kelas::where('id', $this->kelasId)->first();

        $semester = Semester::where('id', $kelas->id_semester)->first();

        $nilais = NilaiHuruf::with(['mahasiswa', 'kelas', 'matkul'])
            ->where('kelas_id', $this->kelasId)
            ->where('semester_id', $semester->id)
            ->where('mahasiswa_id', $this->userId)
            ->get();

        $combinedData = $matkuls->map(function ($matkul) use ($nilais) {
            $nilai = $nilais->firstWhere('matkul_id', $matkul->id);
            return [
                'matkul' => $matkul,
                'nilai' => $nilai
            ];
        });

        $semesters = NilaiHuruf::where('mahasiswa_id', $this->userId)
            ->select('semester_id')
            ->with('semester')
            ->groupBy('semester_id')
            ->get();

        $mahasiswa = Mahasiswa::with('kelas.semester')
            ->where('id', $this->userId)
            ->first();

        $sem = $mahasiswa->kelas->semester->semester;

        $riwayat = false;
        return view("pages.mahasiswa.nilai.index", compact("combinedData", "semesters", 'sem', 'riwayat'));
    }

    /**
     * Display the specified resource.
     */
    public function riwayat($semester_id)
    {
        $kelas = Kelas::where('id', $this->kelasId)->first();
        $prodi = Prodi::where('id', $kelas->id_prodi)->first();

        // INI BIMBANG MUNGKIN REVISI
        $matkuls = Matkul::whereHas('kelas', function ($query) use ($prodi, $semester_id) {
            $query->where('id_prodi', $prodi->id)
                ->where('id_semester', $semester_id);
        })
            ->get();

        $semesterRiwayatKhs = Semester::where('id', $semester_id)->first();

        $nilais = NilaiHuruf::with(['mahasiswa', 'kelas', 'matkul'])
            ->whereHas('kelas', function ($query) use ($prodi, $semester_id) {
                $query->where('id_prodi', $prodi->id)
                    ->where('id_semester', $semester_id);
            })
            ->where('mahasiswa_id', $this->userId)
            ->get();


        $combinedData = $matkuls->mapWithKeys(function ($matkul) use ($nilais) {
            $nilai = $nilais->firstWhere('matkul_id', $matkul->id);
            return [$matkul->id => [
                'matkul' => $matkul,
                'nilai' => $nilai
            ]];
        });


        $semesters = NilaiHuruf::where('mahasiswa_id', $this->userId)
            ->select('semester_id')
            ->with('semester')
            ->groupBy('semester_id')
            ->get();

        $mahasiswa = Mahasiswa::with('kelas.semester')
            ->where('id', $this->userId)
            ->first();

        $riwayat = true;
        return view("pages.mahasiswa.nilai.index", compact("combinedData", "semesters", 'riwayat','semesterRiwayatKhs'));
    }


    public function khs($semester)
    {
        $kelas = Kelas::where('id', $this->kelasId)->first();
        $prodi = Prodi::where('id', $kelas->id_prodi)->first();

        $ipks = NilaiHuruf::with(['matkul', 'kelas.prodi'])
            ->where('mahasiswa_id', $this->userId)
            ->whereHas('kelas.prodi', function ($query) use ($prodi) {
                $query->where('id', $prodi->id);
            })
            ->get();

        $ipss = NilaiHuruf::with(['matkul', 'kelas.prodi'])
            ->where('mahasiswa_id', $this->userId)
            ->whereHas('kelas.prodi', function ($query) use ($prodi) {
                $query->where('id', $prodi->id);
            })
            ->whereHas('kelas', function ($query) use ($semester) {
                $query->where('id_semester', $semester);
            })
            ->get();

        $tahunAkademik = TahunAkademik::where('status', 1)->first();
        $tahunAkademikFormatted = str_replace('/', '-', $tahunAkademik->tahun_akademik);
        $kaprodi = Kaprodi::where('prodis_id', 1)->where('status', 1)->first();

        return view('pages.mahasiswa.nilai.khs', compact('ipks', 'ipss', 'tahunAkademikFormatted', 'kaprodi'));
    }

    public function riwayatKhs($semester)
    {
        $kelas = Kelas::where('id', $this->kelasId)->first();
        $prodi = Prodi::where('id', $kelas->id_prodi)->first();
        $getSemesterById = Semester::where('id',$semester)->first();

        $ipks = NilaiHuruf::with(['matkul', 'kelas.prodi'])
            ->where('mahasiswa_id', $this->userId)
            ->whereHas('kelas.prodi', function ($query) use ($prodi) {
                $query->where('id', $prodi->id);
            })
            ->whereHas('kelas.semester', function ($query) use ($getSemesterById) {
                $query->where('semester','<=' ,$getSemesterById->semester);
            })
            ->get();

        $ipss = NilaiHuruf::with(['matkul', 'kelas.prodi'])
            ->where('mahasiswa_id', $this->userId)
            ->whereHas('kelas.prodi', function ($query) use ($prodi) {
                $query->where('id', $prodi->id);
            })
            ->whereHas('kelas', function ($query) use ($getSemesterById) {
                $query->where('id_semester', $getSemesterById->id);
            })
            ->get();

        $tahunAkademik = TahunAkademik::where('status', 1)->first();
        $tahunAkademikFormatted = str_replace('/', '-', $tahunAkademik->tahun_akademik);
        $kaprodi = Kaprodi::where('prodis_id', 1)->where('status', 1)->first();

        return view('pages.mahasiswa.nilai.khs', compact('ipks', 'ipss', 'tahunAkademikFormatted', 'kaprodi'));
    }
}
