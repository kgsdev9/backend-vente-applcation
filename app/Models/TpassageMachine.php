<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpassageMachine extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'TcomandeLigneReference',
    ];
}
