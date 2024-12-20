<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TEtudetechnique extends Model
{
    use HasFactory;

    protected $fillable = [
        'numeetudeclient',
        'numetudeprixclient',
        'client_id',
        'montant_etude',
        'type_etude',
        'statutet',
        'duree_traitement',
        'responsable_etude',
        'redacteur_id', 
    ];
}
