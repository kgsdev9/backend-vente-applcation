<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelledocument',
        'fichierdocument',
        'dossier_id'
    ];
}
