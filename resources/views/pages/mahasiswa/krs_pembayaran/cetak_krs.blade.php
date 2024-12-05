<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak KRS</title>
    <style>
        @media print {
            body * {
                visibility: visible;
            }

            .custom-table {
                width: 100%;
                max-width: 100%;
                border: 1px solid black;
                border-collapse: collapse;
                font-size: 0.9em;
            }

            .custom-table th,
            .custom-table td {
                border: 1px solid black;
                padding: 5px;
                text-align: center;
                min-width: 50px;
            }

            .info-cell {
                text-align: left !important;
                vertical-align: top;
                padding: 20px !important;
                width: 360px;
            }

            .empty-cell {
                height: 25px;
            }

            .custom-table td:nth-child(3) {
                text-align: left !important;
                padding-left: 10px;
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
            <h3 class="fw-bold">POLITEKNIK SAWUNGGALIH AJI</h3>
            <h5 class="fw-bold">KARTU RENCANA STUDI</h5>
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
                        <div style="margin-left: 35px; margin-top: 5px; margin-bottom: 5px;">:
                            {{ $krs->mahasiswa->nama_lengkap }}</div>
                        <div style="margin-top: 5px; margin-bottom: 5px;">NIM</div>
                        <div style="margin-left: 35px; margin-top: 5px; margin-bottom: 5px;">:
                            {{ $krs->mahasiswa->nim }}</div>
                        <div style="margin-top: 5px; margin-bottom: 5px;">Kelas</div>
                        <div style="margin-left: 35px; margin-top: 5px; margin-bottom: 5px;">:
                            {{ $krs->mahasiswa->kelas->nama_kelas }}</div>
                    </div>

                    <div style="font-weight: normal; font-size: 13px; margin-left: 5px; margin-top: 45px">
                        <b>*) Syarat untuk mengikuti ujian, kehadiran minimal 75%</b>
                    </div>
                </td>
                <th rowspan="2">No</th>
                <th rowspan="2">Kode</th>
                <th rowspan="2">Mata Kuliah</th>
                <th colspan="3">SKS</th>
            </tr>
            <tr>
                <th>T</th>
                <th>P</th>
                <th>JML</th>
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
                <td style="padding: 10px"><b>Jumlah SKS</b></td>
                <td>{{ $totalSksTeori }}</td>
                <td>{{ $totalSksPraktek }}</td>
                <td>{{ $totalSksTeori + $totalSksPraktek }}</td>
            </tr>
        </table>
    </div>
    <table style="width: 100%; border-collapse: collapse; margin: 40px 0;">
        <tr>
            <td colspan="2" style="text-align: right; padding-bottom: 10px;">Purworejo,
                {{ date('d F Y') }}</td>
        </tr>
        <tr>
            <td style="width: 50%; text-align: left; padding-right: 20px;">Pembina Akademik</td>
            <td style="width: 50%; text-align: right; padding-left: 20px;">Mahasiswa</td>
        </tr>
        <tr>
            <td style="padding-bottom: 50px; text-align: center; position: relative;">
                <div style="position: absolute; left: 20%; transform: translateX(-50%);">
                    <form id="pembinaForm" action="/presensi/krs/diajukan/{{ $krs->id }}/update" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="checkbox" id="pembinaCheckbox" disabled checked>
                    </form>
                </div>
            </td>
            <td style="padding-bottom: 50px; text-align: center; position: relative;">
                <div style="position: absolute; right: 20%; transform: translateX(50%);">
                    <form id="mahasiswaForm" action="/presensi/krs/diajukan/{{ $krs->id }}/update-mahasiswa"
                        method="POST">
                        @method('PUT')
                        @csrf
                        <input type="checkbox" id="mahasiswaCheckbox" checked disabled>
                    </form>
                </div>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-right: 20px;">
                {{ $krs->mahasiswa->pembimbingAkademik->nama }}</td>
            <td style="text-align: right;">{{ $krs->mahasiswa->nama_lengkap }}</td>
        </tr>
    </table>
</body>
<script>
    window.print();
</script>

</html>
