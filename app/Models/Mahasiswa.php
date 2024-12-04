<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mahasiswa extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function absen()
    {
        return $this->hasMany(Absen::class, 'absens_id');
    }
    public function aktif()
    {
        return $this->hasMany(Aktif::class);
    }
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'absens_id');
    }
    public function uts()
    {
        return $this->hasMany(Uts::class);
    }
    public function uas()
    {
        return $this->hasMany(Uas::class);
    }

    public function pembimbingAkademik()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }

    public function nilaiHuruf()
    {
        return $this->hasMany(NilaiHuruf::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function krs(){
        return $this->hasMany(Krs::class);
    }
}
