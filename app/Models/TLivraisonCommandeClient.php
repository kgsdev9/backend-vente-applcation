<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TLivraisonCommandeClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'numlivraison',
        'codecmde',
        'client_id',
        'datelivraison',
        'quantitelivre',
        'reliquat',
        'statutlivr',
        'codecommercial',
    ];
}
