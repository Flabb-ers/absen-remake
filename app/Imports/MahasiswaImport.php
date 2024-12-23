<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;


class MahasiswaImport implements ToModel
{
    protected $kelasId;

    public function __construct($kelasId) {
        $this->kelasId = $kelasId;
    }

    public function model(array $row)
    {
        if (!isset($row[0]) || $row[0] == 'NIM') {
            return null;
        }

        $alamat = implode(' ', array_filter([
            $row[14],
            'RT ' . $row[15], 
            'RW ' . $row[16],
            $row[17],
            $row[18],
            $row[19],
            $row[20]
        ]));

        return new Mahasiswa([
            'dosen_pembimbing_id' => null,
            'nama_lengkap' => $row[1],
            'nim' => $row[0],
            'nisn' => $row[7],
            'nik' => $row[5],
            'email' => $row[25],
            'password' => Hash::make($row[0]),
            'alamat' => $alamat,
            'no_telephone' => $row[24],
            'tanggal_lahir' => \Carbon\Carbon::parse($row[3])->format('Y-m-d'),
            'tempat_lahir' => $row[2],
            'nama_ibu' => $row[35],
            'jenis_kelamin' => $row[4],
            'kelas_id' => $this->kelasId,
            'status_krs' => false,
            'is_first_login' => true
        ]);
    }
}