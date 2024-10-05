@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="row"> 
                        @foreach ($kelass as $kelas)
                            <div class="col col-md-6">
                                <div class="card text-center" style="margin-bottom: 15px;">
                                    <div class="card-body">
                                        <h5 class="card-title">Kelas {{ $kelas->nama_kelas }}</h5>
                                        <p class="card-text">{{ $kelas->prodi->nama_prodi }} | Semester {{ $kelas->semester->semester }}</p>
                                        <a href="/presensi/data-mahasiswa/{{ $kelas->nama_kelas }}" class="btn btn-warning"><span class="mdi mdi-eye"></span> Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
