<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCommandeLigneDetailClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'codecommande',
        'reference',
        'numligne',
        'prixunitaire',
        'quantitecmde',
        'quantitelivre',
        'reliquat',
        'remiseligne',
        'montantht',
    ];
}


// Gestion des reliquats et des livraisons
// Lorsqu’une livraison partielle est effectuée, l’assistante saisit la quantité livrée, met à jour la table des livraisons (Tlivraison), puis ajuste les quantités livrées dans Tdetailcommande et Tcommande.
// Si le reliquat n’est pas égal à 0, cela signifie que des produits restent à livrer.
// Chaque fois qu'une nouvelle livraison est effectuée pour la même commande, elle est enregistrée dans Tlivraison jusqu'à ce que toutes les quantités soient livrées.
