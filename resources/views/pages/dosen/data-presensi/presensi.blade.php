@extends('layouts.main')

<style>
    input[readonly] {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }

    @media (max-width: 576px) {
        .card-header h5 {
            font-size: 16px;
        }

        .form-check-label {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }

        ul {
            padding-left: 0;
        }

        li {
            list-style-type: none;
        }
    }
</style>

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center mb-3">PRESENSI MAHASISWA</h5>
                            <div class="row">
                                <div class="col-md-5 col-12">
                                    <ul class="list-unstyled">
                                        <li class="d-flex">
                                            <span style="width: 140px;">Mata Kuliah</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $jadwal->matkul->nama_matkul }}</span>
                                        </li>
                                        <li class="d-flex mt-2">
                                            <span style="width: 140px;">Dosen</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $jadwal->dosen->nama }}</span>
                                        </li>
                                        <li class="d-flex mt-2">
                                            <span style="width: 140px;">Pertemuan ke</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $pertemuan }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-5 offset-md-2 col-12">
                                    <ul class="list-unstyled">
                                        <li class="d-flex">
                                            <span style="width: 140px;">Program Studi</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $jadwal->kelas->prodi->nama_prodi }}</span>
                                        </li>
                                        <li class="d-flex mt-2">
                                            <span style="width: 140px;">Kelas</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $jadwal->kelas->nama_kelas }}</span>
                                        </li>
                                        <li class="d-flex mt-2">
                                            <span style="width: 140px;">Tanggal</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ \Carbon\Carbon::parse(now())->format('d/m/Y') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <button type="button" class="btn btn-secondary mb-3 btn-sm" id="hadirSemua">Hadir
                                    Semua</button>
                                <form method="POST" action="{{ route('data-presensi.store') }}">
                                    @csrf
                                    <input type="hidden" name="pertemuan" id="pertemuan" value="{{ $pertemuan }}">
                                    <input type="hidden" value="{{ $jadwal->id }}" id="jadwals_Id" name="jadwals_id">
                                    <input type="hidden" value="{{ $jadwal->matkul->id }}" id="matkuls_id"
                                        name="matkuls_id">
                                    <input type="hidden" name="tahun" value="{{ $tahun->tahun_akademik }}">
                                    <input type="hidden" value="{{ $jadwal->dosen->id }}" id="dosens_id" name="dosens_id">
                                    <input type="hidden" value="{{ $jadwal->kelas->prodi->id }}" id="prodis_id"
                                        name="prodis_id">
                                    <input type="hidden" value="{{ $jadwal->kelas->id }}" id="kelas_id" name="kelas_id">

                                    @forelse ($mahasiswas as $mahasiswa)
                                        <div class="form-group">
                                            <input type="hidden" value="{{ $mahasiswa->id }}" id="{{ $mahasiswa->id }}"
                                                name="mahasiswas_id[]">
                                            <h6>{{ $mahasiswa->nama_lengkap }} [{{ $mahasiswa->nim }}]</h6>
                                            <label>Status Kehadiran:</label>
                                            <div class="row">
                                                @foreach (['H' => 'Hadir', 'T' => 'Terlambat', 'I' => 'Izin', 'S' => 'Sakit', 'A' => 'Alpha', 'C' => 'Cabut'] as $value => $label)
                                                    <div class="col-md-2 col-6">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="radio" required class="form-check-input"
                                                                    name="status[{{ $mahasiswa->id }}]"
                                                                    value="{{ $value }}"
                                                                    data-mahasiswa-id="{{ $mahasiswa->id }}">
                                                                {{ $label }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <hr>
                                        </div>
                                    @empty
                                        <p class="text-center fw-bolder my-5">Mahasiswa belum ditambahkan</p>
                                    @endforelse

                                    <h5 class="text-center mb-3">BERITA ACARA PERKULIAHAN</h5>
                                    <div class="row">
                                        <div class="col-md-8 col-12">
                                            <div class="mb-3 form-group">
                                                <label for="materiResume">Ikhtisar Materi Kuliah</label>
                                                <input type="text" id="materiResume" class="form-control form-control-sm"
                                                    name="materiResume" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <div class="form-group">
                                                <label for="jumlahHadir">Hadir</label>
                                                <input type="number" id="jumlahHadir" class="form-control form-control-sm"
                                                    name="jumlahHadir" readonly value="0">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <div class="form-group">
                                                <label for="jumlahTidakHadir">Tidak Hadir</label>
                                                <input type="number" id="jumlahTidakHadir"
                                                    class="form-control form-control-sm" name="jumlahTidakHadir" readonly
                                                    value="0">
                                            </div>
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
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jumlahHadirInput = document.getElementById('jumlahHadir');
            const jumlahTidakHadirInput = document.getElementById('jumlahTidakHadir');

            const updateCounts = () => {
                let jumlahHadir = 0;
                let jumlahTidakHadir = 0;
                const statusRadios = document.querySelectorAll('input[type="radio"]:checked');
                statusRadios.forEach(radio => {
                    if (radio.value === 'H' || radio.value === 'T') {
                        jumlahHadir++;
                    } else {
                        jumlahTidakHadir++;
                    }
                });

                jumlahHadirInput.value = jumlahHadir;
                jumlahTidakHadirInput.value = jumlahTidakHadir;
            };

            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', updateCounts);
            });

            const setAllToH = () => {
                document.querySelectorAll('input[type="radio"]').forEach(radio => {
                    if (radio.value === 'H') {
                        radio.checked = true;
                    }
                });
                updateCounts();
            };

            document.getElementById('hadirSemua').addEventListener('click', setAllToH);

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    allowOutsideClick: false,
                    confirmButtonText: 'Isi Kontrak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/presensi/data-kontrak/isi-kontrak/{{ $jadwal->id }}`;
                    }
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                    allowOutsideClick: false,
                });
            @endif
        });
    </script>

@endsection
