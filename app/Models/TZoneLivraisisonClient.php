<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TZoneLivraisisonClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'libellezonelivraison',
        'adressegeo',
        'adressepostale',
        'client_id',
        'telephone',
        'fax',
    ];
}
