<div>
    <div class="card-header bg-white" style="padding: 2px">
        <div>
            @if ($aktifs->isEmpty())
                <a href="/presensi/data-nilai/{{ $kelas_id }}/{{ $matkul_id }}/{{ $jadwal_id }}/aktif/create"
                    class="btn btn-success btn-sm" style="margin-top: -20px">
                    <span class="mdi mdi-plus"></span> Tambah
                </a>
            @endif
        </div>
    </div>
    <div class="card-body" style="margin-top: -20px">
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    @forelse ($aktifs as $aktif)
                        <tr>
                            <td>Keaktifan Mahasiswa</td>
                            <td>
                                <a href="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/' . $jadwal_id . '/aktif/edit') }}"
                                    class="btn btn-warning btn-sm">
                                    <span class="mdi mdi-pencil"></span> Edit
                                </a>
                                <form action="{{ url('/presensi/data-nilai/' . $kelas_id . '/' . $matkul_id . '/' . $jadwal_id . '/aktif') }}"
                                      method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-button"
                                        data-id="{{ $aktif->id }}">
                                        <span class="mdi mdi-delete"></span> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="2">Nilai keaktifan belum ditambahkan</td>
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
