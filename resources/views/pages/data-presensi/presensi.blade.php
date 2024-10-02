@extends('layouts.main')

<style>
    input[readonly] {
    background-color: #e9ecef; 
    color: #6c757d; 
    cursor: not-allowed;
}

</style>
@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <h5 class="text-center">PRESENSI MAHASISWA</h5>
                                <div>
                                    <ul>
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <li>Mata Kuliah :
                                                    {{ $jadwal->matkul->nama_matkul }}</li>
                                            </div>
                                            <div class="col offset-md-4 ">
                                                <li>Program Studi : 
                                                    {{ $jadwal->kelas->prodi->nama_prodi }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <li>Dosen : {{ $jadwal->dosen->nama }}</li>
                                            </div>
                                            <div class="col offset-md-4">
                                                <li>Kelas : {{ $jadwal->kelas->nama_kelas }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <li>Pertemuan ke {{ $pertemuan }}</li>
                                            </div>
                                            <div class="col offset-md-4">
                                                <li>Tanggal : {{ \Carbon\Carbon::parse(now())->format('d/m/Y') }}</li>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
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
                                                @foreach (['H' => 'Hadir', 'I' => 'Izin', 'S' => 'Sakit', 'A' => 'Alpha', 'C' => 'Cabut', 'T' => 'Terlambat'] as $value => $label)
                                                    <div class="col col-md-2">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input"
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

                                    <h5 class="text-center">BERITA ACARA PERKULIAHAN</h5>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3 form-group">
                                                <label for="materiResume">Ikhtisar Materi Kuliah</label>
                                                <input type="text" id="materiResume" class="form-control form-control-sm"
                                                    name="materiResume" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="jumlahHadir">Hadir</label>
                                                <input type="number" id="jumlahHadir" class="form-control form-control-sm"
                                                    name="jumlahHadir" readonly value="0">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="jumlahTidakHadir">Tidak Hadir</label>
                                                <input type="number" id="jumlahTidakHadir" class="form-control form-control-sm"
                                                    name="jumlahTidakHadir" readonly value="0">
                                            </div>
                                        </div>
                                    </div>
{{-- 
                                    <h5 class="text-center mb-3">KONTRAK PERKULIAHAN</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3 form-group">
                                                <label for="materiKontrak">Materi Perkuliahan</label>
                                                <input type="text" id="materiKontrak" class="form-control form-control-sm"
                                                    name="materiKontrak" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 form-group">
                                                <label for="pustakaKontrak">Daftar Pustaka</label>
                                                <input type="text" id="pustakaKontrak" class="form-control form-control-sm"
                                                    name="pustakaKontrak" required>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <button type="submit" class="btn btn-primary btn-sm"> <span
                                            class="mdi mdi-content-save"></span> Simpan</button>
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
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                });
            @endif
        });
    </script>
@endsection
