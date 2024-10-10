@extends('layouts.main')


@section('container')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="breadcrumb">
            <a href="/presensi/dashboard" class="breadcrumb-item">
                <span class="mdi mdi-home"></span> Dashboard
            </a>
            <span class="breadcrumb-item">Rekap Berita</span>
            <span class="breadcrumb-item">Diajukan</span>
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
                                    @forelse ($beritas as $berita)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $berita->kelas->nama_kelas }}</td>
                                            <td>{{ $berita->kelas->prodi->nama_prodi }}</td>
                                            <td>{{ $berita->jadwal->dosen->nama }}</td>
                                            <td>{{ $berita->matkul->nama_matkul }}</td>
                                            <td><a href="/presensi/pengajuan-konfirmasi/rekap-berita/{{ $berita->pertemuan }}/{{$berita->matkuls_id}}/{{$berita->kelas_id}}/{{ $berita->jadwal_id }}" class="btn btn-warning btn-sm"><span class="mdi mdi-eye"></span> Lihat</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="8">Belum ada pengajuan rekap</td>
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