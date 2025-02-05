@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Master</span>
                <span class="breadcrumb-item active">Data Semester</span>
            </div> 
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="p-2">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#tambahModal">
                                            <span class="mdi mdi-plus"></span> Tambah
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-5 d-flex justify-content-end">
                                        <div class="d-flex gap-2 align-items-center">
                                            <label for="toggleSemesterStatus" class="fw-bolder">
                                                <span class="d-none d-md-inline">Status Semester:</span>
                                                @if ($genap != null)
                                                    <span class="badge badge-info badge-sm rounded-pill">Genap</span>
                                                @else
                                                    <span class="badge badge-warning badge-sm rounded-pill">Ganjil</span>
                                                @endif
                                            </label>
                                            <div class="form-switch">
                                                <input class="form-check-input" type="checkbox" id="toggleSemesterStatus"
                                                    style="width: 50px; height: 20px;"
                                                    @if ($genap) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> # </th>
                                            <th> Semesters </th>
                                            <th> Status </th>
                                            <th> Opsi </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($semesters as $semester)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>Semester {{ $semester->semester }}</td>
                                                <td>
                                                    @if ($semester->status == 1)
                                                        <span class="bg-success rounded"
                                                            style="width: 15px; height: 15px; display: inline-block;"></span>
                                                    @else
                                                        <span class="bg-danger rounded"
                                                            style="width: 15px; height: 15px; display: inline-block;"></span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <form id="delete-form-{{ $semester->id }}"
                                                        action="{{ route('data-semester.destroy', $semester->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="confirmDelete('{{ $semester->id }}')">
                                                            <span class="mdi mdi-delete"></span> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Semester Belum Ditambahkan</td>
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
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="mb-3">
                            <label for="namaSemester" class="form-label">Semester <span style="color: red;">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="namaSemester" name="semester"
                                placeholder="Semester">
                            <div id="semesterError" class="invalid-feedback"></div>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">
                                <span class="mdi mdi-content-save"></span> Simpan
                            </button>
                        </div>
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

                let semester = $('#namaSemester').val();
                $('#semesterError').text('');

                $.ajax({
                    url: '{{ route('data-semester.store') }}',
                    method: 'POST',
                    data: {
                        semester: semester,
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
                            $("#namaSemester").addClass('is-invalid');
                            $('#semesterError').text(response.responseJSON.errors.semester[0]);
                        }
                    }
                });
            });

            $('#toggleSemesterStatus').on('change', function() {
                let checkbox = $(this);
                let isChecked = checkbox.is(':checked');
                let semester = isChecked ? 'genap' : 'ganjil';

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Ubah status semester aktif menjadi semester ${semester}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('status.update') }}",
                            method: 'PUT',
                            data: {
                                semester: semester
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.success, 'success')
                                        .then(() => {
                                            location.reload();
                                        });
                                } else if (response.warning) {
                                    Swal.fire('Peringatan!', response.warning,
                                            'warning')
                                        .then(() => {
                                            window.location
                                                .reload();
                                        });
                                }
                            },
                            error: function(xhr) {
                                if (xhr.responseJSON.error) {
                                    Swal.fire('Gagal!', xhr.responseJSON.error,
                                        'error');
                                } else {
                                    Swal.fire('Gagal!',
                                        'Terjadi kesalahan saat mengubah status.',
                                        'error');
                                }
                                checkbox.prop('checked', !isChecked);
                            }
                        });
                    } else {
                        checkbox.prop('checked', !isChecked);
                    }
                });
            });
            $('#tambahModal').on('hidden.bs.modal', function() {
                console.log('ets');

                $('#tambahForm')[0].reset();
                $('#semesterError').text('');
                $('#semester').removeClass('is-invalid');
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

            // Function to clear validation
            function clearValidation(formId) {
                $(formId).find('input, select').removeClass('is-invalid');
                $(formId).find('.invalid-feedback').text('');
                $(formId)[0].reset();
            }
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data semester ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    const url = form.action;

                    axios.delete(url, {
                            headers: {
                                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(response => {
                            Swal.fire('Terhapus!', response.data.success, 'success').then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Gagal menghapus semester.', 'error');
                        });
                }
            });

        }
    </script>
@endsection
