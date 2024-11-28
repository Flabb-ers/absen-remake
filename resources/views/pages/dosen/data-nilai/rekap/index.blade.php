<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    th,
    td {
        border: 1px solid;
        padding: 0 8px;
        text-align: center;
    }

    td:nth-child(3) {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }
</style>

<div class="container-fluid px-4">
    <div class="card mb-4">
        <div>
            <i class="fa fa-table me-1"></i>
            Rekap Nilai Mahasiswa
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <td rowspan="2">No</td>
                            <td rowspan="2">NIM</td>
                            <td rowspan="2">Nama</td>
                            <td colspan="{{ $jumlahTugas + 1 }}">Tugas</td>
                            <td colspan="2">Aktif</td>
                            <td colspan="2">Etika</td>
                            <td colspan="2"></td>
                            <td colspan="4">Ujian</td>
                            <td rowspan="2">Jumlah</td>
                            <td rowspan="2">NA</td>
                        </tr>
                        <tr>
                            @for ($i = 1; $i <= $jumlahTugas; $i++)
                                <td>{{ $i }}</td>
                            @endfor
                            <td>%Tugas</td>
                            <td>K</td>
                            <td>%K</td>
                            <td>E</td>
                            <td>%E</td>
                            <td>TOT</td>
                            <td>%P</td>
                            <td>UTS</td>
                            <td>%MID</td>
                            <td>UAS</td>
                            <td>%UAS</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            function getKeterangan($jumlah)
                            {
                                if ($jumlah >= 85 && $jumlah <= 100) {
                                    return 'A';
                                } elseif ($jumlah >= 80 && $jumlah < 85) {
                                    return 'A-';
                                } elseif ($jumlah >= 75 && $jumlah < 80) {
                                    return 'B+';
                                } elseif ($jumlah >= 70 && $jumlah < 75) {
                                    return 'B';
                                } elseif ($jumlah >= 65 && $jumlah < 70) {
                                    return 'B-';
                                } elseif ($jumlah >= 60 && $jumlah < 65) {
                                    return 'C+';
                                } elseif ($jumlah >= 55 && $jumlah < 60) {
                                    return 'C';
                                } elseif ($jumlah >= 50 && $jumlah < 55) {
                                    return 'C-';
                                } elseif ($jumlah >= 40 && $jumlah < 50) {
                                    return 'D';
                                } elseif ($jumlah >= 0 && $jumlah < 40) {
                                    return 'E';
                                } else {
                                    return '-';
                                }
                            }
                        @endphp

                        @foreach ($mahasiswas as $mahasiswa)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ $mahasiswa->nama_lengkap }}</td>

                                @php
                                    $tugasGroup = $groupedTugas[$mahasiswa->id] ?? collect();

                                    $totalNilaiTugas = 0;
                                    $jumlahTugasDikumpulkan = 0;

                                    for ($i = 1; $i <= $jumlahTugas; $i++) {
                                        $tugas = $tugasGroup->firstWhere('tugas_ke', $i);
                                        if ($tugas) {
                                            $nilai = $tugas->nilai;
                                            if ($nilai === null || $nilai === '-') {
                                                $nilai = 0;
                                            }
                                            $totalNilaiTugas += $nilai;
                                            $jumlahTugasDikumpulkan++;
                                        }
                                    }

                                    $persentaseTugas =
                                        $jumlahTugasDikumpulkan > 0
                                            ? ($totalNilaiTugas / ($jumlahTugas * 100)) * 25
                                            : 0;

                                    $nilaiKeaktifan = $dataAktif[$mahasiswa->id]->nilai ?? 0;
                                    $persentaseKeaktifan = ($nilaiKeaktifan / 100) * 5;

                                    $nilaiEtika = $dataEtika[$mahasiswa->id]->nilai ?? 0;
                                    $persentaseEtika = ($nilaiEtika / 100) * 5;

                                    $totalKehadiran = $dataAbsensi[$mahasiswa->id]['total_kegiatan'] ?? 0;
                                    $persentaseKehadiran =
                                        $totalPertemuan > 0 ? ($totalKehadiran / $totalPertemuan) * 15 : 0;

                                    $nilaiUts = $utss[$mahasiswa->id]->nilai ?? 0;
                                    $persentaseUts = ($nilaiUts / 100) * 25;

                                    $nilaiUas = $uass[$mahasiswa->id]->nilai ?? 0;
                                    $persentaseUas = ($nilaiUas / 100) * 25;

                                    $jumlahTotal =
                                        $persentaseTugas +
                                        $persentaseKeaktifan +
                                        $persentaseEtika +
                                        $persentaseKehadiran +
                                        $persentaseUts +
                                        $persentaseUas;
                                @endphp

                                @for ($i = 1; $i <= $jumlahTugas; $i++)
                                    <td>
                                        @php
                                            $tugas = $tugasGroup->firstWhere('tugas_ke', $i);
                                        @endphp
                                        {{ $tugas ? $tugas->nilai ?? 0 : '0' }}
                                    </td>
                                @endfor

                                <td>{{ number_format($persentaseTugas, 2) }}%</td>
                                <td>{{ $nilaiKeaktifan }}</td>
                                <td>{{ number_format($persentaseKeaktifan, 2) }}%</td>
                                <td>{{ $nilaiEtika }}</td>
                                <td>{{ number_format($persentaseEtika, 2) }}%</td>
                                <td>{{ $totalKehadiran }}</td>
                                <td>{{ number_format($persentaseKehadiran, 2) }}%</td>
                                <td>{{ $nilaiUts }}</td>
                                <td>{{ number_format($persentaseUts, 2) }}%</td>
                                <td>{{ $nilaiUas }}</td>
                                <td>{{ number_format($persentaseUas, 2) }}%</td>
                                <td>{{ number_format($jumlahTotal, 2) }}%</td>
                                <td>{{ getKeterangan($jumlahTotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-end">
            @if (!$approve && $cekNilaiLengkap)
                @if (!$approve)
                    <form action="/presensi/data-nilai/rekap" method="POST">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $jadwals->kelas_id }}">
                        <input type="hidden" name="matkul_id" value="{{ $jadwals->matkuls_id }}">
                        <input type="hidden" name="jadwal_id" value="{{ $jadwals->id }}">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-send"></span> Ajukan Verifikasi
                        </button>
                    </form>
                @endif
            @elseif ($approve && $approve->status == 0)
                <div class="btn btn-warning btn-sm">
                    <span class="mdi mdi-clock"></span> Pending
                </div>
            @elseif ($approve && $approve->status == 1)
                <a href="/presensi/data-nilai/rekap/{{ $jadwals->kelas_id }}/{{ $jadwals->matkuls_id }}/{{ $jadwals->id }}"
                    method="GET" class="btn btn-success btn-sm">
                    <span class="mdi mdi-file-document"></span> Rekap Nilai (Approved)
                </a>
            @endif
        </div>
    </div>
</div>
