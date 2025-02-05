<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Kaprodi extends Authenticatable
{
    use HasFactory,Notifiable;

    protected $guarded = ['id'];

    protected $table = 'kaprodi';

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodis_id');
    }
}
