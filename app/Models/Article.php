<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable  = [
        'designation',
        'code',
        'description',
        'prixuniataire',
        'famillearticle_id'
    ];


    public function famillearticle(){
        return $this->belongsTo(FamilleArticle::class, 'famillearticle_id');
    }
}
