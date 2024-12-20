<style>
    table {
        border-collapse: collapse;
        width: 90%;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    .monotype-corsiva {
        font-family: 'Monotype Corsiva', cursive;
    }

    td:nth-child(5),
    td:nth-child(6),
    td:nth-child(7) {
        padding: 0;
        margin-top: -5px
    }

    .container {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .left,
    .right {
        width: 45%;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .info-item div {
        margin-right: 15px;
    }

    .monotype-corsiva {
        font-family: 'Monotype Corsiva', cursive;
    }

    @media screen {
        body * {
            display: none;
        }
    }
</style>
<div style="display: flex; align-items: center; justify-content: space-between; font-size: 1em">
    <!-- Logo -->
    <div style="flex-shrink: 0; margin-right: -100px">
        <img src="{{ asset('images/file.png') }}" alt="Logo" style="height: 100px; width: auto">
    </div>

    <!-- Teks -->
    <div style="text-align: center; flex: 1;">
        <div style="font-weight: bold">YAYASAN SAWUNGGALIH AJI PURWOREJO</div>
        <div class="monotype-corsiva">SAWUNGGALIH AJI FUNDATION PURWOREJO</div>
        <div style="font-weight: bold">POLITEKNIK SAWUNGGALIH AJI</div>
        <div class="monotype-corsiva">SAWUNGGALIH AJI POLYTECHNIC</div>
        <div>JL. Wismoaji No.8 Kutoarjo Purworejo</div>
        <div>Telp.(0275) 642466. 3140444 Fax.(0275) 642467</div>
    </div>
</div>


<hr style="border: 1px solid black; margin-bottom: 15px;">
<div style="font-weight: bold; text-align:center;margin-bottom:20px">
    <div style="text-decoration: underline">KARTU HASIL STUDI</div>
    <div class="monotype-corsiva">Study Result File</div>
</div>
<div class="container">
    <div class="left">
        <div class="info-item">
            <div style="margin-right: 105px;">NIM</div>
            <div>{{ $ipss->first()->mahasiswa->nim }}</div>
        </div>
        <div class="monotype-corsiva">Student Number</div>

        <div class="info-item">
            <div style="margin-right: 86px;">NAMA</div>
            <div>{{ $ipss->first()->mahasiswa->nama_lengkap }}</div>
        </div>
        <div class="monotype-corsiva">Name of Student</div>

        <div class="info-item">
            <div style="margin-right: 15px;">SEMESTER / T.A</div>
            <div> : {{ toRoman($ipss->first()->mahasiswa->kelas->semester->semester) }} / {{ $tahunAkademikFormatted }}
            </div>
        </div>
        <div class="monotype-corsiva">Semester / Year of Academic</div>
    </div>

    <div class="right">
        <div class="info-item">
            <div style="margin-right: 15px;">PROGRAM STUDI</div>
            <div>: {{ $ipss->first()->mahasiswa->kelas->prodi->nama_prodi }}</div>
        </div>

        <div class="info-item">
            <div style="margin-right: 61px;" class="monotype-corsiva">Study Program</div>
            <div>: {{ $ipss->first()->mahasiswa->kelas->prodi->alias_nama }}</div>
        </div>

        <div class="info-item">
            <div style="margin-right: 77px;">JENJANG</div>
            <div>: {{ $ipss->first()->mahasiswa->kelas->prodi->jenjang }}</div>
        </div>

        <div class="info-item">
            <div style="margin-right: 107px;" class="monotype-corsiva">Degree</div>
            <div>:  {{ $ipss->first()->mahasiswa->kelas->prodi->alias_jenjang }} </div>
        </div>
    </div>
</div>
<table>
    <tr>
        <td style="text-align:center;font-weight:bold">
            <div>No</div>
            <div class="monotype-corsiva">No</div>
        </td>
        <td colspan="2" style="text-align:center;font-weight:bold">
            <div>Kode</div>
            <div class="monotype-corsiva">Code</div>
        </td>
        <td style="text-align:center;font-weight:bold">
            <div>MATA KULIAH</div>
            <div class="monotype-corsiva">Courses</div>
        </td>
        <td style="text-align:center;font-weight:bold">
            <div>SKS</div>
            <div class="monotype-corsiva">Semester Credit</div>
        </td>
        <td style="text-align:center;font-weight:bold">
            <div>NILAI</div>
            <div class="monotype-corsiva">Grade</div>
        </td>
        <td style="text-align:center;font-weight:bold">
            <div>KREDIT</div>
            <div class="monotype-corsiva">Credit</div>
        </td>
    </tr>
    @foreach ($ipss as $ips)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td colspan="2" style="text-align:center">{{ $ips->matkul->kode }}</td>
            <td style="max-width: 200px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                <div style="display: block; width: 100%;">
                    <span>{{ $ips->matkul->nama_matkul }}</span><br>
                    <span>({{ $ips->matkul->alias }})</span>
                </div>
            </td>
            <td style="text-align:center">{{ $ips->matkul->praktek + $ips->matkul->teori }}</td>
            <td style="text-align:center">{{ $ips->nilai_huruf }}</td>
            <td style="text-align:center">
                {{ calculateKredit($ips->nilai_huruf, $ips->matkul->praktek + $ips->matkul->teori) }}</td>
        </tr>
    @endforeach
    @php
        $sksIps = $ipss->sum(function ($ips) {
            return $ips->matkul->praktek + $ips->matkul->teori;
        });
        $sksIpk = $ipks->sum(function ($ipk) {
            return $ipk->matkul->praktek + $ipk->matkul->teori;
        });

        $kreditIps = $ipss->sum(function ($ips) {
            return calculateKredit($ips->nilai_huruf, $ips->matkul->praktek + $ips->matkul->teori);
        });
        $kreditIpk = $ipks->sum(function ($ipk) {
            return calculateKredit($ipk->nilai_huruf, $ipk->matkul->praktek + $ipk->matkul->teori);
        });
    @endphp
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="2" style="padding-top:0;padding-bottom:0">
            <div>JUMLAH</div>
            <div>Total</div>
        </td>
        <td style="text-align:center">{{ $sksIps }}</td>
        <td></td>
        <td style="text-align:center">{{ $kreditIps }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="2" style="padding-top:0;padding-bottom:0">
            <div>Kumulatif</div>
            <div>Cumulative</div>
        </td>
        <td style="text-align:center">{{ $sksIpk }}</td>
        <td></td>
        <td style="text-align:center">{{ $kreditIpk }}</td>
    </tr>
    <tr>
        <td colspan="2"></td>

        <td colspan="2" style="margin-top: -5px;padding-top:0;padding-bottom:0">
            <div>Indeks Prestasi Semester</div>
            <div>IPS : {{ number_format(round($kreditIps / $sksIps, 2), 2, '.', '') }}</div>
            <div class="monotype-corsiva">Grade Point</div>
        </td>
        <td colspan="2" style="margin-top: -5px;padding-top:0;padding-bottom:0">
            <div>Indeks Prestasi Kumulatif</div>
            <div>IPK : {{ number_format(round($kreditIpk / $sksIpk, 2), 2, '.', '') }}</div>
            <div class="monotype-corsiva">Cumulative GPA</div>
        </td>
    </tr>
</table>
<div style="margin-left:386px;margin-top:10px">
    Purworejo, {{ now()->translatedFormat('d F Y') }}
</div>
<div class="container">
    <div class="left">
        <div>Ketua Progam Studi,</div>
        <div class="monotype-corsiva" style="margin-bottom:80px">Head of Study Programmed</div>
        <div style="font-weight:bold">
            {{ $kaprodi->nama ?? '...........................................................' }}</div>
    </div>
    <div class="right">
        <div>Pembina Akademik,</div>
        <div class="monotype-corsiva" style="margin-bottom:80px">Academic Supervisor</div>
        <div>{{ $ipss->first()->mahasiswa->pembimbingAkademik->nama }}</div>
    </div>
</div>


@php
    function calculateKredit($nilai, $sks)
    {
        $nilaiToKredit = [
            'A' => 4,
            'A-' => 3.7,
            'B+' => 3.4,
            'B' => 3,
            'B-' => 2.7,
            'C+' => 2.4,
            'C' => 2,
            'C-' => 1.7,
            'D' => 1,
            'E' => 0,
        ];

        $kredit = isset($nilaiToKredit[$nilai]) ? $nilaiToKredit[$nilai] : 0;
        return $kredit * $sks;
    }
    function toRoman($num)
    {
        $n = intval($num);
        $result = '';

        $romanNumerals = [
            1000 => 'M',
            900 => 'CM',
            500 => 'D',
            400 => 'CD',
            100 => 'C',
            90 => 'XC',
            50 => 'L',
            40 => 'XL',
            10 => 'X',
            9 => 'IX',
            5 => 'V',
            4 => 'IV',
            1 => 'I',
        ];

        foreach ($romanNumerals as $value => $symbol) {
            while ($n >= $value) {
                $result .= $symbol;
                $n -= $value;
            }
        }

        return $result;
    }
@endphp
<script>
    window.print()
</script>
