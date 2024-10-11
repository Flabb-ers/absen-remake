@extends('layouts.main')

@section('container')
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
                                <a class="nav-link active" id="tab-tugas" href="#">Tugas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-uts" href="#">UTS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-uas" href="#">UAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled">Sikap</a>
                            </li>
                        </ul>

                        <div class="card" style="margin-left: 1px;border-radius:0px 15px 15px 15px">
                            <div class="card-body">
                                <div id="tab-content">
                                    {{-- kontrn --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk AJAX -->
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
            let jadwal_id = "{{ $jadwal_id }}"

            loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/tugas`);

            $('#tab-tugas').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/tugas`);
                setActiveTab('#tab-tugas');
            });

            $('#tab-uts').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/uts`);
                setActiveTab('#tab-uts');
            });

            $('#tab-uas').on('click', function(e) {
                e.preventDefault();
                loadTabContent(`/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/uas`);
                setActiveTab('#tab-uas');
            });

            function setActiveTab(activeTabId) {
                $('.nav-link').removeClass('active'); 
                $(activeTabId).addClass('active'); 
            }

            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();

                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data ini akan dihapus dan tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
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
