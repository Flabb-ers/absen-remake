<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function absen()
    {
        return $this->hasMany(Absen::class, 'absens_id');
    }

    public function pembimbingAkademik()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }
}
