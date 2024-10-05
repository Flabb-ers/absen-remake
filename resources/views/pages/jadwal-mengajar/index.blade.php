@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                <span class="mdi mdi-plus"></span> Tambah
                            </button>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mata Kuliah</th>
                                            <th>Dosen</th>
                                            <th>Ruangan</th>
                                            <th>Kelas</th>
                                            <th>Hari</th>
                                            <th>Jam</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($jadwals as $jadwal)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jadwal->matkul->nama_matkul }}</td>
                                                <td>{{ $jadwal->dosen->nama }}</td>
                                                <td>{{ $jadwal->ruangan->nama }}</td>
                                                <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                                <td>{{ $jadwal->hari }}</td>
                                                <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm editButton"
                                                        data-id="{{ $jadwal->id }}"
                                                        data-matkul="{{ $jadwal->matkul->id }}"
                                                        data-dosen="{{ $jadwal->dosen->id }}"
                                                        data-kelas="{{ $jadwal->kelas->id }}"
                                                        data-ruangan="{{ $jadwal->ruangan->id }}"
                                                        data-tahun="{{ $jadwal->tahun }}"
                                                        data-jam_mulai="{{ $jadwal->waktu_mulai }}"
                                                        data-jam_selesai="{{ $jadwal->waktu_selesai }}"
                                                        data-hari="{{ $jadwal->hari }}" data-bs-toggle="modal"
                                                        data-bs-target="#editModal"><span class="mdi mdi-pencil"></span>
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-danger btn-sm deleteButton"
                                                        data-id="{{ $jadwal->id }}">
                                                        <span class="mdi mdi-delete"></span> Hapus
                                                    </button>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Jadwal belum ditambahkan</td>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Jadwal Mengajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="matkul" class="form-label">Matkul <span style="color: red;">*</span></label>
                                    <select class="form-select" id="matkul" name="matkul">
                                        <option selected>--Matkul--</option>
                                        @foreach ($matkuls as $matkul)
                                            <option value="{{ $matkul->id }}" data-teori="{{ $matkul->teori }}"
                                                data-praktek="{{ $matkul->praktek }}">
                                                {{ $matkul->nama_matkul }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="namaMatkulError"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sks" class="form-label">SKS </label>
                                    <input type="text" class="form-control form-control-sm" id="sks" name="sks"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="dosen" class="form-label">Dosen <span style="color: red;">*</span></label>
                                    <select class="form-select" id="dosen" name="dosen">
                                        <option selected>--Dosen--</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="dosenError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Hari <span style="color: red;">*</span></label>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="hari"
                                                            value="Senin" required>
                                                        Senin
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="hari"
                                                            value="Selasa" required>
                                                        Selasa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="hari"
                                                            value="Rabu" required>
                                                        Rabu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="hari"
                                                            value="Kamis" required>
                                                        Kamis
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="hari"
                                                            value="Jumat" required>
                                                        Jum'at
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" id="hariError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="kelas" class="form-label">Kelas <span style="color: red;">*</span></label>
                                    <select class="form-select" id="kelas" name="kelas">
                                        <option selected>--Kelas--</option>
                                        @foreach ($kelass as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="kelasError"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">Tahun Akademik <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="tahun"
                                        name="tahun" disabled value="{{ $tahun->tahun_akademik }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ruangan" class="form-label">Ruangan <span style="color: red;">*</span></label>
                                    <select class="form-select" id="ruangan" name="ruangan">
                                        <option selected>--Ruangan--</option>
                                        @foreach ($ruangans as $ruangan)
                                            <option value="{{ $ruangan->id }}">{{ $ruangan->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="ruanganError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jamMulai" class="form-label">Jam Mulai <span style="color: red;">*</span></label>
                                    <input type="time" class="form-control form-control-sm" id="jamMulai"
                                        name="jam_mulai">
                                    <div class="invalid-feedback" id="jamMulaiError"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jamSelesai" class="form-label">Jam Selesai <span style="color: red;">*</span></label>
                                    <input type="time" class="form-control form-control-sm" id="jamSelesai"
                                        name="jam_selesai">
                                    <div class="invalid-feedback" id="jamSelesaiError"></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><span class="mdi mdi-content-save"></span>
                            Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jadwal Mengajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editid">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editMatkul" class="form-label">Matkul <span style="color: red;">*</span></label>
                                    <select class="form-select" id="editMatkul" name="matkul" required>
                                        <option selected>--Matkul--</option>
                                        @foreach ($matkuls as $matkul)
                                            <option value="{{ $matkul->id }}" data-teori="{{ $matkul->teori }}"
                                                data-praktek="{{ $matkul->praktek }}">
                                                {{ $matkul->nama_matkul }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="editNamaMatkulError"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editSks" class="form-label">SKS <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="editSks"
                                        name="sks" disabled>
                                    <div class="invalid-feedback" id="editSksError"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editDosen" class="form-label">Dosen <span style="color: red;">*</span></label>
                                    <select class="form-select" id="editDosen" name="dosen" required>
                                        <option selected>--Dosen--</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="editDosenError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Hari <span style="color: red;">*</span></label>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="editHari"
                                                            value="Senin" required>
                                                        Senin
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="editHari"
                                                            value="Selasa" required>
                                                        Selasa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="editHari"
                                                            value="Rabu" required>
                                                        Rabu
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="editHari"
                                                            value="Kamis" required>
                                                        Kamis
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col col-md-2">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="editHari"
                                                            value="Jumat" required>
                                                        Jum'at
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" id="editHariError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editKelas" class="form-label">Kelas <span style="color: red;">*</span></label>
                                    <select class="form-select" id="editKelas" name="kelas" required>
                                        <option selected>--Kelas--</option>
                                        @foreach ($kelass as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="editKelasError"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tahunEdit" class="form-label">Tahun Akademik <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="tahunEdit"
                                        name="tahunEdit" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editRuangan" class="form-label">Ruangan <span style="color: red;">*</span></label>
                                    <select class="form-select" id="editRuangan" name="ruangan" required>
                                        <option selected>--Ruangan--</option>
                                        @foreach ($ruangans as $ruangan)
                                            <option value="{{ $ruangan->id }}">{{ $ruangan->nama }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="editRuanganError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editJamMulai" class="form-label">Jam Mulai <span style="color: red;">*</span></label>
                                    <input type="time" class="form-control form-control-sm" id="editJamMulai"
                                        name="jam_mulai" required>
                                    <div class="invalid-feedback" id="editJamMulaiError"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editJamSelesai" class="form-label">Jam Selesai <span style="color: red;">*</span></label>
                                    <input type="time" class="form-control form-control-sm" id="editJamSelesai"
                                        name="jam_selesai" required>
                                    <div class="invalid-feedback" id="editJamSelesaiError"></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" id="editButton"><span
                                class="mdi mdi-content-save"></span> Simpan</button>
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

            $('#matkul').change(function() {
                const selectedOption = $(this).find('option:selected');
                const teori = parseInt(selectedOption.data('teori')) || 0;
                const praktek = parseInt(selectedOption.data('praktek')) || 0;
                const totalSKS = teori + praktek;
                $('#sks').val(totalSKS);
            });

            $('#tambahForm').submit(function(e) {
                e.preventDefault();

                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').text('');


                let matkul_id = $('#matkul').val();
                let dosen_id = $('#dosen').val();
                let ruangan_id = $('#ruangan').val();
                let kelas_id = $('#kelas').val();
                let tahun = $('#tahun').val();
                let jam_mulai = $('#jamMulai').val();
                let jam_selesai = $('#jamSelesai').val();
                let hari = $('input[name="hari"]:checked').val();

                $.ajax({
                    url: '{{ route('jadwal-mengajar.store') }}',
                    method: 'POST',
                    data: {
                        matkul_id: matkul_id,
                        dosen_id: dosen_id,
                        ruangan_id: ruangan_id,
                        kelas_id: kelas_id,
                        tahun: tahun,
                        jam_mulai: jam_mulai,
                        jam_selesai: jam_selesai,
                        hari: hari
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

                            if (errors.matkul_id) {
                                $('#matkul').addClass('is-invalid');
                                $('#namaMatkulError').text(errors.matkul_id[0]);
                            }
                            if (errors.dosen_id) {
                                $('#dosen').addClass('is-invalid');
                                $('#dosenError').text(errors.dosen_id[0]);
                            }
                            if (errors.ruangan_id) {
                                $('#ruangan').addClass('is-invalid');
                                $('#ruanganError').text(errors.ruangan_id[0]);
                            }
                            if (errors.kelas_id) {
                                $('#kelas').addClass('is-invalid');
                                $('#kelasError').text(errors.kelas_id[0]);
                            }

                            if (errors.jam_selesai) {
                                $('#jamSelesai').addClass('is-invalid');
                                $('#jamSelesaiError').text(errors.jam_selesai[0]);
                            }

                            if (errors.jam_mulai) {
                                $('#jamMulai').addClass('is-invalid');
                                $('#jamMulaiError').text(errors.jam_mulai[0]);
                            }

                        } else if (response.status === 400) {
                            // Menangani error 400
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.responseJSON.error ||
                                    'Terjadi kesalahan. Silakan coba lagi.',
                            });
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

            $('#editModal').on('show.bs.modal', function(event) {

                let button = $(event.relatedTarget);
                let jadwalId = button.data('id');
                let matkulId = button.data('matkul');
                let dosenId = button.data('dosen');
                let kelasId = button.data('kelas');
                let ruanganId = button.data('ruangan');
                let jamMulai = button.data('jam_mulai');
                let jamSelesai = button.data('jam_selesai');
                let hari = button.data('hari');
                let id = button.data('id');
                let tahun = button.data('tahun');


                let modal = $(this);
                modal.find('#editMatkul').val(matkulId);
                modal.find('#editDosen').val(dosenId);
                modal.find('#editKelas').val(kelasId);
                modal.find('#editRuangan').val(ruanganId);
                modal.find('#editJamMulai').val(jamMulai);
                modal.find('#editJamSelesai').val(jamSelesai);
                modal.find('#tahunEdit').val(tahun);
                modal.find('#editid').val(id);
                modal.find('input[name="editHari"][value="' + hari + '"]').prop('checked', true);

                const selectedOption = modal.find('#editMatkul option:selected');
                const teori = parseInt(selectedOption.data('teori')) || 0;
                const praktek = parseInt(selectedOption.data('praktek')) || 0;
                const totalSKS = teori + praktek;
                modal.find('#editSks').val(totalSKS);
            });

            $('#editMatkul').change(function() {
                const selectedOption = $(this).find('option:selected');
                const teori = parseInt(selectedOption.data('teori')) || 0;
                const praktek = parseInt(selectedOption.data('praktek')) || 0;
                const totalSKS = teori + praktek;
                $('#editSks').val(totalSKS);
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();

                $('input, select').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                let id = $('#editid').val();
                let matkulId = $('#editMatkul').val();
                let dosenId = $('#editDosen').val();
                let kelasId = $('#editKelas').val();
                let ruanganId = $('#editRuangan').val();
                let jamMulai = $('#editJamMulai').val();
                let jamSelesai = $('#editJamSelesai').val();
                let hari = $('input[name="editHari"]:checked').val();

                $.ajax({
                    url: '{{ route('jadwal-mengajar.update', ':id') }}'.replace(':id', id),
                    type: 'PUT',
                    data: {
                        matkul_id: matkulId,
                        dosen_id: dosenId,
                        kelas_id: kelasId,
                        ruangan_id: ruanganId,
                        jam_mulai: jamMulai,
                        jam_selesai: jamSelesai,
                        hari: hari
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

                            if (errors.matkul_id) {
                                $('#editMatkul').addClass('is-invalid');
                                $('#editNamaMatkulError').text(errors.matkul_id[0]);
                            }
                            if (errors.dosen_id) {
                                $('#editDosen').addClass('is-invalid');
                                $('#editDosenError').text(errors.dosen_id[0]);
                            }
                            if (errors.hari) {
                                $('#editHariError').text(errors.hari[0]);
                                $('input[name="editHari"]').addClass('is-invalid');
                            }
                            if (errors.kelas_id) {
                                $('#editKelas').addClass('is-invalid');
                                $('#editKelasError').text(errors.kelas_id[0]);
                            }
                            if (errors.ruangan_id) {
                                $('#editRuangan').addClass('is-invalid');
                                $('#editRuanganError').text(errors.ruangan_id[0]);
                            }
                            if (errors.jam_mulai) {
                                $('#editJamMulai').addClass('is-invalid');
                                $('#editJamMulaiError').text(errors.jam_mulai[0]);
                            }
                            if (errors.jam_selesai) {
                                $('#editJamSelesai').addClass('is-invalid');
                                $('#editJamSelesaiError').text(errors.jam_selesai[0]);
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

            $(document).on('click', '.deleteButton', function() {
                let jadwalId = $(this).data('id');

                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Jadwal ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('jadwal-mengajar.destroy', ':id') }}'
                                .replace(':id',
                                    jadwalId),
                            type: 'DELETE',
                            success: function(response) {
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
                                    text: 'Terjadi kesalahan saat menghapus jadwal',
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

            // Function to clear validation
            function clearValidation(formId) {
                $(formId).find('input, select').removeClass('is-invalid');
                $(formId).find('.invalid-feedback').text('');
                $(formId)[0].reset();
            }
        });
    </script>
@endsection
