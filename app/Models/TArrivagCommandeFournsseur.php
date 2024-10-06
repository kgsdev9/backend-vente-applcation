<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TArrivagCommandeFournsseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'codearrivcmde',
        'tfourniseur_id',
        'qtecmde',
        'datecmde',
        'montantht',
        'montanttc',
        'montnadsci',
        'montanttva',
        'datereceptioncmde',
        'statutcmde',
    ];

    public function getTotalAttribute()
    {
        $prixApresRemise = $this->prix;
        if ($this->remisearticle && $this->remisearticle !== 0)
        {
            $prixApresRemise = $this->prix * (1 - $this->remisearticle / 100);
        }

        return $prixApresRemise * $this->quantite;
    }


}
