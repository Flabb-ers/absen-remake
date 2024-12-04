@extends('layouts.main')

@section('container')
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
                                            @if (isset($pembayaran) && $pembayaran->status_pembayaran == 0 && $pembayaran->keterangan == 'Belum')
                                                <div class="alert alert-danger mt-2" role="alert">
                                                    <span id="statusKRS">Pembayaran Belum Lunas
                                                        Segera Hubungi
                                                        Akademik</span>
                                                </div>
                                            @elseif(isset($pembayaran) && $pembayaran->status_pembayaran == 1 && $pembayaran->keterangan == 'Sudah')
                                                <div class="alert alert-success mt-2" role="alert">
                                                    <span id="statusKRS">Pembayaran Diverifikasi, Segera Ajukan KRS</span>
                                                </div>
                                            @elseif(empty($pembayaran))
                                                <button class="btn btn-info btn-sm mt-2" type="submit"><i
                                                        class="mdi mdi-file-upload"></i> Upload</button>
                                            @elseif($pembayaran->status_pembayaran == 0 && $pembayaran->keterangan == null)
                                                <div class="btn btn-warning btn-sm mt-2" type="submit"><i
                                                        class="mdi mdi-clock-alert"></i> Pending</div>
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
                                                            <i class="mdi mdi-file-send"></i> Ajukan KRS
                                                        </button>
                                                    </div>
                                                </form>
                                            @elseif($krs->status_krs == 0)
                                                <div class="d-grid gap-2">
                                                    <button class="btn btn-primary" disabled id="cetakKRSBtn">
                                                        <i class="mdi mdi-printer"></i> Cetak KRS
                                                    </button>
                                                </div>
                                            @elseif($krs->status_krs == 1)
                                                <div class="d-grid gap-2">
                                                    <button class="btn btn-primary" id="cetakKRSBtn">
                                                        <i class="mdi mdi-send-file"></i> Cetak KRS
                                                    </button>
                                                </div>
                                            @endif
                                            @if ($krs == null)
                                                <div class="alert alert-info mt-4" role="alert">
                                                    <strong>Status KRS:</strong>
                                                    <span id="statusKRS">Belum Diproses</span>
                                                </div>
                                            @elseif($krs->status_krs == 0)
                                                <div class="alert alert-warning mt-4" role="alert">
                                                    <strong>Status KRS:</strong>
                                                    <span id="statusKRS">Diproses</span>
                                                </div>
                                            @elseif($krs->status_krs == 1)
                                                <div class="alert alert-success mt-4" role="alert">
                                                    <strong>Status KRS:</strong>
                                                    <span id="statusKRS">Selesai</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary" disabled id="cetakKRSBtn">
                                                    <i class="mdi mdi-file-send"></i> Ajukan KRS
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
                                        <small>Daftar mata kuliah yang akan diambil akan otomatis disesuaikan dengan paket
                                            yang tersedia.</small>
                                    </div>
                                </div>
                            </div>
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
@endsection
