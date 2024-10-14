@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">Mahasiswa</span>
            </div> 
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
                            @foreach ($kelass as $kelas)
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">Kelas {{ $kelas->nama_kelas }}</h5>
                                            <div class="mb-2">
                                                <i class="mdi mdi-account-school"></i>
                                                <span class="ms-1">{{ count($kelas->mahasiswa) }} Mahasiswa</span>
                                            </div>
                                            <p class="card-text mb-4">{{ $kelas->prodi->nama_prodi }} | Semester {{ $kelas->semester->semester }}</p>
                                            <div class="mt-auto">
                                                <a href="/presensi/data-mahasiswa/{{ $kelas->id }}" class="btn btn-warning w-100">
                                                    <i class="mdi mdi-eye"></i> Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection