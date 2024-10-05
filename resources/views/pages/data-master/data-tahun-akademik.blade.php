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
                                            <th> # </th>
                                            <th> Tahun Akademik </th>
                                            <th> Status </th>
                                            <th> Opsi </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tahuns as $tahun)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $tahun->tahun_akademik }}</td>
                                                @if ($tahun->status == 1)
                                                    <td><span class="bg-success rounded"
                                                            style="width: 15px; height: 15px; display: inline-block;"></span>
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="bg-danger rounded"
                                                            style="width: 15px; height: 15px; display: inline-block;"></span>
                                                    </td>
                                                @endif
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit-btn"
                                                        data-id="{{ $tahun->id }}"
                                                        data-tahun="{{ $tahun->tahun_akademik }}" data-toggle="modal"
                                                        data-status="{{ $tahun->status }}" data-toggle="modal"
                                                        data-target="#editModal"><span class="mdi mdi-pencil"></span>
                                                        Edit</button>
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        data-id="{{ $tahun->id }}">
                                                        <span class="mdi mdi-delete"></span> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tahun akademik belum ditambahkan</td>
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

    <!-- Modal Bootstrap untuk Tambah Data -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="mb-3">
                            <label for="tahunAkademik" class="form-label">Tahun Akademik <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="tahunAkademik"
                                name="tahun_akademik" placeholder="YYYY/YYYY" >
                            <div id="tahunAkademikError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status <span style="color: red;">*</span></label><br>
                            <div class="d-flex flex-wrap">
                                <div class="form-group me-3">
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label" for="status_aktif">
                                            <input class="form-check-input" type="radio" name="status" id="status_aktif"
                                                value="1" required>
                                            Aktif</label>
                                    </div>
                                </div>
                                <div class="form-group me-3">
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label" for="status_non_aktif">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="status_non_aktif" value="0" required>
                                            Non-Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap untuk Edit Data -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Tahun Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editTahunId" name="id">
                        <div class="mb-3">
                            <label for="editTahunAkademik" class="form-label">Tahun Akademik <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editTahunAkademik"
                                name="tahun_akademik" >
                            <div id="editTahunAkademikError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status <span style="color: red;">*</span></label><br>
                            <div class="d-flex flex-wrap">
                                <div class="form-group me-3">
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label" for="edit_status_aktif">
                                            <input class="form-check-input" type="radio" name="statusEdit"
                                                id="edit_status_aktif" value="1" required>
                                            Aktif</label>
                                    </div>
                                </div>
                                <div class="form-group me-3">
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label" for="edit_status_non_aktif">
                                            <input class="form-check-input" type="radio" name="statusEdit"
                                                id="edit_status_non_aktif" value="0" required>
                                            Non-Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">
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

                let tahun_akademik = $('#tahunAkademik').val();
                let status = $('input[name="status"]:checked').val();
                $('#tahunAkademikError').text('');

                $.ajax({
                    url: '{{ route('data-tahun-akademik.store') }}',
                    method: 'POST',
                    data: {
                        tahun_akademik: tahun_akademik,
                        status: status,
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
                            window.location.reload();
                        });
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            $("#tahunAkademik").addClass('is-invalid');
                            $('#tahunAkademikError').text(response.responseJSON.errors
                                .tahun_akademik[0]);
                        }
                    }
                });
            });

            $('.edit-btn').on('click', function() {
                let id = $(this).data('id');
                let tahun = $(this).data('tahun');
                let status = $(this).data('status'); 

                $('#editTahunId').val(id);
                $('#editTahunAkademik').val(tahun);
                $('input[name="statusEdit"]').prop('checked', false); 
                if (status == 1) {
                    $('#edit_status_aktif').prop('checked', true); 
                } else {
                    $('#edit_status_non_aktif').prop('checked', true);
                }

                $('#editTahunAkademikError').text('');
                $('#editTahunAkademik').removeClass('is-invalid');
                $('#editModal').modal('show');
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();

                let id = $('#editTahunId').val();
                let tahun_akademik = $('#editTahunAkademik').val();
                let status = $('input[name="statusEdit"]:checked').val(); // Ambil nilai status

                $.ajax({
                    url: '{{ route('data-tahun-akademik.update', ':id') }}'.replace(':id', id),
                    method: 'PUT',
                    data: {
                        tahun_akademik: tahun_akademik,
                        status: status // Kirim nilai status
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
                            location.reload();
                        });
                    },
                    error: function(response) {
                        if (response.status === 422) {
                            $('#editTahunAkademik').addClass('is-invalid');
                            $('#editTahunAkademikError').text(response.responseJSON.errors
                                .tahun_akademik[0]);
                        }
                    }
                });
            });

            $('.delete-btn').click(function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Tahun akademik ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('data-tahun-akademik.destroy', ':id') }}'
                                .replace(':id', id),
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
