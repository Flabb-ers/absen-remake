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
    }
</style>

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center mb-3">EDIT PRESENSI MAHASISWA</h5>
                            @if ($absens->isNotEmpty())
                                @php $absen = $absens->first(); @endphp
                                <form method="POST" action="{{ route('data-presensi.update', $absen->kelas_id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="pertemuan" value="{{ $absen->pertemuan }}">
                                    <input type="hidden" name="matkuls_id" value="{{ $absen->matkuls_id }}">
                                    <input type="hidden" name="kelas_id" value="{{ $absen->kelas_id }}">
                                    <input type="hidden" name="jadwals_id" value="{{ $absen->jadwals_id }}">

                                    <div class="row">
                                        <div class="col-md-5 col-12">
                                            <ul class="list-unstyled">
                                                <li class="d-flex">
                                                    <span style="width: 140px;">Mata Kuliah</span>
                                                    <span style="margin-right: 5px;">:</span>
                                                    <span>{{ $absen->matkul->nama_matkul }}</span>
                                                </li>
                                                <li class="d-flex mt-2">
                                                    <span style="width: 140px;">Dosen</span>
                                                    <span style="margin-right: 5px;">:</span>
                                                    <span>{{ $absen->dosen->nama }}</span>
                                                </li>
                                                <li class="d-flex mt-2">
                                                    <span style="width: 140px;">Pertemuan ke</span>
                                                    <span style="margin-right: 5px;">:</span>
                                                    <span>{{ $absen->pertemuan }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-5 offset-md-2 col-12">
                                            <ul class="list-unstyled">
                                                <li class="d-flex">
                                                    <span style="width: 140px;">Program Studi</span>
                                                    <span style="margin-right: 5px;">:</span>
                                                    <span>{{ $absen->kelas->prodi->nama_prodi }}</span>
                                                </li>
                                                <li class="d-flex mt-2">
                                                    <span style="width: 140px;">Kelas</span>
                                                    <span style="margin-right: 5px;">:</span>
                                                    <span>{{ $absen->kelas->nama_kelas }}</span>
                                                </li>
                                                <li class="d-flex mt-2">
                                                    <span style="width: 140px;">Tanggal</span>
                                                    <span style="margin-right: 5px;">:</span>
                                                    <span>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <hr>
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
                                                                    @if (
                                                                        $absens->firstWhere('mahasiswas_id', $mahasiswa->id) &&
                                                                            $absens->firstWhere('mahasiswas_id', $mahasiswa->id)->status === $value) checked @endif
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
                                        <div class="col-md-8">
                                            <div class="mb-3 form-group">
                                                <label for="materiResume">Ikhtisar Materi Kuliah</label>
                                                <input type="text" id="materiResume" class="form-control form-control-sm"
                                                    name="materiResume" value="{{ $resume->materi }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="jumlahHadir">Hadir</label>
                                                <input type="number" id="jumlahHadir" class="form-control form-control-sm"
                                                    name="jumlahHadir" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="jumlahTidakHadir">Tidak Hadir</label>
                                                <input type="number" id="jumlahTidakHadir"
                                                    class="form-control form-control-sm" name="jumlahTidakHadir" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <span class="mdi mdi-content-save"></span> Simpan
                                    </button>
                                </form>
                            @else
                                <p>Tidak ada data absensi untuk pertemuan ini.</p>
                            @endif
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

            // Update jumlah awal
            updateCounts();
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                }).then(() => {
                    window.location.href =
                        "{{ route('data-presensi.index') }}";
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                }).then(() => {
                    window.location.href =
                        "{{ route('data-presensi.index') }}";
                });
            @endif
        });
    </script>
@endsection
