<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TReferenceClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'prixunitaire',
        't_client_id'
    ];
}


