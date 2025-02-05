@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">Pembayaran</span>
                <span class="breadcrumb-item">Diajukan</span>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Program Studi</th>
                                            <th>Semester</th>
                                            <th>Kelas</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pembayarans as $pembayaran)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pembayaran->mahasiswa->nama_lengkap }}</td>
                                                <td>{{ $pembayaran->prodi->nama_prodi }}</td>
                                                <td>Semester {{ $pembayaran->semester->semester }}</td>
                                                <td>{{ $pembayaran->kelas->nama_kelas }}</td>
                                                <td>
                                                    @if (Request::is('presensi/pembayaran/diajukan*'))
                                                        <a href="/presensi/pembayaran/diajukan/{{ $pembayaran->id }}/edit"
                                                            class="btn btn-warning btn-sm">
                                                            <span class="mdi mdi-eye"></span> Lihat
                                                        </a>
                                                    @elseif (Request::is('presensi/pembayaran/disetujui*'))
                                                        <a href="/presensi/pembayaran/disetujui/{{ $pembayaran->id }}/edit"
                                                            class="btn btn-warning btn-sm">
                                                            <span class="mdi mdi-eye"></span> Lihat
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="8">Belum ada pengajuan pembayaran</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
    @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ implode(' ', $errors->all()) }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection
