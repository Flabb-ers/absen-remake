<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class,'dosens_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class,'kelas_id');
    }

    public function matkul(){

        return $this->belongsTo(Matkul::class,'matkuls_id');
    }

    public function ruangan(){
        return $this->belongsTo(Ruangan::class,'ruangans_id');
    }

    public function absen(){

        return $this->hasMany(Absen::class);
    }
}
