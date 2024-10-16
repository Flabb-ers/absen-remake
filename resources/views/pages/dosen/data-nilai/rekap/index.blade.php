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

    /* Tambahkan aturan CSS berikut */
    td:nth-child(3) { /* Kolom "Nama" */
        white-space: nowrap; /* Mencegah pemotongan teks */
        overflow: hidden; /* Sembunyikan overflow */
        text-overflow: ellipsis; /* Tampilkan elipsis jika teks terlalu panjang */
        max-width: 150px; /* Atur lebar maksimum kolom "Nama" */
    }
</style>

<div class="table-responsive">
    <table>
        <tr>
            <td rowspan="2">No</td>
            <td rowspan="2">NIM</td>
            <td rowspan="2">Nama</td>
            <td colspan="{{ $jumlahTugas + 1 }}">Tugas</td>
            <td colspan="2">Aktif</td>
            <td colspan="2">Etika</td>
            <td colspan="2">Kehadiran</td>
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
            <td>Tot</td>
            <td>%P</td> 
            <td>UTS</td>
            <td>%MID</td>
            <td>UAS</td>
            <td>%UAS</td>
        </tr>
        @php
            $groupedTugas = $tugass->groupBy('mahasiswa_id');

            function getKeterangan($jumlah) {
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
        @foreach ($groupedTugas as $mahasiswaId => $tugasGroup)
            @php
                $firstTugas = $tugasGroup->first();
                $mahasiswa = $firstTugas->mahasiswa ?? null;

                // Inisialisasi variabel
                $totalNilaiTugas = 0;
                $jumlahTugasDikumpulkan = 0;

                // Menghitung nilai tugas
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

                $persentaseTugas = $jumlahTugasDikumpulkan > 0 ? ($totalNilaiTugas / ($jumlahTugas * 100)) * 25 : 0;

                $nilaiKeaktifan = $dataAktif[$mahasiswaId]->nilai ?? 0;
                $persentaseKeaktifan = ($nilaiKeaktifan / 100) * 5;

                $nilaiEtika = $dataEtika[$mahasiswaId]->nilai ?? 0;
                $persentaseEtika = ($nilaiEtika / 100) * 5;

                $totalKehadiran = $dataAbsensi[$mahasiswaId]['total_kegiatan'] ?? 0; 
                $persentaseKehadiran = $totalPertemuan > 0 ? ($totalKehadiran / $totalPertemuan) * 15 : 0;

                $nilaiUts = $utss[$mahasiswaId]->nilai ?? 0; 
                $persentaseUts = ($nilaiUts / 100) * 25;

                $nilaiUas = $uass[$mahasiswaId]->nilai ?? 0; 
                $persentaseUas = ($nilaiUas / 100) * 25; 

                // Hitung jumlah total
                $jumlahTotal = $persentaseTugas + $persentaseKeaktifan + $persentaseEtika + $persentaseKehadiran + $persentaseUts + $persentaseUas;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mahasiswa ? $mahasiswa->nim : '-' }}</td>
                <td>{{ $mahasiswa ? $mahasiswa->nama_lengkap : '-' }}</td>

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
    </table>
</div>
