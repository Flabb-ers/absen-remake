@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center mb-4">EDIT NILAI ETIKA MAHASISWA</h5>
                            <div class="row mb-4">
                                <div class="col-md-5 col-12">
                                    <ul class="list-unstyled">
                                        <li class="d-flex align-items-center">
                                            <span style="width: 140px;">Mata Kuliah</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $etikas->first()->matkul->nama_matkul }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mt-2">
                                            <span style="width: 140px;">Dosen</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $etikas->first()->jadwal->dosen->nama }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-5 offset-md-2 col-12">
                                    <ul class="list-unstyled">
                                        <li class="d-flex align-items-center">
                                            <span style="width: 140px;">Program Studi</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $etikas->first()->kelas->prodi->nama_prodi }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mt-2">
                                            <span style="width: 140px;">Kelas</span>
                                            <span style="margin-right: 5px;">:</span>
                                            <span>{{ $etikas->first()->kelas->nama_kelas }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <form method="POST"
                                    action="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/' . $jadwal_id . '/etika') }}">
                                    @csrf
                                    @method('PUT')
                                    @foreach ($mahasiswas as $mahasiswa)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $mahasiswa->nama_lengkap }}</h6>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <label for="nilai_{{ $mahasiswa->id }}" class="me-2">Nilai:</label>
                                                <input type="number" name="nilai[]" id="nilai_{{ $mahasiswa->id }}"
                                                    value="{{ $etikas->where('mahasiswa_id', $mahasiswa->id)->first()->nilai ?? '' }}"
                                                    min="0" max="100" required class="form-control"
                                                    style="width: 100%;">
                                                <input type="hidden" value="{{ $mahasiswa->id }}" name="mahasiswas_id[]">
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach

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
                    let kelas_id = '{{ session('kelas_id') }}';
                    let matkul_id = '{{ session('matkul_id') }}';
                    let jadwal_id = '{{ session('jadwal_id') }}';
                    let activeTab = '{{ session('tab') }}';
                    window.location.href =
                        `/presensi/data-nilai/${kelas_id}/${matkul_id}/${jadwal_id}/detail?tab=${activeTab}`;
                }
            });
        </script>
    @endif
@endsection
