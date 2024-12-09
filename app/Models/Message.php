<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->belongsTo(Dosen::class, 'receiver_id');
    }

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function parentMessage()
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }
}
