<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCommandeClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'codecommande',
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

    public function client()  {
        return $this->belongsTo(TClient::class, 'client_id');
    }

    public function referenceclients()
    {
        return $this->hasMany(TCommandeLigneDetailClient::class,  'codecommande', 'codecommande');
    }

}


