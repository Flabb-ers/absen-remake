@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Master</span>
                <span class="breadcrumb-item active">Data Mata Kuliah</span>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#tambahModal">
                                    <span class="mdi mdi-plus"></span> Tambah
                                </button>
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" id="search" class="form-control"
                                        placeholder="Cari Mata Kuliah...">
                                    <button class="btn btn-outline-secondary" type="button" id="clearSearchButton">
                                        <span class="mdi mdi-close"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Nama Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Prodi</th>
                                            <th>Semester</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($matkuls as $matkul)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $matkul->kode }}</td>
                                                <td>{{ $matkul->nama_matkul }}</td>
                                                <td>{{ $matkul->praktek + $matkul->teori }} </td>
                                                <td>{{ $matkul->prodi->nama_prodi }}</td>
                                                <td>Semester {{ $matkul->semester->semester }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm editMatkul"
                                                        data-id="{{ $matkul->id }}" data-nama="{{ $matkul->nama_matkul }}"
                                                        data-alias="{{ $matkul->alias }}" data-kode="{{ $matkul->kode }}"
                                                        data-teori="{{ $matkul->teori }}"
                                                        data-prodi="{{ $matkul->prodi_id }}"
                                                        data-teori="{{ $matkul->teori }}"
                                                        data-semester="{{ $matkul->semester_id }}"
                                                        data-teori="{{ $matkul->teori }}"
                                                        data-praktek="{{ $matkul->praktek }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal">
                                                        <span class="mdi mdi-pencil"></span> Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-button deleteMatkul"
                                                        data-id="{{ $matkul->id }}"
                                                        data-nama="{{ $matkul->nama_matkul }}">
                                                        <span class="mdi mdi-delete"></span> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">Matkul belum ditambahkan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $matkuls->links() }}
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
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_matkul" class="form-label">Nama Mata Kuliah (Indonesia) <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="nama_matkul" name="nama_matkul"
                                placeholder="Nama Mata Kuliah (Indonesia)">
                            <div id="namaMatkulError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="alias" class="form-label">Nama Mata Kuliah (English)<span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="alias" name="alias"
                                placeholder="Nama Mata Kuliah (English)">
                            <div id="namaAliasError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="kode" name="kode"
                                placeholder="Kode">
                            <div id="kodeError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Prodi <span style="color: red;">*</span></label>
                            <select class="form-select" id="prodi" name="prodi" required>
                                <option selected>--Prodi--</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="prodiError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester <span style="color: red;">*</span></label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option selected>--Semester--</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}">Semester {{ $semester->semester }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="SemesterError"></div>
                        </div>
                        <div class="mb-3 row">
                            <label class="form-label">SKS <span style="color: red;">*</span></label>
                            <div class="col col-md-6">
                                <input type="number" class="form-control form-control-sm" id="teori" name="teori"
                                    placeholder="Jumlah Teori">
                                <div id="teoriError" class="invalid-feedback"></div>
                            </div>
                            <div class="col col-md-6">
                                <input type="number" class="form-control form-control-sm" id="praktek" name="praktek"
                                    placeholder="Jumlah Peaktek">
                                <div id="praktekError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="edit_nama_matkul" class="form-label">Nama Mata Kuliah (Indonesia) <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit_nama_matkul"
                                name="nama_matkul">
                            <div id="editNamaMatkulError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alias" class="form-label">Nama Mata Kuliah (English)<span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit_alias"
                                name="edit_alias">
                            <div id="editNamaAliasError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kodeEdit" class="form-label">Kode <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="kodeEdit" name="kodeEdit"
                                placeholder="kode">
                            <div id="kodeErrorEdit" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_prodi" class="form-label">Prodi <span style="color: red;">*</span></label>
                            <select class="form-select" id="edit_prodi" name="edit_prodi" required>
                                <option selected>--Prodi--</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="editProdiError"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_semester" class="form-label">Semester <span
                                    style="color: red;">*</span></label>
                            <select class="form-select" id="edit_semester" name="edit_semester" required>
                                <option selected>--Semester--</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}">Semester {{ $semester->semester }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="editSemesterError"></div>
                        </div>
                        <div class="mb-3 row">
                            <label class="form-label">SKS <span style="color: red;">*</span></label>
                            <div class="col col-md-6">
                                <input type="number" class="form-control form-control-sm" id="teoriEdit" name="teori"
                                    placeholder="Jumlah Teori">
                                <div id="teoriErrorEdit" class="invalid-feedback"></div>
                            </div>
                            <div class="col col-md-6">
                                <input type="number" class="form-control form-control-sm" id="praktekEdit"
                                    name="praktek" placeholder="Jumlah Peaktek">
                                <div id="praktekErrorEdit" class="invalid-feedback"></div>
                            </div>
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

            $('#tambahForm').submit(function(e) {
                e.preventDefault();

                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                let nama_matkul = $('#nama_matkul').val();
                let alias = $('#alias').val();
                let prodi_id = $('#prodi').val();
                let semester_id = $('#semester').val();
                let teori = $('#teori').val();
                let praktek = $('#praktek').val();
                let kode = $('#kode').val();

                $('#namaMatkulError, #sksError, #dosenError, #ruanganError, #kelasError')
                    .text('').removeClass('is-invalid');

                $.ajax({
                    url: '{{ route('data-matkul.store') }}',
                    method: 'POST',
                    data: {
                        nama_matkul: nama_matkul,
                        alias: alias,
                        kode: kode,
                        semester_id: semester_id,
                        prodi_id: prodi_id,
                        teori: teori,
                        praktek: praktek

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
                            if (errors.nama_matkul) {
                                $('#nama_matkul').addClass('is-invalid');
                                $('#namaMatkulError').text(errors.nama_matkul[0]);
                            }
                            if (errors.alias) {
                                $('#alias').addClass('is-invalid');
                                $('#namaAliasError').text(errors.alias[0]);
                            }
                            if (errors.sks) {
                                $('#sks').addClass('is-invalid');
                                $('#sksError').text(errors.sks[0]);
                            }
                            if (errors.kode) {
                                $('#kode').addClass('is-invalid');
                                $('#kodeError').text(errors.kode[0]);
                            }
                            if (errors.prodi_id) {
                                $('#prodi').addClass('is-invalid');
                                $('#prodiError').text(errors.prodi_id[0]);
                            }
                            if (errors.semester_id) {
                                $('#semester').addClass('is-invalid');
                                $('#SemesterError').text(errors.semester_id[0]);
                            }
                            if (errors.praktek) {
                                $('#praktek').addClass('is-invalid');
                                $('#praktekError').text(errors.praktek[0]);
                            }
                            if (errors.teori) {
                                $('#teori').addClass('is-invalid');
                                $('#teoriError').text(errors.teori[0]);
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

            $(document).on('click', '.editMatkul', function() {

                let id = $(this).data('id');
                let nama = $(this).data('nama');
                let alias = $(this).data('alias');
                let semester = $(this).data('semester');
                let prodi = $(this).data('prodi');
                let kode = $(this).data('kode');
                let praktek = $(this).data('praktek');
                let teori = $(this).data('teori');

                $('#editId').val(id);
                $('#edit_nama_matkul').val(nama);
                $('#edit_alias').val(alias);
                $('#edit_prodi').val(prodi);
                $('#edit_semester').val(semester);
                $('#kodeEdit').val(kode);
                $('#praktekEdit').val(praktek);
                $('#teoriEdit').val(teori);
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();

                $('input, select').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                let id = $('#editId').val();
                let namaMatkul = $('#edit_nama_matkul').val();
                let alias = $('#edit_alias').val();
                let kode = $('#kodeEdit').val();
                let praktek = $('#praktekEdit').val();
                let teori = $('#teoriEdit').val();
                let prodi_id = $('#edit_prodi').val();
                let semester_id = $('#edit_semester').val();


                $.ajax({
                    url: '{{ route('data-matkul.update', ':id') }}'.replace(':id', id),
                    type: 'PUT',
                    data: {
                        nama_matkul: namaMatkul,
                        alias: alias,
                        kode: kode,
                        teori: teori,
                        praktek: praktek,
                        semester_id: semester_id,
                        prodi_id: prodi_id
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
                            const errors = response.responseJSON.errors;
                            if (errors.nama_matkul) {
                                $('#edit_nama_matkul').addClass('is-invalid');
                                $('#editNamaMatkulError').text(errors.nama_matkul[0]);
                            }
                            if (errors.alias) {
                                $('#edit_alias').addClass('is-invalid');
                                $('#editNamaAliasError').text(errors.alias[0]);
                            }
                            if (errors.kode) {
                                $('#kodeEdit').addClass('is-invalid');
                                $('#kodeErrorEdit').text(errors.kode[0]);
                            }
                            if (errors.prodi_id) {
                                $('#edit_prodi').addClass('is-invalid');
                                $('#editProdiError').text(errors.prodi_id[0]);
                            }
                            if (errors.semester_id) {
                                $('#edit_semester').addClass('is-invalid');
                                $('#editSemesterError').text(errors.semester_id[0]);
                            }
                            if (errors.teori) {
                                $('#teoriEdit').addClass('is-invalid');
                                $('#teoriErrorEdit').text(errors.teori[0]);
                            }
                            if (errors.praktek) {
                                $('#praktekEdit').addClass('is-invalid');
                                $('#praktekErrorEdit').text(errors.praktek[0]);
                            }
                            if (errors.sks) {
                                $('#edit_sks').addClass('is-invalid');
                                $('#editSksError').text(errors.sks[0]);
                            }
                            if (errors.kelas_id) {
                                $('#edit_kelas').addClass('is-invalid');
                                $('#editkelasError').text(errors.kelas_id[0]);
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

            $('#edit_kelas').change(function() {
                let selectedOption = $(this).find('option:selected');
                let semester = selectedOption.data('semester');
                let prodi = selectedOption.data('prodi');
                let jenisKelas = selectedOption.data('jenis-kelas');

                $('#edit_semester').text('Semester ' + semester);
                $('#edit_program_studi').text(prodi);
                $('#edit_jenis_kelas').text(jenisKelas);
            });

            $(document).on('click', '.deleteMatkul', function() {
                let id = $(this).data('id');
                let nama = $(this).data('nama');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menghapus mata kuliah " + nama,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('data-matkul.destroy', ':id') }}'.replace(':id',
                                id),
                            method: 'DELETE',
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dihapus!',
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
                                    text: 'Terjadi kesalahan saat menghapus mata kuliah.',
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

            $('#search').on('keyup', function() {
                let searchQuery = $(this).val();

                $.ajax({
                    url: '{{ route('data-matkul.search') }}',
                    method: 'GET',
                    data: {
                        search: searchQuery
                    },
                    success: function(response) {
                        $('tbody').empty();
                        if (response.data.length > 0) {
                            response.data.forEach(function(matkul, index) {
                                $('tbody').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${matkul.kode}</td>
                            <td>${matkul.nama_matkul}</td>
                            <td>${matkul.teori + matkul.praktek}</td>
                            <td>${matkul.prodi.nama_prodi}</td>
                            <td>Semester ${matkul.semester.semester}</td>
                            <td>
                                <button class="btn btn-primary btn-sm editMatkul" 
                                    data-id="${matkul.id}" 
                                    data-nama="${matkul.nama_matkul}" 
                                    data-alias="${matkul.alias}" 
                                    data-kode="${matkul.kode}" 
                                    data-prodi="${matkul.prodi_id}" 
                                    data-semester="${matkul.semester_id}" 
                                    data-kode="${matkul.kode}" 
                                    data-teori="${matkul.teori}" 
                                    data-praktek="${matkul.praktek}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal">
                                    <span class="mdi mdi-pencil"></span> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-button deleteMatkul" 
                                    data-id="${matkul.id}" 
                                    data-nama="${matkul.nama_matkul}">
                                    <span class="mdi mdi-delete"></span> Hapus
                                </button>
                            </td>
                        </tr>
                    `);
                            });

                            updatePagination(response);
                        } else {
                            $('tbody').append(
                                '<tr><td class="text-center" colspan="7">Tidak ada hasil ditemukan</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        console.error('Terjadi kesalahan saat mencari mata kuliah.');
                    }
                });
            });

            //     function updatePagination(response) {
            //         $('#pagination').empty();
            //         for (let i = 1; i <= response.last_page; i++) {
            //             $('#pagination').append(`
        //     <a href="#" class="page-link" data-page="${i}">${i}</a>
        // `);
            //         }
            //     }

            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1]; // Ambil nomor halaman dari URL
                let searchQuery = $('#search').val();

                $.ajax({
                    url: '{{ route('data-matkul.search') }}',
                    method: 'GET',
                    data: {
                        search: searchQuery,
                        page: page
                    },
                    success: function(response) {
                        $('tbody').empty();
                        if (response.data.length > 0) {
                            response.data.forEach(function(matkul, index) {
                                $('tbody').append(`
                        <tr>
                            <td>${index + 1 + (response.current_page - 1) * response.per_page}</td>
                            <td>${matkul.kode}</td>
                            <td>${matkul.nama_matkul}</td>
                            <td>${matkul.teori + matkul.praktek}</td>
                            <td>${matkul.prodi.nama_prodi}</td>
                            <td>Semester ${matkul.semester.semester}</td>
                            <td>
                                <button class="btn btn-primary btn-sm editMatkul" 
                                    data-id="${matkul.id}" 
                                    data-nama="${matkul.nama_matkul}" 
                                    data-alias="${matkul.alias}" 
                                    data-kode="${matkul.kode}" 
                                    data-prodi="${matkul.prodi_id}" 
                                    data-semester="${matkul.semester_id}" 
                                    data-teori="${matkul.teori}" 
                                    data-praktek="${matkul.praktek}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal">
                                    <span class="mdi mdi-pencil"></span> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-button deleteMatkul" 
                                    data-id="${matkul.id}" 
                                    data-nama="${matkul.nama_matkul}">
                                    <span class="mdi mdi-delete"></span> Hapus
                                </button>
                            </td>
                        </tr>
                    `);
                            });

                            // Memperbarui paginasi
                            updatePagination(response);
                        } else {
                            $('tbody').append(
                                '<tr><td class="text-center" colspan="7">Tidak ada hasil ditemukan</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        console.error('Terjadi kesalahan saat mencari mata kuliah.');
                    }
                });
            });

            function updatePagination(response) {
                $('.pagination').empty();

                $('.pagination').append(`
        <li class="page-item ${response.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="?page=${response.current_page - 1}" aria-label="Previous">
                <span aria-hidden="true">&#8249;</span>
            </a>
        </li>
    `);

                for (let i = 1; i <= response.last_page; i++) {
                    $('.pagination').append(`
            <li class="page-item ${i === response.current_page ? 'active' : ''}">
                <a class="page-link" href="?page=${i}">${i}</a>
            </li>
        `);
                }

                $('.pagination').append(`
        <li class="page-item ${response.current_page === response.last_page ? 'disabled' : ''}">
            <a class="page-link" href="?page=${response.current_page + 1}" aria-label="Next">
                <span aria-hidden="true">&#8250;</span>
            </a>
        </li>
    `);
            }
        });
    </script>
@endsection
