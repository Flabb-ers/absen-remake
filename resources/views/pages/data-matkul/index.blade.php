@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Mata Kuliah</span>
            </div>
            <div class="container">
                <div class="row">
                    @foreach ($semesters as $semester)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm hover-effect">
                                <div class="card-body">
                                    <h3 class="card-text text-muted">Semester {{ $semester->semester }}</h3>
                                    <a href="/presensi/data/matkul/{{ $semester->id }}" class="btn btn-primary btn-sm">
                                        Lihat Detail
                                        <i class="mdi mdi-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <style>
                .hover-effect {
                    transition: transform 0.3s ease;
                }

                .hover-effect:hover {
                    transform: scale(1.05);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                }
            </style>
        </div>
    </div>
@endsection
