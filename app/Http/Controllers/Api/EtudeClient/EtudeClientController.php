<?php

namespace App\Http\Controllers\Api\EtudeClient;

use App\Http\Controllers\Controller;
use App\Models\TEtudeClient;
use App\Models\TReferenceClient;
use App\Traits\RechercheAndPagination;
use Illuminate\Http\Request;

class EtudeClientController extends Controller
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
        $query = TEtudeClient::query()
        ->orderByDesc('created_at');


        if ($request->has('client_id'))
        {
            $query->where('tclient_id', $request->client_id);
        }
        // Champs sur lesquels on peut effectuer une recherche

        // Champs sur lesquels on peut effectuer une recherche
        $critererecherche = ['numetudeclient', 'numetudeprixclient'];

        // Relations à charger
        $relations = ['user', 'client'];

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
    public function store(Request $request)
    {

        $numeetudeclient = TEtudeClient::generateNumeroEtudeClient();
        $numetudeprixclient = TEtudeClient::generateNumeroEtudePrixClient();

        TEtudeClient::create([
            'numetudeclient' => $numeetudeclient ,
            'numetudeprixclient'=> $numetudeprixclient,
            'tclient_id'=>  $request->client,
            'montant_etude'=> $request->totalht,
            'duree_traitement'=> $request->dureetude ?? now(),
            'responsable_etude'=> $request->responsabletude,
            'redacteur_id'=> $request->redacteur_id ?? 1,
            'qtecmde'=> $request->totalqte ?? 1,
            'montantht'=> $request->totalht ?? 1,
        ]);

        foreach ($request->references as $ref)
        {
            TReferenceClient::create([
                'reference' => $ref['reference'],
                'prixunitaire' => $ref['price'],
                'qte' => $ref['quantity'],
                'numetudeclient' => $numeetudeclient,
                'numetudeprixclient' => $numetudeprixclient,
                't_client_id' => $request->client,
            ]);
        }
        return response()->json('etude enregistré avec success');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $etudeclient = TEtudeClient::findOrFail($id);

        $referenceClient = TReferenceClient::where('t_client_id', $etudeclient->tclient_id)
                                            ->where('numetudeclient', $etudeclient->numetudeclient)
                                            ->get();
        return response()->json([
            'etudeclient' => $etudeclient,
            'referenceClient' => $referenceClient
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
        // Récupérer l'instance de l'étude client existante
        $etudeClient = TEtudeClient::findOrFail($id);

        // Mettre à jour les informations de l'étude client
        $etudeClient->update([
            'tclient_id' => $request->client,
            'montant_etude' => $request->montantetude,
            'duree_traitement' => $request->dureetude,
            'responsable_etude' => $request->responsabletude,
            'redacteur_id' => $request->redacteur_id ?? $etudeClient->redacteur_id,
        ]);

        TReferenceClient::where('numetudeclient', $etudeClient->numetudeclient)
                       ->where('t_client_id', $etudeClient->tclient_id)
                       ->delete();

        // Ajouter les nouvelles références
        foreach ($request->references as $ref)
        {
            TReferenceClient::create([
                'reference' => $ref['reference'],
                'prixunitaire' => $ref['prixunitaire'],
                't_client_id' => $request->client,
                'qte' => $ref['qte'],
                'numetudeclient' => $etudeClient->numetudeclient,
                'numetudeprixclient'  => $etudeClient->numetudeprixclient
            ]);
        }

        return response()->json('Étude et références mises à jour avec succès');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $etudeclient = TEtudeClient::findOrFail($id);

        TReferenceClient::where('t_client_id', $etudeclient->tclient_id)
                        ->where('numetudeclient', $etudeclient->numetudeclient)
                        ->delete();

        $etudeclient->delete();

        return response()->json(['message' => 'Étude client et ses références associées supprimées avec succès.'], 200);
    }

}
