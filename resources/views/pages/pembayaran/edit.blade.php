@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">Pembayaran</span>
                <span class="breadcrumb-item">{{ Request::is('presensi/pembayaran/diajukan*') ? 'Diajukan' : 'Disetujui' }}</span>
                <span class="breadcrumb-item">Lihat</span>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Konfirmasi Pembayaran </h4>
                            <p>Silakan periksa detail pembayaran berikut:</p>

                            <table class="table">
                                <tr>
                                    <td><strong>Nama Pembayar:</strong> {{ $pembayaran->mahasiswa->nama_lengkap }}</td>
                                    <td><strong>NIM:</strong> {{ $pembayaran->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas:</strong> {{ $pembayaran->mahasiswa->kelas->nama_kelas }}</td>
                                    <td><strong>Prodi:</strong> {{ $pembayaran->mahasiswa->kelas->prodi->nama_prodi }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Semester:</strong> Semester
                                        {{ $pembayaran->mahasiswa->kelas->semester->semester }}</td>
                                    <td><strong>Status Pembayaran:</strong>
                                        @if ($pembayaran->status_pembayaran == 1 && $pembayaran->keterangan == 'Sudah')
                                            <span class="badge bg-success">Sudah Lunas</span>
                                        @elseif($pembayaran->keterangan == 'Belum' && $pembayaran->status_pembayaran == 0)
                                            <span class="badge bg-warning">Belum Lunas</span>
                                        @else
                                            <span class="badge bg-info">Belum Diverifikasi</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pengajuan:</strong> {{ $pembayaran->created_at }}</td>
                                    <td><strong>Keterangan:</strong>
                                        {{ $pembayaran->keterangan ?? 'Tidak Ada Keterangan' }}
                                    </td>
                                </tr>
                            </table>

                            <div class="mt-4">
                                <h5>Bukti Pembayaran:</h5>
                                <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                                    class="img-fluid" style="max-width: 300px;">
                            </div>

                            <div class="mt-4">
                                @if (Request::is('presensi/pembayaran/diajukan*'))
                                    <form action="/presensi/pembayaran/diajukan/update/{{ $pembayaran->id }}" method="POST"
                                        class="d-inline" id="verifikasiForm">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_pembayaran" value="1">
                                        <input type="hidden" name="keterangan" value="Sudah">
                                        <button type="button" class="btn btn-success btn-sm" id="verifikasiBtn">
                                            <span class="mdi mdi-check-circle"></span> Verifikasi
                                        </button>
                                    </form>
                                    @if ($pembayaran->keterangan == 'Belum' && $pembayaran->status_pembayaran == 0)
                                        <button type="button" class="btn btn-warning btn-sm" disabled>
                                            <span class="mdi mdi-alert-circle"></span> Belum Lunas
                                        </button>
                                    @elseif(empty($pembayaran->keterangan) && $pembayaran->status_pembayaran == 0)
                                        <form action="/presensi/pembayaran/diajukan/update/{{ $pembayaran->id }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="keterangan" value="Belum">
                                            <input type="hidden" name="status_pembayaran" value="0">
                                            <button type="button" class="btn btn-warning btn-sm" id="belumLunasBtn">
                                                <span class="mdi mdi-alert-circle"></span> Belum Lunas
                                            </button>
                                        </form>
                                    @endif
                                @endif
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

    <script>
        // SweetAlert for Verifikasi Button
        document.getElementById('verifikasiBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin memverifikasi pembayaran ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Verifikasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('verifikasiForm').submit();
                }
            });
        });

        // SweetAlert for Belum Lunas Button
        document.getElementById('belumLunasBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin statusnya belum lunas? Pastikan informasi ini benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Belum Lunas',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.form.submit();
                }
            });
        });
    </script>
@endsection
