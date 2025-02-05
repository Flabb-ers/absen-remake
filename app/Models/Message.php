<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wadir;

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
        return $this->morphTo();
    }

    public function matkul()
    {
        return $this->belongsTo(Matkul::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function scopeUnread($query, $receiverId, $receiverType)
    {
        return $query->where([
            'receiver_id' => $receiverId,
            'receiver_type' => $receiverType,
            'read' => false
        ]);
    }

    public function markAsRead()
    {
        $this->update([
            'read' => true,
            'read_at' => now()
        ]);
    }

    public function parentMessage()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
}
