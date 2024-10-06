<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelledossier',
        'codedossier',
        'tdepartement_id'
    ];

    public function departement(){
        return $this->belongsTo(TDepartement::class, 'tdepartement_id');
    }
}
