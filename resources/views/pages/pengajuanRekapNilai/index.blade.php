@extends('layouts.main')

@section('container')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="breadcrumb">
            <a href="/presensi/dashboard" class="breadcrumb-item">
                <span class="mdi mdi-home"></span> Dashboard
            </a>
            <span class="breadcrumb-item">Rekap Nilai</span>
            <span class="breadcrumb-item">Diajukan</span>
        </div> 
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tahun Akademik</th>
                                        <th>Kelas</th>
                                        <th>Program Studi</th>
                                        <th>Dosen</th>
                                        <th>Mata Kuliah</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuans as $pengajuan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pengajuan->tahun }}</td>
                                            <td>{{ $pengajuan->kelas->nama_kelas }}</td>
                                            <td>{{ $pengajuan->kelas->prodi->nama_prodi }}</td>
                                            <td>{{ $pengajuan->jadwal->dosen->nama }}</td>
                                            <td>{{ $pengajuan->matkul->nama_matkul }}</td>
                                            <td><a href="/presensi/data-nilai/pengajuan/rekap-nilai/{{ $pengajuan->kelas_id }}/{{$pengajuan->matkul_id}}/{{ $pengajuan->jadwal_id }}" class="btn btn-warning btn-sm"><span class="mdi mdi-eye"></span> Lihat</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="8">Belum ada pengajuan rekap</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif

@endsection
