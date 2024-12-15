<?php

namespace App\Http\Controllers\Api\Factures;

use App\Http\Controllers\Controller;
use App\Models\TFacture;
use App\Models\TfactureLigne;
use Illuminate\Http\Request;
use App\Traits\RechercheAndPagination;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
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
            ->where('codefacture', 'like', 'AC%')
            ->orderByDesc('created_at');

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        // Champs sur lesquels on peut effectuer une recherche
        $critererecherche = ['codefacture', 'created_at'];

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function generateIdentifier(string $type, string $year): string
    {

        $lastIdentifier = DB::table('t_factures')
            ->where('codefacture', 'like', "{$type}-{$year}-%")
            ->orderBy('codefacture', 'desc')
            ->value('codefacture');

        if ($lastIdentifier) {

            $lastNumber = (int) substr($lastIdentifier, strrpos($lastIdentifier, '-') + 1);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {

            $newNumber = str_pad(1, 5, '0', STR_PAD_LEFT);
        }

        // Retourner le nouvel identifiant
        return "{$type}-{$year}-{$newNumber}";
    }
    public function store(Request $request)
    {

        $facture = TFacture::create([
            'codefacture' => $this->generateIdentifier('AC', date('y')),
            'remise' => $request->input('client.remise'),
            'numcommande' => $request->input('client.numcommande'),
            'numvente' => $request->input('client.numvente'),
            'date_echance' => $request->input('client.date_echance'),
            'mode_reglement_id' => $request->input('client.mode_reglement_id'),
            'adresse' => $request->input('adresse'),
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'codedevise_id' => $request->input('client.codedevise_id'),
            'tva' => $request->input('client.tva'),
            'montantht' => $request->input('montantht'),
            'montanttva' => $request->input('montanttva'),
            'montantttc' => $request->input('totalttc'),
            'telephone' => $request->input('telephone'),
        ]);


        // Boucle pour enregistrer les lignes de facture
        foreach ($request->input('items') as $ligne) {
            TfactureLigne::create([
                'codefacture' => $facture->codefacture,
                'designation' => $ligne['designation'],
                'quantite' => $ligne['qte'],
                'prix_unitaire' => $ligne['prixUnitaire'],
                'remise' => $ligne['remise'],
                'montant_ht' => $ligne['montantht'],
                'montant_tva' => $ligne['montanttva'],
                'montant_ttc' => $ligne['montanttc'],
            ]);
        }



        // Début de la transaction pour garantir la consistance des données
        \DB::beginTransaction();

        try {
            // Créer une nouvelle facture (enregistrement principal)


            // Valider la transaction
            \DB::commit();

            return response()->json(['message' => 'Facture créée avec succès !', 'facture' => $facture], 200);
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            \DB::rollBack();
            return response()->json(['error' => 'Échec de la création de la facture.'], 500);
        }
    }



    public function update(Request $request, $id)
    {
        // Début de la transaction pour garantir la consistance des données
        \DB::beginTransaction();

        try {
            // Trouver la facture à modifier
            $facture = TFacture::where('codefacture', $request->codefacture)->first();

            // Supprimer les anciennes lignes
            TFactureLigne::where('codefacture', $facture->codefacture)->delete();

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
                    'codefacture' => $facture->codefacture,
                    'designation' => $ligne['designation'],
                    'quantite' => $ligne['qte'],
                    'prix_unitaire' => $ligne['prixUnitaire'],
                    'remise' => $ligne['remise'],
                    'montant_ht' => $ligne['montantht'],
                    'montant_tva' => $ligne['montanttva'],
                    'montant_ttc' => $ligne['montanttc'],
                ]);
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
    public function edit($codefacurue)
    {
        $facture = TFacture::where('codefacture', $codefacurue)->first();

        $factureligne = TfactureLigne::where('codefacture', $codefacurue)->get();

        return response()->json([
            'facture' => $facture,
            'factureligne' => $factureligne,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($codefacture)
    {
        $facture = TFacture::where('codefacture', $codefacture)->first();
        $facture->delete();
        return response()->json(['message' => 'facture supprimé avec succés'], 200);
    }
}
