<?php

namespace App\Http\Controllers;

use App\Models\Uas;
use App\Models\Uts;
use App\Models\Absen;
use App\Models\Aktif;
use App\Models\Etika;
use App\Models\Tugas;
use App\Models\Wadir;
use App\Models\Jadwal;
use App\Models\Kaprodi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanRekapNilai;
use Illuminate\Support\Facades\Log;

class RekapNilaiController extends Controller
{
    public function index($kelas_id, $matkul_id, $jadwal_id)
    {
        $mahasiswa_ids = collect();

        $tugas_mahasiswa_ids = Tugas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $aktif_mahasiswa_ids = Aktif::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $etika_mahasiswa_ids = Etika::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $absen_mahasiswa_ids = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->pluck('mahasiswas_id');

        $uts_mahasiswa_ids = Uts::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $uas_mahasiswa_ids = Uas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $mahasiswa_ids = $mahasiswa_ids->concat($tugas_mahasiswa_ids)
            ->concat($aktif_mahasiswa_ids)
            ->concat($etika_mahasiswa_ids)
            ->concat($absen_mahasiswa_ids)
            ->concat($uts_mahasiswa_ids)
            ->concat($uas_mahasiswa_ids)
            ->unique();


        $mahasiswas = Mahasiswa::withTrashed()
            ->whereIn('id', $mahasiswa_ids)
            ->orderBy('nim', 'asc')
            ->get();

        $tugass = Tugas::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $groupedTugas = $tugass->groupBy('mahasiswa_id');
        $jumlahTugas = max(1, $tugass->pluck('tugas_ke')->unique()->count());

        $aktifs = Aktif::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();
        $dataAktif = $aktifs->keyBy('mahasiswa_id');

        $etikas = Etika::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();
        $dataEtika = $etikas->keyBy('mahasiswa_id');

        $absens = Absen::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->get();

        $dataAbsensi = $absens->groupBy('mahasiswas_id');

        $totalPertemuan = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->max('pertemuan');

        $jadwals = Jadwal::where('id', $jadwal_id)->first();

        $dataAbsensi = $dataAbsensi->map(function ($absensiGroup, $mahasiswaId) use ($totalPertemuan) {
            $totalKehadiran = $absensiGroup->whereIn('status', ['H', 'T'])->count();
            $persentaseKehadiran = $totalPertemuan > 0 ? ($totalKehadiran / $totalPertemuan) * 15 : 0;

            return [
                'total_kegiatan' => $totalKehadiran,
                'persentase_kehadiran' => number_format($persentaseKehadiran, 2),
                'absensi' => $absensiGroup,
            ];
        });

        $utss = Uts::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        $uass = Uas::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        $approve = PengajuanRekapNilai::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->first();

        return view(
            'pages.dosen.data-nilai.rekap.index',
            compact(
                'mahasiswas',
                'groupedTugas',
                'jumlahTugas',
                'dataAktif',
                'dataEtika',
                'dataAbsensi',
                'totalPertemuan',
                'utss',
                'uass',
                'jadwals',
                'approve'
            )
        );
    }


    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kelas_id' => 'required',
            'jadwal_id' => 'required',
            'matkul_id' => 'required'
        ]);

        $tahun = TahunAkademik::where('status', 1)->first();

        PengajuanRekapNilai::create([
            'kelas_id' => $validateData['kelas_id'],
            'matkul_id' => $validateData['matkul_id'],
            'jadwal_id' => $validateData['jadwal_id'],
            'tahun' => $tahun->tahun_akademik
        ]);

        session()->flash('success', 'Pengajuan verifikasi nilai berhasil');
        session()->flash('tab', 'rekap');
        session()->flash('kelas_id', $validateData['kelas_id']);
        session()->flash('matkul_id', $validateData['matkul_id']);
        session()->flash('jadwal_id', $validateData['jadwal_id']);
        return redirect()->back();
    }


    public function pengajuan()
    {
        $pengajuans = PengajuanRekapNilai::with([
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('status', 0)
            ->latest()
            ->get();

        $kelasAll = Jadwal::all();
        return view('pages.pengajuanRekapNilai.index', compact('pengajuans', 'kelasAll'));
    }

    public function disetujui()
    {
        $pengajuans = PengajuanRekapNilai::with([
            'kelas' => function ($query) {
                $query->withTrashed();
            },
            'kelas.prodi' => function ($query) {
                $query->withTrashed();
            },
            'jadwal' => function ($query) {
                $query->withTrashed();
            },
            'matkul' => function ($query) {
                $query->withTrashed();
            }
        ])
            ->where('status', 1)
            ->latest()
            ->get();

        $kelasAll = Jadwal::all();
        return view('pages.pengajuanRekapNilai.disetujui', compact('pengajuans', 'kelasAll'));
    }

    public function diajukan($kelas_id, $matkul_id, $jadwal_id)
    {

        $mahasiswa_ids = collect();

        $tugas_mahasiswa_ids = Tugas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $aktif_mahasiswa_ids = Aktif::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $etika_mahasiswa_ids = Etika::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $absen_mahasiswa_ids = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->pluck('mahasiswas_id');

        $uts_mahasiswa_ids = Uts::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $uas_mahasiswa_ids = Uas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $mahasiswa_ids = $mahasiswa_ids->concat($tugas_mahasiswa_ids)
            ->concat($aktif_mahasiswa_ids)
            ->concat($etika_mahasiswa_ids)
            ->concat($absen_mahasiswa_ids)
            ->concat($uts_mahasiswa_ids)
            ->concat($uas_mahasiswa_ids)
            ->unique();


        $mahasiswas = Mahasiswa::withTrashed()
            ->whereIn('id', $mahasiswa_ids)
            ->orderBy('nim', 'asc')
            ->get();

        $tugass = Tugas::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $groupedTugas = $tugass->groupBy('mahasiswa_id');
        $jumlahTugas = max(1, $tugass->pluck('tugas_ke')->unique()->count());

        $aktifs = Aktif::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();
        $dataAktif = $aktifs->keyBy('mahasiswa_id');

        $etikas = Etika::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();
        $dataEtika = $etikas->keyBy('mahasiswa_id');

        $absens = Absen::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->get();

        $dataAbsensi = $absens->groupBy('mahasiswas_id');

        $totalPertemuan = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->max('pertemuan');

        $jadwals = Jadwal::where('id', $jadwal_id)->first();

        $dataAbsensi = $dataAbsensi->map(function ($absensiGroup, $mahasiswaId) use ($totalPertemuan) {
            $totalKehadiran = $absensiGroup->whereIn('status', ['H', 'T'])->count();
            $persentaseKehadiran = $totalPertemuan > 0 ? ($totalKehadiran / $totalPertemuan) * 15 : 0;

            return [
                'total_kegiatan' => $totalKehadiran,
                'persentase_kehadiran' => number_format($persentaseKehadiran, 2),
                'absensi' => $absensiGroup,
            ];
        });

        $utss = Uts::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        $uass = Uas::with('kelas', 'mahasiswa')
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        $kaprodi = Kaprodi::Where('prodis_id', $jadwals->first()->kelas->prodi->id)->first();

        $wadir = Wadir::first();

        return view(
            'pages.pengajuanRekapNilai.diajukan',
            compact(
                'mahasiswas',
                'groupedTugas',
                'jumlahTugas',
                'dataAktif',
                'dataEtika',
                'kaprodi',
                'dataAbsensi',
                'totalPertemuan',
                'utss',
                'uass',
                'jadwals',
                'wadir'
            )
        );
    }

    public function update(Request $request, $kelas_id, $matkul_id, $jadwal_id)
    {
        DB::beginTransaction();

        try {
            if ($request->has('konfirmasi')) {
                $updateData['setuju'] = true;
            }

            Tugas::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->update($updateData);

            Etika::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->update($updateData);

            Aktif::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->update($updateData);

            Uas::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->update($updateData);

            Uts::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->update($updateData);

            $tugasSetuju = Tugas::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->where('setuju', true)
                ->exists();

            $etikaSetuju = Etika::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->where('setuju', true)
                ->exists();

            $aktifSetuju = Aktif::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->where('setuju', true)
                ->exists();

            $uasSetuju = Uas::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->where('setuju', true)
                ->exists();

            $utsSetuju = Uts::where('kelas_id', $kelas_id)
                ->where('jadwal_id', $jadwal_id)
                ->where('matkul_id', $matkul_id)
                ->where('setuju', true)
                ->exists();

            if ($tugasSetuju && $etikaSetuju && $aktifSetuju && $uasSetuju && $utsSetuju) {
                $pengajuan = PengajuanRekapNilai::where('kelas_id', $kelas_id)
                    ->where('jadwal_id', $jadwal_id)
                    ->where('matkul_id', $matkul_id)
                    ->first();
                if ($pengajuan) {
                    $pengajuan->update(['status' => 1]);
                }
            }

            DB::commit();

            return redirect('/presensi/data-nilai/pengajuan/rekap-nilai')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect('/presensi/data-nilai/pengajuan/rekap-nilai')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rekap($kelas_id, $matkul_id, $jadwal_id)
    {
        $mahasiswa_ids = collect();

        $tugas_mahasiswa_ids = Tugas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $aktif_mahasiswa_ids = Aktif::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $etika_mahasiswa_ids = Etika::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $absen_mahasiswa_ids = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->pluck('mahasiswas_id');

        $uts_mahasiswa_ids = Uts::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $uas_mahasiswa_ids = Uas::where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->pluck('mahasiswa_id');

        $mahasiswa_ids = $mahasiswa_ids->concat($tugas_mahasiswa_ids)
            ->concat($aktif_mahasiswa_ids)
            ->concat($etika_mahasiswa_ids)
            ->concat($absen_mahasiswa_ids)
            ->concat($uts_mahasiswa_ids)
            ->concat($uas_mahasiswa_ids)
            ->unique();


        $mahasiswas = Mahasiswa::withTrashed()
            ->whereIn('id', $mahasiswa_ids)
            ->orderBy('nim', 'asc')
            ->get();

        $tugass = Tugas::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $groupedTugas = $tugass->groupBy('mahasiswa_id');
        $jumlahTugas = max(1, $tugass->pluck('tugas_ke')->unique()->count());

        $aktifs = Aktif::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $dataAktif = $aktifs->keyBy('mahasiswa_id');

        $etikas = Etika::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get();

        $dataEtika = $etikas->keyBy('mahasiswa_id');

        $absens = Absen::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->get();


        $dataAbsensi = $absens->groupBy('mahasiswas_id');

        $totalPertemuan = Absen::where('kelas_id', $kelas_id)
            ->where('matkuls_id', $matkul_id)
            ->where('jadwals_id', $jadwal_id)
            ->max('pertemuan');

        $jadwals = Jadwal::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'kelas.prodi' => function ($query) {
            $query->withTrashed();
        }, 'matkul' => function ($query) {
            $query->withTrashed();
        }])
            ->withTrashed()
            ->where('id', $jadwal_id)
            ->first();

        $dataAbsensi = $dataAbsensi->map(function ($absensiGroup, $mahasiswaId) use ($totalPertemuan) {
            $totalKehadiran = $absensiGroup->whereIn('status', ['H', 'T'])->count();
            $persentaseKehadiran = $totalPertemuan > 0 ? ($totalKehadiran / $totalPertemuan) * 15 : 0;

            return [
                'total_kegiatan' => $totalKehadiran,
                'persentase_kehadiran' => number_format($persentaseKehadiran, 2),
                'absensi' => $absensiGroup,
            ];
        });

        $utss = Uts::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        $uass = Uas::with(['kelas' => function ($query) {
            $query->withTrashed();
        }, 'mahasiswa' => function ($query) {
            $query->withTrashed();
        }])
            ->where('kelas_id', $kelas_id)
            ->where('matkul_id', $matkul_id)
            ->where('jadwal_id', $jadwal_id)
            ->get()
            ->keyBy('mahasiswa_id');

        if ($jadwals && $jadwals->kelas && $jadwals->kelas->prodi) {
            $kaprodi = Kaprodi::where('prodis_id', $jadwals->kelas->prodi->id)
                ->first();
        }

        $wadir = Wadir::first();

        return view(
            'pages.dosen.data-nilai.rekap.rekap',
            compact(
                'mahasiswas',
                'groupedTugas',
                'jumlahTugas',
                'dataAktif',
                'dataEtika',
                'kaprodi',
                'dataAbsensi',
                'totalPertemuan',
                'utss',
                'uass',
                'jadwals',
                'wadir'
            )
        );
    }
}
