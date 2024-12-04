<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prodi extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'prodi';

    protected $dates = ['deleted_at'];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function kaprodi()
    {
        return $this->hasOne(Kaprodi::class, 'prodis_id');
    }

    public function absen()
    {
        return $this->hasMany(Absen::class);
    }

    public function matkul()
    {
        return $this->hasMany(Matkul::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function krs(){
        return $this->hasMany(Krs::class);
    }
}
