<?php

namespace App\Http\Controllers\Api\Clients;

use App\Models\Facture;
use App\Traits\RechercheAndPagination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TClient;
use App\Models\TFacture;

class ClientController extends Controller
{

    use RechercheAndPagination;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = TClient::all();
        return response()->json($clients);
    }

    // public function allClients(Request $request)  {
    //     $query = TClient::orderByDesc('created_at');
    //     $factures = $query->paginate($request->query('per_page', 10)); // Modifier la taille de page par défaut ici
    //     return response()->json($factures);

    // }

    public function allClients(Request $request)
    {
        // Créer la requête pour les utilisateurs (instance de Builder)
        $query = TClient::query()->orderByDesc('created_at');

        // Champs sur lesquels on peut effectuer une recherche
        $critererecherche = ['libtiers', 'email', 'telephone','created_at'];

        // Relations à charger
        $relations = ['regimefiscal', 'codedevise'];

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

        $codeclient =  "CO". rand(1000, 300400);

        TClient::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'city_id' => 1,
            'country_id' => 1,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'codeclient'=> $codeclient,
            'telephone' => $request->telephone,
            'fax' => $request->fax,
        ]);

        return response()->json(['message' => 'Client cré avec success']);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = TClient::findOrFail($id);
        $listefactures = TFacture::where('client_id', $client->id)->with('modereglement', 'codedevise')->get();

        return response()->json([
            'listefactures'=> $listefactures,
            'client' => $client,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = TClient::findOrFail($id);
        return response()->json([
            'client' => $client
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
        TClient::where('id', '=' ,$id)->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'fax' => $request->fax,
        ]);
        return response()->json('client modifé avec success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $client =  TClient::find($id);
       $client->delete();
       return response()->json('client supprimé avec success');
    }
}
