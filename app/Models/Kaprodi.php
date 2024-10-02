<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaprodi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'kaprodi';

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodis_id');
    }
}
