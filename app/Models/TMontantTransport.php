<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TMontantTransport extends Model
{
    use HasFactory;
    protected $fillable = [
        'montanttransport',
        'tventedirect_id',
        'ttafacture_id',
    ];
}
