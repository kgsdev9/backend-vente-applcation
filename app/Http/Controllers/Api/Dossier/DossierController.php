<?php

namespace App\Http\Controllers\Api\Dossier;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Dossier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            $query = Dossier::orderByDesc('created_at')->with('departement');
            // Gérer les critères de recherche
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function (Builder $query) use ($searchTerm) {
                    $query->where('nom', 'like', "%$searchTerm%")
                          ->orWhereDate('created_at', '=', $searchTerm); // exemple: recherche par date de creation
                });
            }

            // Paginer les résultats avec une taille de page par défaut
            $users = $query->paginate($request->query('per_page', 10));

            return response()->json($users);

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
        Dossier::create([
        'nom'=> $request->designation,
        'codedossier'=> rand(100,400),
        'departement_id'=> $request->departementid,
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
        $dossier = Dossier::with('departement')->findOrFail($id);

        // $itemsFacture = Document::where('dossier_id', $dossier->id)->get();

        $itemsFacture = Document::where('dossier_id', $dossier->id)->get()->map(function($document) {
            $document->file_url = asset('storage/documents/' . $document->nom); // Ajoutez l'URL du fichier
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
