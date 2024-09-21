<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypedeContrrat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];
}
