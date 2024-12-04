@extends('layouts.main')

@section('container')
    <style>
        .custom-table {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
            font-size: 0.9em;
            /* Reduced text size */
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            min-width: 50px;
            /* Ensure minimum width for empty columns */
        }

        .info-cell {
            text-align: left !important;
            vertical-align: top;
            padding: 20px !important;
            width: 360px;
        }

        .empty-cell {
            height: 25px;
            /* Consistent height for empty rows */
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">KRS</span>
                <span class="breadcrumb-item">Diajukan</span>
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
                                    <tr style="text-align: left !important;">
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
                            <form id="myForm" action="/presensi/krs/diajukan/{{ $krs->id }}/update" method="POST">
                                @method('PUT')
                                @csrf
                                <input type="checkbox" id="myCheckbox">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('myCheckbox').addEventListener('change', function() {
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
                        document.getElementById('myForm').submit();
                        Swal.fire('Verifikasi berhasil!', '', 'success');
                    } else {
                        document.getElementById('myCheckbox').checked = false;
                        Swal.fire('Verifikasi dibatalkan', '', 'error');
                    }
                });
            }
        });
    </script>
@endsection
