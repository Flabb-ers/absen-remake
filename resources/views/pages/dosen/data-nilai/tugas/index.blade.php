<div>
    <div class="card-header bg-white" style="padding: 2px">
        <div>
            <a href="/presensi/data-nilai/{{ $kelas_id }}/{{ $matkul_id }}/{{ $jadwal_id }}/tugas/create"
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
                        <th>Tugas</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tugas as $tugasKe)
                        <tr>
                            <td>Tugas Ke {{ $tugasKe->tugas_ke }}</td>
                            <td>
                                <a href="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/' . $jadwal_id . '/tugas/' . $tugasKe->tugas_ke . '/edit') }}"
                                    class="btn btn-warning btn-sm">
                                    <span class="mdi mdi-pencil"></span> Edit
                                </a>
                                <form
                                    action="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/' . $jadwal_id . '/tugas/' . $tugasKe->tugas_ke . '/delete') }}"
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
                            <td class="text-center" colspan="2">Nilai tugas belum ditambahkan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.delete-button').on('click', function(e) {
            e.preventDefault(); 
            const form = $(this).closest('form'); 

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data ini akan dihapus dan tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); 
                }
            });
        });
    });
</script>
