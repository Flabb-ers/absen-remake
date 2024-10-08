<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangans_id');
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
    public function pengajuanPresensi()
    {
        return $this->hasMany(PengajuanRekapPresensi::class);
    }

    public function pengajuanBerita()
    {
        return $this->hasMany(PengajuanRekapBerita::class);
    }
}
