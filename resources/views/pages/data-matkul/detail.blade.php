@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <a href="/presensi/data/matkul" class="breadcrumb-item">
                    <span id="dataMasterBreadcrumb">Data Mata Kuliah</span>
                </a>
                <span class="breadcrumb-item active">Semester {{ $semester->semester }}</span>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" id="search" class="form-control"
                                        placeholder="Cari Mata Kuliah...">
                                    <button class="btn btn-outline-secondary" type="button" id="clearSearchButton">
                                        <span class="mdi mdi-close"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Nama Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Prodi</th>
                                            <th>Semester</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($matkuls as $matkul)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $matkul->kode }}</td>
                                                <td>{{ $matkul->nama_matkul }}</td>
                                                <td>{{ $matkul->praktek + $matkul->teori }} </td>
                                                <td>{{ $matkul->prodi->nama_prodi }}</td>
                                                <td>Semester {{ $matkul->semester->semester }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="6">Matkul belum ditambahkan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $matkuls->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function clearValidation(formId) {
                $(formId).find('input, select').removeClass('is-invalid');
                $(formId).find('.invalid-feedback').text('');
                $(formId)[0].reset();
            }

            $('#search').on('keyup', function() {
                let searchQuery = $(this).val();

                $.ajax({
                    url: '{{ route('data-matkul.search') }}',
                    method: 'GET',
                    data: {
                        search: searchQuery
                    },
                    success: function(response) {
                        $('tbody').empty();
                        if (response.data.length > 0) {
                            response.data.forEach(function(matkul, index) {
                                $('tbody').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${matkul.kode}</td>
                            <td>${matkul.nama_matkul}</td>
                            <td>${matkul.teori + matkul.praktek}</td>
                            <td>${matkul.prodi.nama_prodi}</td>
                            <td>Semester ${matkul.semester.semester}</td>
                        </tr>
                    `);
                            });

                            updatePagination(response);
                        } else {
                            $('tbody').append(
                                '<tr><td class="text-center" colspan="7">Tidak ada hasil ditemukan</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        console.error('Terjadi kesalahan saat mencari mata kuliah.');
                    }
                });
            });

            //     function updatePagination(response) {
            //         $('#pagination').empty();
            //         for (let i = 1; i <= response.last_page; i++) {
            //             $('#pagination').append(`
        //     <a href="#" class="page-link" data-page="${i}">${i}</a>
        // `);
            //         }
            //     }

            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                let searchQuery = $('#search').val();

                $.ajax({
                    url: '{{ route('data-matkul.search') }}',
                    method: 'GET',
                    data: {
                        search: searchQuery,
                        page: page
                    },
                    success: function(response) {
                        $('tbody').empty();
                        if (response.data.length > 0) {
                            response.data.forEach(function(matkul, index) {
                                $('tbody').append(`
                        <tr>
                            <td>${index + 1 + (response.current_page - 1) * response.per_page}</td>
                            <td>${matkul.kode}</td>
                            <td>${matkul.nama_matkul}</td>
                            <td>${matkul.teori + matkul.praktek}</td>
                            <td>${matkul.prodi.nama_prodi}</td>
                            <td>Semester ${matkul.semester.semester}</td>
                        </tr>
                    `);
                            });

                            updatePagination(response);
                        } else {
                            $('tbody').append(
                                '<tr><td class="text-center" colspan="7">Tidak ada hasil ditemukan</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        console.error('Terjadi kesalahan saat mencari mata kuliah.');
                    }
                });
            });

            function updatePagination(response) {
                $('.pagination').empty();

                $('.pagination').append(`
        <li class="page-item ${response.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="?page=${response.current_page - 1}" aria-label="Previous">
                <span aria-hidden="true">&#8249;</span>
            </a>
        </li>
    `);

                for (let i = 1; i <= response.last_page; i++) {
                    $('.pagination').append(`
            <li class="page-item ${i === response.current_page ? 'active' : ''}">
                <a class="page-link" href="?page=${i}">${i}</a>
            </li>
        `);
                }

                $('.pagination').append(`
        <li class="page-item ${response.current_page === response.last_page ? 'disabled' : ''}">
            <a class="page-link" href="?page=${response.current_page + 1}" aria-label="Next">
                <span aria-hidden="true">&#8250;</span>
            </a>
        </li>
    `);
            }
        });
    </script>
@endsection
