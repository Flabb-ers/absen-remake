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
                            <form method="POST" action="{{ route('data-kontrak.store') }}">
                                @csrf
                                <input type="hidden" name="jadwals_id" value="{{ $jadwal->id }}">
                                <input type="hidden" name="matkuls_id" value="{{ $jadwal->matkul->id }}">
                                <input type="hidden" name="dosens_id" value="{{ $jadwal->dosen->id }}">
                                <input type="hidden" name="prodis_id" value="{{ $jadwal->kelas->prodi->id }}">
                                <input type="hidden" name="kelas_id" value="{{ $jadwal->kelas->id }}">
                                <input type="hidden" name="tahun" value="{{ $tahun->tahun_akademik }}">
                                @for ($i = 1; $i <= 14; $i++)
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Pertemuan ke-{{ $i }}</h6>
                                        </div>
                                        <input type="hidden" name="pertemuan[{{ $i }}]"
                                            value="{{ $i }}">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="materiKontrak_{{ $i }}">Materi Perkuliahan</label>
                                                <input type="text" id="materiKontrak_{{ $i }}"
                                                    name="materiKontrak[{{ $i }}]"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pustakaKontrak_{{ $i }}">Daftar Pustaka</label>
                                                <input type="text" id="pustakaKontrak_{{ $i }}"
                                                    name="pustakaKontrak[{{ $i }}]"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                                <a href="/presensi/data-kontrak" class="btn btn-info btn-sm"><span
                                        class="mdi mdi-arrow-left"></span> Kembali</a>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <span class="mdi mdi-content-save"></span> Simpan
                                </button>
                            </form>
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
