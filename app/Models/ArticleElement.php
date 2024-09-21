<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantite',
        'prix',
        'remisearticle',
        'article_id',
        'facture_id'
    ];


    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function getTotalAttribute()
    {
        $prixApresRemise = $this->prix;
        if ($this->remisearticle && $this->remisearticle !== 0)
        {
            $prixApresRemise = $this->prix * (1 - $this->remisearticle / 100);
        }

        return $prixApresRemise * $this->quantite;
    }

}
