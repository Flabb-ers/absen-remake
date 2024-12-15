@extends('layouts.main')

@php
    use App\Models\Absen;
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
                <span class="breadcrumb-item" id="dataMasterBreadcrumb">Presensi</span>
            </div>
            <div class="row">
                @forelse ($jadwals as $jadwal)
                    <div class="col-lg-4 col-md-6 col-sm-12 grid-margin stretch-card">
                        <div class="card hover-effect text-bg-light mb-3">
                            <div class="card-header"> [PRESENSI] {{ $jadwal->matkul->nama_matkul }} </div>
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
                                        @if (($pertemuanCounts[$jadwal->id] ?? 0) < 14)
                                            @if ($absens->isEmpty())
                                                <a href="/presensi/data-presensi/isi-presensi/{{ $jadwal->id }}"
                                                    class="btn btn-dark btn-sm w-100 mb-2">
                                                    <span class="mdi mdi-clipboard-edit-outline"></span> Isi Presensi
                                                </a>
                                            @else
                                                <a href="/presensi/data-presensi/edit/{{ $pertemuanCounts[$jadwal->id] ?? 0 }}/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}/{{ $jadwal->id }}"
                                                    class="btn btn-warning btn-sm w-100 mb-2">
                                                    <span class="mdi mdi-clipboard-edit-outline"></span> Edit Presensi
                                                </a>
                                            @endif
                                        @else
                                            <button class="btn btn-secondary btn-sm w-100 mb-2" disabled>
                                                <span class="mdi mdi-clipboard-edit-outline"></span> Presensi Sudah Selesai
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 7)
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-success dropdown-toggle w-100" type="button"
                                                    id="dropdownMenuSizeButton2" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    Rekap Presensi
                                                </button>
                                                <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuSizeButton2">

                                                    @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 7)
                                                        @php
                                                            if (isset($pengajuan[$jadwal->id])) {
                                                                $rekap_1_7 = $pengajuan[$jadwal->id]
                                                                    ->where('pertemuan', '1-7')
                                                                    ->first();
                                                                $rekap_1_7_approved =
                                                                    $rekap_1_7 && $rekap_1_7->status == 1;
                                                                $rekap_1_7_pending =
                                                                    $rekap_1_7 && $rekap_1_7->status == 0;
                                                            } else {
                                                                $rekap_1_7 = null;
                                                                $rekap_1_7_approved = false;
                                                                $rekap_1_7_pending = false;
                                                            }
                                                        @endphp

                                                        @if ($rekap_1_7_approved)
                                                            <li>
                                                                <a href="/presensi/data-presensi/rekap/1-7/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}/{{ $jadwal->id }}"
                                                                    class="dropdown-item text-center text-success">
                                                                    <span class="mdi mdi-file-document-outline"></span>
                                                                    Rekap 1 - 7 (
                                                                    Approved)
                                                                </a>
                                                            </li>
                                                        @elseif($rekap_1_7_pending)
                                                            <li>
                                                                <span class="dropdown-item text-center text-warning">
                                                                    <span class="mdi mdi-clock-outline"></span>
                                                                    Rekap 1 - 7 (Pending)
                                                                </span>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <form action="/presensi/pengajuan-konfirmasi/rekap-presensi"
                                                                    method="POST" class="dropdown-item text-center">
                                                                    @csrf
                                                                    <input type="hidden" name="matkul_id"
                                                                        value="{{ $jadwal->matkul->id }}">
                                                                    <input type="hidden" name="kelas_id"
                                                                        value="{{ $jadwal->kelas->id }}">
                                                                    <input type="hidden" name="jadwal_id"
                                                                        value="{{ $jadwal->id }}">
                                                                    <input type="hidden" name="rentang" value="1-7">
                                                                    <button type="submit"
                                                                        class="btn btn-link p-0 text-decoration-none text-info">
                                                                        <span class="mdi mdi-send"></span>
                                                                        Ajukan Rekap 1 - 7
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endif

                                                    {{-- Rekap 8-14 --}}
                                                    @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 14)
                                                        @php
                                                            if (isset($pengajuan[$jadwal->id])) {
                                                                $rekap_8_14 = $pengajuan[$jadwal->id]
                                                                    ->where('pertemuan', '8-14')
                                                                    ->first();
                                                                $rekap_8_14_approved =
                                                                    $rekap_8_14 && $rekap_8_14->status == 1;
                                                                $rekap_8_14_pending =
                                                                    $rekap_8_14 && $rekap_8_14->status == 0;
                                                            } else {
                                                                $rekap_8_14 = null;
                                                                $rekap_8_14_approved = false;
                                                                $rekap_8_14_pending = false;
                                                            }
                                                        @endphp

                                                        @if ($rekap_8_14_approved)
                                                            <li>
                                                                <a href="/presensi/data-presensi/rekap/8-14/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}/{{ $jadwal->id }}"
                                                                    class="dropdown-item text-center text-success">
                                                                    <span class="mdi mdi-file-document-outline"></span>
                                                                    Rekap 8 - 14 (Approved)
                                                                </a>
                                                            </li>
                                                        @elseif($rekap_8_14_pending)
                                                            <li>
                                                                <span class="dropdown-item text-center text-warning">
                                                                    <span class="mdi mdi-clock-outline"></span>
                                                                    Rekap 8 - 14 (Pending)
                                                                </span>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <form action="/presensi/pengajuan-konfirmasi/rekap-presensi"
                                                                    method="POST" class="dropdown-item text-center">
                                                                    @csrf
                                                                    <input type="hidden" name="matkul_id"
                                                                        value="{{ $jadwal->matkul->id }}">
                                                                    <input type="hidden" name="kelas_id"
                                                                        value="{{ $jadwal->kelas->id }}">
                                                                    <input type="hidden" name="jadwal_id"
                                                                        value="{{ $jadwal->id }}">
                                                                    <input type="hidden" name="rentang" value="8-14">
                                                                    <button type="submit"
                                                                        class="btn btn-link p-0 text-decoration-none text-info">
                                                                        <span class="mdi mdi-send"></span>
                                                                        Ajukan Rekap 8 - 14
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 7)
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle w-100" type="button"
                                                    id="dropdownMenuSizeButton1" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">Rekap Berita Acara
                                                    Perkuliahan</button>
                                                <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuSizeButton1">
                                                    @php
                                                        // Cek apakah ada data berita untuk jadwal ini
                                                        $berita_1_7 = isset($berita[$jadwal->id])
                                                            ? $berita[$jadwal->id]->where('pertemuan', '1-7')->first()
                                                            : null;
                                                        $berita_1_7_approved = $berita_1_7 && $berita_1_7->status == 1;
                                                        $berita_1_7_pending = $berita_1_7 && $berita_1_7->status == 0;

                                                        $berita_8_14 = isset($berita[$jadwal->id])
                                                            ? $berita[$jadwal->id]->where('pertemuan', '8-14')->first()
                                                            : null;
                                                        $berita_8_14_approved =
                                                            $berita_8_14 && $berita_8_14->status == 1;
                                                        $berita_8_14_pending =
                                                            $berita_8_14 && $berita_8_14->status == 0;
                                                    @endphp

                                                    <!-- Rekap 1 - 7 -->
                                                    @if ($berita_1_7_approved)
                                                        <li>
                                                            <a href="/presensi/data-presensi/rekap/berita-acara-perkuliahan/1-7/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}/{{ $jadwal->id }}"
                                                                class="dropdown-item text-center text-success">
                                                                <span class="mdi mdi-file-document-outline"></span>
                                                                Rekap 1 - 7 (Approved)
                                                            </a>
                                                        </li>
                                                    @elseif($berita_1_7_pending)
                                                        <li>
                                                            <span class="dropdown-item text-center text-warning">
                                                                <span class="mdi mdi-clock-outline"></span>
                                                                Rekap 1 - 7 (Pending)
                                                            </span>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form action="/presensi/pengajuan-konfirmasi/rekap-berita"
                                                                method="POST" class="dropdown-item text-center">
                                                                @csrf
                                                                <input type="hidden" name="matkul_id"
                                                                    value="{{ $jadwal->matkul->id }}">
                                                                <input type="hidden" name="kelas_id"
                                                                    value="{{ $jadwal->kelas->id }}">
                                                                <input type="hidden" name="jadwal_id"
                                                                    value="{{ $jadwal->id }}">
                                                                <input type="hidden" name="rentang" value="1-7">
                                                                <input type="hidden" name="dosen_id"
                                                                    value="{{ $jadwal->dosen->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-link p-0 text-decoration-none text-info">
                                                                    <span class="mdi mdi-send"></span>
                                                                    Ajukan Rekap 1 - 7
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    <!-- Rekap 8 - 14 -->
                                                    @if ($berita_8_14_approved)
                                                        <li>
                                                            <a href="/presensi/data-presensi/rekap/berita-acara-perkuliahan/8-14/{{ $jadwal->matkul->id }}/{{ $jadwal->kelas->id }}/{{ $jadwal->id }}"
                                                                class="dropdown-item text-center text-success">
                                                                <span class="mdi mdi-file-document-outline"></span>
                                                                Rekap 8 - 14 (Approved)
                                                            </a>
                                                        </li>
                                                    @elseif($berita_8_14_pending)
                                                        <li>
                                                            <span class="dropdown-item text-center text-warning">
                                                                <span class="mdi mdi-clock-outline"></span>
                                                                Rekap 8 - 14 (Pending)
                                                            </span>
                                                        </li>
                                                    @else
                                                        <!-- Cek apakah jumlah pertemuan sudah mencapai 14 sebelum menampilkan tombol ajukan -->
                                                        @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 14)
                                                            <li>
                                                                <form action="/presensi/pengajuan-konfirmasi/rekap-berita"
                                                                    method="POST" class="dropdown-item text-center">
                                                                    @csrf
                                                                    <input type="hidden" name="matkul_id"
                                                                        value="{{ $jadwal->matkul->id }}">
                                                                    <input type="hidden" name="kelas_id"
                                                                        value="{{ $jadwal->kelas->id }}">
                                                                    <input type="hidden" name="jadwal_id"
                                                                        value="{{ $jadwal->id }}">
                                                                    <input type="hidden" name="rentang" value="8-14">
                                                                    <input type="hidden" name="dosen_id"
                                                                        value="{{ $jadwal->dosen->id }}">
                                                                    <button type="submit"
                                                                        class="btn btn-link p-0 text-decoration-none text-info">
                                                                        <span class="mdi mdi-send"></span>
                                                                        Ajukan Rekap 8 - 14
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
                        <p class="text-center">Belum Ada Jadwal 🎉🎉....</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                }).then(() => {
                    location.reload();
                });
            });
        </script>
    @endif

@endsection
