@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#tambahModal">
                                <span class="mdi mdi-plus"></span> Tambah
                            </button>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Nama Ruangan </th>
                                            <th> Opsi </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($ruangans as $ruangan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $ruangan->nama }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit-btn"
                                                        data-id="{{ $ruangan->id }}" data-nama="{{ $ruangan->nama }}"
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                        <span class="mdi mdi-pencil"></span> Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        data-id="{{ $ruangan->id }}">
                                                        <span class="mdi mdi-delete"></span> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Ruangan belum ditambahkan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Ruang Kelas</h5>
                    <button type="button" class="btn-close-tambah btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="mb-3">
                            <label for="ruangan" class="form-label">Nama Ruangan <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="ruangan" name="nama"
                                placeholder="Nama Ruangan" >
                            <div id="namaError" class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Ruang Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-ruangan" class="form-label">Nama Ruangan <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit-ruangan" name="nama"
                                placeholder="Nama Ruangan">
                            <div id="edit-namaError" class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan
                        </button>
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
            //TAMBAH
            $('#tambahForm').submit(function(e) {
                e.preventDefault();

                let nama = $('#ruangan').val();

                $('#namaError').text('').removeClass('is-invalid');

                $.ajax({
                    url: '{{ route('data-ruangan.store') }}',
                    method: 'POST',
                    data: {
                        nama: nama
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
                            if (errors.nama) {
                                $("#ruangan").addClass('is-invalid');
                                $('#namaError').text(errors.nama[0]);
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

            // EDIT
            $('.edit-btn').click(function() {
                let id = $(this).data('id');
                let nama = $(this).data('nama');

                $('#edit-id').val(id);
                $('#edit-ruangan').val(nama);
                $('#edit-namaError').text('').removeClass('is-invalid');
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();

                let id = $('#edit-id').val();
                let nama = $('#edit-ruangan').val();
                $('#edit-namaError').text('').removeClass('is-invalid');

                $.ajax({
                    url: '{{ route('data-ruangan.update', ':id') }}'.replace(':id', id),
                    method: 'PUT',
                    data: {
                        nama: nama
                    },
                    success: function(response) {
                        $('#editModal').modal('hide');
                        $('#editForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: response.success,
                            confirmButtonText: 'Oke'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            const errors = response.responseJSON.errors;
                            if (errors.nama) {
                                $("#edit-ruangan").addClass('is-invalid');
                                $('#edit-namaError').text(errors.nama[0]);
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

            // HAPUS
            $('.delete-btn').click(function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ruangan ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('data-ruangan.destroy', ':id') }}'.replace(':id',
                                id),
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Dihapus!',
                                    response.success,
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire(
                                    'Oops...',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
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
