<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TEtudeClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'numeetudeclient',
        'numetudeprixclient',
        'tclient_id',
        'montant_etude',
        'statutet',
        'duree_traitement',
        'responsable_etude',
        'redacteur_id',
    ];
}
