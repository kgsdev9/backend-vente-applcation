<?php

namespace App\Http\Controllers\Api\Commandes\CommandesClient;

use App\Http\Controllers\Controller;
use App\Models\TClient;
use App\Models\TCommandeClient;
use App\Models\TCommandeLigneDetailClient;
use App\Models\TReferenceClient;
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

        if ($request->has('client_id'))
        {
            $query->where('client_id', $request->client_id);
        }
        // Champs sur lesquels on peut effectuer une recherche
        $critererecherche = ['codecommande', 'datecmde'];

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
            'datecmde' =>  $request->datecmde ?? now(),
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
        $cmdeclient = TCommandeClient::findOrFail($id);
        $itemsCmdes = TCommandeLigneDetailClient::where('codecommande', $cmdeclient->codecommande)->get();
        $listereferenceclient = TReferenceClient::where('t_client_id', $cmdeclient->client_id)->get();

        return response()->json([
            'cmdeclient'=> $cmdeclient,
            'itemscmdes'=> $itemsCmdes,
            'listeferencesclient'=> $listereferenceclient,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Trouver la commande principale existante
        $commande = TCommandeClient::where('codecommande', $request->codecommande)->firstOrFail();

        // Mettre à jour les informations de la commande principale
        $commande->update([
            'client_id' => $request->client_id,
            'datecmde' => $request->datecmde,
            'montanttva' => $request->montantva,
            'montantht' => $request->montantht,
            'montanttc' => $request->montantttc,
        ]);

        // Supprimer les anciens détails de la commande
        $commande->referenceclients()->delete();

        // Réinsérer les détails de la commande
        foreach ($request->items as $item)
        {
            $commande->referenceclients()->create([
                'codecommande' => $commande->codecommande,
                'reference' => $item['reference'],
                'numligne' => $commande->referenceclients()->count() + 1,
                'prixunitaire' => $item['prixunitaire'],
                'quantitecmde' => $item['quantite'],
                'remiseligne' => $item['remiseligne'] ?? 0,
                'montantht' => $item['montantht'],
            ]);
        }

        // Retourner une réponse appropriée
        return response()->json(['message' => 'Commande mise à jour avec succès.', 'commande' => $commande->codecommande], 200);
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
