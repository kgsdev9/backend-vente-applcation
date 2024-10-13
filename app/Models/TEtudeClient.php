<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class TEtudeClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'numeetudeclient',
        'numetudeprixclient',
        'tclient_id',
        'montant_etude',
        'statutet',
        'duree_traitement',
        'responsable_etude',
        'redacteur_id',
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
        $etudeNumber = 'ETU-' . Str::upper(Str::random(8));
        while (self::where('numeetudeclient', $etudeNumber)->exists())
        {
            $etudeNumber = 'ETU-' . Str::upper(Str::random(8));
        }

        return $etudeNumber;
    }

    public static function generateNumeroEtudePrixClient()
    {
        $etudeNumber = 'ETU-' . Str::upper(Str::random(8));
        while (self::where('numeetudeclient', $etudeNumber)->exists())
        {
            $etudeNumber = 'ETU-' . Str::upper(Str::random(8));
        }

        return $etudeNumber;
    }


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($etude) {
    //         $etude->numeetudeclient = self::generateUniqueEtudeNumber();
    //     });
    // }
}
