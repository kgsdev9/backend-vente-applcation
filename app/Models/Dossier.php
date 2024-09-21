<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'codedossier',
        'departement_id'
    ];


public function departement(){
    return $this->belongsTo(Departement::class, 'departement_id');
}

}





