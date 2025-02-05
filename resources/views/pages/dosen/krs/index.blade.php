@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">KRS</span>
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
                                            <th>Nama</th>
                                            <th>Program Studi</th>
                                            <th>Semester</th>
                                            <th>Kelas</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($krss as $pembayaran)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pembayaran->mahasiswa->nama_lengkap }}</td>
                                                <td>{{ $pembayaran->prodi->nama_prodi }}</td>
                                                <td>Semester {{ $pembayaran->semester->semester }}</td>
                                                <td>{{ $pembayaran->kelas->nama_kelas }}</td>
                                                <td>
                                                    <a href="/presensi/krs/{{ Request::is('presensi/krs/diajukan') ? 'diajukan' : 'disetujui' }}/{{ $pembayaran->id }}/edit"
                                                        class="btn btn-warning btn-sm"><span class="mdi mdi-eye"></span>
                                                        Lihat
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="8">Belum ada pengajuan KRS</td>
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
