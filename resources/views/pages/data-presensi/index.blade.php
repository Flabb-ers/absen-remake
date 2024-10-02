@extends('layouts.main')

@php
    use App\Models\Absen;
    use Carbon\Carbon;
@endphp

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                @foreach ($jadwals as $jadwal)
                    <div class="col-lg-4 col-md-6 col-sm-12 grid-margin stretch-card">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header">{{ $jadwal->matkul->nama_matkul }}</div>
                            <div class="card-body">
                                <ul class="info-list">
                                    <li><strong>Dosen:</strong> {{ $jadwal->dosen->nama }}</li>
                                    <li><strong>Program Studi:</strong> {{ $jadwal->kelas->prodi->nama_prodi }}</li>
                                    <li><strong>SKS:</strong> {{ $jadwal->matkul->praktek + $jadwal->matkul->teori }}</li>
                                    <li><strong>Hari:</strong> {{ $jadwal->hari }}</li>
                                    <li><strong>Waktu:</strong>
                                        {{ Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - 
                                        {{ Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                    </li>
                                    <li><strong>Kelas:</strong> {{ $jadwal->kelas->nama_kelas }}</li>
                                    <li><strong>Ruangan:</strong> {{ $jadwal->ruangan->nama }}</li>
                                </ul>

                                @php
                                    $absens = Absen::where('jadwals_id', $jadwal->id)
                                        ->where('kelas_id', $jadwal->kelas->id)
                                        ->whereDate('tanggal', Carbon::today()->format('Y-m-d'))
                                        ->get();
                                @endphp

                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($absens->isEmpty())
                                            <a href="/presensi/data-presensi/isi-presensi/{{ $jadwal->id }}"
                                                class="btn btn-dark btn-sm w-100 mb-2">
                                                <span class="mdi mdi-clipboard-edit-outline"></span> Isi Absensi
                                            </a>
                                        @else
                                            <a href="/presensi/data-presensi/edit/{{ $pertemuanCounts[$jadwal->id] ?? 0 }}/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}"
                                                class="btn btn-warning btn-sm w-100 mb-2">
                                                <span class="mdi mdi-clipboard-edit-outline"></span> Edit Absensi
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-primary dropdown-toggle w-100" type="button"
                                                id="dropdownMenuSizeButton1" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">Rekap Berita Acara Perkuliahan</button>
                                            <div class="dropdown-menu  w-100" aria-labelledby="dropdownMenuSizeButton1">
                                                <li>
                                                    <a href="/presensi/data-presensi/rekap/berita-acara-perkuliahan/1-7/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}"
                                                        class="dropdown-item">
                                                        <span class="mdi mdi-file-document-outline"></span> Pertemuan 1 - 7
                                                    </a>
                                                </li>
                                                @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 8)
                                                    <li>
                                                        <a href="/presensi/data-presensi/rekap/berita-acara-perkuliahan/8-14/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}"
                                                            class="dropdown-item">
                                                            <span class="mdi mdi-file-document-outline"></span> Pertemuan 8 - 14
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item disabled">
                                                            <span class="mdi mdi-file-document-outline"></span> Pertemuan 8 - 14
                                                        </a>
                                                    </li>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-success dropdown-toggle w-100" type="button"
                                                id="dropdownMenuSizeButton2" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">Rekap Presensi</button>
                                            <div class="dropdown-menu  w-100" aria-labelledby="dropdownMenuSizeButton2">
                                                <li>
                                                    <a href="/presensi/data-presensi/rekap/1-7/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}"
                                                        class="dropdown-item">
                                                        <span class="mdi mdi-file-document-outline"></span> Pertemuan 1 - 7
                                                    </a>
                                                </li>
                                                @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 8)
                                                    <li>
                                                        <a href="/presensi/data-presensi/rekap/8-14/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}"
                                                            class="dropdown-item">
                                                            <span class="mdi mdi-file-document-outline"></span> Pertemuan 8 - 14
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item disabled">
                                                            <span class="mdi mdi-file-document-outline"></span> Pertemuan 8 - 14
                                                        </a>
                                                    </li>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
