<?php

namespace App\Http\Controllers\Api;

use App\Models\Facture;
use Illuminate\Http\Request;
use App\Models\ArticleElement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class FactureController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Facture::orderByDesc('created_at')->with('client');

        // Gérer les critères de recherche
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('numeroFacture', 'like', "%$searchTerm%");
        }

        // Filtrer par client
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Paginer les résultats avec une taille de page par défaut
        $factures = $query->paginate($request->query('per_page', 10)); // Modifier la taille de page par défaut ici

        return response()->json($factures);
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
    public function store(Request $request)
    {

        $nbr = rand(1, 100);
        $numerocommande = "FA".date('y')."/".str_pad(++$nbr, 5,'0',STR_PAD_LEFT);

            // Récupérez les données directement depuis la requête
            $data = $request->all();
            // Créez une nouvelle instance de facture
            $invoice = new Facture();
            $invoice->numeroFacture =$numerocommande;
            $invoice->client_id = $data['client_id'] ?? '';
            $invoice->mode_reglement_id = $data['modeReglementid'] ?? '';
            $invoice->date_echance = $data['dateEcheance'] ?? '';
            $invoice->codedevise_id = $data['codedevise'] ?? '';
            $invoice->user_id = 2; // administrateur
            $invoice->remise = $data['remise'] ?? 0; // Assurez-vous que remise a une valeur par défaut si elle est absente
            $invoice->save();

            // Enregistrez les articles de la facture
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $item = new ArticleElement();
                    $item->article_id = $itemData['id_article'] ?? '';
                    $item->prix = $itemData['prix'] ?? null;
                    $item->remisearticle = $itemData['remiseArticle'] ?? null;
                    $item->quantite = $itemData['quantite'] ?? null;
                    $invoice->items()->save($item);
                }
            }

            return response()->json(['message' => 'Facture enregistrée avec succès'], 200);

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
    public function edit($id)
    {
        $facture = Facture::findOrFail($id); // Utilisation de findOrFail pour gérer les cas où la facture n'est pas trouvée

        $itemsFacture = ArticleElement::where('facture_id', $facture->id)->get();

        return response()->json([
            'facture' => $facture,
            'itemsFacture' => $itemsFacture
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request, Facture $facture)
     {
         // Mettre à jour les champs de la facture
         $facture->update([
             'client_id' => $request->client_id,
             'remise' => $request->remise,
             'codedevise_id' => $request->codedevise,
             'date_echance' => $request->date_echance,
             'mode_reglement_id' => $request->modeReglementid,
         ]);
         // Mettre à jour les items de la facture
         $facture->items()->delete(); // Supprimer tous les items existants
         foreach ($request->items as $itemData) {
             $facture->items()->create([
                 'article_id' => $itemData['article_id'],
                 'prix' => $itemData['prix'],
                 'remisearticle' => $itemData['remisearticle'],
                 'quantite' => $itemData['quantite'],
             ]);
         }
         return response()->json(['message' => 'Facture mise à jour avec succès'], 200);
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Facture $facture)
    {
        // Supprimer la facture et ses items
        try {
            $facture->items()->delete(); // Supprimer les items de la facture
            $facture->delete(); // Supprimer la facture elle-même
            return response()->json(['message' => 'Facture supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression de la facture'], 500);
        }
    }
}
