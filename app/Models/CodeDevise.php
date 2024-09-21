<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeDevise extends Model
{
    use HasFactory;

    protected $fillable  = [
        'nom'
    ];
}
