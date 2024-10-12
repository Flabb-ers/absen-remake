<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosens_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function matkul()
    {

        return $this->belongsTo(Matkul::class, 'matkuls_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangans_id');
    }

    public function absen()
    {

        return $this->hasMany(Absen::class);
    }

    public function kontrak()
    {
        return $this->hasMany(Kontrak::class);
    }

    public function pengajuanPresensi()
    {
        return $this->hasOne(PengajuanRekapPresensi::class);
    }

    public function pengajuanBerita()
    {
        return $this->hasOne(PengajuanRekapBerita::class);
    }
    public function pengajuanKontreak()
    {
        return $this->hasMany(PengajuanRekapkontrak::class);
    }

    public function uas(){
        return $this->hasMany(Uas::class);
    }

    public function uts(){
        return $this->hasMany(Uts::class);
    }

    public function etika()
    {
        return $this->hasMany(Etika::class);
    }

    public function aktif()
    {
        return $this->hasMany(Aktif::class);
    }
}
