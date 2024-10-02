<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class,'kelas_id');
    }

}
