@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Master</span>
                <span class="breadcrumb-item active">Data Kelas</span>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="p-2">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#tambahModal">
                                    <span class="mdi mdi-plus"></span> Tambah
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Nama Kelas </th>
                                            <th> Program Studi </th>
                                            <th> Semester </th>
                                            <th> Kode </th>
                                            <th> Jenis Kelas </th>
                                            <th> Opsi </th>
                                        </tr>
                                    </thead>
                                    <tbody id="kelasTable">
                                        @forelse ($kelass as $kelas)
                                            <tr id="row_{{ $kelas->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $kelas->nama_kelas }}</td>
                                                <td>{{ $kelas->prodi->nama_prodi }}</td>
                                                <td>Semester {{ $kelas->semester->semester }}</td>
                                                {{-- ini --}}
                                                <td>{{ $kelas->kode_kelas }}</td>
                                                <td>{{ $kelas->jenis_kelas }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit-btn "
                                                        data-id="{{ $kelas->id }}" data-kelas="{{ $kelas->nama_kelas }}"
                                                        data-prodi="{{ $kelas->prodi->id }}"
                                                        data-semester="{{ $kelas->semester->id }}"\ {{-- ini --}}
                                                        data-kode="{{ $kelas->kode_kelas }}"
                                                        data-jenis="{{ $kelas->jenis_kelas }}" data-toggle="modal"
                                                        data-target="#editModal"> <span class="mdi mdi-pencil"></span>
                                                        Edit</button>
                                                    <button class="btn btn-danger btn-sm deleteKelas"
                                                        data-id="{{ $kelas->id }}">
                                                        <span class="mdi mdi-delete"></span> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Kelas belum ditambahkan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $kelass->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap untuk Tambah Data -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        <div class="mb-3">
                            <label for="kodeKelas" class="form-label">Kode Keals<span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="kodeKelas"
                                placeholder="Kode Kelas" name="kode_kelas">
                            <div id="kodeKelasError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="namaProdi" class="form-label">Program Studi <span
                                    style="color: red;">*</span></label>
                            <select class="form-select" id="namaProdi" name="id_prodi" required>
                                <option selected disabled>--Program Studi--</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="errorProdi"></div>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester <span style="color: red;">*</span></label>
                            <select class="form-select" id="semester" name="id_semester" required>
                                <option selected disabled>--Semester--</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}">Semester {{ $semester->semester }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="errorSemester"></div>
                        </div>
                        <div class="mb-3">
                            <label for="jenisKelas" class="form-label">Jenis Kelas <span
                                    style="color: red;">*</span></label>
                            <select class="form-select" id="jenisKelas" name="jenis_kelas" required>
                                <option selected disabled>--Jenis Kelas--</option>
                                <option value="Reguler">Reguler</option>
                                <option value="Karyawan">Karyawan</option>
                            </select>
                            <div class="invalid-feedback" id="errorJenisKelas"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit Data -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editKelasId" name="id">
                        <div class="mb-3">
                            <label for="kodeKelas" class="form-label">Kode Kelas<span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="kodeKelasEdit"
                                placeholder="Kode Kelas">
                            <div id="kodeKelasErrorEdit" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="editNamaProdi" class="form-label">Program Studi <span
                                    style="color: red;">*</span></label>
                            <select class="form-select" id="editNamaProdi" name="id_prodi" required>
                                <option selected disabled>--Program Studi--</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="editErrorProdi"></div>
                        </div>
                        <div class="mb-3">
                            <label for="editSemester" class="form-label">Semester <span
                                    style="color: red;">*</span></label>
                            <select class="form-select" id="editSemester" name="id_semester" required>
                                <option selected disabled>--Semester--</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}">Semester {{ $semester->semester }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="editErrorSemester"></div>
                        </div>
                        <div class="mb-3">
                            <label for="editJenisKelas" class="form-label">Jenis Kelas <span
                                    style="color: red;">*</span></label>
                            <select class="form-select" id="editJenisKelas" name="jenis_kelas" required>
                                <option selected disabled>--Jenis Kelas--</option>
                                <option value="Reguler">Reguler</option>
                                <option value="Karyawan">Karyawan</option>
                            </select>
                            <div class="invalid-feedback" id="editErrorJenisKelas"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#tambahForm').submit(function(e) {
                e.preventDefault();

                let idProdi = $('#namaProdi').val();
                let idSemester = $('#semester').val();
                let jenisKelas = $('#jenisKelas').val();
                let kodeKelas = $('#kodeKelas').val();
                console.log(kodeKelas);
                

                $('#namaProdi').removeClass('is-invalid');
                $('#semester').removeClass('is-invalid');
                $('#jenisKelas').removeClass('is-invalid');
                $('#kodeKelas').removeClass('is-invalid');
                $('#errorProdi').text('');
                $('#errorSemester').text('');
                $('#errorJenisKelas').text('');

                $.ajax({
                    url: '{{ route('data-kelas.store') }}',
                    method: 'POST',
                    data: {
                        id_prodi: idProdi,
                        id_semester: idSemester,
                        jenis_kelas: jenisKelas,
                        kode_kelas : kodeKelas
                    },
                    
                    success: function(response) {
                        $('#tambahModal').modal('hide');
                        $('#tambahForm')[0].reset();

                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: response.success,
                            confirmButtonText: 'Oke'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            const errors = response.responseJSON.errors;

                            if (errors.id_prodi) {
                                $('#namaProdi').addClass('is-invalid');
                                $('#errorProdi').text(errors.id_prodi[0]);
                            }
                            if (errors.id_semester) {
                                $('#semester').addClass('is-invalid');
                                $('#errorSemester').text(errors.id_semester[0]);
                            }
                            if (errors.jenis_kelas) {
                                $('#jenisKelas').addClass('is-invalid');
                                $('#errorJenisKelas').text(errors.jenis_kelas[0]);
                            }

                            if (errors.kode_kelas) {
                                $('#kodeKelas').addClass('is-invalid');
                                $('#kodeKelasError').text(errors.kode_kelas[0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan. Silakan coba lagi.',
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                let prodiId = $(this).data('prodi');
                let semesterId = $(this).data('semester');
                let jenisKelas = $(this).data('jenis');
                let kodeKelas = $(this).data('kode');

                $('#editKelasId').val(id);
                $('#editNamaProdi').val(prodiId);
                $('#editSemester').val(semesterId);
                $('#editJenisKelas').val(jenisKelas);
                $('#kodeKelasEdit').val(kodeKelas);

                $('#editModal').modal('show');
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();

                let id = $('#editKelasId').val();
                let idProdi = $('#editNamaProdi').val();
                let idSemester = $('#editSemester').val();
                let jenisKelas = $('#editJenisKelas').val();
                let kodekelas = $('#kodeKelasEdit').val();

                // Reset validasi error
                $('#editNamaProdi').removeClass('is-invalid');
                $('#editSemester').removeClass('is-invalid');
                $('#editJenisKelas').removeClass('is-invalid');
                $('#kodeKelasEdit').removeClass('is-invalid');
                $('#editErrorProdi').text('');
                $('#editErrorSemester').text('');
                $('#editErrorJenisKelas').text('');

                $.ajax({
                    url: `{{ url('/presensi/data-master/data-kelas') }}/${id}`,
                    method: 'PUT',
                    data: {
                        id_prodi: idProdi,
                        id_semester: idSemester,
                        jenis_kelas: jenisKelas,
                        kode_kelas : kodekelas
                    },
                    success: function(response) {
                        $('#editModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: response.success,
                            confirmButtonText: 'Oke'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            const errors = response.responseJSON.errors;

                            if (errors.id_prodi) {
                                $('#editNamaProdi').addClass('is-invalid');
                                $('#editErrorProdi').text(errors.id_prodi[0]);
                            }
                            if (errors.id_semester) {
                                $('#editSemester').addClass('is-invalid');
                                $('#editErrorSemester').text(errors.id_semester[0]);
                            }
                            if (errors.jenis_kelas) {
                                $('#editJenisKelas').addClass('is-invalid');
                                $('#editErrorJenisKelas').text(errors.jenis_kelas[0]);
                            }
                            if (errors.kode_kelas) {
                                $('#kodeKelasEdit').addClass('is-invalid');
                                $('#kodeKelasErrorEdit').text(errors.kode_kelas[0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan. Silakan coba lagi.',
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.deleteKelas', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data kelas dan semua mahasiswa yang ada di kelas ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('/presensi/data-master/data-kelas') }}/${id}`,
                            method: 'DELETE',
                            success: function(response) {
                                // Menghapus baris dari tabel
                                $('#row_' + id).remove();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: response.success,
                                    confirmButtonText: 'Oke'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Gagal menghapus data. Silakan coba lagi.',
                                });
                            }
                        });
                    }
                });
            });
            $('.addClose, .editClose').on('click', function() {
                clearValidation('#tambahForm');
                clearValidation('#editForm');
            });

            $('#tambahModal').on('hidden.bs.modal', function() {
                clearValidation('#tambahForm');
            });

            $('#editModal').on('hidden.bs.modal', function() {
                clearValidation('#editForm');
            });

            function clearValidation(formId) {
                $(formId).find('input, select').removeClass('is-invalid');
                $(formId).find('.invalid-feedback').text('');
                $(formId)[0].reset();
            }
        });
    </script>
@endsection
