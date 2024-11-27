@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            @if (Auth::guard('admin')->check())
                                <h4>Dashboard Admin</h4>
                                <p>Konten khusus untuk Admin</p>
                            @elseif(Auth::guard('direktur')->check())
                                <h4>Dashboard Direktur</h4>
                                <p>Konten khusus untuk Direktur</p>
                            @elseif(Auth::guard('wakil_direktur')->check())
                                <h4>Dashboard Wakil Direktur</h4>
                                <p>Konten khusus untuk Wakil Direktur</p>
                            @elseif(Auth::guard('dosen')->check())
                                <h4>Dashboard Dosen</h4>
                                <p>Konten khusus untuk Dosen</p>
                            @elseif(Auth::guard('mahasiswa')->check())
                                <h4>Dashboard Mahasiswa</h4>
                                <p>Konten khusus untuk Mahasiswa</p>
                            @elseif(Auth::guard('kaprodi')->check())
                                <h4>Dashboard Kaprodi</h4>
                                <p>Konten khusus untuk Kaprodi</p>
                            @else
                                <p>Anda harus login terlebih dahulu.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
