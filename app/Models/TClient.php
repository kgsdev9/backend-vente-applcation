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
        'email',
        'telephone',
        'numerocomtribuabe',
        'numerodecompte',
        'capital',
        'tregimefiscal_id',
        'tcodedevise_id',
        'sitelivraison',
    ];

    public function regimefiscal(){
        return $this->belongsTo(TregimeFiscal::class, 'tregimefiscal_id');
    }

    public function codedevise(){
        return $this->belongsTo(TcodeDevise::class, 'tcodedevise_id');
    }
}
