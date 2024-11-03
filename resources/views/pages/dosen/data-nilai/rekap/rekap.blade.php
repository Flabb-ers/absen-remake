<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Nilai Mahasiswa</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

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

        .container-fluid {
            padding: 15px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            margin-bottom: 20px;
            width: 100%;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        .header-info h4 {
            font-size: 11px;
            font-weight: bold;
            margin: 3px;
        }

        thead tr td {
            background-color: #d9d9d9;
        }

        .signature-section {
            margin-top: 50px;
            width: 100%;
            position: relative;
            height: 200px;
        }

        .left-signature,
        .center-signature,
        .right-signature {
            width: 200px;
            font-size: 12px;
            font-weight: bold;
        }

        .left-signature {
            position: absolute;
            left: 10%;
            text-align: left;
        }

        .center-signature {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            margin-top: 160px;
        }

        .right-signature {
            position: absolute;
            right: 10%;
            text-align: left;
        }

        .signature-title {
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        .signature-role {
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        .signature-name {
            margin: 0;
            padding: 0;
            margin-top: 80px;
            font-weight: bold;
        }

        .right-signature .signature-date {
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        @page {
            size: A4 landscape;
            margin: 30px;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>



</head>

<body>
    <div class="container-fluid">
        @php
            use Carbon\Carbon;
            $totalKehadiranSemuaMahasiswa = 0;
            $jumlahMahasiswa = count($mahasiswas);

            foreach ($mahasiswas as $mahasiswa) {
                $totalKehadiran = $dataAbsensi[$mahasiswa->id]['total_kegiatan'] ?? 0;
                $totalKehadiranSemuaMahasiswa += $totalKehadiran;
            }

            $rataRataKehadiran = $jumlahMahasiswa > 0 ? $totalKehadiranSemuaMahasiswa / $jumlahMahasiswa : 0;
        @endphp

        <div style="text-align:center">
            <h4 style="font-size:11px; margin: 2px;">DAFTAR NILAI</h4>
            <h4 style="font-size:11px; margin: 2px;">Semester : @if ($jadwals->kelas->semester->semester % 2 == 0)
                    Genap
                @else
                    Ganjil
                @endif {{ $jadwals->tahun }}</h4>
            <h4 style="font-size:11px; margin: 2px;">Politeknik Sawunggalih Aji</h4>
        </div>

        <div class="header-info">
            <div>
                <h4 style="display: inline-block; width: 140px;">Mata Kuliah</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $jadwals->matkul->nama_matkul }}</h4>
                <br>
                <h4 style="display: inline-block; width: 140px;">Dosen</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $jadwals->dosen->nama }}</h4>
                <br>
                <h4 style="display: inline-block; width: 140px;">NIDN</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $jadwals->dosen->nidn }}</h4>
                <br>
                <h4 style="display: inline-block; width: 140px;">Program Studi</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $jadwals->kelas->prodi->jenjang }}
                    {{ $jadwals->kelas->prodi->nama_prodi }}</h4>
            </div>
            <div>
                <h4 style="display: inline-block; width: 200px;">Kelas</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left: -60px">:</h4>
                <h4 style="display: inline-block;">
                    @if ($jadwals->kelas->jenis_kelas == 'Reguler')
                        A
                    @elseif($jadwals->kelas->jenis_kelas == 'Karyawan')
                        B
                    @else
                        -
                    @endif
                </h4>
                <br>
                <h4 style="display: inline-block; width: 200px;">Semester</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $jadwals->kelas->semester->semester }}</h4>
                <br>
                <h4 style="display: inline-block; width: 200px;">Kode Kelas</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $jadwals->kelas->kode_kelas }}</h4>
                <br>
                <h4 style="display: inline-block; width: 200px;">Jumlah TM</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $totalPertemuan }}</h4>
                <br>
                <div>
                    <h4 style="display: inline-block; width: 200px;">Rata-rata Kehadiran</h4>
                    <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                    <h4 style="display: inline-block;">{{ number_format($rataRataKehadiran, 2) }}</h4>
                </div>
            </div>
        </div>

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
                                    $jumlahTugasDikumpulkan > 0 ? ($totalNilaiTugas / ($jumlahTugas * 100)) * 25 : 0;

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

        <div class="signature-section">
            <div class="left-signature">
                <p class="signature-title">Mengesahkan</p>
                <p class="signature-role">Kaprodi</p>
                <p class="signature-name">{{ $kaprodi->nama }}</p>
            </div>

            <div class="right-signature">
                <p class="signature-date">Purworejo, {{ Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>

                <p class="signature-role">Dosen Pengampu</p>
                <p class="signature-name">{{ $jadwals->dosen->nama }}</p>
            </div>

            <div class="center-signature">
                <p class="signature-title">Mengetahui</p>
                <p class="signature-role">Wakil Direktur I</p>
                <p class="signature-name">{{ $wadir->nama }}</p>
            </div>
        </div>
