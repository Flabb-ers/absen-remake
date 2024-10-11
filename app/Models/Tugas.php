<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = "Tugas";

    public function matkul(){
        return $this->belongsTo(Matkul::class,'matkul_id');
    }

    public function jadwal(){
        return $this->belongsTo(jadwal::class,'jadwal_id');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class,'kelas_id');
    }
}
