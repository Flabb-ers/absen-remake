<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $absens->first()->kelas->nama_kelas }} | Semester {{ $absens->first()->matkul->nama_matkul }}</title>
    <style>
        body {
            font-family: Helvetica;
            margin: 0;
            padding: 10px;
        }

        .container {
            max-width: 720px;
            margin: 0 auto;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 100%; 
            max-width: 720px;
            display: block;
        }

        th, td {
            border: 1px solid black;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 60px;
            font-size: 10px;
            padding: 5px;
        }

        th:first-child, td:first-child {
            padding: 0;
        }

        .header-info {
            display: flex;
            justify-content: space-between; 
            margin-top: 20px;
            margin-bottom: 10px;
            width: 100%;
        }

        .header-info h5 {
            font-size: 10px;
            margin: 0;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature-box {
            width: 45%;
        }

        .signature-box.left {
            text-align: left;
            margin-left: 20px;
        }

        .signature-box.right {
            text-align: left; 
            margin-right: 20px;
        }

        .signature-box h5 {
            margin: 10px 0;
            font-weight: normal;
        }

        th {
            font-weight: bold;
        }

        @media print {
            table {
                width: auto;
            }

            td {
                white-space: nowrap;
            }
        }

        @media (max-width: 600px) {
            th, td {
                font-size: 8px;
                width: auto;
            }

            .signature-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .signature-box {
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="text-align: center">
            <h3>POLITEKNIK SAWUNGGALIH AJI</h3>
            <h4 style="margin-top:-20px">PRESENSI MAHASISWA SEMESTER {{ $absens->first()->kelas->semester->semester }}</h4>
            <h5 style="margin-top:-18px;margin-bottom:60px">TAHUN AKADEMIK {{ $absens->first()->tahun }}</h5>
        </div>
        
        <div class="header-info">
            <div>
                <h5 style="display: inline-block; width: 150px;">Mata Kuliah</h5>
                <h5 style="display: inline-block; margin-right: 5px;margin-left:-80px">:</h5> 
                <h5 style="display: inline-block;">{{ $absens->first()->matkul->nama_matkul }}</h5>
                
                <br> 
            
                <h5 style="display: inline-block; width: 150px;">Dosen</h5>
                <h5 style="display: inline-block; margin-right: 5px; margin-left:-80px">:</h5>
                <h5 style="display: inline-block;">{{ $absens->first()->dosen->nama }}</h5>
            </div>
            <div style="text-align: left;">
                <h5 style="display: inline-block; width: 150px;">Program Studi</h5>
                <h5 style="display: inline-block; margin-right: 5px;margin-left:-80px">:</h5> 
                <h5 style="display: inline-block;">{{ $absens->first()->prodi->nama_prodi }}</h5>
                
                <br>    
                <h5 style="display: inline-block; width: 150px;">Kelas</h5>
                <h5 style="display: inline-block; margin-right: 5px;margin-left:-80px">:</h5>
                <h5 style="display: inline-block;">{{ $absens->first()->kelas->nama_kelas }}</h5>
            </div>
        </div>

        <table>
            <tr>
                <th rowspan="3">No.</th>
                <th rowspan="3">NIM</th>
                <th rowspan="3">Nama</th>
                <th colspan="1"></th>
                <th colspan="7" style="padding: 2px">Tanggal Pertemuan</th>
            </tr>
            <tr>
                <th style="font-weight: bold;">Pert. Ke</th>
                <th style="font-weight:normal;">8</th>
                <th style="font-weight:normal;">9</th>
                <th style="font-weight:normal;">10</th>
                <th style="font-weight:normal;">11</th>
                <th style="font-weight:normal;">12</th>
                <th style="font-weight:normal;">13</th>
                <th style="font-weight:normal;">14</th>
            </tr>
            <tr>
                <th style="font-weight: bold; padding:2px">Tgl</th>
                @for ($i = 8; $i <= 14; $i++)
                    @php
                        $tanggal = '';
                        foreach ($absens as $absen) {
                            if ($absen->pertemuan == $i) {
                                $tanggal = date('d/m/Y', strtotime($absen->tanggal));
                                break; 
                            }
                        }
                    @endphp
                    <td>{{ $tanggal ?: '-' }}</td> 
                @endfor
            </tr>
        
            @php
                $absenGroupedByMahasiswa = [];
                foreach ($absens as $absen) {
                    $absenGroupedByMahasiswa[$absen->mahasiswas_id][] = $absen;
                }
        
                $jumlahHadirPerKolom = array_fill(8, 7, 0); 
            @endphp
        
            @foreach ($absenGroupedByMahasiswa as $mahasiswaId => $absenItems)
                @php
                    $mahasiswa = $absenItems[0]->mahasiswa;
                @endphp
        
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mahasiswa->nim }}</td>
                    <td colspan="2">{{ $mahasiswa->nama_lengkap }}</td>
        
                    @for ($i = 8; $i <= 14; $i++)
                        @php
                            $status = '';
                            foreach ($absenItems as $absen) {
                                if ($absen->pertemuan == $i) {
                                    $status = $absen->status;
                                    break;
                                }
                            }
        
                            if ($status === 'H' || $status === 'T') {
                                $jumlahHadirPerKolom[$i]++;
                            }
                        @endphp
                        <td>{{ $status ?: '-' }}</td>
                    @endfor
                </tr>
            @endforeach
        
            <tr>
                <td></td>
                <td colspan="3">Jumlah Yang Hadir</td>
                @for ($i = 8; $i <= 14; $i++)
                    <td>{{ $jumlahHadirPerKolom[$i] > 0 ? $jumlahHadirPerKolom[$i] : '-' }}</td> 
                @endfor
            </tr>
        </table>
        

        <div class="signature-section">
            <div class="signature-box left">
                <h5 style="margin-bottom:50px">Mengetahui</h5>
                <h5>.....................................................</h5>
                <h5>Kaprodi</h5>
            </div>
            <div class="signature-box right">
                <h5 style="margin-left: 100px;margin-bottom:50px">Purworejo,</h5>
                <h5 style="margin-left: 100px;">.....................................................</h5>
                <h5 style="margin-left: 100px;">Dosen Pengampu</h5>
            </div>
        </div>
    </div>
</body>
</html>
