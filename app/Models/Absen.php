<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosens_id');
    }

    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'matkuls_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodis_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwals_id');
    }
}
