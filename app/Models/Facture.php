<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numeroFacture',
        'remise',
        'date_echance',
        'mode_reglement_id',
        'client_id',
        'codedevise_id',
        'user_id',
        'tva',
        'adsci',
        'montantht',
        'montantttc',

    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function codedevise()
    {
        return $this->belongsTo(CodeDevise::class, 'codedevise_id');
    }


    //date echeance, mode reglement
    public function items()
    {
        return $this->hasMany(ArticleElement::class);
    }

    public function modereglement()
    {
        return $this->belongsTo(ModeReglement::class, 'mode_reglement_id');
    }
}
