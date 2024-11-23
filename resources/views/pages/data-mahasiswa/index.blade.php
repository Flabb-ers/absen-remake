@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">Mahasiswa</span>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between mb-3">
                            <h4>Data Mahasiswa</h4>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="mdi mdi-file-import"></i> Import Mahasiswa
                            </button>
                        </div>
                        @if ($kelass->isEmpty())
                            <div class="card text-center w-100" style="padding: 2rem;">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Belum Ada Kelas</h4>
                                    <p class="card-text mb-4">Saat ini belum ada kelas yang ditambahkan. Silakan tambahkan
                                        kelas untuk menambahkan mahasiswa.</p>
                                    <a href="/presensi/data-master/data-kelas" class="btn btn-primary">
                                        Tambah Kelas
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
                                @foreach ($kelass as $kelas)
                                    <div class="col">
                                        <div class="card h-100">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title">Kelas {{ $kelas->nama_kelas }}</h5>
                                                <div class="mb-2">
                                                    <i class="mdi mdi-account-school"></i>
                                                    <span class="ms-1">{{ count($kelas->mahasiswa) }} Mahasiswa</span>
                                                </div>
                                                <p class="card-text mb-4">{{ $kelas->prodi->nama_prodi }} | Semester
                                                    {{ $kelas->semester->semester }}</p>
                                                <div class="mt-auto">
                                                    <a href="/presensi/data-mahasiswa/{{ $kelas->id }}"
                                                        class="btn btn-warning w-100">
                                                        <i class="mdi mdi-eye"></i> Lihat Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Import Data Mahasiswa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importForm" action="{{ route('data-mahasiswa-import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="fileInput" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="fileInput" name="file" accept=".xls,.xlsx"
                                required>
                            <div class="form-text">Format yang diperbolehkan: .xls, .xlsx</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="importButton">Import</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            @if($errors->any())
                let errorMessages = @json($errors->all()).map((error, index) => `${index + 1}. ${error}`).join('<br>')
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: errorMessages,
                    confirmButtonText: 'Tutup'
                });
            @elseif(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Tutup'
                });
            @endif
        });
    </script>
    
    
    <script>
        document.getElementById('importButton').addEventListener('click', function() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];

            if (!file) {
                alert('Harap pilih file untuk diunggah.');
                return;
            }

            const validExtensions = ['xls', 'xlsx'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!validExtensions.includes(fileExtension)) {
                alert('Format file tidak valid. Harap unggah file dengan format .xls atau .xlsx.');
                return;
            }

            document.getElementById('importForm').submit();
        });
    </script>
@endsection
