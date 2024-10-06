<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TArrivagCommandeFournsseurDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'codearrivcmde',
        'prixunitaire',
        'quantite',
        'numligne',
        'remiseligne',
        'montantht',
    ];
}
