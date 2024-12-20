@extends('layouts.main')

@section('container')
<style>@media (max-width: 767.98px) {
    .nav-tabs {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        margin-top: 1px;
    }
    .nav-item {
        flex: 1 1 auto;
    }
}
</style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <span class="breadcrumb-item">{{ $jadwal->kelas->nama_kelas }}</span>
                <a href="/presensi/data-nilai/{{ $jadwal->kelas_id }}" class="breadcrumb-item">
                    Mata Kuliah
                </a>
                <span class="breadcrumb-item">{{ $jadwal->matkul->nama_matkul }}</span>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab', 'tugas') === 'tugas' ? 'active' : '' }}"
                                    id="tab-tugas" href="#">Tugas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab', 'tugas') === 'aktif' ? 'active' : '' }}"
                                    id="tab-aktif" href="#">Keaktifan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab', 'tugas') === 'etika' ? 'active' : '' }}"
                                    id="tab-etika" href="#">Etika</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab', 'tugas') === 'uts' ? 'active' : '' }}"
                                    id="tab-uts" href="#">UTS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab', 'tugas') === 'uas' ? 'active' : '' }}"
                                    id="tab-uas" href="#">UAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->query('tab', 'tugas') === 'rekap' ? 'active' : '' }}"
                                    id="tab-rekap" href="#">Rekap</a>
                            </li>
                        </ul>
                        <div class="card" style="margin-left: 1px; border-radius:0px 0px 15px 15px">
                            <div class="card-body">
                                <div id="tab-content">
                                    {{-- Konten akan dimuat di sini --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadTabContent(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $('#tab-content').html(response);
                    },
                    error: function() {
                        $('#tab-content').html('<p>Terjadi kesalahan saat memuat konten.</p>');
                    }
                });
            }

            let kelas_id = "{{ $kelas_id }}";
            let matkul_id = "{{ $matkul_id }}";
            let jadwal_id = "{{ $jadwal_id }}";

            let activeTab = "{{ request()->query('tab', 'tugas') }}";
            loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/${activeTab}`);

            $('#tab-tugas').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/tugas`);
                history.pushState(null, '', `?tab=tugas`);
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });

            $('#tab-uts').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/uts`);
                history.pushState(null, '', `?tab=uts`);
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });

            $('#tab-uas').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/uas`);
                history.pushState(null, '', `?tab=uas`);
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });

            $('#tab-etika').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/etika`);
                history.pushState(null, '', `?tab=etika`);
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });

            $('#tab-aktif').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/aktif`);
                history.pushState(null, '', `?tab=aktif`);
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });
            $('#tab-rekap').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/rekap`);
                history.pushState(null, '', `?tab=rekap`);
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    confirmButtonText: 'Ok'
                });
            @endif
        });
    </script>
@endsection
