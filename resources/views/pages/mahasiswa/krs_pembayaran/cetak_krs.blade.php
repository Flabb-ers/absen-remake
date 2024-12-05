<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak KRS</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @media print {
            body * {
                visibility: visible;
                font-family: 'Rubik', sans-serif;
            }

            .custom-table {
                width: 100%;
                border: 1px solid black;
                border-collapse: collapse;
                font-size: 12px;
            }

            .custom-table th,
            .custom-table td {
                border: 1px solid black;
                text-align: center;
            }

            .info-cell {
                text-align: left !important;
                vertical-align: top;
                padding: 10px !important;
                width: 250px !important;
                min-width: 30px !important;
                max-width: 250px !important;
            }

            .kode {
                width: 50px !important;
                max-width: 50px !important;
            }

            .sks {
                width: 30px;
            }

            .empty-cell {
                height: 17px;
            }

            .custom-table td:nth-child(3) {
                text-align: left !important;
                padding-left: 10px;
            }

            .custom-table .small-font {
                font-size: 12px;
            }


            @page {
                size: auto;
                margin: 10mm;
            }
        }

        @media screen {
            body * {
                display: none;
            }
        }
    </style>

</head>

<body>
    <div style="display: flex; align-items: center;">
        <img src="{{ asset('images/file.png') }}" alt="polsa" width="55px" class="mb-3">
        <div style="margin-left: 10px;">
            <h2 class="fw-bold">POLITEKNIK SAWUNGGALIH AJI</h2>
            <h4 class="fw-bold" style="margin-top:-20px; ">KARTU RENCANA STUDI</h4>
        </div>
    </div>
    <div>
        <table class="custom-table">
            <tr>
                <td class="info-cell" rowspan="12">
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 5px;">
                        <div style="font-weight: bold;">Prodi</div>
                        <div style="font-weight: bold;">: {{ $krs->prodi->nama_prodi }}</div>
                        <div style="font-weight: bold;">Semester</div>
                        <div style="font-weight: bold;">: {{ $krs->semester->semester }}
                            ({{ $krs->semester->semester % 2 == 0 ? 'Genap' : 'Ganjil' }})</div>
                        <div style="font-weight: bold;">Tahun Akd.</div>
                        <div style="font-weight: bold;">: {{ $krs->tahun_ajaran }}</div>
                    </div>

                    <hr style="border: 1px solid black; margin-top: 10px; margin-bottom: 5px;">

                    <div
                        style="display: grid; grid-template-columns: auto 1fr; gap: 5px; font-weight: normal; margin-left: 5px; margin-top: 30px">
                        <div style="margin-top: 5px; margin-bottom: 5px;">Nama</div>
                        <div style="margin-left: 30px; margin-top: 5px; margin-bottom: 5px;">:
                            {{ $krs->mahasiswa->nama_lengkap }}</div>
                        <div style="margin-top: 5px; margin-bottom: 5px;">NIM</div>
                        <div style="margin-left: 30px; margin-top: 5px; margin-bottom: 5px;">:
                            {{ $krs->mahasiswa->nim }}</div>
                        <div style="margin-top: 5px; margin-bottom: 5px;">Kelas</div>
                        <div style="margin-left: 30px; margin-top: 5px; margin-bottom: 5px;">:
                            {{ $krs->mahasiswa->kelas->nama_kelas }}</div>
                    </div>

                    <div style="font-weight: normal; font-size: 11px; margin-left: 5px; margin-top: 45px;">
                        <b>*) Syarat untuk mengikuti ujian, kehadiran minimal 75%</b>
                    </div>
                </td>
                <th rowspan="2">No</th>
                <th rowspan="2" class="kode">Kode</th>
                <th rowspan="2">Mata Kuliah</th>
                <th colspan="3">SKS</th>
            </tr>
            <tr>
                <th class="sks">T</th>
                <th class="sks">P</th>
                <th class="sks">JML</th>
            </tr>

            @php
                $totalSksTeori = 0;
                $totalSksPraktek = 0;
            @endphp

            @foreach ($matkulKrs as $matkul)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $matkul->kode }}</td>
                    <td>{{ $matkul->nama_matkul }}</td>
                    <td>{{ $matkul->teori }}</td>
                    <td>{{ $matkul->praktek }}</td>
                    <td>{{ $matkul->teori + $matkul->praktek }}</td>


                    @php
                        $totalSksTeori += $matkul->teori;
                        $totalSksPraktek += $matkul->praktek;
                    @endphp
                </tr>
            @endforeach

            @for ($i = count($matkulKrs); $i < 8; $i++)
                <tr>
                    <td class="empty-cell"></td>
                    <td class="empty-cell"></td>
                    <td class="empty-cell"></td>
                    <td class="empty-cell"></td>
                    <td class="empty-cell"></td>
                    <td class="empty-cell"></td>
                </tr>
            @endfor

            <tr>
                <td class="empty-cell"></td>
                <td class="empty-cell"></td>
                <td class="empty-cell"></td>
                <td class="empty-cell"></td>
                <td class="empty-cell"></td>
                <td class="empty-cell"></td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td style="padding: 10px;">Jumlah SKS</td>
                <td>{{ $totalSksTeori }}</td>
                <td>{{ $totalSksPraktek }}</td>
                <td>{{ $totalSksTeori + $totalSksPraktek }}</td>
            </tr>
        </table>
    </div>
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0; position: relative;">
        <tr>
            <td colspan="2" style="padding-bottom: 10px;">
                <div style="text-align: right; width: 100%; overflow: hidden;">
                    <span style="display: inline-block; white-space: nowrap; max-width: 100%; text-overflow: ellipsis;">
                        Purworejo, {{ $krs->created_at->translatedFormat('d F Y') }}
                    </span>
                </div>
            </td>            
        </tr>
        <tr>
            <td style="width: 50%; position: absolute; left: 0;">Pembina Akademik</td>
            <td style="width: 29%; position: absolute; right: 0;">Mahasiswa</td>
        </tr>
        <tr>
            <td style="text-align: center; position: relative; height: 50px;">
                <div style="position: absolute; left: 10%; transform: translateX(-50%);">
                    <input type="checkbox" id="pembinaCheckbox" disabled checked>
                </div>
            </td>
            <td style="text-align: center; position: relative; height: 50px;">
                <div style="position: absolute; right: 100px; transform: translateX(-50%);">
                    <input type="checkbox" id="mahasiswaCheckbox" checked disabled>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                {{ $krs->mahasiswa->pembimbingAkademik->nama }}</td>
            <td style="width: 29%; position: absolute; right: 0;">{{ $krs->mahasiswa->nama_lengkap }}</td>
        </tr>
    </table>
</body>
<script>
    window.print();
</script>

</html>
