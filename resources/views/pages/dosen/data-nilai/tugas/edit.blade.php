@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center mb-4">Edit Nilai Mahasiswa</h5>
                            <form method="POST" action="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/tugas/' . $tugas_ke) }}">
                                @csrf
                                @method('PUT')

                                @foreach ($mahasiswas as $mahasiswa)
                                    <div class="mb-3">
                                        <label>{{ $mahasiswa->nama_lengkap }} [{{ $mahasiswa->nim }}]</label>
                                        <input type="hidden" name="mahasiswas_id[]" value="{{ $mahasiswa->id }}">
                                        <input type="number" name="nilai[]" 
                                               value="{{ $tugas->where('mahasiswa_id', $mahasiswa->id)->first()->nilai ?? '' }}" 
                                               min="0" max="100" class="form-control" required>
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
