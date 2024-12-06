<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Mahasiswa;
use App\Models\NilaiHuruf;
use App\Models\Pembayaran;
use App\Models\TahunAkademik;
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
        $kelas = Kelas::where('id', $this->kelasId)->first();
        $matkulKrs = Matkul::where('semester_id', $kelas->id_semester)
            ->where('prodi_id', $kelas->id_prodi)
            ->get();
        $cekStatus = Mahasiswa::where('id', $this->userId)->first();
        $semesters = NilaiHuruf::where('mahasiswa_id', $this->userId)
            ->select('semester_id')
            ->with('semester')
            ->groupBy('semester_id')
            ->get();
        $pembayaran = Pembayaran::where('mahasiswa_id', $this->userId)
            ->where('prodi_id', $kelas->id_prodi)
            ->where('semester_id', $kelas->id_semester)
            ->where('kelas_id', $this->kelasId)
            ->latest()
            ->first();
        $prodiId = $kelas->id_prodi;
        $semesterId = $kelas->id_semester;
        if ($pembayaran) {
            $krs = Krs::with('mahasiswa', 'kelas', 'prodi', 'semester')
                ->where('mahasiswa_id', $this->userId)
                ->where('kelas_id', $this->kelasId)
                ->where('semester_id', $kelas->id_semester)
                ->where('prodi_id', $kelas->id_prodi)
                ->where('created_at', '>=', $pembayaran->created_at)
                ->first();
        } else {
            $krs = Krs::with('mahasiswa', 'kelas', 'prodi', 'semester')
                ->where('mahasiswa_id', $this->userId)
                ->where('kelas_id', $this->kelasId)
                ->where('semester_id', $kelas->id_semester)
                ->where('prodi_id', $kelas->id_prodi)
                ->first();
        }
        $cekPembayaran = $pembayaran !== null;

        if ($cekPembayaran) {
            $cekPembayaran = true;
        } else {
            $cekPembayaran = false;
        }

        $mahasiswa = Mahasiswa::where('id', $this->userId)->first();
        $cekStatusKrs = $mahasiswa->status_krs;
        return view('pages.mahasiswa.krs_pembayaran.index', compact('semesters', 'cekStatus', 'matkulKrs', 'cekPembayaran', 'pembayaran', 'krs', 'cekStatusKrs'));
    }

    public function createPembayaran(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg|max:5120',
        ], [
            'file.required' => 'File bukti pembayaran wajib diunggah.',
            'file.mimes' => 'File harus berformat jpeg, png, atau jpg.',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $mahasiswaId = Mahasiswa::where('id', $request->mahasiswa_id)->first();
        $kelasId = Kelas::where('id', $mahasiswaId->kelas_id)->first();
        $prodiId = $kelasId->id_prodi;
        $semesterId = $kelasId->id_semester;

        $buktiPembayaranPath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $buktiPembayaranPath = $file->store('images', 'public');
            } else {
                return back()->withErrors(['file' => 'File upload failed']);
            }
        } else {
            return back()->withErrors(['file' => 'No file uploaded']);
        }

        Pembayaran::create([
            'mahasiswa_id' => $mahasiswaId->id,
            'prodi_id' => $prodiId,
            'semester_id' => $semesterId,
            'kelas_id' => $kelasId->id,
            'bukti_pembayaran' => $buktiPembayaranPath,
            'status_pembayaran' => 0
        ]);

        return redirect('/presensi/mahasiswa/krs_pembayaran')->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    public function diajukan()
    {
        $pembayarans = Pembayaran::with('mahasiswa', 'prodi', 'kelas', 'semester')->where('status_pembayaran', 0)->latest()->get();
        return view('pages.pembayaran.index', compact('pembayarans'));
    }
    public function disetujui()
    {
        $pembayarans = Pembayaran::with('mahasiswa', 'prodi', 'kelas', 'semester')->where('status_pembayaran', 1)->latest()->get();
        return view('pages.pembayaran.index', compact('pembayarans'));
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::with('mahasiswa', 'mahasiswa.kelas.prodi', 'mahasiswa.kelas.semester', 'mahasiswa.kelas')
            ->where('id', $id)
            ->first();
        return view('pages.pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::where('id', $id)->first();
        if (!$pembayaran) {
            return redirect('/presensi/pembayaran/diajukan')->with('error', 'Data pembayaran tidak ditemukan.');
        }
        $request->validate([
            'status_pembayaran' => 'required|in:0,1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $pembayaran->status_pembayaran = $request->status_pembayaran;
        $pembayaran->keterangan = $request->keterangan;

        $pembayaran->save();
        if ($request->status_pembayaran == 0) {
            return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
        } else {
            Krs::create([
                'mahasiswa_id' => $pembayaran->mahasiswa_id,
                'prodi_id' => $pembayaran->prodi_id,
                'semester_id' => $pembayaran->semester_id,
                'kelas_id' => $pembayaran->kelas_id,
                'status_krs' => 0,
                'setuju_pa' => 0,
                'setuju_mahasiswa' => 0,
                'tahun_ajaran' => TahunAkademik::where('status', 1)->pluck('tahun_akademik')->first()
            ]);
            return redirect('/presensi/pembayaran/diajukan')->with('success', 'Status pembayaran berhasil diperbarui.');
        }
    }

    public function pengajuanKrs(Request $request)
    {
        $mahasiswaId = Mahasiswa::where('id', $this->userId)->first();
        $kelasId = Kelas::where('id', $this->kelasId)->first();
        $prodiId = $kelasId->id_prodi;
        $semesterId = $kelasId->id_semester;
        $tahunAjaran = TahunAkademik::where('status', 1)->first();

        Krs::create([
            'mahasiswa_id' => $mahasiswaId->id,
            'kelas_id' => $kelasId->id,
            'prodi_id' => $prodiId,
            'semester_id' => $semesterId,
            'tahun_ajaran' => $tahunAjaran->tahun_akademik,
            'status_krs' => 0
        ]);

        return redirect()->back()->with('success', 'KRS berhasil diajukan');
    }

    public function krsDiajukan()
    {
        $dosenPa = $this->userId;
        $krss = Krs::with('mahasiswa', 'kelas.prodi', 'kelas')
            ->whereHas('mahasiswa', function ($query) use ($dosenPa) {
                $query->where('dosen_pembimbing_id', $dosenPa);
            })
            ->where('status_krs', 0)
            ->where('setuju_pa', 0)
            ->where('setuju_mahasiswa', 1)
            ->latest()
            ->get();
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        return view('pages.dosen.krs.index', compact('krss', 'kelasAll'));
    }

    public function krsDisetujui()
    {
        $dosenPa = $this->userId;
        $krss = Krs::with('mahasiswa', 'kelas.prodi', 'kelas')
            ->whereHas('mahasiswa', function ($query) use ($dosenPa) {
                $query->where('dosen_pembimbing_id', $dosenPa);
            })
            ->where('status_krs', 1)
            ->where('setuju_pa', 1)
            ->where('setuju_mahasiswa', 1)
            ->latest()
            ->get();
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        return view('pages.dosen.krs.index', compact('krss', 'kelasAll'));
    }

    public function krsEdit($id)
    {
        $krs = Krs::with('mahasiswa', 'kelas', 'prodi', 'semester')->where('id', $id)->first();
        $prodiId = $krs->prodi_id;
        $semesterId = $krs->semester_id;
        $matkulKrs = Matkul::where('prodi_id', $prodiId)
            ->where('semester_id', $semesterId)
            ->get();
        $kelasAll = Jadwal::where('dosens_id', $this->userId)->get();
        return view('pages.dosen.krs.edit', compact('krs', 'matkulKrs', 'kelasAll'));
    }

    public function krsUpdate(Request $request, $id)
    {
        $krs = Krs::where('id', $id)->first();
        if ($request->setuju_mahasiswa) {
            $krs->setuju_mahasiswa = $request->setuju_mahasiswa;
        }
        if ($request->setuju_pa) {
            $krs->setuju_pa = $request->setuju_pa;
        }
        if ($krs->setuju_mahasiswa == 1 && $krs->setuju_pa == 1) {
            $krs->status_krs = 1;
            $mahasiswa = Mahasiswa::where('id', $krs->mahasiswa_id)->first();
            $mahasiswa->status_krs = true;
            $mahasiswa->save();
        }
        $krs->save();
        if ($request->setuju_mahasiswa) {
            return redirect()->back()->with('success', 'KRS berhasil diverifikasi dan langsung diserahkan kepada Dosen Pembimbing Akademink');
        } else {
            return redirect('/presensi/krs/diajukan')->with('success', 'Berhasil memverifikasi KRS');
        }
    }

    public function krsCetak($id)
    {
        $krs = Krs::with('mahasiswa', 'kelas', 'prodi', 'semester')->where('id', $id)->first();
        $prodiId = $krs->prodi_id;
        $semesterId = $krs->semester_id;
        $matkulKrs = Matkul::where('prodi_id', $prodiId)
            ->where('semester_id', $semesterId)
            ->get();
        return view('pages.mahasiswa.krs_pembayaran.cetak_krs', compact('krs', 'matkulKrs'));
    }

    public function showKelas()
    {
        $kelass = Kelas::with('prodi', 'semester', 'mahasiswa')
            ->whereHas('semester', function ($query) {
                $query->where('status', 1);
            })
            ->get();
        return view('pages.krs.index', compact('kelass'));
    }

    public function showDetailMhs($id)
    {
        $kelas = Kelas::where('id', $id)->first();
        $mahasiswas = Mahasiswa::with('kelas.semester', 'kelas')
            ->where('kelas_id', $kelas->id)
            ->orderBy('nim', 'asc')
            ->get();
        return view('pages.krs.detail', compact('mahasiswas', 'kelas'));
    }

    public function krsCetakAdmin($id)
    {
        $mahasiswa = Mahasiswa::with('kelas')->where('id', $id)->first();
        $semesterId = $mahasiswa->kelas->id_semester;
        $prodiId = $mahasiswa->kelas->id_prodi;
        $krs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $semesterId)
            ->where('prodi_id', $prodiId)
            ->first();
        $matkulKrs = Matkul::where('prodi_id', $prodiId)
            ->where('semester_id', $semesterId)
            ->get();
        return view('pages.krs.cetak', compact('krs', 'matkulKrs'));
    }
}
