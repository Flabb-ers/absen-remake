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
        /* Anda dapat mengubah ini jika ingin padding di seluruh tabel */
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
    }

    .container {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 20px;
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
</style>
<div style="text-align: center;font-size:18px">
    <div style="font-weight: bold">YAYASAN SAWUNGGALIH AJI PURWOREJO</div>
    <div class="monotype-corsiva">SAWUNGGALIH AJI FUNDATION PURWOREJO</div>
    <div style="font-weight: bold">POLITEKNIK SAWUNGGALIH AJI</div>
    <div class="monotype-corsiva">SAWUNGGALIH AJI POLYTECHNIC</div>
    <div>JL. Wismoaji No.8 Kutoarjo Purworejo</div>
    <div>Telp.(0275) 642466. 3140444 Fax.(0275) 642467</div>
</div>
<hr style="border: 1px solid black; margin-bottom: 20px;">
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
            <div>:</div>
        </div>

        <div class="info-item">
            <div style="margin-right: 77px;">JENJANG</div>
            <div>: {{ $ipss->first()->mahasiswa->kelas->prodi->jenjang }}</div>
        </div>

        <div class="info-item">
            <div style="margin-right: 107px;" class="monotype-corsiva">Degree</div>
            <div>:</div>
        </div>
    </div>
</div>
<table>
    <tr>
        <td style="text-align:center">
            <div>No</div>
            <div class="monotype-corsiva">No</div>
        </td>
        <td colspan="2" style="text-align:center">
            <div>Kode</div>
            <div class="monotype-corsiva">Code</div>
        </td>
        <td style="text-align:center">
            <div>MATA KULIAH</div>
            <div class="monotype-corsiva">Courses</div>
        </td>
        <td style="text-align:center">
            <div>SKS</div>
            <div class="monotype-corsiva">Semester Credit</div>
        </td>
        <td style="text-align:center">
            <div>NILAI</div>
            <div class="monotype-corsiva">Grade</div>
        </td>
        <td style="text-align:center">
            <div>KREDIT</div>
            <div class="monotype-corsiva">Credit</div>
        </td>
    </tr>
    @foreach ($ipss as $ips)
        <tr>
            <td style="text-align:center">{{ $loop->iteration }}</td>
            <td colspan="2" style="text-align:center">{{ $ips->matkul->kode }}</td>
            <td>{{ $ips->matkul->nama_matkul }}</td>
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
        <td colspan="2">
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
        <td colspan="2">
            <div>Kumulatif</div>
            <div>Cumulative</div>
        </td>
        <td style="text-align:center">{{ $sksIpk }}</td>
        <td></td>
        <td style="text-align:center">{{ $kreditIpk }}</td>
    </tr>
    <tr>
        <td colspan="2"></td>

        <td colspan="2">
            <div>Indeks Prestasi Semester</div>
            <div>IPS : {{ number_format(round($kreditIps / $sksIps, 2), 2, '.', '') }}</div>
            <div class="monotype-corsiva">Grade Point</div>
        </td>
        <td colspan="2">
            <div>Indeks Prestasi Kumulatif</div>
            <div>IPK : {{ number_format(round($kreditIpk / $sksIpk, 2), 2, '.', '') }}</div>
            <div class="monotype-corsiva">Cumulative GPA</div>
        </td>
    </tr>
</table>
<div style="text-align: right">Purworejo, {{ now()->format('d M y') }}</div>
<div class="container">
    <div class="left">
        <div>Ketua Progam Studi,</div>
        <div class="monotype-corsiva" style="margin-bottom:90px">Head of Study Programmed</div>
        <div style="font-weight:bold">{{ $kaprodi->nama }}</div>
    </div>
    <div class="right">
        <div>Pembina Akademik,</div>
        <div class="monotype-corsiva" style="margin-bottom:90px">Academic Supervisor</div>
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
