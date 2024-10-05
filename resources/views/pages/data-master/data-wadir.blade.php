@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
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
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Email</th>
                                            <th>Nomor WhatsApp</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($wadirs as $wadir)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $wadir->nama }}</td>
                                                @if ($wadir->status == 1)
                                                    <td><span class="bg-success rounded"
                                                            style="width: 15px; height: 15px; display: inline-block;"></span>
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="bg-danger rounded"
                                                            style="width: 15px; height: 15px; display: inline-block;"></span>
                                                    </td>
                                                @endif
                                                <td>{{ $wadir->email }}</td>
                                                <td>{{ $wadir->no_telephone }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit-button"
                                                        data-id="{{ $wadir->id }}" data-nama="{{ $wadir->nama }}"
                                                        data-email="{{ $wadir->email }}" data-status="{{ $wadir->status }}"
                                                        data-nomor = "{{ $wadir->no_telephone }}">
                                                        <span class="mdi mdi-pencil"></span> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-button"
                                                        data-id="{{ $wadir->id }}">
                                                        <span class="mdi mdi-delete"></span> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="6">Wakil Direktur belum ditambahkan</td>
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


    {{-- tambah --}}
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Wakil Direktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Dosen <span style="color: red;">*</span></label>
                            <select class="form-select" id="nama">
                                <option selected value="">--Dosen--</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->nama }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                            <div id="dosenError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="no_telephone" class="form-label">Nomor WhatsApp Aktif <span style="color: red;">*</span></label>
                            <input type="no_telephone" class="form-control form-control-sm" id="no_telephone"
                                name="no_telephone" placeholder="Nomor WhatsApp">
                            <div id="NotlpError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control form-control-sm" id="email" name="email"
                                placeholder="Email">
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span style="color: red;">*</span></label>
                            <input type="password" class="form-control form-control-sm" id="password" name="password"
                                placeholder="Password" autocomplete="off">
                            <div id="passwordError" class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- edit --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Wakil Direktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" id="wadir_id">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Dosen <span style="color: red;">*</span></label>
                            <select class="form-select" id="namaEdit" name="nama">
                                <option selected value="">--Dosen--</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->nama }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                            <div id="dosenErrorEdit" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="nomorEdit" class="form-label">Nomor WhatsApp< <span style="color: red;">*</span>/label>
                            <input type="string" class="form-control form-control-sm" id="nomorEdit" name="email"
                                placeholder="Nomor WhatsApp">
                            <div id="notlperror" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="emailEdit" class="form-label">Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control form-control-sm" id="emailEdit" name="email">
                            <div id="emailErrorEdit" class="invalid-feedback"></div>
                        </div>

                        <label class="form-label">Status <span style="color: red;">*</span></label><br>
                        <div class="d-flex flex-wrap">
                            <div class="form-group me-3">
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label" for="status_aktifEdit">
                                        <input class="form-check-input" type="radio" name="status"
                                            id="status_aktifEdit" value="1" required>
                                        Aktif</label>
                                </div>
                            </div>
                            <div class="form-group me-3">
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label" for="status_non_aktifEdit">
                                        <input class="form-check-input" type="radio" name="status"
                                            id="status_non_aktifEdit" value="0" required>
                                        Non-Aktif</label>
                                </div>
                            </div>
                        </div>
                        <div id="statusError" class="invalid-feedback"></div>

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

            $('#tambahForm').submit(function(e) {
                e.preventDefault();
                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                let nama = $('#nama').val();
                let email = $('#email').val();
                let password = $('#password').val();
                let no_telephone = $('#no_telephone').val();
                $('#dosenError, #emailError, #passwordError')
                    .text('').removeClass('is-invalid');

                $.ajax({
                    url: '{{ route('data-wadir.store') }}',
                    method: 'POST',
                    data: {
                        nama: nama,
                        email: email,
                        password: password,
                        no_telephone: no_telephone
                    },
                    success: function(response) {
                        $('#tambahModal').modal('hide');
                        $('#tambahForm')[0].reset();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
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
                                $('#nama').addClass('is-invalid');
                                $('#dosenError').text(errors.nama[0]);
                            }
                            if (errors.email) {
                                $('#email').addClass('is-invalid');
                                $('#emailError').text(errors.email[0]);
                            }
                            if (errors.password) {
                                $('#password').addClass('is-invalid');
                                $('#passwordError').text(errors.password[0]);
                            }
                            if (errors.no_telephone) {
                                $('#no_telephone').addClass('is-invalid');
                                $('#NotlpError').text(errors.no_telephone[0]);
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


            $('.edit-button').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var email = $(this).data('email');
                var status = $(this).data('status');
                var noTlp = $(this).data('nomor');

                $('#wadir_id').val(id);
                $('#namaEdit').val(nama)
                $('#emailEdit').val(email);
                $('#statusEdit').val(status);
                $('#nomorEdit').val(noTlp);

                if (status == 1) {
                    $('#status_aktifEdit').prop('checked', true);
                } else {
                    $('#status_non_aktifEdit').prop('checked', true);
                }

                $('#editModal').modal('show');
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();
                let id = $('#wadir_id').val();
                let nama = $('#namaEdit').val();
                let email = $('#emailEdit').val();
                let no_telephone = $('#nomorEdit').val();
                let status = $('input[name="status"]:checked').val();

                $.ajax({
                    url: '{{ route('data-wadir.update', ':id') }}'.replace(':id', id),
                    method: 'PUT',
                    data: {
                        nama: nama,
                        email: email,
                        status: status,
                        no_telephone: no_telephone
                    },
                    success: function(response) {
                        $('#editModal').modal('hide');
                        $('#editForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
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
                                $('#namaEdit').addClass('is-invalid');
                                $('#dosenErrorEdit').text(errors.nama[0]);
                            }
                            if (errors.email) {
                                $('#emailEdit').addClass('is-invalid');
                                $('#emailErrorEdit').text(errors.email[0]);
                            }
                            if (errors.no_telephone) {
                                $('#nomorEdit').addClass('is-invalid');
                                $('#notlperror').text(errors.no_telephone[0]);
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


            $('.delete-button').on('click', function() {
                const deleteId = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus wakil direktur ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('data-wadir.destroy', ':id') }}'.replace(':id',
                                deleteId),
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses!',
                                    text: response.success,
                                    confirmButtonText: 'Oke'
                                }).then(() => {
                                    location
                                        .reload();
                                });
                            },
                            error: function(response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan. Silakan coba lagi.',
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
