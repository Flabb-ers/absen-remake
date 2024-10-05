<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="/presensi/dashboard">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#data-master" aria-expanded="false"
                aria-controls="data-master">
                <i class="icon-folder menu-icon"></i>
                <span class="menu-title">Data Master</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="data-master">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-matkul">Data Matkul</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-prodi">Data Prodi</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-semester">Data
                            Semester</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-kelas">Data Kelas</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-tahun-akademik">Tahun
                            Akademik</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-ruangan">Data Ruangan</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-dosen">Dosen</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-kaprodi">Kaprodi</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-wadir">Wakil Direktur</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-direktur">Direktur</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ Request::is('presensi/data-mahasiswa*') ? 'active' : '' }}">
            <a class="nav-link" href="/presensi/data-mahasiswa">
                <i class="mdi mdi-calendar-month-outline menu-icon"></i>
                <span class="menu-title">Mahasiswa</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/presensi/jadwal-mengajar">
                <i class="mdi mdi-calendar-month-outline menu-icon"></i>
                <span class="menu-title">Jadwal Mengajar</span>
            </a>
        </li>
        <li class="nav-item">Dosen</li>
        <li class="nav-item">
            <a class="nav-link" href="/presensi/data-presensi">
                <i class="mdi mdi-clipboard-edit-outline menu-icon"></i>
                <span class="menu-title">Presensi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/presensi/data-kontrak">
                <i class="mdi mdi-clipboard-edit-outline menu-icon"></i>
                <span class="menu-title">Kontrak</span>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <i class="icon-bar-graph menu-icon"></i>
                <span class="menu-title">Charts</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="icon-grid-2 menu-icon"></i>
                <span class="menu-title">Tables</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic
                            table</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false"
                aria-controls="icons">
                <i class="icon-contract menu-icon"></i>
                <span class="menu-title">Icons</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false"
                aria-controls="auth">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html">
                            Register </a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#error" aria-expanded="false"
                aria-controls="error">
                <i class="icon-ban menu-icon"></i>
                <span class="menu-title">Error pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="error">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404
                        </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500
                        </a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../../../docs/documentation.html">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Documentation</span>
            </a>
        </li> --}}
    </ul>
</nav>
