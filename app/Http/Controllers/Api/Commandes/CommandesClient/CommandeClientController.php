<?php

namespace App\Http\Controllers\Api\Commandes\CommandesClient;

use App\Http\Controllers\Controller;
use App\Models\TClient;
use App\Models\TCommandeClient;
use App\Traits\RechercheAndPagination;
use Illuminate\Http\Request;

class CommandeClientController extends Controller
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
        $query = TCommandeClient::query()

                                ->orderByDesc('created_at');

        // Champs sur lesquels on peut effectuer une recherche
        $critererecherche = ['codecmde', 'datecmde'];

        // Relations à charger
        $relations = ['client'];

        // Appliquer la recherche et la pagination via le trait
        $clients = $this->applySearchAndPagination($query, $request, $critererecherche, $relations);

        return response()->json($clients);
    }

    public function getAllReferenceClient($id)
    {

        $client = TClient::with('references')->findOrFail($id);
        return response()->json(['references' => $client->references]);


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
        // Créer la commande principale
        $commande = TCommandeClient::create([
            'client_id' => $request->client_id,
            'codecommande' => 'CM-' . time(),
            'montantadsci' => $request->montantadsci,
            'montanttva' => $request->montantTVA,
            'datecmde' =>  $request->datecmde,
            'montantht' => $request->montantHT,
            'montanttc' => $request->montantTTC,
        ]);

        // Enregistrer les détails de la commande
        foreach ($request->items as $item) {
            $commande->referenceclients()->create([
                'codecommande' => $commande->codecomde,
                'reference' => $item['designation'],
                'numligne' => $commande->referenceclients()->count() + 1,
                'prixunitaire' => $item['prix'],
                'quantitecmde' => $item['quantite'],
                'remiseligne' => $item['remiseArticle'] ?? 0,
                'montantht' => $item['montant'],
            ]);
        }

        // Retourner une réponse appropriée
        return response()->json(['message' => 'Commande créée avec succès.', 'commande' => $commande->codecmde], 201);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
