@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <h5 class="text-center">EDIT KONTRAK PERKULIAHAN</h5>
                                <div>
                                    <ul>
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <li>Mata Kuliah : {{ $kontrak->matkul->nama_matkul }}</li>
                                            </div>
                                            <div class="col offset-md-4">
                                                <li>Program Studi : {{ $kontrak->kelas->prodi->nama_prodi}}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <li>Dosen : {{ $kontrak->jadwal->dosen->nama }}</li>
                                            </div>
                                            <div class="col offset-md-4">
                                                <li>Kelas : {{ $kontrak->kelas->nama_kelas }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col-md-4">
                                                <li>Pertemuan ke {{ $kontrak->pertemuan }}</li>
                                            </div>
                                            <div class="col offset-md-4">
                                                <li>Tanggal : {{ \Carbon\Carbon::parse(now())->format('d/m/Y') }}</li>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('data-kontrak.update', $kontrak->id) }}">
                                    @csrf
                                    @method('PUT') 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3 form-group">
                                                <label for="materiKontrak">Materi Perkuliahan</label>
                                                <input type="text" id="materiKontrak" class="form-control form-control-sm" 
                                                    name="materiKontrak" value="{{ $kontrak->materi }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 form-group">
                                                <label for="pustakaKontrak">Daftar Pustaka</label>
                                                <input type="text" id="pustakaKontrak" class="form-control form-control-sm" 
                                                    name="pustakaKontrak" value="{{ $kontrak->pustaka}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <span class="mdi mdi-content-save"></span> Simpan Perubahan
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
