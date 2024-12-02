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
                            <!-- Upload Dokumen -->
                            <div class="col-md-6">
                                <div class="upload-container">
                                    <input type="file" id="fileInput" multiple class="file-input" accept=".pdf,.jpg,.png">
                                    <div class="upload-zone">
                                        <div class="upload-icon">
                                            <i class="mdi mdi-cloud-upload"></i>
                                        </div>
                                        <p class="upload-text">Drag & Drop atau Klik untuk Upload</p>
                                        <small class="text-muted">Maks 5MB per file. Format: JPG, PNG, JPEG</small>
                                    </div>
                                </div>

                                <!-- Preview File -->
                                <div id="filePreview" class="file-preview mt-3">
                                    <!-- File preview akan ditambahkan secara dinamis -->
                                </div>
                            </div>

                            <!-- Cetak KRS -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Kartu Rencana Studi (KRS)</label>
                                    <div class="d-grid gap-2">
                                        @if($cekStatus->status_krs == 1)
                                        <button class="btn btn-primary" id="cetakKRSBtn">
                                            <i class="mdi mdi-printer"></i> Cetak KRS
                                        </button>
                                        @elseif($cekStatus->status_krs == 0)
                                        <button class="btn btn-primary" disabled id="cetakKRSBtn">
                                            <i class="mdi mdi-printer"></i> Cetak KRS
                                        </button>
                                        @endif
                                    </div>

                                    <!-- Informasi Status KRS -->
                                    <div class="alert alert-info mt-3" role="alert">
                                        <strong>Status KRS:</strong>
                                        <span id="statusKRS">Belum Diproses</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Mata Kuliah -->
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <h5 class="mb-3">Mata Kuliah yang Akan Diambil</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode Mata Kuliah</th>
                                            <th>Nama Mata Kuliah</th>
                                            <th>SKS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($matkulKrs as $matkul )    
                                        <tr>
                                            <td>{{ $matkul->kode }}</td>
                                            <td>{{ $matkul->nama_matkul }}</td>
                                            <td>{{ $matkul->teori + $matkul->praktek }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="alert alert-info mt-3">
                                    <small>Daftar mata kuliah yang akan diambil akan otomatis disesuaikan dengan paket yang tersedia.</small>
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
            background-color: #f4f4f4;
            border-radius: 5px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .file-preview-item .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-preview-item .file-icon {
            font-size: 24px;
            color: #007bff;
        }

        .file-preview-item .file-delete {
            color: #dc3545;
            cursor: pointer;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const uploadContainer = document.querySelector('.upload-container');
            const filePreview = document.getElementById('filePreview');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, () => uploadContainer.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, () => uploadContainer.classList.remove('dragover'), false);
            });

            uploadContainer.addEventListener('drop', handleDrop, false);
            fileInput.addEventListener('change', handleFiles, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }

            function handleFiles(files) {
                if (!(files instanceof FileList)) {
                    files = fileInput.files;
                }

                filePreview.innerHTML = ''; 
                Array.from(files).forEach(file => {
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File ${file.name} melebihi batas 5MB`);
                        return;
                    }

                    const filePreviewItem = document.createElement('div');
                    filePreviewItem.classList.add('file-preview-item');
                    
                    const fileIcon = getFileIcon(file.type);
                    
                    filePreviewItem.innerHTML = `
                        <div class="file-info">
                            <i class="mdi ${fileIcon} file-icon"></i>
                            <span>${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
                        </div>
                        <i class="mdi mdi-close-circle file-delete"></i>
                    `;

                    const deleteBtn = filePreviewItem.querySelector('.file-delete');
                    deleteBtn.addEventListener('click', () => {
                        filePreviewItem.remove();
                        const dataTransfer = new DataTransfer();
                        Array.from(fileInput.files).filter(f => f !== file).forEach(f => dataTransfer.items.add(f));
                        fileInput.files = dataTransfer.files;
                    });

                    filePreview.appendChild(filePreviewItem);
                });
            }

            function getFileIcon(fileType) {
                switch(fileType) {
                    case 'application/pdf': return 'mdi-file-pdf';
                    case 'image/jpeg': 
                    case 'image/png': return 'mdi-file-image';
                    default: return 'mdi-file';
                }
            }
        });
    </script>
    @endpush
@endsection