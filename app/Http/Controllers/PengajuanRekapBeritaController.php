<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Wadir;
use App\Models\Jadwal;
use App\Models\Resume;
use App\Models\Kaprodi;
use App\Models\Direktur;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\PengajuanRekapBerita;
use Illuminate\Support\Facades\Session;
use App\Notifications\PengajuanResumeNotification;

class PengajuanRekapBeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $prodiId;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->prodiId = Session::get('user.prodiId');
            return $next($request);
        });
    }
    public function index()
    {
        $prodiId = $this->prodiId;
        $beritas = PengajuanRekapBerita::with([
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            },
            'jadwal.dosen' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('status', 0)
            ->when($prodiId, function ($query) use ($prodiId) {
                return $query->whereHas('kelas.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
                });
            })
            ->latest()
            ->get();


        $kelasAll = Jadwal::all();

        return view('pages.pengajuanRekapBerita.index', compact('beritas', 'kelasAll'));
    }

    public function confirm()
    {
        $prodiId = $this->prodiId;
        $beritas = PengajuanRekapBerita::with([
            'matkul' => function ($query) {
                $query->withTrashed();
            },
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            },
            'jadwal.dosen' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('status', 1)
            ->when($prodiId, function ($query) use ($prodiId) {
                return $query->whereHas('kelas.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
                });
            })
            ->latest()
            ->get();

        $kelasAll = Jadwal::all();

        return view('pages.pengajuanRekapBerita.disetujui', compact('beritas', 'kelasAll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kelas = Kelas::findOrFail($request->kelas_id);
        $wadirs = Wadir::all();
        $direkturs = Direktur::all();
        $kaprodi = Kaprodi::where('prodis_id', $kelas->id_prodi)->first();
        $validateData = $request->validate([
            "matkul_id" => "required",
            "kelas_id" => "required",
            "jadwal_id" => "required",
            "rentang" => 'required|in:1-7,8-14',
            "dosen_id" => 'required'
        ]);

        $resume = PengajuanRekapBerita::with('matkul','kelas','jadwal','dosen')->create([
            'matkuls_id' => $validateData['matkul_id'],
            'kelas_id' => $validateData['kelas_id'],
            'jadwal_id' => $validateData['jadwal_id'],
            'pertemuan' => $validateData['rentang'],
            'dosens_id' => $validateData['dosen_id'],
        ]);
        foreach ($wadirs as $wadir) {
            $wadir->notify(new PengajuanResumeNotification($resume));
        }
        foreach ($direkturs as $direktur) {
            $direktur->notify(new PengajuanResumeNotification($resume));
        }
        $kaprodi->notify(new PengajuanResumeNotification($resume));
        return redirect()->back()->with('success', 'Pengajuan Rekap Berita Acara Perkuliahan Berhasil');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($pertemuan, $matkul_id, $kelas_id, $jadwal_id)
    {
        $prodiId = $this->prodiId;
        $range = [];
        if ($pertemuan == '1-7') {
            $range = range(1, 7);
        } elseif ($pertemuan == '8-14') {
            $range = range(8, 14);
        }

        $beritas = Resume::with('dosen', 'matkul', 'kelas.prodi')
            ->where('matkuls_id', $matkul_id)
            ->where('kelas_id', $kelas_id)
            ->where('jadwals_id', $jadwal_id)
            ->whereIn('pertemuan', $range)
            ->when($prodiId, function ($query) use ($prodiId) {
                return $query->whereHas('kelas.prodi', function ($query) use ($prodiId) {
                    $query->where('id', $prodiId);
                });
            })
            ->get();

        $semester = Semester::where('status', 1)->first();
        if ($semester) {
            $sem = ($semester->semester % 2 == 0) ? "GENAP" : "GANJIL";
        }
        $tahunAkademik = TahunAkademik::where('status', 1)->first();
        return view('pages.pengajuanRekapBerita.rekap', compact('beritas', 'tahunAkademik', 'sem', 'range'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $pertemuan, $matkul_id, $kelas_id, $jadwal_id)
    {
        $rentang = [];
        if ($pertemuan == '1-7') {
            $rentang = range(1, 7);
        } elseif ($pertemuan == '8-14') {
            $rentang = range(8, 14);
        }
    
        try {
            $resumeRecords = Resume::where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->where('jadwals_id', $jadwal_id)
                ->whereIn('pertemuan', $rentang)
                ->get();
    
            $allKaprodiApproved = true;
            $allWadirApproved = true;
    
            foreach ($resumeRecords as $resume) {
                if ($request->has('kaprodi')) {
                    $resume->setuju_kaprodi = true;
                }
                
                if ($request->has('wakil_direktur')) {
                    $resume->setuju_wadir = true;
                }
    
                if ($request->has('uncheck_kaprodi')) {
                    $resume->setuju_kaprodi = false;
                }
                
                if ($request->has('uncheck_wakil_direktur')) {
                    $resume->setuju_wadir = false;
                }
    
                if (!$resume->setuju_kaprodi) {
                    $allKaprodiApproved = false;
                }
                if (!$resume->setuju_wadir) {
                    $allWadirApproved = false;
                }
    
                $resume->save();
            }
    
            $statusBerita = ($allKaprodiApproved && $allWadirApproved) ? 1 : 0;
    
            $pengajuan = PengajuanRekapBerita::where('matkuls_id', $matkul_id)
                ->where('kelas_id', $kelas_id)
                ->where('pertemuan', $pertemuan)
                ->where('jadwal_id', $jadwal_id)
                ->first();
    
            if ($pengajuan) {
                $pengajuan->update(['status' => $statusBerita]);
            }

            if($statusBerita){
                $dosen = Dosen::findOrFail($resumeRecords->first()->dosens_id);
                $dosen->notify(new PengajuanResumeNotification($pengajuan));
            }
    
            return redirect()->back()->with('success', 'Status persetujuan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status persetujuan: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanRekapBerita $pengajuanRekapBerita)
    {
        //
    }
}
