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
                            <h5 class="text-center">EDIT PRESENSI MAHASISWA</h5>
                            @if ($absens->isNotEmpty())
                                @php $absen = $absens->first(); @endphp
                                <form method="POST" action="{{ route('data-presensi.update', $absen->kelas_id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="pertemuan" value="{{ $absen->pertemuan }}">
                                    <input type="hidden" name="matkuls_id" value="{{ $absen->matkuls_id }}">
                                    <input type="hidden" name="kelas_id" value="{{ $absen->kelas_id }}">
                                    <input type="hidden" name="kelas_id" value="{{ $absen->jadwals_id }}">
                                    <div class="card-header mb-3">
                                        <ul>
                                            <div class="row">
                                                <div class="col col-md-4">
                                                    <li>Mata Kuliah: {{ $absen->matkul->nama_matkul }}</li>
                                                </div>
                                                <div class="col offset-md-4">
                                                    <li>Program Studi: {{ $absen->prodi->nama_prodi }}</li>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col col-md-4">
                                                    <li>Dosen: {{ $absen->dosen->nama }}</li>
                                                </div>
                                                <div class="col offset-md-4">
                                                    <li>Kelas: {{ $absen->kelas->nama_kelas }}</li>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col col-md-4">
                                                    <li>Pertemuan ke {{ $absen->pertemuan }}</li>
                                                </div>
                                                <div class="col offset-md-4">
                                                    <li>Tanggal:
                                                        {{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</li>
                                                </div>
                                            </div>
                                        </ul>
                                    </div>

                                    @foreach ($mahasiswas as $mahasiswa)
                                        <div class="form-group">
                                            <input type="hidden" value="{{ $mahasiswa->id }}" name="mahasiswas_id[]">
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
                                    @endforeach
                                @else
                                    <p>Tidak ada data absensi untuk pertemuan ini.</p>
                            @endif

                            <h5 class="text-center">BERITA ACARA PERKULIAHAN</h5>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3 form-group">
                                        <label for="materiResume">Ikhtisar Materi Kuliah</label>
                                        <input type="text" id="materiResume" class="form-control form-control-sm" name="materiResume"
                                            value="{{ $resume->materi }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="jumlahHadir">Hadir</label>
                                        <input type="number" id="jumlahHadir" class="form-control form-control-sm" name="jumlahHadir"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="jumlahTidakHadir">Tidak Hadir</label>
                                        <input type="number" id="jumlahTidakHadir" class="form-control form-control-sm"
                                            name="jumlahTidakHadir" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- <h5 class="text-center mb-3">KONTRAK PERKULIAHAN</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-group">
                                        <label for="materiKontrak">Materi Perkuliahan</label>
                                        <input type="text" id="materiKontrak" class="form-control form-control-sm" name="materiKontrak"
                                            value="{{ $kontrak->materi }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 form-group">
                                        <label for="pustakaKontrak">Daftar Pustaka</label>
                                        <input type="text" id="pustakaKontrak" class="form-control form-control-sm" name="pustakaKontrak"
                                            value="{{ $kontrak->pustaka }}" required>
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
