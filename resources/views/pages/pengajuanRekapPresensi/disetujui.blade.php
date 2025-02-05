@extends('layouts.main')


@section('container')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="breadcrumb">
            <a href="/presensi/dashboard" class="breadcrumb-item">
                <span class="mdi mdi-home"></span> Dashboard
            </a>
            <span class="breadcrumb-item">Rekap Presensi</span>
            <span class="breadcrumb-item">Disetujui</span>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kelas</th>
                                        <th>Program Studi</th>
                                        <th>Dosen</th>
                                        <th>Mata Kuliah</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($presensis as $presensi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $presensi->kelas->nama_kelas }}</td>
                                            <td>{{ $presensi->kelas->prodi->nama_prodi }}</td>
                                            <td>{{ $presensi->jadwal->dosen->nama }}</td>
                                            <td>{{ $presensi->matkul->nama_matkul }}</td>
                                            <td><a href="/presensi/data-presensi/rekap/{{ $presensi->pertemuan }}/{{ $presensi->matkul_id }}/{{ $presensi->kelas_id }}/{{ $presensi->jadwals_id}}" class="btn btn-success btn-sm"><span class="mdi mdi-file-download"></span> Rekap</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="8">Belum ada yang disetujui rekap</td>
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
@endsection