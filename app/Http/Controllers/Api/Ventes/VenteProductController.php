<?php

namespace App\Http\Controllers\Api\Ventes;

use App\Http\Controllers\Controller;
use App\Models\TFacture;
use App\Models\TfactureLigne;
use App\Models\TProduct;
use App\Traits\RechercheAndPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteProductController extends Controller
{
    use RechercheAndPagination;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Créer la requête pour les utilisateurs (instance de Builder)
        $query = TFacture::query()
            ->where('numvente', 'like', 'vp%')
            ->orderByDesc('created_at');

        if ($request->has('datevente')) {
            $query->whereDate('created_at', '=', $request->datevente);
        }

        $critererecherche = ['numvente', 'created_at'];

        // Relations à charger
        $relations = [];

        // Appliquer la recherche et la pagination via le trait
        $clients = $this->applySearchAndPagination($query, $request, $critererecherche, $relations);
        return response()->json($clients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function generateIdentifier(string $type, string $year): string
    {

        $lastIdentifier = DB::table('t_factures')
            ->where('numvente', 'like', "{$type}-{$year}-%")
            ->orderBy('numvente', 'desc')
            ->value('numvente');

        if ($lastIdentifier) {

            $lastNumber = (int) substr($lastIdentifier, strrpos($lastIdentifier, '-') + 1);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {

            $newNumber = str_pad(1, 5, '0', STR_PAD_LEFT);
        }

        // Retourner le nouvel identifiant
        return "{$type}-{$year}-{$newNumber}";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Début de la transaction pour garantir la consistance des données
        \DB::beginTransaction();

        try {
            // Créer une nouvelle facture
            $facture = TFacture::create([
                'numvente' => $this->generateIdentifier('VP', date('y')),
                'adresse' => $request->input('adresse'),
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'montantht' => str_replace(',', '.', $request->input('montantht')),
                'montanttva' => str_replace(',', '.', $request->input('montanttva')),
                'montantttc' => str_replace(',', '.', $request->input('totalttc')),
                'telephone' => $request->input('telephone'),
            ]);

            // Boucle pour enregistrer les lignes de facture et mettre à jour le stock
            foreach ($request->input('items') as $ligne) {
                // Créer la ligne de facture
                TFactureLigne::create([
                    'numvente' => $facture->numvente,
                    'tproduct_id' => $ligne['product_id'],
                    'quantite' => $ligne['qte'],
                    'prix_unitaire' => $ligne['prixUnitaire'],
                    'remise' => $ligne['remise'],
                    'montant_ht' => $ligne['montantht'],
                    'montant_tva' => $ligne['montanttva'],
                    'montant_ttc' => $ligne['montanttc'],
                ]);

                // Mettre à jour la quantité disponible du produit
                $product = TProduct::findOrFail($ligne['product_id']);
                $product->qtedisponible -= $ligne['qte']; // Soustraction de la quantité commandée
                $product->save(); // Sauvegarder la mise à jour
            }

            // Valider la transaction
            \DB::commit();

            return response()->json(['message' => 'Facture créée avec succès !', 'facture' => $facture], 200);
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            \DB::rollBack();
            return response()->json(['error' => 'Échec de la création de la facture.'], 500);
        }
    }





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function edit($vente)
    {
        $ventes = TFacture::where('numvente', $vente)->first();
        $venteslignes = TfactureLigne::with(['product' => function ($query) {
            // Sélectionner les colonnes nécessaires (id et qtedisponible)
            $query->select('id', 'qtedisponible');
        }])
            ->where('numvente', $ventes->numvente)
            ->get();

        return response()->json([
            'ventes' => $ventes,
            'venteslignes' => $venteslignes,
        ]);
    }





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Début de la transaction pour garantir la consistance des données
        \DB::beginTransaction();

        try {
            // Trouver la facture à modifier
            $facture = TFacture::where('numvente', $id)->first();

            // Remettre en stock les quantités des anciennes lignes
            $oldLines = TFactureLigne::where('numvente', $facture->numvente)->get();
            foreach ($oldLines as $oldLine) {
                $product = TProduct::findOrFail($oldLine->tproduct_id);
                $product->qtedisponible += $oldLine->quantite; // Ajouter l'ancienne quantité au stock
                $product->save();
            }

            // Supprimer les anciennes lignes
            TFactureLigne::where('numvente', $facture->numvente)->delete();

            // Mettre à jour les informations principales de la facture
            $facture->update([
                'adresse' => $request->input('adresse'),
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'montantht' => $request->input('montantht'),
                'montanttva' => $request->input('montanttva'),
                'montantttc' => $request->input('totalttc'),
                'telephone' => $request->input('telephone'),
            ]);

            // Boucle pour enregistrer les nouvelles lignes et mettre à jour le stock
            foreach ($request->input('items') as $ligne) {
                // Créer la nouvelle ligne
                TFactureLigne::create([
                    'numvente' => $facture->numvente,
                    'tproduct_id' => $ligne['product_id'],
                    'quantite' => $ligne['qte'],
                    'prix_unitaire' => $ligne['prixUnitaire'],
                    'remise' => $ligne['remise'],
                    'montant_ht' => $ligne['montantht'],
                    'montant_tva' => $ligne['montanttva'],
                    'montant_ttc' => $ligne['montanttc'],
                ]);

                // Mettre à jour la quantité disponible du produit
                $product = TProduct::findOrFail($ligne['product_id']);
                $product->qtedisponible -= $ligne['qte']; // Déduire la nouvelle quantité
                $product->save();
            }

            // Valider la transaction
            \DB::commit();

            return response()->json(['message' => 'Facture modifiée avec succès !', 'facture' => $facture], 200);
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            \DB::rollBack();
            return response()->json(['error' => 'Échec de la modification de la facture.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($numvente)
    {
        $vente = TFacture::where('numvente', $numvente)->first();
        $vente->delete();
        return response()->json(['message' => 'vente supprimé avec succés'], 200);
    }
}
