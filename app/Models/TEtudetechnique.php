<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TEtudetechnique extends Model
{
    use HasFactory;

    protected $fillable = [
        'numetudetech',
        'client_id',
        'delaideproduction',
        'statutet',
    ];
}
