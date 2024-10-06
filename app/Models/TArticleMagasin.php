<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TArticleMagasin extends Model
{
    use HasFactory;

    protected $fillable = [
        'libellearticle',
        'tcategoriearticle_id',
        'description',
        'prixunitaire',
        'stockdispo',
        'stockminimum',
    ];


}

