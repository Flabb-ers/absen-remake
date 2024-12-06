@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Presensi</span>
                <span class="breadcrumb-item active">Mata Kuliah</span>
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
                                                    <div class="dropdown">
                                                        <button class="btn btn-warning btn-sm dropdown-toggle"
                                                            type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            Pilih Pertemuan
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 1)
                                                            <li><a class="dropdown-item"
                                                                   href="/presensi/data/presence/{{ $jadwal->matkuls_id }}/{{ $jadwal->kelas_id }}/{{ $jadwal->id }}/1-7">Pertemuan 1-7</a></li>
                                                        @endif
                                                        
                                                        @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 8)
                                                            <li><a class="dropdown-item"
                                                                   href="/presensi/data/presence/{{ $jadwal->matkuls_id }}/{{ $jadwal->kelas_id }}/{{ $jadwal->id }}/8-14">Pertemuan 8-14</a></li>
                                                        @endif     
                                                        </ul>
                                                    </div>
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
