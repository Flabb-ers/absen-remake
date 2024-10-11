<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function prodi()
    {
        return  $this->belongsTo(Prodi::class, 'id_prodi');
    }
    public function semester()
    {
        return  $this->belongsTo(Semester::class, 'id_semester');
    }

    public function mahasiswa()
    {
        return  $this->hasMany(Mahasiswa::class);
    }

    public function matkul()
    {
        return $this->hasMany(Matkul::class);
    }

    public function jadwal()
    {

        return $this->hasMany(Jadwal::class);
    }

    public function kontrak()
    {
        return $this->hasMany(Kontrak::class);
    }

    public function absen()
    {
        return $this->hasMany(Absen::class);
    }

    public function resume()
    {
        return $this->hasMany(Resume::class);
    }
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
    public function pengajuanPresensi()
    {
        return $this->hasMany(PengajuanRekapPresensi::class);
    }
    public function pengajuanBerita()
    {
        return $this->hasMany(PengajuanRekapBerita::class);
    }
    public function pengajuanKontreak()
    {
        return $this->hasMany(PengajuanRekapkontrak::class);
    }
}
