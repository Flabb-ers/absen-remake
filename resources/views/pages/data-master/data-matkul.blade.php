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
                                            <th>Kode</th>
                                            <th>Nama Mata Kuliah</th>
                                            <th>SKS</th>
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
                                                <td>
                                                    <button class="btn btn-primary btn-sm editMatkul"
                                                        data-id="{{ $matkul->id }}" data-nama="{{ $matkul->nama_matkul }}"
                                                        data-kode="{{ $matkul->kode }}" data-teori="{{ $matkul->teori }}"
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
                            <label for="nama_matkul" class="form-label">Nama Mata kuliah <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="nama_matkul" name="nama_matkul"
                                placeholder="Nama Mata Kuliah">
                            <div id="namaMatkulError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="kode" name="kode"
                                placeholder="Kode">
                            <div id="kodeError" class="invalid-feedback"></div>
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
                            <label for="edit_nama_matkul" class="form-label">Nama Mata Kuliah <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="edit_nama_matkul"
                                name="nama_matkul">
                            <div id="editNamaMatkulError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kodeEdit" class="form-label">Kode <span style="color: red;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="kodeEdit" name="kode"
                                placeholder="kode">
                            <div id="kodeErrorEdit" class="invalid-feedback"></div>
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

            $('#kelas').change(function() {

                var selectedOption = $(this).find('option:selected');
                var semester = selectedOption.data('semester');
                var prodi = selectedOption.data('prodi');
                var jenisKelas = selectedOption.data(
                    'jenis-kelas')

                $('#semester').text('Semester ' + semester);
                $('#program_studi').text(prodi);
                $('#jenis_kelas').text(jenisKelas);
            });
            $('#tambahForm').submit(function(e) {
                e.preventDefault();

                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                let nama_matkul = $('#nama_matkul').val();
                let dosen_id = $('#nama').val();
                let ruangan_id = $('#ruangan').val();
                let kelas_id = $('#kelas').val();
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
                        kode: kode,
                        dosens_id: dosen_id,
                        ruangans_id: ruangan_id,
                        kelas_id: kelas_id,
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
                            if (errors.sks) {
                                $('#sks').addClass('is-invalid');
                                $('#sksError').text(errors.sks[0]);
                            }
                            if (errors.kode) {
                                $('#kode').addClass('is-invalid');
                                $('#kodeError').text(errors.kode[0]);
                            }
                            if (errors.dosens_id) {
                                $('#nama').addClass('is-invalid');
                                $('#dosenError').text(errors.dosens_id[0]);
                            }
                            if (errors.ruangans_id) {
                                $('#ruangan').addClass('is-invalid');
                                $('#ruanganError').text(errors.ruangans_id[0]);
                            }
                            if (errors.kelas_id) {
                                $('#kelas').addClass('is-invalid');
                                $('#kelasError').text(errors.kelas_id[0]);
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
                let kelas = $(this).data('kelas');
                let dosen = $(this).data('dosen');
                let ruangan = $(this).data('ruangan');
                let kode = $(this).data('kode');
                let praktek = $(this).data('praktek');
                let teori = $(this).data('teori');


                $('#editId').val(id);
                $('#edit_nama_matkul').val(nama);
                $('#edit_kelas').val(kelas);
                $('#edit_dosen').val(dosen);
                $('#edit_ruangan').val(ruangan);
                $('#kodeEdit').val(kode);
                $('#praktekEdit').val(praktek);
                $('#teoriEdit').val(teori);
                $('#edit_kelas').trigger('change');
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();

                $('input, select').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                let id = $('#editId').val();
                let namaMatkul = $('#edit_nama_matkul').val();
                let kode = $('#kodeEdit').val();
                let praktek = $('#praktekEdit').val();
                let teori = $('#teoriEdit').val();
                let dosenId = $('#edit_dosen').val();
                let ruanganId = $('#edit_ruangan').val();
                let kelasId = $('#edit_kelas').val();


                $.ajax({
                    url: '{{ route('data-matkul.update', ':id') }}'.replace(':id', id),
                    type: 'PUT',
                    data: {
                        nama_matkul: namaMatkul,
                        kode: kode,
                        teori: teori,
                        praktek: praktek,
                        dosen_id: dosenId,
                        ruangan_id: ruanganId,
                        kelas_id: kelasId
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
                            if (errors.kode) {
                                $('#kodeEdit').addClass('is-invalid');
                                $('#kodeErrorEdit').text(errors.kode[0]);
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
                            if (errors.dosen_id) {
                                $('#edit_dosen').addClass('is-invalid');
                                $('#dosenError').text(errors.dosen_id[0]);
                            }
                            if (errors.ruangan_id) {
                                $('#edit_ruangan').addClass('is-invalid');
                                $('#ruanganError').text(errors.ruangan_id[0]);
                            }
                            if (errors.kelas_id) {
                                $('#edit_kelas').addClass('is-invalid');
                                $('#kelasError').text(errors.kelas_id[0]);
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

        });
    </script>
@endsection
