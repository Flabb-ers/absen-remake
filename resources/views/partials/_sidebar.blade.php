<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="/presensi/dashboard">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @if (Auth::guard('admin')->check())
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#data-master" aria-expanded="false"
                    aria-controls="data-master">
                    <i class="mdi mdi-folder menu-icon"></i>
                    <span class="menu-title">Data Master</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="data-master">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-matkul">Data
                                Matkul</a>
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
                        <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-ruangan">Data
                                Ruangan</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-dosen">Dosen</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-kaprodi">Kaprodi</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="/presensi/data-master/data-wadir">Wakil
                                Direktur</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link"
                                href="/presensi/data-master/data-direktur">Direktur</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item {{ Request::is('presensi/data-mahasiswa*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data-mahasiswa">
                    <i class="mdi mdi-account-group menu-icon"></i>
                    <span class="menu-title">Mahasiswa</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/presensi/jadwal-mengajar">
                    <i class="mdi mdi-calendar-month-outline menu-icon"></i>
                    <span class="menu-title">Jadwal Mengajar</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('presensi/data/perkuliahan*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data/perkuliahan">
                    <i class="mdi  mdi-clipboard-check-outline menu-icon"></i>
                    <span class="menu-title">Data Perkuliahan</span>
                </a>
            </li>
            {{-- <li class="nav-item {{ Request::is('presensi/data/resume*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data/resume">
                    <i class="mdi mdi-file-document menu-icon"></i>
                    <span class="menu-title">Data Berita Acara</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('presensi/data/contract*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data/contract">
                    <i class="mdi mdi-paperclip menu-icon"></i>
                    <span class="menu-title">Data Kontrak</span>
                </a>
            </li> --}}
            <li class="nav-item {{ Request::is('presensi/data/value*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data/value">
                    <i class="mdi  mdi-star-outline menu-icon"></i>
                    <span class="menu-title">Data Nilai</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#rekap-nilai" aria-expanded="false"
                    aria-controls="rekap-nilai">
                    <i class="icon-folder menu-icon"></i>
                    <span class="menu-title">Rekap Nilai</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="rekap-nilai">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link"
                                href="/presensi/data-nilai/pengajuan/rekap-nilai">Diajukan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/presensi/data-nilai/pengajuan/nilai-disetuju">
                                Disetujui
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#pembayaran" aria-expanded="false"
                    aria-controls="pembayaran">
                    <i class="mdi mdi-receipt-text-check-outline menu-icon"></i>
                    <span class="menu-title">Pembayaran</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pembayaran">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="/presensi/pembayaran/diajukan">Diajukan</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="/presensi/pembayaran/disetujui">Disetujui</a>
                        </li>
                    </ul>
                </div>
            </li>
            </li>
            <li class="nav-item {{ Request::is('presensi/krs/*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/krs/kategori">
                    <i class="mdi  mdi-card-bulleted-settings menu-icon"></i>
                    <span class="menu-title">KRS</span>
                </a>
            </li>
        @elseif(Auth::guard('dosen')->check())
            <li class="nav-item {{ Request::is('presensi/data-presensi*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data-presensi">
                    <i class="mdi mdi-clipboard-edit-outline menu-icon"></i>
                    <span class="menu-title">Presensi</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('presensi/data-kontrak*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data-kontrak">
                    <i class="mdi mdi-clipboard-edit-outline menu-icon"></i>
                    <span class="menu-title">Kontrak</span>
                </a>
            </li>
            <li
                class="nav-item {{ Request::is('presensi/data-nilai/*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#nilai" aria-expanded="false"
                    aria-controls="data-nilai">
                    <i class="mdi mdi-folder menu-icon"></i>
                    <span class="menu-title">Nilai</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse {{ Request::is('presensi/data-nilai/*') ? 'show' : '' }}"
                    id="nilai">
                    <ul class="nav flex-column sub-menu">
                        @if ($kelasAll->isNotEmpty())
                            @foreach ($kelasAll->unique('kelas_id') as $kelas)
                                @if (isset($kelas->kelas))
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('presensi/data-nilai/' . $kelas->kelas->id.'/*') ? 'active' : '' }}" id="sidebar-kelas-{{ $kelas->kelas->id }}"
                                            href="/presensi/data-nilai/{{ $kelas->kelas->id }}">
                                            {{ $kelas->kelas->nama_kelas }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            <a class="nav-link disabled" href="#">Belum ada jadwal</a>
                        @endif
                    </ul>
                </div>
            </li>
            @if (Session::get('user.status_pa') == 1)
                <li class="nav-item {{ Request::is('presensi/krs*') ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#pengajuan-rekap" aria-expanded="false"
                        aria-controls="pengajuan-rekap">
                        <i class="mdi mdi-card-bulleted-settings menu-icon"></i>
                        <span class="menu-title">KRS</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse {{ Request::is('presensi/krs*') ? 'show' : '' }}" id="pengajuan-rekap">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a
                                    class="nav-link  {{ Request::is('presensi/krs/diajukan*') ? 'active' : '' }}"
                                    href="/presensi/krs/diajukan">Diajukan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('presensi/krs/disetujui*') ? 'active' : '' }}"
                                    href="/presensi/krs/disetujui">Disetujui</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
        @elseif(auth::guard('wakil_direktur')->check() || auth::guard('kaprodi')->check() || auth::guard('direktur')->check())
            @if (Auth::guard('kaprodi')->check())
                </li>
                <li class="nav-item {{ Request::is('presensi/data/matkul*') ? 'active' : '' }}">
                    <a class="nav-link" href="/presensi/data/matkul">
                        <i class="mdi mdi-book menu-icon"></i>
                        <span class="menu-title">Data Matkul</span>
                    </a>
                </li>
            @endif
            <li class="nav-item {{ Request::is('presensi/data/perkuliahan*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data/perkuliahan">
                    <i class="mdi  mdi-clipboard-check-outline menu-icon"></i>
                    <span class="menu-title">Data Perkuliahan</span>
                </a>
            </li>
            {{-- <li class="nav-item {{ Request::is('presensi/data/resume*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data/resume">
                    <i class="mdi mdi-file-document menu-icon"></i>
                    <span class="menu-title">Data Berita Acara</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('presensi/data/contract*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data/contract">
                    <i class="mdi mdi-paperclip menu-icon"></i>
                    <span class="menu-title">Data Kontrak</span>
                </a>
            </li> --}}
            <li class="nav-item {{ Request::is('presensi/data/value*') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/data/value">
                    <i class="mdi  mdi-star-outline menu-icon"></i>
                    <span class="menu-title">Data Nilai</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#pengajuan-rekap" aria-expanded="false"
                    aria-controls="pengajuan-rekap">
                    <i class="icon-folder menu-icon"></i>
                    <span class="menu-title">Rekap Presensi</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pengajuan-rekap">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link"
                                href="/presensi/pengajuan-konfirmasi/rekap-presensi">Diajukan</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link"
                                href="/presensi/pengajuan-konfirmasi/presensi-disetujui">Disetujui</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#pengajuan-rekap-berita" aria-expanded="false"
                    aria-controls="pengajuan-rekap-berita">
                    <i class="icon-folder menu-icon"></i>
                    <span class="menu-title">Rekap Berita Acara</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pengajuan-rekap-berita">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link"
                                href="/presensi/pengajuan-konfirmasi/rekap-berita">Diajukan</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link"
                                href="/presensi/pengajuan-konfirmasi/berita-disetujui">Disetujui</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#pengajuan-rekap-kontrak" aria-expanded="false"
                    aria-controls="pengajuan-rekap-kontrak">
                    <i class="icon-folder menu-icon"></i>
                    <span class="menu-title">Rekap Kontrak Kuliah</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pengajuan-rekap-kontrak">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link"
                                href="/presensi/pengajuan-konfirmasi/rekap-kontrak">Diajukan</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link"
                                href="/presensi/pengajuan-konfirmasi/kontrak-disetujui">Disetujui</a>
                        </li>
                    </ul>
                </div>
            </li>
        @elseif(Auth::guard('mahasiswa')->check())
            <li class="nav-item {{ Request::is('presensi/mahasiswa/nilai') ? 'active' : '' }}">
                <a class="nav-link" href="/presensi/mahasiswa/nilai">
                    <i class="mdi mdi-clipboard-edit-outline menu-icon"></i>
                    <span class="menu-title">Nilai</span>
                </a>
            </li>

            <li
                class="nav-item {{ Request::is('presensi/mahasiswa/nilai/*') && !Request::is('presensi/mahasiswa/nilai') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#riwayat" aria-expanded="false"
                    aria-controls="riwayat">
                    <i class="icon-folder menu-icon"></i>
                    <span class="menu-title">Riwayat Nilai</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="riwayat">
                    <ul class="nav flex-column sub-menu">
                        @forelse ($semesters as $semester)
                            <li
                                class="nav-item {{ Request::is('presensi/mahasiswa/riwayat/' . $semester->semester->id) ? 'active' : '' }}">
                                <a class="nav-link" href="/presensi/mahasiswa/riwayat/{{ $semester->semester->id }}">
                                    Semester {{ $semester->semester->semester }}
                                </a>
                            </li>
                        @empty
                            <li class="nav-link">Belum ada riwayat</li>
                        @endforelse
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/presensi/mahasiswa/krs_pembayaran">
                    <i class="mdi mdi-receipt-text-check-outline menu-icon"></i>
                    <span class="menu-title">KRS & Pembayaran</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
