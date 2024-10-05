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
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Prodi</th>
                                            <th>Status</th>
                                            <th>No WhatsApp</th>
                                            <th>Email</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kaprodis as $kaprodi)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $kaprodi->nama }}</td>
                                                <td>{{ $kaprodi->prodi->nama_prodi }}</td>
                                                @if ($kaprodi->status == 1)
                                                    <td><span class="bg-success rounded"
                                                            style="width: 15px; height: 15px; display: inline-block;"></span>
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="bg-danger rounded"
                                                            style="width: 15px; height: 15px; display: inline-block;"></span>
                                                    </td>
                                                @endif
                                                <td>{{ $kaprodi->no_telephone }}</td>
                                                <td>{{ $kaprodi->email }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm editKaprodi"
                                                        data-id="{{ $kaprodi->id }}" data-nama="{{ $kaprodi->nama }}"
                                                        data-id="{{ $kaprodi->id }}"
                                                        data-notlp="{{ $kaprodi->no_telephone }}"
                                                        data-prodi="{{ $kaprodi->prodis_id }}"
                                                        data-email="{{ $kaprodi->email }}"
                                                        data-status="{{ $kaprodi->status }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal">
                                                        <span class="mdi mdi-pencil"></span>
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-button"
                                                        data-id="{{ $kaprodi->id }}">
                                                        <span class="mdi mdi-delete"></span> Hapus
                                                    </button>


                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">Kaprodi belum ditambahkan</td>
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
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Kaprodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Dosen <span style="color: red;">*</span></label>
                            <select class="form-select" id="nama" name="nama">
                                <option selected value="">--Dosen--</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->nama }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                            <div id="dosenError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Prodi <span style="color: red;">*</span></label>
                            <select class="form-select" id="prodi" name="prodis_id">
                                <option selected value="">--Prodi--</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <div id="prodiError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="no_telephone" class="form-label">Nomor WhatsApp Aktif <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="no_telephone" name="no_telephone"
                                placeholder="No WhatsApp">
                            <div id="noTelephoneError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Email">
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
                    <h5 class="modal-title" id="editModalLabel">Edit Kaprodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Dosen <span style="color: red;">*</span></label>
                            <select class="form-select" id="edit_nama" name="nama">
                                <option selected value="">--Dosen--</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->nama }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                            <div id="editDosenError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_prodi" class="form-label">Prodi <span style="color: red;">*</span></label>
                            <select class="form-select" id="edit_prodi" name="prodis_id">
                                <option selected value="">--Prodi--</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <div id="editProdiError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_noTelephone" class="form-label">Nomor WhatsApp <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit_noTelephone" name="edit_noTelephone"
                                placeholder="Nomor WhatsApp">
                            <div id="tlpEmailError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control form-control-sm" id="edit_email" name="email"
                                placeholder="Email">
                            <div id="editEmailError" class="invalid-feedback"></div>
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
                let prodi = $('#prodi').val();
                let notlp = $('#no_telephone').val();
                let email = $('#email').val();
                let password = $('#password').val();
                $('#dosenError, #prodiError, #emailError, #passwordError')
                    .text('').removeClass('is-invalid');

                $.ajax({
                    url: '{{ route('data-kaprodi.store') }}',
                    method: 'POST',
                    data: {
                        nama: nama,
                        prodis_id: prodi,
                        email: email,
                        password: password,
                        no_telephone: notlp
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
                            if (errors.prodis_id) {
                                $('#prodi').addClass('is-invalid');
                                $('#prodiError').text(errors.prodis_id[0]);
                            }
                            if (errors.email) {
                                $('#email').addClass('is-invalid');
                                $('#emailError').text(errors.email[0]);
                            }
                            if (errors.no_telephone) {
                                $('#no_telephone').addClass('is-invalid');
                                $('#noTelephoneError').text(errors.no_telephone[0]);
                            }
                            if (errors.password) {
                                $('#password').addClass('is-invalid');
                                $('#passwordError').text(errors.password[0]);
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

            $('.editKaprodi').on('click', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const prodi = $(this).data('prodi');
                const email = $(this).data('email');
                const status = $(this).data('status');
                const tele = $(this).data('notlp');


                $('#edit_id').val(id);
                $('#edit_noTelephone').val(tele);
                $('#edit_nama').val(nama);
                $('#edit_prodi').val(prodi);
                $('#edit_email').val(email);
                if (status === 1) {
                    $('#status_aktifEdit').prop('checked', true);
                } else {
                    $('#status_non_aktifEdit').prop('checked', true);
                }
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                let nama = $('#edit_nama').val();
                let prodi = $('#edit_prodi').val();
                let email = $('#edit_email').val();
                let phone = $('#edit_noTelephone').val();
                let status = $('input[name="status"]:checked').val();


                $('#editDosenError, #editProdiError, #editEmailError, #statusError')
                    .text('').removeClass('is-invalid');

                $.ajax({
                    url: '{{ route('data-kaprodi.update', ':id') }}'.replace(':id', id),
                    method: 'PUT',
                    data: {
                        nama: nama,
                        prodis_id: prodi,
                        email: email,
                        status: status,
                        no_telephone: phone
                    },
                    success: function(response) {
                        $('#editModal').modal('hide');
                        $('#editForm')[0].reset();

                        Swal.fire({
                            icon: 'success',
                            title: 'Kaprodi berhasil diperbarui',
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
                                $('#edit_nama').addClass('is-invalid');
                                $('#editDosenError').text(errors.nama[0]);
                            }
                            if (errors.prodis_id) {
                                $('#edit_prodi').addClass('is-invalid');
                                $('#editProdiError').text(errors.prodis_id[0]);
                            }
                            if (errors.email) {
                                $('#edit_email').addClass('is-invalid');
                                $('#editEmailError').text(errors.email[0]);
                            }
                            if (errors.no_telephone) {
                                $('#edit_noTelephone').addClass('is-invalid');
                                $('#tlpEmailError').text(errors.no_telephone[0]);
                            }
                            if (errors.status) {
                                $('#status_aktifEdit, #status_non_aktifEdit').addClass(
                                    'is-invalid');
                                $('#statusError').text(errors.status[0]);
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
                    text: 'Apakah Anda yakin ingin menghapus Kaprodi ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('data-kaprodi.destroy', ':id') }}'.replace(
                                ':id',
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
