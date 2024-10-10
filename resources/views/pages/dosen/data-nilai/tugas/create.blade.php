@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center mb-4">NILAI MAHASISWA</h5>
                            <div class="row mb-4">
                                <div class="col-md-5 col-12">
                                    <ul class="list-unstyled">
                                        <li class="d-flex align-items-center">
                                            <span style="width: 140px;">Mata Kuliah</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $matkul->nama_matkul }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mt-2">
                                            <span style="width: 140px;">Dosen</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $jadwal->dosen->nama }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mt-2">
                                            <span style="width: 140px;">Tugas ke</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $nextTugasKe }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-5 offset-md-2 col-12">
                                    <ul class="list-unstyled">
                                        <li class="d-flex align-items-center">
                                            <span style="width: 140px;">Program Studi</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $jadwal->kelas->prodi->nama_prodi }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mt-2">
                                            <span style="width: 140px;">Kelas</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $jadwal->kelas->nama_kelas }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <form method="POST" action="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/tugas') }}">
                                    @csrf
                                    <input type="hidden" name="tugas_ke" id="tugas_ke" value="{{ $nextTugasKe }}">
                                    <input type="hidden" value="{{ $jadwal->id }}" id="jadwal_Id" name="jadwal_id">

                                    @forelse ($mahasiswas as $mahasiswa)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $mahasiswa->nama_lengkap }}</h6>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <label for="nilai_{{ $mahasiswa->id }}" class="me-2">Nilai:</label>
                                                <input type="number" name="nilai[]" id="nilai_{{ $mahasiswa->id }}" min="0" max="100" required class="form-control" style="width: 100%;">
                                                <input type="hidden" value="{{ $mahasiswa->id }}" id="{{ $mahasiswa->id }}" name="mahasiswas_id[]">
                                            </div>
                                        </div>
                                        <hr>
                                    @empty
                                        <p class="text-center fw-bolder my-5">Mahasiswa belum ditambahkan</p>
                                    @endforelse

                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <span class="mdi mdi-content-save"></span> Simpan
                                    </button>
                                </form>
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
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: true,
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/detail') }}";
                }
            });
        </script>
    @endif
@endsection
