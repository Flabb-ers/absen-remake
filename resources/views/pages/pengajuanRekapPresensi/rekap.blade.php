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

        th,
        td {
            border: 1px solid black;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 60px;
            font-size: 10px;
            padding: 5px;
        }

        th:first-child,
        td:first-child {
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

        .btn {
            background-color: rgb(33, 102, 175);
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }

        .btn:hover {
            background-color: rgb(16, 71, 130);
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
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

            th,
            td {
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
            <h4 style="margin-top:-20px">PRESENSI MAHASISWA SEMESTER {{ $absens->first()->kelas->semester->semester }}
            </h4>
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
                <th colspan="{{ count($rentang) }}" style="padding: 2px">Tanggal Pertemuan</th>
            </tr>
            <tr>
                <th style="font-weight: bold;">Pert. Ke</th>
                @foreach ($rentang as $r)
                    <th style="font-weight:normal;">{{ $r }}</th>
                @endforeach
            </tr>
            <tr>
                <th style="font-weight: bold; padding:2px">Tgl</th>
                @foreach ($rentang as $r)
                    @php
                        $tanggal = '';
                        foreach ($absens as $absen) {
                            if ($absen->pertemuan == $r) {
                                $tanggal = date('d/m/Y', strtotime($absen->tanggal));
                                break;
                            }
                        }
                    @endphp
                    <td>{{ $tanggal ?: '' }}</td>
                @endforeach
            </tr>

            @php
                $absenGroupedByMahasiswa = [];
                foreach ($absens as $absen) {
                    $absenGroupedByMahasiswa[$absen->mahasiswas_id][] = $absen;
                }

                $jumlahHadirPerKolom = array_fill_keys($rentang, 0);
            @endphp

            @foreach ($absenGroupedByMahasiswa as $mahasiswaId => $absenItems)
                @php
                    $mahasiswa = $absenItems[0]->mahasiswa;
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mahasiswa->nim }}</td>
                    <td colspan="2">{{ $mahasiswa->nama_lengkap }}</td>

                    @php
                        $statusPerKolom = []; 
                    @endphp

                    @foreach ($rentang as $r)
                        @php
                            $status = '';

                            foreach ($absenItems as $absen) {
                                if ($absen->pertemuan == $r) {
                                    $status = $absen->status;
                                    break;
                                }
                            }

                           
                            if (isset($jumlahHadirPerKolom[$r]) && ($status === 'H' || $status === 'T')) {
                                $jumlahHadirPerKolom[$r]++;
                            }

                            $statusPerKolom[$r] = $status; 
                        @endphp
                        <td>{{ $statusPerKolom[$r] ?: '' }}</td>
                    @endforeach
                </tr>
            @endforeach

            <tr>
                <td></td>
                <td colspan="3">Jumlah Yang Hadir</td>
                @foreach ($rentang as $r)
                    <td>{{ isset($jumlahHadirPerKolom[$r]) ? $jumlahHadirPerKolom[$r] : 0 }}</td>
                @endforeach
            </tr>
        </table>

        <div class="signature-section">
            <div class="signature-box left">
                <h5 style="margin-bottom:50px">Mengetahui</h5>
                <h5>.....................................................</h5>
                <h5>Kaprodi</h5>
            </div>
            <div class="signature-box right">
                <h5 style="margin-bottom:50px; margin-left: 100px">Purworejo,</h5>
                <h5 style="margin-left: 100px;">.....................................................</h5>
                <h5 style="margin-left: 100px;">Dosen Pengampu</h5>
            </div>
        </div>



        {{-- @php
        
            $isKaprodi = auth()->guard('kaprodi')->check(); // Cek jika pengguna adalah kaprodi
            $isWadir = auth()->guard('wakil_direktur')->check(); // Cek jika pengguna adalah wakil direktur

            $kaprodiApproved = true; // Contoh: status approve Kaprodi
            $wakilDiterApproved = false; // Contoh: status approve Wakil Direktur
        @endphp

        <div style="border: 1px solid black; padding: 10px; margin-top: 20px; width:240px">
            <form action="">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="kaprodi"
                        {{ $isKaprodi ? ($kaprodiApproved ? 'checked readonly' : '') : 'disabled' }}>
                    <label class="form-check-label" for="kaprodi">Kaprodi</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="wakil_direktur"
                        {{ $isWadir ? ($wakilDiterApproved ? 'checked readonly' : '') : 'disabled' }}>
                    <label class="form-check-label" for="wakil_direktur">Wakil Direktur</label>
                </div>
            </form>
        </div> --}}

        <div style="border: 1px solid black; padding: 10px; margin-top: 20px; width:240px">
            @php
                $rentangUrl = min($rentang) . '-' . max($rentang);
            @endphp

            <form id="attendanceForm" method="POST"
                action="/presensi/pengajuan-konfirmasi/rekap-presensi/{{ $rentangUrl }}/{{ $absens->first()->matkuls_id }}/{{ $absens->first()->kelas_id }}/{{ $absens->first()->jadwals_id }}">
                @csrf
                @method('PUT')
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="kaprodi" name="kaprodi"
                        {{ $absens->every(fn($absen) => $absen->setuju_kaprodi == 1) ? 'checked' : '' }}
                        onchange="confirmSubmission(this)">
                    <label class="form-check-label" for="kaprodi">Kaprodi</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="wakil_direktur" name="wakil_direktur"
                        {{ $absens->every(fn($absen) => $absen->setuju_wadir == 1) ? 'checked' : '' }}
                        onchange="confirmSubmission(this)">
                    <label class="form-check-label" for="wakil_direktur">Wakil Direktur</label>
                </div>
            </form>

        </div>
        <div style="margin-top: 30px;">
            <a href="/presensi/pengajuan-konfirmasi/rekap-presensi" class="btn">Kembali</a>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmSubmission(checkbox) {
            const isChecked = checkbox.checked;
            const label = checkbox.nextElementSibling.innerText;
            if (isChecked) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin menyetujui ${label}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, setujui',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('attendanceForm').submit();
                    } else {
                        checkbox.checked = false;
                    }
                });
            } else {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin membatalkan persetujuan ${label}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, batalkan',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('attendanceForm').submit();
                    } else {
                        checkbox.checked = true;
                    }
                });
            }
        }
    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Sukses!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>

</html>
