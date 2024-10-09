@extends('layouts.main')


@section('container')
<div class="main-panel">
    <div class="content-wrapper">
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
                                    @forelse ($kontraks as $kontrak)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $kontrak->kelas->nama_kelas }}</td>
                                            <td>{{ $kontrak->kelas->prodi->nama_prodi }}</td>
                                            <td>{{ $kontrak->jadwal->dosen->nama }}</td>
                                            <td>{{ $kontrak->matkul->nama_matkul }}</td>
                                            <td><a href="/presensi/pengajuan-konfirmasi/rekap-kontrak/{{ $kontrak->jadwal_id }}/{{$kontrak->matkul_id}}/{{$kontrak->kelas_id}}" class="btn btn-warning btn-sm"><span class="mdi mdi-eye"></span> Lihat</a></td>
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