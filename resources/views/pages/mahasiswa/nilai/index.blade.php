@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">Rekap Nilai</span>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $allNilaiFilled = $combinedData->every(function($data) {
                                    return $data['nilai'] && $data['nilai']->nilai_total && $data['nilai']->nilai_huruf;
                                });

                            @endphp

                            @if ($allNilaiFilled)
                                <a href="/presensi/mahasiswa/khs/{{$sem}}" class="btn btn-primary mb-3">Cetak KHS</a>
                            @endif

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Mata kuliah</th>
                                            <th>SKS</th>
                                            <th>Nilai</th>
                                            <th>Predikat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($combinedData as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data['matkul']->kode }}</td>
                                                <td>{{ $data['matkul']->nama_matkul }}</td>
                                                <td>{{ $data['matkul']->praktek + $data['matkul']->teori ?? 'Belum ada nilai' }}</td>
                                                <td>{{ $data['nilai']->nilai_total ?? 'Belum dinilai' }}</td>
                                                <td>{{ $data['nilai']->nilai_huruf ?? 'Belum dinilai' }}</td>
                                            </tr>
                                        @endforeach
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
