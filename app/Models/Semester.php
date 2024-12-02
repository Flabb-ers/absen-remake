<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function kelas(){

        return $this->hasMany(Kelas::class);
    }

    public function nilaiHuruf(){
        return $this->hasMany(NilaiHuruf::class);   
    }

    public function matkul(){
        return $this->hasMany(Matkul::class);
    }
}
