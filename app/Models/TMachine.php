<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMachine extends Model
{
    use HasFactory;
    protected $fillable = [
        'libmachine',
    ];

}
