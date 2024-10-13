<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCommandeClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'codecmde',
        'client_id',
        'datecmde',
        'quantitecmde',
        'quantitelivre',
        'reliquat',
        'delailivrcmde',
        'dateconfirmation',
        'montantadsci',
        'montanttva',
        'montantht',
        'montanttc',
        'statutcmde',
    ];
}
