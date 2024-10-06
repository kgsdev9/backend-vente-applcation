<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TDepartement extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelledepartement',
    ];
}
