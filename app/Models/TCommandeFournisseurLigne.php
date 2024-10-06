<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCommandeFournisseurLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'codearrivcmde',
        'prixunitaire',
        'quantite',
        'remiseligne',
        'montantht',
    ];
}
