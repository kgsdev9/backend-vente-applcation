<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TfactureLigne extends Model
{
    use HasFactory;
    protected $fillable = [
        'prixunitaire',
        'reference',
        'quantite',
        'remeligne',
        'tva',
        'montantht',
        'montantadsci',
        'montanttc'
    ];
}
