<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matkul extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }


    public function kontrak()
    {
        return $this->hasMany(Kontrak::class);
    }
    public function absen()
    {
        return $this->hasMany(Absen::class);
    }

    public function resume()
    {
        return $this->hasMany(Resume::class);
    }
    public function pengajuanPresensi()
    {
        return $this->hasMany(PengajuanRekapPresensi::class);
    }

    public function pengajuanBerita()
    {
        return $this->hasMany(PengajuanRekapBerita::class);
    }

    public function pengajuanKontreak()
    {
        return $this->hasMany(PengajuanRekapkontrak::class);
    }
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
    public function uas()
    {
        return $this->hasMany(Uas::class);
    }

    public function uts()
    {
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
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function pengajuanNilai()
    {
        return $this->hasMany(PengajuanRekapNilai::class);
    }
    public function nilaiHuruf()
    {
        return $this->hasMany(NilaiHuruf::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}
