<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Matkul;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Prodi::create([
            'nama_prodi' => 'Teknik Informatika',
            'singkatan' => 'TI',
            'kode_prodi' => 'WKWKWKW',
            'Jenjang' => 'D3',
        ]);
        Prodi::create([
            'nama_prodi' => 'Akuntansi',
            'singkatan' => 'AK',
            'kode_prodi' => 'WKWKWKW',
            'Jenjang' => 'D3',
        ]);
        Prodi::create([
            'nama_prodi' => 'Administrasi Bisnis',
            'singkatan' => 'AB',
            'kode_prodi' => 'WKWKWKW',
            'Jenjang' => 'D3',
        ]);

        Semester::create([
            'semester' => 1,
            'status' => 1
        ]);
        Semester::create([
            'semester' => 12,
            'status' => 1
        ]);
        Semester::create([
            'semester' => 3,
            'status' => 1
        ]);
        Semester::create([
            'semester' => 4,
            'status' => 1
        ]);
        Semester::create([
            'semester' => 5,
            'status' => 1
        ]);
        Semester::create([
            'semester' => 6,
            'status' => 1
        ]);
        Semester::create([
            'semester' => 7,
            'status' => 1
        ]);
        Semester::create([
            'semester' => 8,
            'status' => 1
        ]);

        TahunAkademik::create([
            'tahun_akademik' => '2024/2025',
            'status' => 1
        ]);
        TahunAkademik::create([
            'tahun_akademik' => '2024/2026',
            'status' => 0
        ]);
        TahunAkademik::create([
            'tahun_akademik' => '2024/2027',
            'status' => 0
        ]);
        TahunAkademik::create([
            'tahun_akademik' => '2024/2028',
            'status' => 0
        ]);

        Dosen::create([
            'nama' => 'Dosen stau',
            'nidn' => '121456645674',
            'jenis_kelamin' => 'Laki - Laki',
            'pembimbing_akademik' => 1,
            'no_telephone' => '098329931710',
            'agama' => 'islam',
            'status' => 1,
            'tanggal_lahir' => '12/12/12',
            'tempat_lahir' => 'Purworejo',
            'email' => 'dhimdafas@gmail.com',
            'password' => Hash::make('password')
        ]);
        Dosen::create([
            'nama' => 'Dosen tiga',
            'nidn' => '121456645434',
            'pembimbing_akademik' => 1,
            'jenis_kelamin' => 'Laki - Laki',
            'no_telephone' => '098329931710',
            'agama' => 'islam',
            'status' => 1,
            'tanggal_lahir' => '12/12/12',
            'tempat_lahir' => 'Purworejo',
            'email' => 'dhimdfas@gmail.com',
            'password' => Hash::make('password')
        ]);
        Dosen::create([
            'nama' => 'Dosen empat',
            'nidn' => '12145632644',
            'pembimbing_akademik' => 1,
            'jenis_kelamin' => 'Laki - Laki',
            'no_telephone' => '098329931710',
            'agama' => 'islam',
            'status' => 1,
            'tanggal_lahir' => '12/12/12',
            'tempat_lahir' => 'Purworejo',
            'email' => 'dimfawsaas@gmail.com',
            'password' => Hash::make('password')
        ]);
        Dosen::create([
            'nama' => 'Dosen dua',
            'nidn' => '1214562334644',
            'pembimbing_akademik' => 1,
            'jenis_kelamin' => 'Laki - Laki',
            'no_telephone' => '09832993172323',
            'agama' => 'islam',
            'status' => 1,
            'tanggal_lahir' => '12/12/12',
            'tempat_lahir' => 'Purworejo',
            'email' => 'dhiafawass@gmail.com',
            'password' => Hash::make('password')
        ]);

        Ruangan::create([
            'nama' => 'LS.1.1'
        ]);
        Ruangan::create([
            'nama' => 'LS.1.2'
        ]);
        Ruangan::create([
            'nama' => 'LS.2.1'
        ]);
        Ruangan::create([
            'nama' => 'LS.2.2'
        ]);
        Ruangan::create([
            'nama' => 'A.1.1'
        ]);
        Ruangan::create([
            'nama' => 'A.1.2'
        ]);

        Admin::create([
            'nama' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin123'),
        ]);

        Kelas::create([
            'kode_kelas' => '22222',
            'nama_kelas' => 'TI1A',
            'jenis_kelas' => 'Reguler',
            'id_prodi' => 1,
            'id_semester' => 1
        ]);

        $matkuls = [
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Matematika Diskrit',
                'alias' => 'Discrete Mathematics',
                'kode' => 'MD101',
                'teori' => 3,
                'praktek' => 0,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Algoritma dan Pemrograman',
                'alias' => 'Algorithm and Programming',
                'kode' => 'AP102',
                'teori' => 3,
                'praktek' => 2,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Sistem Operasi',
                'alias' => 'Operating Systems',
                'kode' => 'SO103',
                'teori' => 3,
                'praktek' => 2,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Struktur Data',
                'alias' => 'Data Structures',
                'kode' => 'SD104',
                'teori' => 3,
                'praktek' => 2,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Basis Data',
                'alias' => 'Database Systems',
                'kode' => 'BD105',
                'teori' => 3,
                'praktek' => 2,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Pemrograman Berorientasi Objek',
                'alias' => 'Object-Oriented Programming',
                'kode' => 'PBO106',
                'teori' => 3,
                'praktek' => 2,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Jaringan Komputer',
                'alias' => 'Computer Networks',
                'kode' => 'JK107',
                'teori' => 3,
                'praktek' => 2,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Manajemen Proyek TI',
                'alias' => 'IT Project Management',
                'kode' => 'MPTI108',
                'teori' => 3,
                'praktek' => 0,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Keamanan Sistem Informasi',
                'alias' => 'Information Security',
                'kode' => 'KSI109',
                'teori' => 3,
                'praktek' => 2,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Pemrograman Web',
                'alias' => 'Web Programming',
                'kode' => 'PW110',
                'teori' => 3,
                'praktek' => 2,
            ],
            [
                'prodi_id' => 1,
                'semester_id' => 1,
                'nama_matkul' => 'Kecerdasan Buatan',
                'alias' => 'Artificial Intelligence',
                'kode' => 'AI111',
                'teori' => 3,
                'praktek' => 2,
            ],
        ];

        foreach ($matkuls as $matkul) {
            Matkul::create($matkul);
        }
        for ($i = 1; $i <= 15; $i++) {
            Mahasiswa::create([
                'dosen_pembimbing_id' => null, // Diisi null
                'nama_lengkap' => 'Mahasiswa ' . $i,
                'nim' => 'NIM' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nisn' => 'NISN' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nik' => '320101' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'email' => 'mahasiswa' . $i . '@example.com',
                'password' => Hash::make('password'), // Password default
                'alamat' => 'Alamat Mahasiswa ' . $i,
                'no_telephone' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'tanggal_lahir' => now()->subYears(rand(18, 24))->format('Y-m-d'),
                'tempat_lahir' => 'Kota ' . $i,
                'nama_ibu' => 'Ibu Mahasiswa ' . $i,
                'jenis_kelamin' => $i % 2 == 0 ? 'Laki-laki' : 'Perempuan',
                'kelas_id' => 1, // Semua kelas_id diisi 1
                'status_krs' => rand(0, 1),
                'is_first_login' => true,
            ]);
        }
    }
}
