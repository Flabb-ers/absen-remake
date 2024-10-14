<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function matkul()
    {
        return $this->hasMany(Matkul::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function absen()
    {
        return $this->hasMany(Absen::class);
    }

    public function resume()
    {
        return $this->hasMany(Resume::class);
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function pengajuanBerita()
    {
        return $this->hasMany(PengajuanRekapBerita::class);
    }
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}
