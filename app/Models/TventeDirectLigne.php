<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TventeDirectLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'numvte',
        'numprofvte',
        'reference',
        'qte',
        'prixunitaire',
        'remiseligne',
        'montantht',
        'montantttc',
    ];
}
