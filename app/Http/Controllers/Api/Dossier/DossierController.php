<?php

namespace App\Http\Controllers\Api\Dossier;

use App\Http\Controllers\Controller;
use App\Models\TDocument;
use App\Models\TDossier;
use App\Traits\RechercheAndPagination;
use Illuminate\Http\Request;

class DossierController extends Controller
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
        $query = TDossier::query()->orderByDesc('created_at');

        // Champs sur lesquels on peut effectuer une recherche
        $critererecherche = ['libelledossier', 'created_at'];

        // Relations à charger
        $relations = ['departement'];

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
        TDossier::create([
        'libelledossier'=> $request->designation,
        'codedossier'=> rand(100,400),
        'tdepartement_id'=> $request->departementid,
        ]);

        return response()->json('création effectutée avec succes');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dossier = TDossier::with('departement')->findOrFail($id);
        $itemsFacture = TDocument::where('dossier_id', $dossier->id)->get()->map(function($document) {
            $document->file_url = asset('storage/documents/' . $document->libelledocument);
            return $document;
        });
        return response()->json([
            'dossier' => $dossier,
            'documents' => $itemsFacture
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
