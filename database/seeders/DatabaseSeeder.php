<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\Semester;
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
            'nama_prodi'=>'Teknik Informatika',
            'singkatan'=>'TI',
            'kode_prodi'=>'WKWKWKW',
            'Jenjang'=>'D3',
        ]);
        Prodi::create([
            'nama_prodi'=>'Akuntansi',
            'singkatan'=>'AK',
            'kode_prodi'=>'WKWKWKW',
            'Jenjang'=>'D3',
        ]);
        Prodi::create([
            'nama_prodi'=>'Administrasi Bisnis',
            'singkatan'=>'AB',
            'kode_prodi'=>'WKWKWKW',
            'Jenjang'=>'D3',
        ]);

        Semester::create([
            'semester'=>1,
            'status'=>1
        ]);
        Semester::create([
            'semester'=>12,
            'status'=>1
        ]);
        Semester::create([
            'semester'=>3,
            'status'=>1
        ]);
        Semester::create([
            'semester'=>4,
            'status'=>1
        ]);
        Semester::create([
            'semester'=>5,
            'status'=>1
        ]);
        Semester::create([
            'semester'=>6,
            'status'=>1
        ]);
        Semester::create([
            'semester'=>7,
            'status'=>1
        ]);
        Semester::create([
            'semester'=>8,
            'status'=>1
        ]);

        TahunAkademik::create([
            'tahun_akademik'=>'2024/2025',
            'status'=>1
        ]);
        TahunAkademik::create([
            'tahun_akademik'=>'2024/2026',
            'status'=>0
        ]);
        TahunAkademik::create([
            'tahun_akademik'=>'2024/2027',
            'status'=>0
        ]);
        TahunAkademik::create([
            'tahun_akademik'=>'2024/2028',
            'status'=>0
        ]);

        Dosen::create([
            'nama'=>'Dosen stau',
            'nidn'=>'121456645674',
            'jenis_kelamin'=>'Laki - Laki',
            'pembimbing_akademik'=>1,
            'no_telephone'=>'098329931710',
            'agama'=>'islam',
            'status'=>1,
            'tanggal_lahir'=>'12/12/12',
            'tempat_lahir'=>'Purworejo',
            'email'=>'dhimdafas@gmail.com',
            'password'=>Hash::make('password')
        ]);
        Dosen::create([
            'nama'=>'Dosen tiga',
            'nidn'=>'121456645434',
            'pembimbing_akademik'=>1,
            'jenis_kelamin'=>'Laki - Laki',
            'no_telephone'=>'098329931710',
            'agama'=>'islam',
            'status'=>1,
            'tanggal_lahir'=>'12/12/12',
            'tempat_lahir'=>'Purworejo',
            'email'=>'dhimdfas@gmail.com',
            'password'=>Hash::make('password')
        ]);
        Dosen::create([
            'nama'=>'Dosen empat',
            'nidn'=>'12145632644',
            'pembimbing_akademik'=>1,
            'jenis_kelamin'=>'Laki - Laki',
            'no_telephone'=>'098329931710',
            'agama'=>'islam',
            'status'=>1,
            'tanggal_lahir'=>'12/12/12',
            'tempat_lahir'=>'Purworejo',
            'email'=>'dimfawsaas@gmail.com',
            'password'=>Hash::make('password')
        ]);
        Dosen::create([
            'nama'=>'Dosen dua',
            'nidn'=>'1214562334644',
            'pembimbing_akademik'=>1,
            'jenis_kelamin'=>'Laki - Laki',
            'no_telephone'=>'09832993172323',
            'agama'=>'islam',
            'status'=>1,
            'tanggal_lahir'=>'12/12/12',
            'tempat_lahir'=>'Purworejo',
            'email'=>'dhiafawass@gmail.com',
            'password'=>Hash::make('password')
        ]);

        Ruangan::create([
            'nama'=>'LS.1.1'
        ]);
        Ruangan::create([
            'nama'=>'LS.1.2'
        ]);
        Ruangan::create([
            'nama'=>'LS.2.1'
        ]);
        Ruangan::create([
            'nama'=>'LS.2.2'
        ]);
        Ruangan::create([
            'nama'=>'A.1.1'
        ]);
        Ruangan::create([
            'nama'=>'A.1.2'
        ]);

        Admin::create([
            'nama'=> 'Admin',
            'email'=>'admin@email.com',
            'password'=> Hash::make('admin123'),
        ]);

    }
}
