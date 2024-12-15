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
    {{-- @if (Auth::check() && Auth::user()->is_first_login)
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
    @endif --}}
@endsection
