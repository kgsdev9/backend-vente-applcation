<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TventeDirect extends Model
{
    use HasFactory;
    protected $fillable = [
        'numvte',
        'numprofvte',
        'libtiers',
        'telephone',
        'fax',
        'datevte',
        'statutvente',
        'montantht',
        'montanttc',
        'montanttva',
        'montantadsci',
    ];
}
