@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Niai</span>
                <span class="breadcrumb-item active">Mata Kuliah</span>
                <span class="breadcrumb-item active">{{ $jadwals->first()->dosen->nama }}</span>
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
                                            <th>Kode</th>
                                            <th>Nama Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Prodi</th>
                                            <th>Semester</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($jadwals as $jadwal)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jadwal->matkul->kode }}</td>
                                                <td>{{ $jadwal->matkul->nama_matkul }}</td>
                                                <td>{{ $jadwal->matkul->praktek + $jadwal->matkul->teori }} </td>
                                                <td>{{ $jadwal->matkul->prodi->nama_prodi }}</td>
                                                <td>Semester {{ $jadwal->matkul->semester->semester }}</td>
                                                <td>
                                                    <a target="_blank" class="btn btn-sm {{ ($pertemuanCounts[$jadwal->id] ?? null) == null ? 'btn-secondary disabled' : 'btn-warning' }}"
                                                        href="{{ ($pertemuanCounts[$jadwal->id] ?? null) == null ? '#' : '/presensi/data/value/'.$jadwal->kelas_id.'/'.$jadwal->matkuls_id.'/'.$jadwal->id.'/cek' }}">
                                                        <i class="mdi mdi-eye"></i> Lihat
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">Matkul belum ditambahkan</td>
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
