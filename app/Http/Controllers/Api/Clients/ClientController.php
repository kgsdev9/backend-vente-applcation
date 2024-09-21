<?php

namespace App\Http\Controllers\Api\Clients;

use App\Models\Client;
use App\Models\Facture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    public function allClients(Request $request)  {
        $query = Client::orderByDesc('created_at');
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
      
        $codeclient =  "CO". rand(1000, 300400);

        Client::create([
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
        $client = Client::findOrFail($id);
        $listefactures = Facture::where('client_id', $client->id)->with('modereglement', 'codedevise')->get();

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
        $client = Client::findOrFail($id);
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
        Client::where('id', '=' ,$id)->update([
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
       $client =  Client::find($id);
       $client->delete();
       return response()->json('client supprimé avec success');
    }
}
