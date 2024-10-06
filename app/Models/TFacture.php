<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TFacture extends Model
{
    use HasFactory;
    protected $fillable = [
        'codefacture',
        'remise',
        'numcommande',
        'numvente',
        'date_echance',
        'mode_reglement_id',
        'client_id',
        'codedevise_id',
        'user_id',
        'tva',
        'dateecheance',
        'adsci',
        'idregimevente',
        'idconditionvte',
        'montantht',
        'numcpteclient',
        'numcptecontribuable',
        'montantttc',
    ];

    public function client()
    {
        return $this->belongsTo(TClient::class, 'client_id');
    }

    public function codedevise()
    {
        return $this->belongsTo(TcodeDevise::class, 'codedevise_id');
    }

    //date echeance, mode reglement
   
    public function modereglement()
    {
        return $this->belongsTo(TmodeReglement::class, 'mode_reglement_id');
    }


}
