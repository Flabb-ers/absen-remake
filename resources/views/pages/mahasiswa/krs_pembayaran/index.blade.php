@extends('layouts.main')

@section('container')
    @php
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

            foreach ($romanNumerals as $value => $roman) {
                while ($n >= $value) {
                    $result .= $roman;
                    $n -= $value;
                }
            }
            return $result;
        }
    @endphp
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">KRS & Pembayaran</span>
            </div>

            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">KRS & Pembayaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('upload_bukti_pembayaran') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="upload-container">
                                            <input type="hidden" name="mahasiswa_id" value="{{ Session::get('user.id') }}">

                                            <input type="file" id="fileInput" name="file" class="file-input"
                                                accept=".jpg,.png,.jpeg">

                                            <div class="upload-zone">
                                                <div class="upload-icon">
                                                    <i class="mdi mdi-cloud-upload"></i>
                                                </div>
                                                <p class="upload-text">Drag & Drop atau Klik untuk Upload</p>
                                                <small class="text-muted">Maks 5MB per file. Format: JPG, PNG, JPEG</small>
                                            </div>

                                            <!-- Preview file (optional) -->
                                            <div class="file-preview mt-3 d-none"></div>

                                            @if ($errors->has('file'))
                                                <div class="alert alert-danger mt-2">
                                                    <ul>
                                                        @foreach ($errors->get('file') as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if (isset($pembayaran))
                                                @if ($pembayaran->status_pembayaran == 0 && $pembayaran->keterangan == 'Belum' && $cekStatusKrs == 0)
                                                    <div class="alert alert-danger mt-2" role="alert">
                                                        <span id="statusKRS">Pembayaran Belum Lunas, Segera Hubungi
                                                            Akademik</span>
                                                    </div>
                                                @elseif ($pembayaran->status_pembayaran == 1 && $pembayaran->keterangan == 'Sudah' && $cekStatusKrs == 0 || $cekStatusKrs == 1)
                                                    <div class="alert alert-success mt-2" role="alert">
                                                        <span id="statusKRS">Pembayaran Diverifikasi, Segera Ajukan
                                                            KRS</span>
                                                    </div>
                                                @elseif ($pembayaran->status_pembayaran == 0 && $pembayaran->keterangan == null)
                                                    <div class="btn btn-warning btn-sm mt-2">
                                                        <i class="mdi mdi-clock-alert"></i> Pending
                                                    </div>
                                                @endif
                                            @elseif ($cekStatusKrs == 0)
                                                <button class="btn btn-info btn-sm mt-2" type="submit">
                                                    <i class="mdi mdi-file-upload"></i> Upload
                                                </button>
                                            @endif
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Kartu Rencana Studi (KRS)</label>
                                        @if (isset($pembayaran) && $pembayaran->status_pembayaran == 1 && $pembayaran->keterangan == 'Sudah')
                                            @if ($krs == null)
                                                <form action="/presensi/mahasiswa/krs" method="POST">
                                                    @csrf
                                                    <div class="d-grid gap-2">
                                                        <button class="btn btn-primary" id="cetakKRSBtn">
                                                            <i class="mdi mdi-printer"></i> Cetak KRS
                                                        </button>
                                                    </div>
                                                </form>
                                            @elseif($cekStatusKrs == 0)
                                                <div class="d-grid gap-2">
                                                    <button class="btn btn-primary" disabled id="cetakKRSBtn">
                                                        <i class="mdi mdi-printer"></i> Cetak KRS
                                                    </button>
                                                </div>
                                            @elseif($cekStatusKrs == 1)
                                                <div class="d-grid gap-2">
                                                    <a class="btn btn-primary"
                                                        href="/presensi/mahasiswa/krs/{{ $krs->id }}/cetak"
                                                        id="cetakKRSBtn" target="_bank">
                                                        <i class="mdi mdi-printer"></i> Cetak KRS
                                                    </a>
                                                </div>
                                            @endif

                                            @if ($krs == null)
                                                <div class="alert alert-info mt-4" role="alert">
                                                    <strong>Status KRS:</strong>
                                                    <span id="statusKRS">Belum Diproses</span>
                                                </div>
                                            @elseif($krs->setuju_mahasiswa == 1 && $krs->setuju_pa == 0)
                                                <div class="alert alert-warning mt-4" role="alert">
                                                    <strong>Status KRS:</strong>
                                                    <span id="statusKRS">Diproses</span>
                                                </div>
                                            @elseif($krs->status_krs == 1 && $krs->setuju_mahasiswa == 1 && $krs->setuju_pa == 1)
                                                <div class="alert alert-success mt-4" role="alert">
                                                    <strong>Status KRS:</strong>
                                                    <span id="statusKRS">Selesai</span>
                                                </div>
                                            @elseif($krs->setuju_mahasiswa == 0)
                                                <div class="alert alert-info mt-4" role="alert">
                                                    <strong>Status KRS:</strong>
                                                    <span id="statusKRS">Belum Diproses</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary" disabled id="cetakKRSBtn">
                                                    <i class="mdi mdi-file-send"></i> Cetak KRS
                                                </button>
                                            </div>
                                            <div class="alert alert-info mt-4" role="alert">
                                                <strong>Status KRS:</strong>
                                                <span id="statusKRS">Belum Diproses</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($krs != null && $pembayaran->status_pembayaran == 1 && $pembayaran->keterangan == 'Sudah')
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
                                <div style="display: flex; align-items: center; margin-top:40px">
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
                                                    <div style="font-weight: bold;">:
                                                        {{ $krs->prodi->nama_prodi }}</div>
                                                    <div style="font-weight: bold;">Semester</div>
                                                    <div style="font-weight: bold;">:
                                                        {{ toRoman($krs->semester->semester) }}
                                                        ({{ $krs->semester->semester % 2 == 0 ? 'Genap' : 'Ganjil' }})
                                                    </div>
                                                    <div style="font-weight: bold;">Tahun Akd.</div>
                                                    <div style="font-weight: bold;">:
                                                        {{ $krs->tahun_ajaran }}</div>
                                                </div>

                                                <hr style="border: 1px solid black; margin-top: 10px; margin-bottom: 5px;">

                                                <div
                                                    style="display: grid; grid-template-columns: auto 1fr; gap: 5px; font-weight: normal; margin-left: 5px; margin-top: 30px">
                                                    <div style="margin-top: 5px; margin-bottom: 5px;">
                                                        Nama</div>
                                                    <div style="margin-left: 35px; margin-top: 5px; margin-bottom: 5px;">
                                                        :
                                                        {{ $krs->mahasiswa->nama_lengkap }}</div>
                                                    <div style="margin-top: 5px; margin-bottom: 5px;">
                                                        NIM</div>
                                                    <div style="margin-left: 35px; margin-top: 5px; margin-bottom: 5px;">
                                                        :
                                                        {{ $krs->mahasiswa->nim }}</div>
                                                    <div style="margin-top: 5px; margin-bottom: 5px;">
                                                        Kelas</div>
                                                    <div style="margin-left: 35px; margin-top: 5px; margin-bottom: 5px;">
                                                        :
                                                        {{ $krs->mahasiswa->kelas->nama_kelas }}</div>
                                                </div>

                                                <div
                                                    style="font-weight: normal; font-size: 13px; margin-left: 5px; margin-top: 45px">
                                                    <b>*) Syarat untuk mengikuti ujian, kehadiran
                                                        minimal 75%</b>
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
                                </div class="tabel-responsive">
                                <div>
                                    <table style="width: 100%; border-collapse: collapse; margin: 40px 0;">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: left; padding-bottom: 10px;">
                                                Purworejo, {{ $krs->created_at->translatedFormat('d F Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 33%; text-align: left; padding-right: 20px;">
                                                Pembina Akademik
                                            </td>
                                            <td style="width: 33%; text-align: center;"></td>
                                            <td style="width: 33%; text-align: left;">
                                                Mahasiswa
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; position: relative; padding-bottom: 50px;">
                                                <div style="position: absolute; left: 20%; transform: translateX(-50%);">
                                                    <form id="pembinaForm"
                                                        action="/presensi/krs/diajukan/{{ $krs->id }}/update"
                                                        method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <input type="checkbox" id="pembinaCheckbox"
                                                            {{ $krs->setuju_pa == 1 ? 'checked' : '' }} disabled>
                                                    </form>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td style="text-align: center; position: relative; padding-bottom: 30px;">
                                                <div>
                                                    <form id="mahasiswaForm"
                                                        action="/presensi/mahasiswa/krs/{{ $krs->id }}/update"
                                                        method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <input type="hidden" name="setuju_mahasiswa" value="1">
                                                        <input type="checkbox" id="mahasiswaCheckbox"
                                                            {{ $krs->setuju_mahasiswa == 1 ? 'checked' : '' }}
                                                            @if ($krs->setuju_mahasiswa == 1) disabled @endif>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; padding-right: 20px;">
                                                {{ $krs->mahasiswa->pembimbingAkademik->nama }}
                                            </td>
                                            <td></td>
                                            <td style="text-align: left;">
                                                {{ $krs->mahasiswa->nama_lengkap }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @elseif ($cekStatusKrs == 0)
                                <div class="row mt-5">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">Mata Kuliah yang Akan Diambil</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Mata Kuliah</th>
                                                        <th>Nama Mata Kuliah</th>
                                                        <th>SKS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($matkulKrs as $matkul)
                                                        <tr>
                                                            <td>{{ $matkul->kode }}</td>
                                                            <td>{{ $matkul->nama_matkul }}</td>
                                                            <td>{{ $matkul->teori + $matkul->praktek }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="alert alert-info mt-3">
                                            <small>Daftar mata kuliah yang akan diambil akan otomatis disesuaikan dengan
                                                paket
                                                yang tersedia.</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .upload-container {
                position: relative;
                border: 2px dashed #4a4a4a;
                border-radius: 10px;
                padding: 30px;
                text-align: center;
                transition: all 0.3s ease;
                background-color: #fff;
            }

            .upload-container:hover {
                border-color: #007bff;
                background-color: rgba(0, 123, 255, 0.05);
            }

            .upload-container.dragover {
                background-color: rgba(0, 123, 255, 0.1);
                border-color: #007bff;
            }

            .file-input {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                cursor: pointer;
                z-index: 10;
            }

            .upload-zone {
                pointer-events: none;
            }

            .upload-icon {
                font-size: 50px;
                color: #4a4a4a;
                margin-bottom: 15px;
            }

            .file-preview {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .file-preview-item {
                background-color: #f8f9fa;
                border: 1px solid #e9ecef;
                border-radius: 5px;
                padding: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: calc(50% - 10px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .file-preview-item .file-info {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #333;
            }

            .file-preview-item .file-icon {
                font-size: 20px;
                color: #007bff;
            }

            .file-preview-item .file-delete {
                color: #dc3545;
                cursor: pointer;
            }

            .file-preview-item img {
                max-width: 100px;
                max-height: 100px;
                border-radius: 5px;
            }
        </style>
    @endpush
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
    @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ implode(' ', $errors->all()) }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    <script>
        document.getElementById('mahasiswaCheckbox').addEventListener('change', function() {
            if (this.checked) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Anda akan menandatangani pengajuan KRS ini.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('mahasiswaForm').submit();
                    } else {
                        document.getElementById('mahasiswaCheckbox').checked = false;
                    }
                });
            }
        });
    </script>
@endsection
