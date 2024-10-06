@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">KONTRAK PERKULIAHAN</h5>
                            <div>
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
                            </div>
                            <hr>
                            <div class="card-body">
                                <form method="POST" action="{{ route('data-kontrak.store') }}">
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

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3 form-group">
                                                <label for="materiKontrak">Materi Perkuliahan</label>
                                                <input type="text" id="materiKontrak"
                                                    class="form-control form-control-sm" name="materiKontrak" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 form-group">
                                                <label for="pustakaKontrak">Daftar Pustaka</label>
                                                <input type="text" id="pustakaKontrak"
                                                    class="form-control form-control-sm" name="pustakaKontrak" required>
                                            </div>
                                        </div>
                                    </div>
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
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/presensi/data-kontrak';
                    }
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endsection
