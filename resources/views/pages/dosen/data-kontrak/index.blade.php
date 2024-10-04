@extends('layouts.main')

@php
    use App\Models\Absen;
    use App\Models\Kontrak;
    use Carbon\Carbon;
@endphp

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                @forelse ($jadwals as $jadwal)
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
                                    $latestAbsen = Absen::where('jadwals_id', $jadwal->id)
                                        ->where('kelas_id', $jadwal->kelas->id)
                                        ->orderBy('pertemuan', 'desc')
                                        ->first();

                                    $latestKontrak = Kontrak::where('jadwals_id', $jadwal->id)
                                        ->where('kelas_id', $jadwal->kelas->id)
                                        ->orderBy('pertemuan', 'desc')
                                        ->first();

                                    $latestAbsenPertemuan = $latestAbsen ? $latestAbsen->pertemuan : 0;
                                    $latestKontrakPertemuan = $latestKontrak ? $latestKontrak->pertemuan : 0;
                                @endphp

                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($latestAbsenPertemuan > 0)
                                            @if ($latestAbsenPertemuan == $latestKontrakPertemuan)
                                                <a href="/presensi/data-kontrak/{{ $latestKontrak->id }}/edit"
                                                    class="btn btn-warning btn-sm w-100 mb-2">
                                                    <span class="mdi mdi-clipboard-edit-outline"></span> Edit Kontrak
                                                    Pertemuan {{ $latestKontrakPertemuan }}
                                                </a>
                                            @elseif($latestAbsenPertemuan > $latestKontrakPertemuan)
                                                <a href="/presensi/data-kontrak/isi-kontrak/{{ $jadwal->id }}"
                                                    class="btn btn-dark btn-sm w-100 mb-2">
                                                    <span class="mdi mdi-clipboard-edit-outline"></span> Tambah Kontrak
                                                    Pertemuan {{ $latestAbsenPertemuan }}
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="/presensi/data-kontrak/rekap/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}/{{ $jadwal->id }}"
                                            class="btn btn-success btn-sm w-100 mb-2">
                                            <span class="mdi mdi-file-document"></span> Rekap Kontrak
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
                        <p class="text-center">Belum Ada Kontrak 🎉🎉....</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
