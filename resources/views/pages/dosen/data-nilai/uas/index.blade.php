<div>
    <div class="card-header bg-white" style="padding: 2px">
        <div>
            <a href="/presensi/data-nilai/{{ $kelas_id }}/{{ $matkul_id }}/{{ $jadwal_id }}/uas/create"
                class="btn btn-success btn-sm" style="margin-top: -20px">
                <span class="mdi mdi-plus"></span> Tambah
            </a>
        </div>
    </div>
    <div class="card-body" style="margin-top: -20px">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($uass as $uas)
                        <tr>
                            <td>
                                <a href="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/' . $jadwal_id . '/uas/' . $uas->tugas_ke . '/edit') }}"
                                    class="btn btn-warning btn-sm">
                                    <span class="mdi mdi-pencil"></span> Edit
                                </a>
                                <form
                                    action="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/' . $jadwal_id . '/uas/' . $uas->tugas_ke . '/delete') }}"
                                    method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-button"
                                        data-id="{{ $tugasKe->id }}">
                                        <span class="mdi mdi-delete"></span> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="2">Nilai UAS belum ditambahkan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

