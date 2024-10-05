<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTarifQuantite extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'quantite',
        'prixunitaire',
        'montantht',
        'remiseligne',
    ];

}
