<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Berita Acara Kuliah</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin: 0;
            padding-top: 20px;
            flex-direction: column;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }

        .kop-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            text-align: center;
            margin-top: -40px;
        }

        img {
            width: 80px;
            margin-left: 190px;
            margin-bottom: -45px;
        }

        .kop-header h3 {
            margin: 0;
            line-height: 1.5;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            margin-bottom: 30px;
            width: 100%;
            text-align: left;
        }

        .header-info h4 {
            font-size: 14px;
            margin: 7px;
            font-weight: normal;
        }

        table {
            border-collapse: collapse;
            width: 1160px;
            border: 4px double;
        }

        th {
            border: 1px solid;
            padding: 5px 6px;
        }

        td {
            border: 1px solid;
            padding-top: 13px;
            padding-bottom: 13px;
        }

        th {
            border-bottom: 4px double;
            font-weight: normal;
        }

        .pertemuan-ke {
            width: 90px;
        }

        .tanggal,
        .waktu {
            width: 115px;
        }

        .ikhtisar {
            width: 570px;
            padding-left: 7px;
            padding-right: 7px;
        }

        .hadir,
        .tidak-hadir {
            width: 75px;
        }

        .ttd-dosen {
            width: 100px;
        }

        .double-hr {
            border: none;
            border-top: 3px double #000;
            margin: 10px 0;
            width: 100%;
        }

        .btn {
            background-color: rgb(33, 102, 175);
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            display: inline-block;
            margin-top: 5px;
            margin-bottom: 40px
        }

        .btn:hover {
            background-color: rgb(16, 71, 130);
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }

        @media print {
            body {
                visibility: hidden;
            }

            .container {
                visibility: visible;
                margin: 0;
            }

            table {
                page-break-inside: auto;
                width: 100%;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th {
                border-bottom: 4px double;
                font-weight: normal;
                padding: 4px 3px;
            }

            img {
                margin-left: 170px;
            }

            @page {
                size: A4 landscape;
                margin: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('images/file.png') }}" alt="Logo">
        <div class="kop-header">
            <div>
                <h3>POLITEKNIK SAWUNGGALIH AJI</h3>
                <h3>BERITA ACARA PERKULIAHAN</h3>
                <h3>SEMESTER {{ $sem }} TAHUN AKADEMIK {{ $tahunAkademik->tahun_akademik }}</h3>
            </div>
        </div>
        <hr class="double-hr">

        <div class="header-info">
            <div>
                <h4 style="display: inline-block; width: 140px;">Dosen</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $beritas->first()->dosen->nama }}</h4>
                <br>
                <h4 style="display: inline-block; width: 140px;">Mata Kuliah</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-60px">:</h4>
                <h4 style="display: inline-block;">{{ $beritas->first()->matkul->nama_matkul }}</h4>
            </div>
            <div>
                <h4 style="display: inline-block; width: 200px;">Prodi</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-80px">:</h4>
                <h4 style="display: inline-block;">{{ $beritas->first()->kelas->prodi->nama_prodi }}</h4>

                <br>
                <h4 style="display: inline-block; width: 200px;">Semester/Kelas</h4>
                <h4 style="display: inline-block; margin-right: 5px; margin-left:-80px">:</h4>
                <h4 style="display: inline-block;">Semester
                    {{ $beritas->first()->kelas->semester->semester }}/{{ $beritas->first()->kelas->nama_kelas }}</h4>
            </div>
        </div>
        <table>
            <tr>
                <th class="pertemuan-ke">Pertemuan Ke</th>
                <th class="tanggal">Tanggal</th>
                <th class="waktu">Waktu</th>
                <th class="ikhtisar">Ikhtisar Materi Kuliah</th>
                <th class="hadir">Jml Mhs Hadir</th>
                <th class="tidak-hadir">Jml Mhs Tdk Hadir</th>
                <th class="ttd-dosen">Ttd Dosen</th>
            </tr>
            @foreach ($range as $i)
                @php
                    $berita = $beritas->firstWhere('pertemuan', $i);
                @endphp
                <tr>
                    <td class="pertemuan-ke" style="text-align: center">{{ $i }}</td>
                    <td class="tanggal" style="text-align: center">
                        {{ $berita ? \Carbon\Carbon::parse($berita->tanggal)->format('d-m-Y') : '' }}</td>
                    <td class="waktu" style="text-align: center">
                        {{ $berita ? \Carbon\Carbon::parse($berita->waktu)->format('H:i') : '' }}</td>
                    <td class="ikhtisar">{{ $berita->materi ?? '' }}</td>
                    <td class="hadir" style="text-align: center">{{ $berita->hadir ?? '' }}</td>
                    <td class="tidak-hadir" style="text-align: center">{{ $berita->tidak_hadir ?? '' }}</td>
                    <td class="ttd-dosen"></td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align: center;">UTS/UAS</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>


        <div style="margin-top:15px;margin-bottom:5px">
            Catatan : Daftar ini harus diisi setiap perkuliahan sebagai dasar perhitungan honor mengajar
        </div>

        <div style="border: 1px solid black; padding: 10px; margin-top: 20px; width: 240px">
            @php
                $rentangUrl = min($range) . '-' . max($range);
                $roleWadir = Auth::guard('wakil_direktur')->check();
                $roleDirektur = Auth::guard('direktur')->check();
                $wadirOrDir = $roleWadir || $roleDirektur;
                $isKaprodi = Auth::guard('kaprodi')->check();
            @endphp

            <form id="approvalForm" method="POST"
                action="/presensi/pengajuan-konfirmasi/rekap-berita/{{ $rentangUrl }}/{{ $beritas->first()->matkuls_id }}/{{ $beritas->first()->kelas_id }}/{{ $beritas->first()->jadwals_id }}">
                @csrf
                @method('PUT')
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="kaprodi" name="kaprodi"
                        {{ $beritas->where('setuju_kaprodi', 1)->count() == count($range) ? 'checked' : '' }}
                        onchange="confirmSubmission(this)" @if (!$isKaprodi) disabled @endif>
                    <label class="form-check-label" for="kaprodi">Kaprodi</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="wakil_direktur" name="wakil_direktur"
                        {{ $beritas->where('setuju_wadir', 1)->count() == count($range) ? 'checked' : '' }}
                        onchange="confirmSubmission(this)" @if (!$wadirOrDir) disabled @endif>
                    <label class="form-check-label" for="wakil_direktur">Wakil Direktur</label>
                </div>
            </form>
        </div>
        <div style="margin-top: 30px;">
            <a href="/presensi/pengajuan-konfirmasi/rekap-berita" class="btn">Kembali</a>
        </div>

        <script src="{{ asset('vendors/js/sweetalert2.all.min.js') }}"></script>
        <script>
            function confirmSubmission(checkbox) {
                const isChecked = checkbox.checked;
                const label = checkbox.nextElementSibling.innerText;
                const formElement = document.getElementById('approvalForm');

                const hiddenUncheckInput = document.createElement('input');
                hiddenUncheckInput.type = 'hidden';

                if (isChecked) {
                    const existingUncheckInput = formElement.querySelector(`input[name="uncheck_${checkbox.name}"]`);
                    if (existingUncheckInput) {
                        existingUncheckInput.remove();
                    }
                } else {
                    hiddenUncheckInput.name = `uncheck_${checkbox.name}`;
                    hiddenUncheckInput.value = '1';
                    formElement.appendChild(hiddenUncheckInput);
                }

                Swal.fire({
                    title: 'Konfirmasi',
                    text: isChecked ?
                        `Apakah Anda yakin ingin menyetujui ${label}?` :
                        `Apakah Anda yakin ingin membatalkan persetujuan ${label}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: isChecked ? 'Ya, setujui' : 'Ya, batalkan',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formElement.submit();
                    } else {
                        checkbox.checked = !isChecked;

                        if (hiddenUncheckInput.parentNode) {
                            hiddenUncheckInput.remove();
                        }
                    }
                });
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
