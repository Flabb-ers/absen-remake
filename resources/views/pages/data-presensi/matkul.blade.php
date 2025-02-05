@extends('layouts.main')

@section('container')
    @php
        function getModelNamespaceFromRole($role)
        {
            $roleToModelMap = [
                'admin' => 'App\Models\Admin',
                'direktur' => 'App\Models\Direktur',
                'wakil_direktur' => 'App\Models\Wadir',
                'kaprodi' => 'App\Models\Kaprodi',
                'mahasiswa' => 'App\Models\Mahasiswa',
                'dosen' => 'App\Models\Dosen',
            ];

            return $roleToModelMap[$role] ?? '';
        }
    @endphp
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="breadcrumb">
                <a href="/presensi/dashboard" class="breadcrumb-item">
                    <span class="mdi mdi-home"></span> Dashboard
                </a>
                <a href="/presensi/data/perkuliahan" class="breadcrumb-item">
                   Data Perkuliahan
                </a>
                {{-- <span class="breadcrumb-item" id="dataMasterBreadcrumb">Data Presensi</span> --}}
                <span class="breadcrumb-item active">{{ $jadwals->first()->dosen->nama }}</span>
                <span class="breadcrumb-item active">Mata Kuliah</span>
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
                                            <th>Kode</th>
                                            <th>Nama Mata Kuliah</th>
                                            <th>SKS</th>
                                            <th>Prodi</th>
                                            <th>Semester</th>
                                            <th>Lembar Monitoring</th>
                                            <th>Presensi</th>
                                            <th>Berita Acara</th>
                                            <th>Kontrak Perkuliahan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($jadwals as $jadwal)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jadwal->matkul->kode }}</td>
                                                <td>{{ $jadwal->matkul->nama_matkul }}</td>
                                                <td>{{ $jadwal->matkul->praktek + $jadwal->matkul->teori }} </td>
                                                <td>{{ $jadwal->kelas->prodi->nama_prodi }}</td>

                                                <td>Semester {{ $jadwal->matkul->semester->semester }}</td>
                                                @php
                                                    $role = Session::get('user.role');
                                                    $userId = Session::get('user.id');
                                                    $receiverType = getModelNamespaceFromRole($role);
                                                    $dosenType = getModelNamespaceFromRole('dosen');

                                                    $messages = App\Models\Message::with([
                                                        'replies',
                                                        'jadwal.dosen',
                                                        'jadwal',
                                                        'kelas',
                                                        'matkul',
                                                        'kelas.prodi',
                                                    ])
                                                        ->where('jadwal_id', $jadwal->id)
                                                        ->where(function ($query) use (
                                                            $jadwal,
                                                            $receiverType,
                                                            $dosenType,
                                                            $userId,
                                                        ) {
                                                            $query
                                                                ->where(function ($subQuery) use (
                                                                    $jadwal,
                                                                    $receiverType,
                                                                    $dosenType,
                                                                    $userId,
                                                                ) {
                                                                    $subQuery
                                                                        ->where('sender_id', $userId)
                                                                        ->where('sender_type', $receiverType)
                                                                        ->where('receiver_id', $jadwal->dosens_id)
                                                                        ->where('receiver_type', $dosenType);
                                                                })
                                                                ->orWhere(function ($subQuery) use (
                                                                    $jadwal,
                                                                    $receiverType,
                                                                    $dosenType,
                                                                    $userId,
                                                                ) {
                                                                    $subQuery
                                                                        ->where('receiver_id', $userId)
                                                                        ->where('receiver_type', $receiverType)
                                                                        ->where('sender_id', $jadwal->dosens_id)
                                                                        ->where('sender_type', $dosenType);
                                                                });
                                                        })
                                                        ->whereNull('parent_id')
                                                        ->first();

                                                @endphp
                                                <td>
                                                    @if ($messages !== null)
                                                        <a class="btn btn-success btn-sm" target="_blank"
                                                            href="/presensi/lembar-monitoring/{{ $jadwal->id }}"
                                                            class="dropdown-item">
                                                            <i class="mdi mdi-download"></i> Download
                                                        </a>
                                                    @else
                                                        <button class="btn btn-success btn-sm" disabled>
                                                            <i class="mdi mdi-download"></i> Download
                                                        </button>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-sm dropdown-toggle {{ $pertemuanCounts[$jadwal->id] == null ? 'btn-secondary' : 'btn-warning' }}"
                                                            type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-expanded="false"
                                                            {{ $pertemuanCounts[$jadwal->id] == null ? 'disabled' : '' }}>
                                                            Pilih Pertemuan
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 1)
                                                                <li><a target="_blank" class="dropdown-item"
                                                                        href="/presensi/data/presence/{{ $jadwal->matkuls_id }}/{{ $jadwal->kelas_id }}/{{ $jadwal->id }}/1-7">Pertemuan
                                                                        1-7</a></li>
                                                            @endif
                                                            @if (($pertemuanCounts[$jadwal->id] ?? 0) >= 8)
                                                                <li><a target="_blank" class="dropdown-item"
                                                                        href="/presensi/data/presence/{{ $jadwal->matkuls_id }}/{{ $jadwal->kelas_id }}/{{ $jadwal->id }}/8-14">Pertemuan
                                                                        8-14</a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-sm dropdown-toggle {{ $beritaCounts[$jadwal->id] == null ? 'btn-secondary' : 'btn-warning' }}"
                                                            type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-expanded="false"
                                                            {{ $beritaCounts[$jadwal->id] == null ? 'disabled' : '' }}>
                                                            Pilih Pertemuan
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            @if (($beritaCounts[$jadwal->id] ?? 0) >= 1)
                                                                <li><a target="_blank" class="dropdown-item"
                                                                        href="/presensi/data/resume/{{ $jadwal->matkuls_id }}/{{ $jadwal->kelas_id }}/{{ $jadwal->id }}/1-7">Pertemuan
                                                                        1-7</a></li>
                                                            @endif

                                                            @if (($beritaCounts[$jadwal->id] ?? 0) >= 8)
                                                                <li><a target="_blank" class="dropdown-item"
                                                                        href="/presensi/data/resume/{{ $jadwal->matkuls_id }}/{{ $jadwal->kelas_id }}/{{ $jadwal->id }}/8-14">Pertemuan
                                                                        8-14</a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>

                                                <td>
                                                    <a target="_blank"
                                                        class="btn btn-sm {{ ($kontrakCounts[$jadwal->id] ?? null) == null ? 'btn-secondary disabled' : 'btn-warning' }}"
                                                        href="{{ ($kontrakCounts[$jadwal->id] ?? null) == null ? '#' : '/presensi/data/contract/' . $jadwal->matkuls_id . '/' . $jadwal->kelas_id . '/' . $jadwal->id . '/cek' }}">
                                                        <i class="mdi mdi-eye"></i> Lihat
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">Matkul belum ditambahkan</td>
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
@endsection
