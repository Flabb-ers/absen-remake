<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendors/mdi/css/materialdesignicons.min.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('/images/logomini.png') }}" />
    <style>
        .form-control {
            color: black !important;
        }

        select.form-control {
            color: black !important;
        }

        select.form-control option {
            color: black !important;
        }
    </style>
    <style>
        .error-container {
            position: relative;
            animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
            transform: translate3d(0, 0, 0);
            backface-visibility: hidden;
            perspective: 1000px;
        }

        @keyframes shake {

            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
        }

        .error-alert {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .success-container {
            margin-bottom: 15px;
        }

        .success-alert {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            padding: 10px 15px;
            position: relative;
        }

        .success-alert strong {
            display: block;
            margin-bottom: 5px;
        }

        .success-alert i {
            margin-right: 8px;
        }

        form-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5 shadow-lg rounded-4">
                            <div class="brand-logo d-flex justify-content-center">
                                <img src="{{ asset('images/logo1.png') }}" alt="logo">
                            </div>

                            @if ($errors->any())
                                <div class="error-container">
                                    <div class="error-alert">
                                        <strong>
                                            <i class="mdi mdi-alert-circle text-danger me-2"></i>
                                            Login Gagal!
                                        </strong>
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="success-container">
                                    <div class="success-alert">
                                        <strong>
                                            <i class="mdi mdi-check-circle text-success me-2"></i>
                                            Berhasil!
                                        </strong>
                                        <p class="mb-0">{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif
                            <form class="pt-3" action="/login" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-md rounded-4"
                                        placeholder="Username or Email" value="{{ old('username') }}" required>
                                </div>
                                <div class="form-group position-relative">
                                    <input type="password" name="password"
                                        class="form-control form-control-md rounded-4" id="passwordInput"
                                        placeholder="Password" required>
                                    <span class="toggle-password" data-target="passwordInput">
                                        <i class="mdi mdi-eye-off-outline"></i>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <select name="role" class="form-control form-control-lg rounded-4" required>
                                            <option value="" {{ old('role') ? '' : 'selected' }} disabled>Pilih
                                                Level</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                            <option value="direktur" {{ old('role') == 'direktur' ? 'selected' : '' }}>
                                                Direktur</option>
                                            <option value="wakil_direktur"
                                                {{ old('role') == 'wakil_direktur' ? 'selected' : '' }}>Wakil Direktur
                                            </option>
                                            <option value="kaprodi" {{ old('role') == 'kaprodi' ? 'selected' : '' }}>
                                                Kaprodi</option>
                                            <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen
                                            </option>
                                            <option value="mahasiswa"
                                                {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-3 d-grid gap-2">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                        LOGIN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('vendors/js/jquery-3.6.0.min.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('/js/off-canvas.js') }}"></script>
    <script src="{{ asset('/js/template.js') }}"></script>
    <script src="{{ asset('/js/settings.js') }}"></script>
    <script src="{{ asset('/js/todolist.js') }}"></script>
    <!-- endinject -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>

</html>
