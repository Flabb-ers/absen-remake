@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">KRS</span>
                <span class="breadcrumb-item">Kelas</span>
                <span class="breadcrumb-item">{{ $kelas->nama_kelas }}</span>
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
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Kelas</th>
                                            <th>Semester</th>
                                            <th>Status KRS</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($mahasiswas as $mahasiswa)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $mahasiswa->nim }}</td>
                                                <td>{{ $mahasiswa->nama_lengkap }}</td>
                                                <td>{{ $mahasiswa->jenis_kelamin }}</td>
                                                <td>{{ $mahasiswa->kelas->nama_kelas }}</td>
                                                <td>Semester {{ $mahasiswa->kelas->semester->semester }}</td>
                                                <td>
                                                    <div
                                                        class="btn btn-sm {{ $mahasiswa->status_krs == 1 ? 'btn-success' : 'btn-warning' }}">
                                                        {{ $mahasiswa->status_krs == 1 ? 'Sudah' : 'Belum' }}
                                                    </div>
                                                </td>
                                                <td>
                                                <a href="/presensi/krs/kategori/cetak/{{ $mahasiswa->id }}" target="_blank" class="btn btn-info btn-sm"><i class="mdi mdi-printer"></i> Cetak</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="9">Mahasiswa Kelas
                                                    {{ $namaKelas->nama_kelas }} belum ditambahkan</td>
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
