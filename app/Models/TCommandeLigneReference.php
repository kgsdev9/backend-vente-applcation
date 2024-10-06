<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCommandeLigneReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'numcmderefe',
        'reference',
        'numligne',
        'prixunitaire',
        'quantite',
        'remiseligne',
        'montantht',
        'tcommandereference_id',
    ];
}
