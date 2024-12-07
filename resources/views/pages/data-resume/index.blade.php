@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Resume</span>
            </div>
            <div class="row">
                @forelse ($getDosen as $dosen)
                    <div class="col-lg-4 col-md-6 col-sm-12 grid-margin stretch-card">
                        <div class="card text-bg-light mb-3 shadow">
                            <div class="card-header d-flex align-items-center">
                                <span class="mdi mdi-account-circle me-2" style="font-size: 24px;"></span>
                                {{ $dosen->nama }}
                            </div>
                            <div class="card-body">
                                <ul class="info-list">
                                    <li><strong><span class="mdi mdi-book-open-variant me-1"></span>Jumlah Mata Kuliah:
                                        </strong>
                                        {{ $dosenMatkulCount->get($dosen->id, 0) }}
                                    </li>
                                </ul>
                                <a href="/presensi/data/resume/{{ $dosen->id }}" class="btn btn-info btn-sm">Lihat</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Data tidak ditemukan</p>
                @endforelse

            </div>
        </div>
    </div>
@endsection
