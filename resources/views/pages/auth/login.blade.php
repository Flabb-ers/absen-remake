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
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="{{ asset('/images/logo.png') }}" alt="logo">
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Login to continue.</h6>
                            
                            <!-- Menampilkan pesan kesalahan -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="pt-3" action="/login" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-md rounded-4"
                                        placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password"
                                        class="form-control form-control-md rounded-4" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <select name="role" class="form-control form-control-lg rounded-4" required>
                                        <option value="" disabled selected>Pilih Level</option>
                                        <option value="admin">Admin</option>
                                        <option value="direktur">Direktur</option>
                                        <option value="wakil_direktur">Wakil Direktur</option>
                                        <option value="kaprodi">Kaprodi</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                    </select>
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
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('/js/off-canvas.js') }}"></script>
    <script src="{{ asset('/js/template.js') }}"></script>
    <script src="{{ asset('/js/settings.js') }}"></script>
    <script src="{{ asset('/js/todolist.js') }}"></script>
    <!-- endinject -->
</body>

</html>
