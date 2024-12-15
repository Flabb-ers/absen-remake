@extends('layouts.main')

@php
    use App\Models\Absen;
    use App\Models\Kontrak;
    use App\Models\PengajuanRekapKontrak;
    use Carbon\Carbon;
@endphp

@section('container')
    <style>
        .hover-effect {
            transition: transform 0.3s ease;
        }

        .hover-effect:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Kontrak</span>
            </div>
            <div class="row">
                @forelse ($jadwals as $jadwal)
                    <div class="col-lg-4 col-md-6 col-sm-12 grid-margin stretch-card">
                        <div class="card hover-effect text-bg-light mb-3">
                            <div class="card-header">[KONTRAK] {{ $jadwal->matkul->nama_matkul }}</div>
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
                                    $rekapStatus = PengajuanRekapKontrak::where('jadwal_id', $jadwal->id)
                                        ->where('kelas_id', $jadwal->kelas->id)
                                        ->where('matkul_id', $jadwal->matkul->id)
                                        ->first();

                                    $latestAbsen = Absen::where('jadwals_id', $jadwal->id)
                                        ->where('kelas_id', $jadwal->kelas->id)
                                        ->orderBy('pertemuan', 'desc')
                                        ->first();

                                    $latestAbsenPertemuan = $latestAbsen ? $latestAbsen->pertemuan : 0;

                                    $latestKontrak = Kontrak::where('jadwals_id', $jadwal->id)
                                        ->where('kelas_id', $jadwal->kelas->id)
                                        ->orderBy('pertemuan', 'desc')
                                        ->first();

                                    $latestKontrakPertemuan = $latestKontrak ? $latestKontrak->pertemuan : 0;
                                    $maxPertemuan = Kontrak::where('jadwals_id', $jadwal->id)->max('pertemuan');
                                    $cekKontrak = Kontrak::where('jadwals_id', $jadwal->id)->first();
                                    $cek = Kontrak::where('jadwals_id', $jadwal->id)->max('pertemuan');
                                @endphp

                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($cek >= 14)
                                            @if (is_null($rekapStatus))
                                                <form action="/presensi/pengajuan-konfirmasi/rekap-kontrak" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                                    <input type="hidden" name="kelas_id" value="{{ $jadwal->kelas->id }}">
                                                    <input type="hidden" name="matkul_id"
                                                        value="{{ $jadwal->matkul->id }}">
                                                    <button type="submit" class="btn btn-info btn-sm w-100 mb-2">
                                                        <span class="mdi mdi-send"></span> Ajukan Rekap Kontrak
                                                    </button>
                                                </form>
                                            @elseif($rekapStatus->status == 0)
                                                <div class="btn btn-warning btn-sm w-100 mb-2">
                                                    <span class="mdi mdi-clock"></span> Pending
                                                </div>
                                            @elseif($rekapStatus->status == 1)
                                                <a href="/presensi/data-kontrak/rekap/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}/{{ $jadwal->id }}"
                                                    class="btn btn-success btn-sm w-100 mb-2">
                                                    <span class="mdi mdi-file-document"></span> Rekap Kontrak (Approved)
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($cekKontrak != null && $cek < 14)
                                            <a href="/presensi/data-kontrak/{{ $jadwal->id }}/edit"
                                                class="btn btn-dark btn-sm w-100 mb-2">
                                                <span class="mdi mdi-clipboard-edit-outline"></span> Isi Kontrak
                                                Perkuliahan
                                            </a>
                                        @elseif($cekKontrak == null && $cek < 14)
                                            <a href="/presensi/data-kontrak/isi-kontrak/{{ $jadwal->id }}"
                                                class="btn btn-dark btn-sm w-100 mb-2">
                                                <span class="mdi mdi-clipboard-edit-outline"></span> Isi Kontrak
                                                Perkuliahan
                                            </a>
                                        @endif
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
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Sukses!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

@endsection
