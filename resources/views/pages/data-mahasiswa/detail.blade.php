@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <a href="/presensi/data-mahasiswa" class="breadcrumb-item">Mahasiswa</a>
                <span class="breadcrumb-item">Kelas</span>
                <span class="breadcrumb-item">{{ $namaKelas->nama_kelas }}</span>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">

                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#tambahModal">
                                    <span class="mdi mdi-plus"></span> Tambah
                                </button>
                                <button type="button" class="btn btn-info btn-sm ms-2" data-bs-toggle="modal"
                                    data-bs-target="#importModal">
                                    <span class="mdi mdi-upload"></span> Import Data
                                </button>

                                <div class="dropdown ms-2" id="semesterDropdown" style="display: none;">
                                    <button class="btn btn-primary dropdown-toggle btn-sm" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">
                                        Semester
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        @foreach ($kelasSem as $kelSem)
                                            <form action="/presensi/data-mahasiswa/move" method="POST"
                                                class="m-0 move-form" data-kelas-id="{{ $kelSem->id }}">
                                                @csrf
                                                <input type="hidden" name="kelas_id" value="{{ $kelSem->id }}">
                                                <input type="hidden" name="mahasiswa_ids" class="mahasiswa-ids">
                                                <button type="submit" class="dropdown-item">Semester
                                                    {{ $kelSem->semester->semester }}</button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm ms-2" id="hapusButton"
                                    style="display: none;">
                                    <span class="mdi mdi-delete"></span> Hapus
                                </button>

                                <div class="ms-auto">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="text" id="search" class="form-control"
                                            placeholder="Cari Mahasiswa...">
                                        <input type="hidden" id="kelas_id" value="{{ $kelasId->first()->id }}">
                                        <button class="btn btn-outline-secondary" type="button" id="clearSearchButton">
                                            <span class="mdi mdi-close"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all" /></th>
                                            <th>#</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Kelas</th>
                                            <th>Semester</th>
                                            <th>Email</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($mahasiswas as $mahasiswa)
                                            <tr>
                                                <td><input type="checkbox" class="select-checkbox"
                                                        data-id="{{ $mahasiswa->id }}" /></td>
                                                <td>{{ $loop->iteration }}
                                                </td>
                                                <td>{{ $mahasiswa->nim }}</td>
                                                <td>{{ $mahasiswa->nama_lengkap }}</td>
                                                <td>{{ $mahasiswa->jenis_kelamin }}</td>
                                                <td>{{ $mahasiswa->kelas->nama_kelas }}</td>
                                                <td>Semester {{ $mahasiswa->kelas->semester->semester }}</td>
                                                <td>{{ $mahasiswa->email }}</td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm edit-btn"
                                                        data-id="{{ $mahasiswa->id }}"
                                                        data-nama="{{ $mahasiswa->nama_lengkap }}"
                                                        data-nim="{{ $mahasiswa->nim }}"
                                                        data-nisn="{{ $mahasiswa->nisn }}"
                                                        data-pembimbing="{{ $mahasiswa->dosen_pembimbing_id }}"
                                                        data-nik="{{ $mahasiswa->nik }}"
                                                        data-kelas="{{ $mahasiswa->kelas_id }}"
                                                        data-semester="{{ $mahasiswa->kelas->semester->semester }}"
                                                        data-prodi="{{ $mahasiswa->kelas->prodi->nama_prodi }}"
                                                        data-jenis="{{ $mahasiswa->kelas->jenis_kelas }}"
                                                        data-tanggallahir="{{ $mahasiswa->tanggal_lahir }}"
                                                        data-tempatlahir="{{ $mahasiswa->tempat_lahir }}"
                                                        data-namaibu="{{ $mahasiswa->nama_ibu }}"
                                                        data-telephone="{{ $mahasiswa->no_telephone }}"
                                                        data-jeniskelamin="{{ $mahasiswa->jenis_kelamin }}"
                                                        data-email="{{ $mahasiswa->email }}"
                                                        data-alamat="{{ $mahasiswa->alamat }}" data-toggle="modal"
                                                        data-target="#editModal">
                                                        <span class="mdi mdi-eye"></span> Lihat
                                                    </button>

                                                    <button class="btn btn-danger btn-sm delete-btn"
                                                        data-id="{{ $mahasiswa->id }}">
                                                        <span class="mdi mdi-delete"></span> Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="9">Mahasiswa Kelas
                                                    {{ $namaKelas->nama_kelas }} belum ditambahkan</td>
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

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $kelasId->first()->id }}">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File (XLS/XLSX/CSV)</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>
                        <div class="mb-3">
                            <div id="error-message" style="color: red; font-size: 14px;"></div>
                            <small class="text-muted">Format file harus CSV/XLSX/XLS.</small>
                        </div>


                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="kelas_id" id="kelas" value="{{ $namaKelas->id }}">
                            <div class="mb-3 col-12 col-md-4">
                                <label for="nama" class="form-label">Nama <span style="color: red;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="nama"
                                    name="nama_lengkap" placeholder="Nama Sesuai KTP">
                                <div id="namaError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="nim" class="form-label">NIM <span style="color: red;">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="nim" name="nim"
                                    placeholder="NIM">
                                <div id="nimError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="nisn" class="form-label">NISN</label>
                                <input type="number" class="form-control form-control-sm" id="nisn" name="nisn"
                                    placeholder="NISN">
                                <div id="nisnError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12 col-md-4">
                                <label for="no_telephone" class="form-label">Nomor Telephone <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="no_telephone"
                                    name="no_telephone" placeholder="Nomor Telephone">
                                <div id="telephoneError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="nik" class="form-label">NIK <span style="color: red;">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="nik" name="nik"
                                    placeholder="NIK">
                                <div id="nikError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="pembimbingAkademik" class="form-label">Pembimbing Akademik<span
                                        style="color: red;">*</span></label>
                                <select class="form-select" id="pembimbing_akademik" name="pembimbingAkademik">
                                    <option selected value="">--Dosen--</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                                <div id="dosenError" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-12 col-md-4">
                                <label for="tanggal_lahir" class="form-label">Tanggal lahir <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control form-control-sm" id="tanggal_lahir"
                                    name="tanggal_lahir">
                                <div id="tanggalError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="tempat_lahir" class="form-label">Tempat lahir <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="tempat_lahir"
                                    name="tempat_lahir" placeholder="Tempat Lahir">
                                <div id="tempatError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="nama_ibu" class="form-label">Nama Ibu <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="nama_ibu"
                                    name="nama_ibu" placeholder="Nama Ibu">
                                <div id="ibuError" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-md-4 col-12">
                                <label class="form-label">Jenis Kelamin <span style="color: red;">*</span></label><br>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check me-3">
                                        <label class="form-check-label" for="jenis_kelamin_1">
                                            <input type="radio" class="form-check-input" value="Laki-Laki"
                                                name="jenis_kelamin" id="jenis_kelamin_1" required>Laki-Laki</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <label class="form-check-label" for="jenis_kelamin_2"><input type="radio"
                                                class="form-check-input" value="Perempuan" name="jenis_kelamin"
                                                id="jenis_kelamin_2" required>Perempuan</label>
                                        <div id="jenisError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="email" class="form-label">Email <span style="color: red;">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="email"
                                    placeholder="Email" name="email">
                                <div id="emailError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="password" class="form-label">Password <span
                                        style="color: red;">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" id="password"
                                        name="password" placeholder="Password">
                                    <span class="input-group-text">
                                        <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                <div id="passwordError" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-12 col-md-3">
                                <label for="kelas" class="form-label">Kelas <span style="color: red;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="kelas_nama"
                                    value="{{ $namaKelas->nama_kelas }}" readonly>
                                <input type="hidden" name="kelas_id" id="kelas_id" value="{{ $namaKelas->id }}">
                                <div id="kelasError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label for="semester" class="form-label">Semester</label>
                                <div id="semester" class="form-control form-control-sm" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label for="program_studi" class="form-label">Prodi</label>
                                <div id="program_studi" class="form-control form-control-sm" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label for="jenis_kelas" class="form-label">Jenis Kelas</label>
                                <div id="jenis_kelas" class="form-control form-control-sm" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label for="alamat" class="form-label">Alamat Lengkap <span
                                        style="color: red;">*</span></label>
                                <textarea class="form-control form-control-sm" id="alamat" name="alamat" rows="3"
                                    placeholder="Alamat Lengkap" style="height: 100px"></textarea>
                                <div id="alamatError" class="invalid-feedback"></div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editId" name="id">
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6">
                                <label for="editNama" class="form-label">Nama <span style="color: red;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editNama"
                                    name="nama_lengkap" placeholder="Nama Sesuai KTP">
                                <div id="editNamaError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-6">
                                <label for="editNim" class="form-label">NIM <span style="color: red;">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editNim" name="nim"
                                    placeholder="NIM">
                                <div id="editNimError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12 col-md-4">
                                <label for="editNisn" class="form-label">NISN</label>
                                <input type="number" class="form-control form-control-sm" id="editNisn" name="nisn"
                                    placeholder="NISN">
                                <div id="editNisnError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="editNik" class="form-label">NIK <span style="color: red;">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editNik" name="nik"
                                    placeholder="NIK">
                                <div id="editNikError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="pembimbing_AkademikEdit" class="form-label">Pembimbing Akademik<span
                                        style="color: red;">*</span></label>
                                <select class="form-select" id="pembimbing_akademikEdit" name="pembimbingAkademikEdit">
                                    <option selected value="">--Dosen--</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                                <div id="dosenErrorEdit" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12 col-md-3">
                                <label for="editKelas" class="form-label">Kelas <span
                                        style="color: red;">*</span></label>
                                <select class="form-select" id="editKelas" name="kelas_id">
                                    <option selected value="">--Kelas--</option>
                                    @foreach ($kelass as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            data-semester="{{ $kelas->semester->semester }}"
                                            data-prodi="{{ $kelas->prodi->nama_prodi }}"
                                            data-jenis="{{ $kelas->jenis_kelas }}">
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="kelasError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label for="editSemester" class="form-label">Semester</label>
                                <div id="editSemester" class="form-control form-control-sm" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;">Semester</div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label for="editProgramStudi" class="form-label">Prodi</label>
                                <div id="editProgramStudi" class="form-control form-control-sm" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label for="editJenisKelas" class="form-label">Jenis Kelas</label>
                                <div id="editJenisKelas" class="form-control form-control-sm" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-12 col-md-4">
                                <label for="editTanggalLahir" class="form-label">Tanggal lahir <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control form-control-sm" id="editTanggalLahir"
                                    name="tanggal_lahir">
                                <div id="editTanggalLahirError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="editTempatLahir" class="form-label">Tempat lahir <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editTempatLahir"
                                    name="tempat_lahir" placeholder="Tempat Lahir">
                                <div id="editTempatLahirError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-4">
                                <label for="editNamaIbu" class="form-label">Nama Ibu <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="editNamaIbu"
                                    name="nama_ibu" placeholder="Nama Ibu">
                                <div id="editNamaIbuError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12 col-md-3">
                                <label for="editNoTelephone" class="form-label">Nomor Telephone <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editNoTelephone"
                                    name="no_telephone" placeholder="Nomor Telephone">
                                <div id="editNoTelephoneError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label class="form-label">Jenis Kelamin <span style="color: red;">*</span></label><br>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check me-3">
                                        <label class="form-check-label" for="editJenisKelamin1">
                                            <input type="radio" class="form-check-input" value="Laki-Laki"
                                                name="jenis_kelamin" id="editJenisKelamin1" required>Laki-Laki
                                        </label>
                                    </div>
                                    <div class="form-check me-3">
                                        <label class="form-check-label" for="editJenisKelamin2">
                                            <input type="radio" class="form-check-input" value="Perempuan"
                                                name="jenis_kelamin" id="editJenisKelamin2" required>Perempuan
                                        </label>
                                        <div id="editJenisKelaminError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label for="editEmail" class="form-label">Email <span
                                        style="color: red;">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="editEmail"
                                    placeholder="Email" name="email">
                                <div id="editEmailError" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3 col-12 col-md-3">
                                <label for="password" class="form-label">Password <span
                                        style="color: red;"></span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" id="passwordEdit"
                                        name="password" placeholder="Password">
                                    <span class="input-group-text">
                                        <i class="fa fa-eye" id="toggleEditPassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                <div id="passwordError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label for="editAlamat" class="form-label">Alamat Lengkap <span
                                        style="color: red;">*</span></label>
                                <textarea class="form-control form-control-sm" id="editAlamat" name="alamat" rows="3"
                                    placeholder="Alamat Lengkap" style="height: 100px"></textarea>
                                <div id="editAlamatError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="mdi mdi-content-save"></span> Simpan
                        </button>
                        <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal" aria-label="Close">
                            <span class="mdi mdi-close"></span>
                            Tutup</button>
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

            $('#importForm').on('submit', function(event) {
                event.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('data-mahasiswa-import') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: response.success
                            }).then(() => {
                                $('#importModal').modal(
                                    'hide');
                            });
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors && errors.file) {
                            $('#error-message').text(errors.file[0]);
                        }

                        if (xhr.status === 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Terjadi kesalahan server, silakan coba lagi'
                            });
                        }

                        if (xhr.status === 422) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Format Tidak Sesuai',
                                text: 'Format file yang Anda unggah tidak valid. Harap unggah file dengan format yang benar.'
                            });
                        }
                    }
                });
            });

            $('#tambahForm').submit(function(e) {
                e.preventDefault();

                $('input, select, textarea').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                let namaLengkap = $('#nama').val();
                let nim = $('#nim').val();
                let nisn = $('#nisn').val();
                let nik = $('#nik').val();
                let email = $('#email').val();
                let password = $('#password').val();
                let alamat = $('#alamat').val();
                let pembimbing_akademik = $('#pembimbing_akademik').val();
                let noTelephone = $('#no_telephone').val();
                let namaIbu = $('#nama_ibu').val();
                let tanggalLahir = $('#tanggal_lahir').val();
                let tempatLahir = $('#tempat_lahir').val();
                let jenisKelamin = $('input[name="jenis_kelamin"]:checked').val();
                let kelasId = $('#kelas').val();

                $.ajax({
                    url: '{{ route('data-mahasiswa.store') }}',
                    method: 'POST',
                    data: {
                        nama_lengkap: namaLengkap,
                        nim: nim,
                        nisn: nisn,
                        nik: nik,
                        email: email,
                        alamat: alamat,
                        password: password,
                        no_telephone: noTelephone,
                        nama_ibu: namaIbu,
                        tanggal_lahir: tanggalLahir,
                        tempat_lahir: tempatLahir,
                        jenis_kelamin: jenisKelamin,
                        kelas_id: kelasId,
                        pembimbing_akademik: pembimbing_akademik
                    },
                    success: function(response) {
                        $('#tambahModal').modal('hide');
                        $('#tambahForm')[0].reset();
                        $('input, select, textarea').removeClass('is-invalid');
                        $('.invalid-feedback').text('');

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
                            if (errors.nama_lengkap) {
                                $('#nama').addClass('is-invalid');
                                $('#namaError').text(errors.nama_lengkap[0]);
                            }
                            if (errors.password) {
                                $('#password').addClass('is-invalid');
                                $('#passwordError').text(errors.password[0]);
                            }
                            if (errors.pembimbing_akademik) {
                                $('#pembimbing_akademik').addClass('is-invalid');
                                $('#dosenError').text(errors.pembimbing_akademik[0]);
                            }
                            if (errors.nim) {
                                $('#nim').addClass('is-invalid');
                                $('#nimError').text(errors.nim[0]);
                            }
                            if (errors.nisn) {
                                $('#nisn').addClass('is-invalid');
                                $('#nisnError').text(errors.nisn[0]);
                            }
                            if (errors.nik) {
                                $('#nik').addClass('is-invalid');
                                $('#nikError').text(errors.nik[0]);
                            }
                            if (errors.kelas_id) {
                                $('#kelas').addClass('is-invalid');
                                $('#kelasError').text(errors.kelas_id[0]);
                            }
                            if (errors.tanggal_lahir) {
                                $('#tanggal_lahir').addClass('is-invalid');
                                $('#tanggalError').text(errors.tanggal_lahir[0]);
                            }
                            if (errors.tempat_lahir) {
                                $('#tempat_lahir').addClass('is-invalid');
                                $('#tempatError').text(errors.tempat_lahir[0]);
                            }
                            if (errors.nama_ibu) {
                                $('#nama_ibu').addClass('is-invalid');
                                $('#ibuError').text(errors.nama_ibu[0]);
                            }
                            if (errors.no_telephone) {
                                $('#no_telephone').addClass('is-invalid');
                                $('#telephoneError').text(errors.no_telephone[0]);
                            }
                            if (errors.email) {
                                $('#email').addClass('is-invalid');
                                $('#emailError').text(errors.email[0]);
                            }
                            if (errors.alamat) {
                                $('#alamat').addClass('is-invalid');
                                $('#alamatError').text(errors.alamat[0]);
                            }
                            if (error.jenis_kelamin) {
                                $('#jenisError').text(errors.jenis_kelamin[0]);
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
                let modal = $('#editModal');

                let id = $(this).data('id');
                let nama = $(this).data('nama');
                let nim = $(this).data('nim');
                let nisn = $(this).data('nisn');
                let nik = $(this).data('nik');
                let kelas = $(this).data('kelas');
                let semester = $(this).data('semester');
                let prodi = $(this).data('prodi');
                let jenisKelas = $(this).data('jenis');
                let tanggalLahir = $(this).data('tanggallahir');
                let tempatLahir = $(this).data('tempatlahir');
                let namaIbu = $(this).data('namaibu');
                let telephone = $(this).data('telephone');
                let jenisKelamin = $(this).data('jeniskelamin');
                let email = $(this).data('email');
                let alamat = $(this).data('alamat');
                let pembimbing = $(this).data('pembimbing');

                // Memasukkan data ke form edit
                modal.find('#editId').val(id);
                modal.find('#editNama').val(nama);
                modal.find('#editNim').val(nim);
                modal.find('#editNisn').val(nisn);
                modal.find('#editNik').val(nik);
                modal.find('#editKelas').val(kelas);
                modal.find('#editSemester').text('Semester ' + semester);
                modal.find('#editProgramStudi').text(prodi);
                modal.find('#editJenisKelas').text(jenisKelas);
                modal.find('#editTanggalLahir').val(tanggalLahir);
                modal.find('#pembimbing_akademikEdit').val(pembimbing);
                modal.find('#editTempatLahir').val(tempatLahir);
                modal.find('#editNamaIbu').val(namaIbu);
                modal.find('#editNoTelephone').val(telephone);
                modal.find("input[name=jenis_kelamin][value='" + jenisKelamin + "']").prop('checked', true);
                modal.find('#editEmail').val(email);
                modal.find('#editAlamat').val(alamat);

                // Membuka modal
                modal.modal('show');
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();

                let id = $('#editId').val();
                let nama = $('#editNama').val();
                let nim = $('#editNim').val();
                let nisn = $('#editNisn').val();
                let nik = $('#editNik').val();
                let kelas_id = $('#editKelas').val();
                let tanggal_lahir = $('#editTanggalLahir').val();
                let tempat_lahir = $('#editTempatLahir').val();
                let nama_ibu = $('#editNamaIbu').val();
                let no_telephone = $('#editNoTelephone').val();
                let jenis_kelamin = $('input[name="jenis_kelamin"]:checked').val();
                let email = $('#editEmail').val();
                let alamat = $('#editAlamat').val();
                let dosen_pembimbing_id = $('#pembimbing_akademikEdit').val();
                let password = $('#passwordEdit').val();


                $.ajax({
                    url: '{{ route('data-mahasiswa.update', ':id') }}'.replace(':id', id),
                    method: 'PUT',
                    data: {
                        nama_lengkap: nama,
                        nim: nim,
                        nisn: nisn,
                        nik: nik,
                        kelas_id: kelas_id,
                        tanggal_lahir: tanggal_lahir,
                        tempat_lahir: tempat_lahir,
                        nama_ibu: nama_ibu,
                        no_telephone: no_telephone,
                        jenis_kelamin: jenis_kelamin,
                        email: email,
                        alamat: alamat,
                        dosen_pembimbing_id: dosen_pembimbing_id,
                        password: password
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

                            if (errors.nama_lengkap) {
                                $('#editNama').addClass('is-invalid');
                                $('#editNamaError').text(errors.nama_lengkap[0]);
                            }
                            if (errors.nim) {
                                $('#editNim').addClass('is-invalid');
                                $('#editNimError').text(errors.nim[0]);
                            }
                            if (errors.nisn) {
                                $('#editNisn').addClass('is-invalid');
                                $('#editNisnError').text(errors.nisn[0]);
                            }
                            if (errors.nik) {
                                $('#editNik').addClass('is-invalid');
                                $('#editNikError').text(errors.nik[0]);
                            }
                            if (errors.no_telephone) {
                                $('#editNoTelephone').addClass('is-invalid');
                                $('#editNoTelephoneError').text(errors.no_telephone[0]);
                            }
                            if (errors.email) {
                                $('#editEmail').addClass('is-invalid');
                                $('#editEmailError').text(errors.email[0]);
                            }
                            if (errors.tanggal_lahir) {
                                $('#editTanggalLahir').addClass('is-invalid');
                                $('#editTanggalLahirError').text(errors.tanggal_lahir[0]);
                            }
                            if (errors.dosen_pembimbing_id) {
                                $('#pembimbing_akademikEdit').addClass('is-invalid');
                                $('#dosenErrorEdit').text(errors.dosen_pembimbing_id[0]);
                            }
                            if (errors.tempat_lahir) {
                                $('#editTempatLahir').addClass('is-invalid');
                                $('#editTempatLahirError').text(errors.tempat_lahir[0]);
                            }
                            if (errors.nama_ibu) {
                                $('#editNamaIbu').addClass('is-invalid');
                                $('#editNamaIbuError').text(errors.nama_ibu[0]);
                            }
                            if (errors.alamat) {
                                $('#editAlamat').addClass('is-invalid');
                                $('#editAlamatError').text(errors.alamat[0]);
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


            $('#editKelas').on('change', function() {
                let selectedOption = $(this).find('option:selected');

                let semester = selectedOption.data('semester');
                let prodi = selectedOption.data('prodi');
                let jenisKelas = selectedOption.data('jenis');

                $('#editSemester').text('Semester ' + semester);
                $('#editProgramStudi').text(prodi);
                $('#editJenisKelas').text(jenisKelas);
            });


            // $('#kelas').change(function() {
            //     let semester = $(this).find(':selected').data('semester');
            //     let prodi = $(this).find(':selected').data('prodi');
            //     let kelas = $(this).find(':selected').text();

            //     $('#semester').text('Semester ' + semester);
            //     $('#program_studi').text(prodi);

            //     let jenisKelas = kelas.endsWith('B') ? 'Kelas Karyawan' : 'Kelas Reguler';
            //     $('#jenis_kelas').text(jenisKelas);
            // });


            $('#tambahModal').on('shown.bs.modal', function() {
                let kelasId = $('#kelas_id').val();
                let kelasNama = $('#kelas_nama').val();
                let semester = '{{ $namaKelas->semester->semester }}';
                let prodi = '{{ $namaKelas->prodi->nama_prodi }}';
                let jenisKelas = kelasNama.endsWith('B') ? 'Kelas Karyawan' : 'Kelas Reguler';

                $('#semester').text('Semester ' + semester);
                $('#program_studi').text(prodi);
                $('#jenis_kelas').text(jenisKelas);
            });


            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data mahasiswa ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('data-mahasiswa.destroy', ':id') }}'.replace(
                                ':id',
                                id),
                            method: 'DELETE',
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses!',
                                    text: response.message,
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
                                    text: 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.',
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
            $('#importModal').on('hidden.bs.modal', function() {
                $('#error-message').text('');
            });

            $('#tambahModal').on('hidden.bs.modal', function() {
                clearValidation('#tambahForm');
            });

            $('#editModal').on('hidden.bs.modal', function() {
                clearValidation('#editForm');
            });


            function clearValidation(formId) {
                $(formId).find('input, select', 'textarea').removeClass('is-invalid');
                $(formId).find('.invalid-feedback').text('');
                $(formId)[0].reset();
            }

            $('#togglePassword').on('click', function() {
                let passwordInput = $('#password');
                let icon = $(this);
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            $('#toggleEditPassword').on('click', function() {
                let passwordInput = $('#passwordEdit');
                let icon = $(this);
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function toggleDropdown() {
                const anyChecked = $('.select-checkbox:checked').length > 0;
                $('#semesterDropdown').toggle(anyChecked);
            }

            function toggleHapusButton() {
                if ($('.select-checkbox:checked').length > 0) {
                    $('#hapusButton').show();
                } else {
                    $('#hapusButton').hide();
                }
            }
            $('#select-all').change(function() {
                const isChecked = this.checked;
                $('.select-checkbox').prop('checked', isChecked);
                toggleDropdown();
                toggleHapusButton();
            });

            $('.select-checkbox').change(function() {
                toggleDropdown();
                toggleHapusButton();
                if (!this.checked) {
                    $('#select-all').prop('checked', false);
                }
                if ($('.select-checkbox:checked').length === $('.select-checkbox').length) {
                    $('#select-all').prop('checked', true);
                }
            });

            $('.move-form').submit(function(e) {
                e.preventDefault()
                const mahasiswaIds = [];

                $('.select-checkbox:checked').each(function() {
                    mahasiswaIds.push($(this).data('id'));
                });

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin memindahkan mahasiswa yang dipilih ke kelas ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, pindahkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $(this).find('.mahasiswa-ids').val(mahasiswaIds.join(','));
                        this.submit();
                    }
                });
            });

            $('#hapusButton').click(function() {
                const mahasiswaIds = [];

                $('.select-checkbox:checked').each(function() {
                    mahasiswaIds.push($(this).data('id'));
                });

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Mahasiswa yang dipilih akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/presensi/data-mahasiswa/delete',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                mahasiswa_ids: mahasiswaIds.join(
                                    ',')
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Dihapus!',
                                    'Mahasiswa yang dipilih telah dihapus.',
                                    'success'
                                );
                                location.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus mahasiswa.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('#search').on('keyup', function() {
                let searchQuery = $(this).val();
                let kelasId = $('#kelas_id').val();

                searchMahasiswa(searchQuery, kelasId);
            });

            function searchMahasiswa(searchQuery, kelasId) {
                $.ajax({
                    url: '{{ route('data-mahasiswa.search') }}',
                    method: 'GET',
                    data: {
                        search: searchQuery,
                        kelas_id: kelasId,
                    },
                    success: function(response) {
                        $('tbody').empty();

                        if (response.data.length > 0) {
                            response.data.forEach(function(mahasiswa, index) {
                                $('tbody').append(`
                        <tr>
                            <td><input type="checkbox" class="select-checkbox" data-id="${mahasiswa.id}" /></td>
                            <td>${index + 1}</td>
                            <td>${mahasiswa.nim}</td>
                            <td>${mahasiswa.nama_lengkap}</td>
                            <td>${mahasiswa.jenis_kelamin}</td>
                            <td>${mahasiswa.kelas ? mahasiswa.kelas.nama_kelas : 'N/A'}</td>
                            <td>${mahasiswa.kelas && mahasiswa.kelas.semester ? 'Semester ' + mahasiswa.kelas.semester.semester : 'N/A'}</td>
                            <td>${mahasiswa.email}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" 
                                    data-id="${mahasiswa.id}" 
                                    data-nama="${mahasiswa.nama_lengkap}" 
                                    data-nim="${mahasiswa.nim}" 
                                    data-nisn="${mahasiswa.nisn}" 
                                    data-pembimbing="${mahasiswa.dosen_pembimbing_id}" 
                                    data-nik="${mahasiswa.nik}" 
                                    data-kelas="${mahasiswa.kelas_id}" 
                                    data-semester="${mahasiswa.kelas.semester ? mahasiswa.kelas.semester.semester : 'N/A'}" 
                                    data-prodi="${mahasiswa.kelas.prodi.nama_prodi}" 
                                    data-jenis="${mahasiswa.kelas.jenis_kelas}" 
                                    data-tanggallahir="${mahasiswa.tanggal_lahir}" 
                                    data-tempatlahir="${mahasiswa.tempat_lahir}" 
                                    data-namaibu="${mahasiswa.nama_ibu}" 
                                    data-telephone="${mahasiswa.no_telephone}" 
                                    data-jeniskelamin="${mahasiswa.jenis_kelamin}" 
                                    data-email="${mahasiswa.email}" 
                                    data-alamat="${mahasiswa.alamat}" 
                                    data-toggle="modal" 
                                    data-target="#editModal">
                                    <span class="mdi mdi-eye"></span> Lihat
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" 
                                    data-id="${mahasiswa.id}">
                                    <span class="mdi mdi-delete"></span> Hapus
                                </button>
                            </td>
                        </tr>
                    `);
                            });
                        } else {
                            $('tbody').append(
                                '<tr><td class="text-center" colspan="9">Tidak ada hasil ditemukan</td></tr>'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                    }
                });
            }
        });
    </script>
@endsection
