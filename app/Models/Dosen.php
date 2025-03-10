<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use HasFactory,SoftDeletes,Notifiable;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function matkul()
    {
        return $this->hasMany(Matkul::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class,'dosens_id');
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

    public function sentMessages() 
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function receivMessages() 
    {
        return $this->morphMany(Message::class, 'receiver');
    }
}
