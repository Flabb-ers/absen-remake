@extends('layouts.main')

@section('container')
    <style>
        @media (min-width: 769px) {
            .custom-table {
                width: 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .custom-table {
                width: 1200px;
            }
        }

        .custom-table {
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

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .custom-table td:nth-child(3) {
            text-align: left !important;
            padding-left: 10px;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">KRS</span>
                <span class="breadcrumb-item">{{ Request::is('presensi/krs/diajuka') ? 'Diajukan' : 'Disetujui' }}</span>
                <span class="breadcrumb-item">Verifikasi</span>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div style="display: flex; align-items: center;">
                                <img src="{{ asset('images/file.png') }}" alt="polsa" width="55px" class="mb-3">
                                <div style="margin-left: 10px;">
                                    <h3 class="fw-bold">POLITEKNIK SAWUNGGALIH AJI</h3>
                                    <h5 class="fw-bold">KARTU RENCANA STUDI</h5>
                                </div>
                            </div>
                            <div class="table-responsive">
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

                                            <div
                                                style="font-weight: normal; font-size: 13px; margin-left: 5px; margin-top: 45px">
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
                                            <form id="pembinaForm"
                                                action="/presensi/krs/diajukan/{{ $krs->id }}/update" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <input type="hidden" name="setuju_pa" value="1">
                                                @if ($krs->setuju_pa == 0)
                                                    <input type="checkbox" id="pembinaCheckbox">
                                                @elseif($krs->setuju_pa == 1)
                                                    <input type="checkbox" id="pembinaCheckbox" disabled checked>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                    <td style="padding-bottom: 50px; text-align: center; position: relative;">
                                        <div style="position: absolute; right: 20%; transform: translateX(50%);">
                                            <form id="mahasiswaForm"
                                                action="/presensi/krs/diajukan/{{ $krs->id }}/update-mahasiswa"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('pembinaCheckbox').addEventListener('change', function() {
            if (this.checked) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Anda akan menandatangani pengajuan KRS dari mahasiswa',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('pembinaForm').submit();
                        Swal.fire('Verifikasi berhasil!', '', 'success');
                    } else {
                        document.getElementById('pembinaCheckbox').checked = false;
                        Swal.fire('Verifikasi dibatalkan', '', 'error');
                    }
                });
            }
        });
    </script>
@endsection
