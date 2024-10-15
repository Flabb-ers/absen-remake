<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etika extends Model
{
    use HasFactory;
    

    protected $guarded = ['id'];

    public function matkul(){
        return $this->belongsTo(Matkul::class,'matkul_id');
    }

    public function jadwal(){
        return $this->belongsTo(jadwal::class,'jadwal_id');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class,'kelas_id');
    }
    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class,'mahasiswa_id');
    }
}
