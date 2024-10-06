<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TcodeDevise extends Model
{
    use HasFactory;
    protected $fillable = [
        'libellecodedevise',
    ];
}

