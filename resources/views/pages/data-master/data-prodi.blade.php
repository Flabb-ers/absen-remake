@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Master</span>
                <span class="breadcrumb-item active">Data Program Studi</span>
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
                                            <th> Kode Prodi </th>
                                            <th> Nama Prodi </th>
                                            <th> Singkatan </th>
                                            <th> Jenjang </th>
                                            <th> Opsi </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($prodis as $prodi)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $prodi->kode_prodi }}</td>
                                                <td>{{ $prodi->nama_prodi }}</td>
                                                <td>{{ $prodi->singkatan }}</td>
                                                <td>{{ $prodi->jenjang }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit-btn"
                                                        data-id="{{ $prodi->id }}" data-kode="{{ $prodi->kode_prodi }}"
                                                        data-nama="{{ $prodi->nama_prodi }}"
                                                        data-singkatan="{{ $prodi->singkatan }}"
                                                        data-jenjang="{{ $prodi->jenjang }}"
                                                        data-jenjang-alias="{{ $prodi->alias_jenjang }}"
                                                        data-nama-alias="{{ $prodi->alias_nama }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal">
                                                        <span class="mdi mdi-pencil"></span> Edit
                                                    </button>

                                                    <form id="delete-form-{{ $prodi->id }}"
                                                        action="{{ route('data-prodi.destroy', $prodi->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="confirmDelete('{{ $prodi->id }}')">
                                                            <span class="mdi mdi-delete"></span> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Prodi belum ditambahkan</td>
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
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Prodi</h5>
                    <button type="button" class="btn-close-tambah btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="mb-3">
                            <label for="kodeProdi" class="form-label">Kode Prodi <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="kodeProdi" name="kode_prodi"
                                placeholder="Kode prodi">
                            <div id="kodeError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="namaProdi" class="form-label">Nama Prodi <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="namaProdi" name="nama_prodi"
                                placeholder="Nama prodi">
                            <div id="namaError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="namaProdiAlias" class="form-label">Nama Prodi (English) <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="namaProdiAlias"
                                name="nama_prodiAlias" placeholder="Nama (Dalam Bahasa Inggris)">
                            <div id="namaErrorAlias" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="singkatan" class="form-label">Singkatan <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="singkatan" name="singkatan"
                                placeholder="Singkatan">
                            <div id="singkatanError" class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="jenjang" class="form-label">Jenjang <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="jenjang" name="jenjang"
                                placeholder="Jenjang">
                            <div id="jenjangError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="jenjang" class="form-label">Jenjang (English)<span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="jenjangAlias"
                                name="jenjangAlias" placeholder="Jenjang (Dalam Bahasa Inggris)">
                            <div id="jenjangAliasError" class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- editModal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Edit Data Prodi</h5>
                    <button type="button" class="btn-close-edit btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label for="kodeProdi" class="form-label">Kode Prodi <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit-kode" name="kode_prodi"
                                placeholder="Kode prodi">
                            <div id="edit-kodeError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="namaProdi" class="form-label">Nama Prodi <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit-nama" name="nama_prodi"
                                placeholder="Nama prodi">
                            <div id="edit-namaError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="namaProdi" class="form-label">Nama Prodi (English) <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit-namaAlias"
                                name="nama_prodiAlias" placeholder="Nama prodi (Dalam Bahasa Inggris)">
                            <div id="edit-namaErrorAlias" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="singkatan" class="form-label">Singkatan <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit-singkatan"
                                name="singkatan" placeholder="Singkatan">
                            <div id="edit-singkatanError" class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="jenjang" class="form-label">Jenjang <span style="color: red;">*</span>s</label>
                            <input type="text" class="form-control form-control-sm" id="edit-jenjang" name="jenjang"
                                placeholder="Jenjang">
                            <div id="edit-jenjangError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-jenjangAlias" class="form-label">Jenjang (English)<span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit-jenjangAlias"
                                name="jenjang" placeholder="Jenjang (Dalam Bahasa Inggris)">
                            <div id="edit-jenjangErrorAlias" class="invalid-feedback"></div>
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


            //Tambah
            $('#tambahForm').submit(function(e) {
                e.preventDefault();

                let kode = $('#kodeProdi').val();
                let nama = $('#namaProdi').val();
                let singkatan = $('#singkatan').val();
                let jenjang = $('#jenjang').val();
                let alias_nama = $('#namaProdiAlias').val();
                let alias_jenjang = $('#jenjangAlias').val();


                $('#kodeError').text('').removeClass('is-invalid');
                $('#namaError').text('').removeClass('is-invalid');
                $('#singkatanError').text('').removeClass('is-invalid');
                $('#jenjangError').text('').removeClass('is-invalid');
                $('#jenjangAliasError').text('').removeClass('is-invalid');
                $('#namaErrorAlias').text('').removeClass('is-invalid');

                $.ajax({
                    url: '{{ route('data-prodi.store') }}',
                    method: 'POST',
                    data: {
                        kode_prodi: kode,
                        nama_prodi: nama,
                        alias_nama: alias_nama,
                        singkatan: singkatan,
                        jenjang: jenjang,
                        alias_jenjang: alias_jenjang,
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
                            if (errors.kode_prodi) {
                                $("#kodeProdi").addClass('is-invalid');
                                $('#kodeError').text(errors.kode_prodi[0]);
                            }
                            if (errors.nama_prodi) {
                                $("#namaProdi").addClass('is-invalid');
                                $('#namaError').text(errors.nama_prodi[0]);
                            }
                            if (errors.singkatan) {
                                $("#singkatan").addClass('is-invalid');
                                $('#singkatanError').text(errors.singkatan[0]);
                            }
                            if (errors.alias_nama) {
                                $("#namaProdiAlias").addClass('is-invalid');
                                $('#namaErrorAlias').text(errors.alias_nama[0]);
                            }
                            if (errors.alias_jenjang) {
                                $("#jenjangAlias").addClass('is-invalid');
                                $('#jenjangAliasError').text(errors.alias_jenjang[0]);
                            }
                            if (errors.jenjang) {
                                $("#jenjang").addClass('is-invalid');
                                $('#jenjangError').text(errors.jenjang[0]);
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


            //data edit
            $('.edit-btn').click(function() {
                let id = $(this).data('id');
                let kode = $(this).data('kode');
                let nama = $(this).data('nama');
                let aliasNama = $(this).data('nama-alias'); 
                let singkatan = $(this).data('singkatan');
                let jenjang = $(this).data('jenjang');
                let aliasJenjang = $(this).data('jenjang-alias');
                




                $('#edit-id').val(id);
                $('#edit-kode').val(kode);
                $('#edit-nama').val(nama);
                $('#edit-singkatan').val(singkatan);
                $('#edit-jenjang').val(jenjang);
                $('#edit-namaAlias').val(aliasNama);
                $('#edit-jenjangAlias').val(aliasJenjang);


                $('#editNamaError').text('');
                $('#editKodeError').text('');
                $('#editSingkatanError').text('');
                $('#editJenjangError').text('');
                $('#editModal').modal('show');
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


            //edit form
            $('#editForm').submit(function(e) {
                e.preventDefault();
                let id = $('#edit-id').val();
                let kode = $('#edit-kode').val();
                let nama = $('#edit-nama').val();
                let singkatan = $('#edit-singkatan').val();
                let jenjang = $('#edit-jenjang').val();
                let alias_nama = $('#edit-namaAlias').val()
                let alias_jenjang = $('#edit-jenjangAlias').val()


                $.ajax({
                    url: '{{ route('data-prodi.update', ':id') }}'.replace(':id', id),
                    method: 'PUT',
                    data: {
                        kode_prodi: kode,
                        nama_prodi: nama,
                        singkatan: singkatan,
                        jenjang: jenjang,
                        alias_nama: alias_nama,
                        alias_jenjang: alias_jenjang,
                    },
                    success: function(response) {
                        $('#editModal').modal('hide');
                        $('#editForm')[0].reset();

                        $('#name-' + id).text(nama);
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
                            if (errors.kode_prodi) {
                                $("#edit-kode").addClass('is-invalid');
                                $('#edit-kodeError').text(errors.kode_prodi[0]);
                            }
                            if (errors.nama_prodi) {
                                $("#edit-nama").addClass('is-invalid');
                                $('#edit-namaError').text(errors.nama_prodi[0]);
                            }
                            if (errors.alias_nama) {
                                $("#edit-namaAlias").addClass('is-invalid');
                                $('#edit-namaErrorAlias').text(errors.alias_nama[0]);
                            }
                            if (errors.alias_jenjang) {
                                $("#edit-jenjangAlias").addClass('is-invalid');
                                $('#edit-jenjangErrorAlias').text(errors.alias_jenjang[0]);
                            }
                            if (errors.singkatan) {
                                $("#edit-singkatan").addClass('is-invalid');
                                $('#edit-singkatanError').text(errors.singkatan[0]);
                            }
                            if (errors.jenjang) {
                                $("#edit-jenjang").addClass('is-invalid');
                                $('#edit-jenjangError').text(errors.jenjang[0]);
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

        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Prodi ini akan dihapus!",
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
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Gagal menghapus prodi.', 'error');
                        });
                }
            });
        }
    </script>
@endsection
