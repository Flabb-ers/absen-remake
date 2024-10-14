<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use HasFactory,SoftDeletes;

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

    public function pembimbingAkademik()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }
}
