<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'codeclient',
        'libtiers',
        'adressepostale',
        'adressegeo',
        'fax',
        'telephone',
        'numerocomtribuabe',
        'cptcompclient',
        'numerodecompte',
        'capital',
        'codepostal',
        'regimefiscal',
        'tcodedevise',
        'sitelivraison',
    ];
}
