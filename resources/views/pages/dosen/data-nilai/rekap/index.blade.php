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
        @php
            $groupedTugas = $tugass->groupBy('mahasiswa_id');
        @endphp
        @foreach ($groupedTugas as $mahasiswaId => $tugasGroup)
            @php
                $firstTugas = $tugasGroup->first();
                $mahasiswa = $firstTugas->mahasiswa ?? null;

                // Hitung nilai tugas
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

                $persentaseTugas = $jumlahTugasDikumpulkan > 0 ? ($totalNilaiTugas / ($jumlahTugas * 100)) * 25 : 0;

                // Ambil nilai keaktifan dan etika
                $nilaiKeaktifan = $dataAktif[$mahasiswaId]->nilai ?? 0;
                $persentaseKeaktifan = ($nilaiKeaktifan / 100) * 5;

                $nilaiEtika = $dataEtika[$mahasiswaId]->nilai ?? 0;
                $persentaseEtika = ($nilaiEtika / 100) * 5; // Hitung persentase etika
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
                        {{ $tugas ? number_format($tugas->nilai ?? 0, 2) : '0' }}
                    </td>
                @endfor

                <td>{{ number_format($persentaseTugas, 2) }}%</td>
                <td>{{ $nilaiKeaktifan }}</td>
                <td>{{ number_format($persentaseKeaktifan, 2) }}%</td>
                <td>{{ $nilaiEtika }}</td>
                <td>{{ number_format($persentaseEtika, 2) }}%</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        @endforeach
    </table>
</div>
