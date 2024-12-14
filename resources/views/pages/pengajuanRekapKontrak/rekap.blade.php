<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: 'Times New Roman';
            margin: 0;
            padding: 5px;
        }

        .container {
            max-width: 720px;
            margin: 0 auto;
            padding: 10px;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 100%;
            max-width: 720px;
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

        th,
        td {
            border: 1px solid black;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 12px;
            padding: 3px;
        }

        th {
            font-weight: bold;
            background-color: black;
            color: white;
        }

        tr:nth-child(odd) {
            background-color: lightgray;
        }

        tr:nth-child(even) {
            background-color: white;
        }

        .presentation {
            font-size: 12px;
        }

        .percentage-item {
            display: flex;
            margin-bottom: 5px;
            margin-left: -20px;
        }

        .percentage-label {
            display: inline-block;
            width: 180px;
        }

        .colon {
            margin-right: 10px;
        }

        .percentage-value {
            display: inline-block;
        }

        .underline {
            border-bottom: 1px solid black;
            flex-grow: 1;
            margin-left: 5px;
            margin-right: 5px;
        }

        .total {
            margin-left: 208px;
        }

        .pertemuan {
            width: 80px;
        }

        .materi {
            width: 400px;
        }

        .header-p {
            margin-bottom: -5px;
        }

        hr {
            border: 0;
            height: 1px;
            background-color: black !important;
            margin: 5px 0;
            -webkit-print-color-adjust: exact;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-table td {
            width: 50%;
            border: none;
            padding: 5px;
            text-align: center;
            vertical-align: top;
            background-color: white;
        }

        .signature-line {
            display: block;
            margin-top: 50px;
        }

        .mengetahui {
            text-align: center;
            margin: 5px 0;
            page-break-before: avoid;
            page-break-after: avoid;
            font-size: 12px;
        }

        @media print {
            th {
                background-color: black !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }

            tr:nth-child(odd) {
                background-color: rgb(204, 202, 202) !important;
                -webkit-print-color-adjust: exact;
            }

            tr:nth-child(even) {
                background-color: white !important;
                -webkit-print-color-adjust: exact;
            }

            td {
                white-space: nowrap;
            }

            .signature-table,
            .signature-table tr,
            .signature-table td {
                display: table !important;
                width: 100% !important;
                page-break-inside: avoid !important;
            }

            .signature-table tr {
                display: table-row !important;
            }

            .signature-table td {
                display: table-cell !important;
                width: 50% !important;
            }

            .mengetahui {
                page-break-before: avoid !important;
                page-break-after: avoid !important;
            }

            .materi {
                width: 400px;
            }

            .signature-section {
                margin-top: -10px !important;
            }
        }

        @media (max-width: 800px) {

            th,
            td {
                font-size: 12px;
            }

            .signature-table td {
                display: block;
                width: 100%;
            }

            @page {
                size: A4 portrait;
                margin: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('images/logomini.png') }}" alt="logo" width="70px"
            style="margin-right:10px;margin-left:15px">
        <div style="display: inline-block;">
            <h3
                style="font-family: Helvetica; margin: 5px 0; margin-bottom:-8px; font-size:16px; color: white; 
        text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">
                POLITEKNIK
            </h3>
            <h2 style="font-family: Helvetica; margin: 5px 0; font-size:26px; margin-bottom: -5px;">
                SAWUNGGALIH AJI
            </h2>
            <h6 style="font-family: Helvetica; margin: 5px 0; font-size: 6px; font-weight: bold;">
                Jl. Wismoaji No. 8 Kutoarjo, Purworejo, Jawa Tengah, Indonesia Kode Pos 54212
            </h6>
            <h6 style="font-family: Helvetica; margin: 5px 0; font-size: 6px;">
                Telp. (0275)642466 (HUNTING) (0275) 3410444 Fax (0275) 642467
            </h6>
            <h6 style="font-family: Helvetica; margin: 5px 0; font-size: 6px;">
                Http://www.polsa.ac.id E-mail : info@polsa.ac.id
            </h6>
        </div>


        <hr>
        <h5 style="font-weight: bolder; text-align: center; margin: 10px 0;">KONTRAK PERKULIAHAN</h5>

        <p style="margin: 5px 0; font-size: 12px;">
            <span style="display: inline-block; width: 150px;">Mata Kuliah/Bobot SKS</span>:
            {{ $kontraks->first()->matkul->nama_matkul }} /
            {{ $kontraks->first()->matkul->praktek + $kontraks->first()->matkul->teori }}
        </p>

        <p style="margin: 5px 0; font-size: 12px;">
            <span style="display: inline-block; width: 150px;">Prodi/Semester</span>:
            {{ $kontraks->first()->kelas->prodi->nama_prodi }} / Semester
            {{ $kontraks->first()->kelas->semester->semester }}
        </p>

        <p style="margin: 5px 0; font-size: 12px;">
            <span>Materi Perkuliahan Satu Semester</span>
        </p>

        <table>
            <thead>
                <tr>
                    <th class="pertemuan">Pertemuan</th>
                    <th class="materi">Materi Perkuliahan</th>
                    <th>Daftar Pustaka</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPertemuan = 16;
                    $kontrakCount = count($kontraks);
                @endphp

                @for ($i = 1; $i <= $totalPertemuan; $i++)
                    @if ($i == 8)
                        <tr>
                            <td>8</td>
                            <td colspan="2">UTS</td>
                        </tr>
                    @elseif ($i == 16)
                        <tr>
                            <td>16</td>
                            <td colspan="2">UAS</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $i }}</td>
                            @php
                                $adjustedIndex = $i > 8 ? $i - 2 : $i - 1;
                            @endphp

                            @if ($adjustedIndex < $kontrakCount)
                                <td>{{ $kontraks[$adjustedIndex]->materi }}</td>
                                <td>{{ $kontraks[$adjustedIndex]->pustaka }}</td>
                            @else
                                <td></td>
                                <td></td>
                            @endif
                        </tr>
                    @endif
                @endfor
            </tbody>
        </table>

        <div class="presentation">
            <p class="header-p">Presentasi Perkuliahan</p>
            <ol>
                <li class="percentage-item">
                    <span class="percentage-label">1. Presensi/Kehadiran</span>
                    <span class="colon">:</span>
                    <span class="percentage-value">15%</span>
                </li>
                <li class="percentage-item">
                    <span class="percentage-label">2. Tugas</span>
                    <span class="colon">:</span>
                    <span class="percentage-value">20%</span>
                </li>
                <li class="percentage-item">
                    <span class="percentage-label">3. Sikap dan Keaktifan</span>
                    <span class="colon">:</span>
                    <span class="percentage-value">15%</span>
                </li>
                <li class="percentage-item">
                    <span class="percentage-label">4. UTS</span>
                    <span class="colon">:</span>
                    <span class="percentage-value">25%</span>
                </li>
                <li class="percentage-item">
                    <span class="percentage-label">5. UAS</span>
                    <span class="colon">:</span>
                    <span class="percentage-value" style="border-bottom: 1px solid black;">25%</span>
                </li>
            </ol>
            <div class="total" style="margin-top: -10px;">100%</div>
        </div>

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <p>Dosen Pengampu</p>
                        <span class="signature-line">.....................................................</span>
                    </td>
                    <td>
                        <p>Komting Mahasiswa</p>
                        <span class="signature-line">.....................................................</span>
                    </td>
                </tr>
            </table>

            <p class="mengetahui">Mengetahui</p>

            <table class="signature-table">
                <tr>
                    <td>
                        <p>Kaprodi</p>
                        <span class="signature-line">.....................................................</span>
                    </td>
                    <td>
                        <p>Wakil Direktur</p>
                        <span class="signature-line">.....................................................</span>
                    </td>
                </tr>
            </table>
        </div>

        <div style="border: 1px solid black; padding: 10px; margin-top: 30px; width:240px">
            @php
                $roleWadir = Auth::guard('wakil_direktur')->check();
                $roleDirektur = Auth::guard('direktur')->check();
                $wadirOrDir = $roleWadir || $roleDirektur;
                $isKaprodi = Auth::guard('kaprodi')->check();
            @endphp
            <form id="attendanceForm" method="POST"
                action="/presensi/pengajuan-konfirmasi/rekap-kontrak/{{ $kontraks->first()->jadwals_id }}/{{ $kontraks->first()->matkuls_id }}/{{ $kontraks->first()->kelas_id }}">
                @csrf
                @method('PUT')

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="kaprodi" name="kaprodi"
                        {{ $kontraks->every(fn($kontrak) => $kontrak->setuju_kaprodi) ? 'checked' : '' }}
                        onchange="confirmSubmission(this)" @if (!$isKaprodi) disabled @endif>
                    <label class="form-check-label" for="kaprodi">Kaprodi</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="wakil_direktur" name="wakil_direktur"
                        {{ $kontraks->every(fn($kontrak) => $kontrak->setuju_wadir) ? 'checked' : '' }}
                        onchange="confirmSubmission(this)" @if (!$wadirOrDir) disabled @endif>
                    <label class="form-check-label" for="wakil_direktur">Wakil Direktur</label>
                </div>
            </form>
        </div>


        <div style="margin-top: 30px;">
            <a href="/presensi/pengajuan-konfirmasi/rekap-kontrak" class="btn">Kembali</a>
        </div>
    </div>

    <script src="{{ asset('vendors/js/sweetalert2.all.min.js') }}"></script>
    <script>
        function confirmSubmission(checkbox) {
            const isChecked = checkbox.checked;
            const label = checkbox.nextElementSibling.innerText;
            const formElement = document.getElementById('attendanceForm');

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

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>

</html>
