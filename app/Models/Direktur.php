<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Direktur extends Authenticatable
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sentMessages() 
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function receivMessages() 
    {
        return $this->morphMany(Message::class, 'receiver');
    }

}
