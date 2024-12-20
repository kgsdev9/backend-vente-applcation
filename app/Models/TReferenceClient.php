<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TReferenceClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'qte',
        'prixunitaire',
        'numetudeclient',
        'numetudeprixclient',
        't_client_id'
    ];
}


