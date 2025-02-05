@extends('layouts.main')

@section('container')
    @php
        use Carbon\Carbon;
    @endphp
    <style>
        input[type="checkbox"]:disabled {
    background-color: #e0e0e0;
    border-color: #ccc;
    cursor: not-allowed;
}

input[type="checkbox"]:disabled:checked {
    background-color: #d6d6d6;
    border-color: #bbb; 
}

    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            @if (Auth::guard('admin')->check())
                                <h4>Dashboard Admin - Presensi Hari Ini</h4>
                                <p>Tanggal: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white mb-4">
                                            <div class="card-body">
                                                <h5>Total Mahasiswa</h5>
                                                <h3>{{ $totalMahaiswa }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white mb-4">
                                            <div class="card-body">
                                                <h5>Total Dosen</h5>
                                                <h3>{{ $totalDosen }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-warning text-white mb-4">
                                            <div class="card-body">
                                                <h5>Total Kelas</h5>
                                                <h3>{{ $totalKelas }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card bg-success text-white mb-4">
                                            <div class="card-body">
                                                <h5>Total Mahasiswa Hadir</h5>
                                                <h3>{{ $totalHadir }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-danger text-white mb-4">
                                            <div class="card-body">
                                                <h5>Total Mahasiswa Tidak Hadir</h5>
                                                <h3>{{ $totalTidakHadir }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5>Rekapitulasi Presensi</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Program Studi</th>
                                                <th>Total Mahasiswa Hadir</th>
                                                <th>Total Mahasiswa Tidak Hadir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td>{{ $row['nama_prodi'] }}</td>
                                                    <td>{{ $row['total_hadir'] }}</td>
                                                    <td>{{ $row['total_tidak_hadir'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif(Auth::guard('direktur')->check() || Auth::guard('wakil_direktur')->check())
                                <div class="dashboard">
                                    <h4>Dashboard {{ Auth::guard('direktur')->check() ? 'Direktur' : 'Wakil Direktur' }}
                                    </h4>
                                    <p>Selamat datang, {{ Auth::user()->nama }}!</p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card bg-primary text-white mb-4">
                                                <div class="card-body">
                                                    <h5><i class="mdi mdi-account-multiple"></i> Total Mahasiswa</h5>
                                                    <h3>{{ $totalMahaiswa }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-success text-white mb-4">
                                                <div class="card-body">
                                                    <h5><i class="mdi mdi-account"></i> Total Dosen</h5>
                                                    <h3>{{ $totalDosen }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-warning text-white mb-4">
                                                <div class="card-body">
                                                    <h5><i class="mdi mdi-calendar-check"></i> Persentase Kehadiran</h5>
                                                    <h3>{{ number_format($persentaseKehadiran, 2) }}%</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="bg-light p-4 rounded">
                                                <div class="row">
                                                    <div class="col-md-4 text-center">
                                                        <div class="card-body position-relative">
                                                            <i
                                                                class="mdi mdi-file-document fs-2 text-primary mb-3 position-relative">
                                                                @if ($kontrak > 0)
                                                                    <span
                                                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                                        {{ $kontrak }}
                                                                    </span>
                                                                @endif
                                                            </i>
                                                            <h5>Kontrak</h5>
                                                            <div class="mt-3">
                                                                <a href="/presensi/pengajuan-konfirmasi/rekap-kontrak"
                                                                    class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 text-center">
                                                        <div class="card-body position-relative">
                                                            <i
                                                                class="mdi mdi-calendar-check fs-2 text-success mb-3 position-relative">
                                                                @if ($presensis > 0)
                                                                    <span
                                                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                                        {{ $presensis }}
                                                                    </span>
                                                                @endif
                                                            </i>
                                                            <h5>Presensi</h5>
                                                            <div class="mt-3">
                                                                <a href="/presensi/pengajuan-konfirmasi/rekap-presensi"
                                                                    class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 text-center">
                                                        <div class="card-body position-relative">
                                                            <i
                                                                class="mdi mdi-file-document-edit fs-2 text-warning mb-3 position-relative">
                                                                @if ($resume > 0)
                                                                    <span
                                                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                                        {{ $resume }}
                                                                    </span>
                                                                @endif
                                                            </i>
                                                            <h5>Berita Acara</h5>
                                                            <div class="mt-3">
                                                                <a href="/presensi/pengajuan-konfirmasi/rekap-berita"
                                                                    class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(Auth::guard('dosen')->check())
                                <h4>Dashboard Dosen</h4>
                                <p>Selamat datang, {{ Auth::guard('dosen')->user()->nama }}!</p>
                        
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white mb-4">
                                            <div class="card-body">
                                                <h5>Total Kelas</h5>
                                                <h3>{{ $totalKelas }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white mb-4">
                                            <div class="card-body">
                                                <h5>Total Mata Kuliah</h5>
                                                <h3>{{ $totalMatakuliah }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-warning text-white mb-4">
                                            <div class="card-body">
                                                <h5>Presensi Hari Ini</h5>
                                                <h3>{{ $totalPresensiHariIni }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                                <h5>Jadwal Mengajar Hari Ini</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <th>Mata Kuliah</th>
                                                    <th>Ruangan</th>
                                                    <th>Jam</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($jadwalsDosenHariIni as $jadwal)
                                                    @php
                                                        $now = Carbon::now();
                                                        $mulai = Carbon::createFromFormat('H:i:s', $jadwal->waktu_mulai);
                                                        $selesai = Carbon::createFromFormat('H:i:s', $jadwal->waktu_selesai);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                                        <td>{{ $jadwal->matkul->nama_matkul }}</td>
                                                        <td>{{ $jadwal->ruangan->nama }}</td>
                                                        <td>{{ $mulai->format('H:i') }} - {{ $selesai->format('H:i') }}</td>
                                                        <td>
                                                            @if ($now->lessThan($mulai))
                                                                <span class="badge badge-warning">Belum Mulai</span>
                                                            @elseif ($now->between($mulai, $selesai))
                                                                <span class="badge badge-success">Berlangsung</span>
                                                            @else
                                                                <span class="badge badge-danger">Selesai</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="5">Tidak Ada Jadwal Mengajar Hari Ini</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        
                                <!-- Informasi Tambahan -->
                                <div class="mt-4">
                                    <h5>Informasi Tambahan</h5>
                                    <p>Pastikan untuk memeriksa jadwal mengajar Anda secara rutin.</p>
                                </div>
                                    
                                @elseif(Auth::guard('mahasiswa')->check())
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <div class="stats-card bg-primary text-white p-3 rounded">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5 class="mb-2">Total Kehadiran</h5>
                                                            <h3>{{ $totalKehadiran }}</h3>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <div class="stats-card bg-success text-white p-3 rounded">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5 class="mb-2">Total Matakuliah</h5>
                                                            <h3>{{ $totalMatakuliah }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <div class="stats-card bg-warning text-white p-3 rounded">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h5 class="mb-2">Status KRS</h5>
                                                            @if (auth()->user()->status_krs == 1)
                                                                <h3>Sudah</h3>
                                                            @else
                                                                <h3>Belum</h3>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="mt-3">Jadwal Kuliah Hari Ini</h5>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Kelas</th>
                                                                <th>Mata Kuliah</th>
                                                                <th>Ruangan</th>
                                                                <th>Jam</th>
                                                                <th>Status Kehadiran</th>
                                                                <th>Presensi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($jadwalsMahasiswa as $jadwal)
                                                                @php
                                                                    $now = Carbon::now();
                                                                    $mulai = Carbon::createFromFormat(
                                                                        'H:i:s',
                                                                        $jadwal->waktu_mulai,
                                                                    );
                                                                    $selesai = Carbon::createFromFormat(
                                                                        'H:i:s',
                                                                        $jadwal->waktu_selesai,
                                                                    );
                                                                    $absen = $absensHariIni
                                                                        ->where('jadwals_id', $jadwal->id)
                                                                        ->first();
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                                                    <td>{{ $jadwal->matkul->nama_matkul }}</td>
                                                                    <td>{{ $jadwal->ruangan->nama }}</td>
                                                                    <td>{{ $mulai->format('H:i') }} -
                                                                        {{ $selesai->format('H:i') }}</td>
                                                                    <td>
                                                                        @if ($now->lessThan($mulai))
                                                                            <span class="badge badge-warning">Belum
                                                                                Mulai</span>
                                                                        @elseif ($now->between($mulai, $selesai))
                                                                            <span
                                                                                class="badge badge-success">Berlangsung</span>
                                                                        @else
                                                                            <span class="badge badge-danger">Selesai</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="form-check form-check-primary">
                                                                            <label class="form-check-label">
                                                                                <input type="checkbox"
                                                                                    {{ $absen ? 'checked' : '' }} disabled>
                                                                                    <i class="input-helper"></i>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr class="text-center">
                                                                    <td colspan="6">Belum Ada Jadwal</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(Auth::guard('kaprodi')->check())
                                    <h4>Dashboard Kaprodi</h4>
                                    <p>Selamat Datang, {{ auth()->user()->nama }}</p>
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <div class="card bg-primary text-white mb-4">
                                                <div class="card-body">
                                                    <h5>Total Mahasiswa {{ $prodi->nama_prodi }}</h5>
                                                    <h3>{{ $mahasiswa }}</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center">
                                            <div class="card-body position-relative">
                                                <i class="mdi mdi-file-document fs-2 text-primary mb-3 position-relative">
                                                    @if ($kontrak > 0)
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                            {{ $kontrak }}
                                                        </span>
                                                    @endif
                                                </i>
                                                <h5>Kontrak</h5>
                                                <div class="mt-3">
                                                    <button class="btn btn-outline-primary btn-sm">Lihat
                                                        Detail</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center">
                                            <div class="card-body position-relative">
                                                <i class="mdi mdi-calendar-check fs-2 text-success mb-3 position-relative">
                                                    @if ($presensis > 0)
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                            {{ $presensis }}
                                                        </span>
                                                    @endif
                                                </i>
                                                <h5>Presensi</h5>
                                                <div class="mt-3">
                                                    <button class="btn btn-outline-primary btn-sm">Lihat
                                                        Detail</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center">
                                            <div class="card-body position-relative">
                                                <i
                                                    class="mdi mdi-file-document-edit fs-2 text-warning mb-3 position-relative">
                                                    @if ($resume > 0)
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                            {{ $resume }}
                                                        </span>
                                                    @endif
                                                </i>
                                                <h5>Berita Acara</h5>
                                                <div class="mt-3">
                                                    <button class="btn btn-outline-primary btn-sm">Lihat
                                                        Detail</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5>Jadwal Hari Ini</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Kelas</th>
                                                    <th>Mata Kuliah</th>
                                                    <th>Ruangan</th>
                                                    <th>Jam</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($jadwals as $jadwal)
                                                    @php
                                                        $now = Carbon::now();
                                                        $mulai = Carbon::createFromFormat(
                                                            'H:i:s',
                                                            $jadwal->waktu_mulai,
                                                        );
                                                        $selesai = Carbon::createFromFormat(
                                                            'H:i:s',
                                                            $jadwal->waktu_selesai,
                                                        );
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                                        <td>{{ $jadwal->matkul->nama_matkul }}</td>
                                                        <td>{{ $jadwal->ruangan->nama }}</td>
                                                        <td>{{ $mulai->format('H:i') }} -
                                                            {{ $selesai->format('H:i') }}
                                                        </td>
                                                        <td>
                                                            @if ($now->lessThan($mulai))
                                                                <span class="badge badge-warning">Belum</span>
                                                            @elseif ($now->between($mulai, $selesai))
                                                                <span class="badge badge-success">Berlangsung</span>
                                                            @else
                                                                <span class="badge badge-danger">Selesai</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="5">Belum Ada jadwal</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>Anda harus login terlebih dahulu.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::check() && Auth::user()->is_first_login)
        <div class="modal fade" id="firstLoginModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-sm">
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="mb-2 text-primary">Atur Password Baru</h4>
                            <p class="text-muted">Silakan atur password untuk login pertama kali</p>
                        </div>
                        <form id="firstLoginForm" method="POST" action="{{ route('first.login') }}">
                            @csrf
                            <div class="mb-3 position-relative">
                                <label class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" required
                                        placeholder="Masukkan password baru">
                                    <span class="input-group-text toggle-password" data-target="password">
                                        <i class="mdi mdi-eye-off-outline" id="togglePassword"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4 position-relative">
                                <label class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" required placeholder="Ulangi password baru">
                                    <span class="input-group-text toggle-password" data-target="password_confirmation">
                                        <i class="mdi mdi-eye-off-outline" id="togglePasswordConfirmation"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Simpan Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style>
            #firstLoginModal .modal-dialog {
                max-width: 400px;
            }

            #firstLoginModal .modal-content {
                border-radius: 10px;
            }

            #firstLoginModal .btn-primary {
                padding: 10px;
            }

            .toggle-password {
                cursor: pointer;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var firstLoginModal = new bootstrap.Modal(document.getElementById('firstLoginModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                firstLoginModal.show();

                document.querySelectorAll('.toggle-password').forEach(function(element) {
                    element.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const passwordInput = document.getElementById(targetId);
                        const icon = this.querySelector('i');
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            icon.classList.remove('mdi-eye-off-outline');
                            icon.classList.add('mdi-eye-outline');
                        } else {
                            passwordInput.type = 'password';
                            icon.classList.remove('mdi-eye-outline');
                            icon.classList.add('mdi-eye-off-outline');
                        }
                    });
                });

                document.getElementById('firstLoginForm').addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('password_confirmation').value;

                    if (password !== confirmPassword) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Konfirmasi password tidak sesuai!'
                        });
                        return false;
                    }

                    if (password.length < 8) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Password minimal 8 karakter!'
                        });
                        return false;
                    }
                });
            });
        </script>
    @endif
@endsection
