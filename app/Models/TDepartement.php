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

    public function users()
    {
        return $this->hasMany(User::class, 'tdepartment_id'); // Correction ici
    }


  
}
