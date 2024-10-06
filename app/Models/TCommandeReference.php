<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCommandeReference extends Model
{
    use HasFactory;
    protected $fillable = [
        'codecomde',
        'client_id',
        'datecomde',
        'delailivraisoncmde',
        'dateconfirmation',
        'remiseglobale',
        'tzonelivraison_id',
        'montantadsci',
        'montanttva',
        'montantht',
        'montanttc',
        'statutcmde',
    ];
}
