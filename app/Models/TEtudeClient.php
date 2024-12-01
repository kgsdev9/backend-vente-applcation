<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class TEtudeClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'numetudeclient',
        'numetudeprixclient',
        'tclient_id',
        'montant_etude',
        'statutet',
        'duree_traitement',
        'responsable_etude',
        'redacteur_id',
        'qtecmde',
        'montantht'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'redacteur_id');
    }

    public function client()  {
        return $this->belongsTo(TClient::class, 'tclient_id');
    }


    public static function generateNumeroEtudeClient()
{
    $year = date('y'); // Année au format court, ex. 24 pour 2024
    $lastEtude = self::where('numetudeclient', 'LIKE', "ETC-$year-%")
                     ->orderBy('numetudeclient', 'desc')
                     ->first();

    if ($lastEtude) {
        // Extraire le dernier numéro et incrémenter
        $lastNumber = (int) substr($lastEtude->numetudeclient, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // Premier numéro pour l'année courante
        $newNumber = '001';
    }

    return "ETC-$year-$newNumber";
}

public static function generateNumeroEtudePrixClient()
{
    $year = date('y'); // Année au format court, ex. 24 pour 2024
    $lastEtudePrix = self::where('numetudeprixclient', 'LIKE', "EPT-$year-%")
                         ->orderBy('numetudeprixclient', 'desc')
                         ->first();

    if ($lastEtudePrix) {
        // Extraire le dernier numéro et incrémenter
        $lastNumber = (int) substr($lastEtudePrix->numetudeprixclient, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        // Premier numéro pour l'année courante
        $newNumber = '001';
    }

    return "EPC-$year-$newNumber";
}


}
