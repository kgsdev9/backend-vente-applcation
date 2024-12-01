<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreferenceEnStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'codearticle',
        'reference',
        'qtedisponible',
        'poidsnet',
        'poidsbrut',

    ];

}
