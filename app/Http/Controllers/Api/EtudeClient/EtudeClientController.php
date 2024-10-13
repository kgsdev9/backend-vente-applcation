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

        // Champs sur lesquels on peut effectuer une recherche
        $critererecherche = ['numeetudeclient', 'created_at'];

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
            'numeetudeclient' => $numeetudeclient ,
            'numetudeprixclient'=> $numetudeprixclient,
            'tclient_id'=>  $request->client,
            'montant_etude'=> $request->montantetude,
            'duree_traitement'=> $request->dureetude,
            'responsable_etude'=> $request->responsabletude,
            'redacteur_id'=> $request->redacteur_id ?? 1,
        ]);

        foreach ($request->references as $ref)
        {
            TReferenceClient::create([
                'reference' => $ref['reference'],
                'prixunitaire' => $ref['price'],
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
