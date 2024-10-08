<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanRekapPresensi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'pengajuan_rekap_presensi';


    public function kelas(){
        return $this->belongsTo(Kelas::class,'kelas_id');
    }
    public function matkul(){
        return $this->belongsTo(Matkul::class,'matkul_id');
    }

    public function jadwal(){
        return $this->belongsTo(Jadwal::class,'jadwals_id');
    }
}
