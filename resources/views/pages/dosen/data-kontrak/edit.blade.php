@extends('layouts.main')

@section('container')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">EDIT KONTRAK PERKULIAHAN</h5>
                            <form method="POST" action="{{ route('data-kontrak.update', $kontrak->first()->jadwals_id) }}">
                                @csrf
                                @method('PUT')

                                @foreach ($kontrak as $index => $item)
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Pertemuan ke-{{ $index + 1 }}</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="materiKontrak_{{ $index }}">Materi Perkuliahan</label>
                                                <input type="text" id="materiKontrak_{{ $index }}"
                                                    name="materiKontrak[{{ $index }}]" value="{{ $item->materi }}"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pustakaKontrak_{{ $index }}">Daftar Pustaka</label>
                                                <input type="text" id="pustakaKontrak_{{ $index }}"
                                                    name="pustakaKontrak[{{ $index }}]" value="{{ $item->pustaka }}"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <a href="/presensi/data-kontrak" class="btn btn-info btn-sm"><span
                                        class="mdi mdi-arrow-left"></span> Kembali</a>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <span class="mdi mdi-content-save"></span> Simpan
                                </button>
                            </form>

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
                allowOutsideClick: false,
                confirmButtonText: 'OK'
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                allowOutsideClick: false,
            });
        </script>
    @endif
@endsection
