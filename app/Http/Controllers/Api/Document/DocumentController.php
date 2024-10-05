<?php

namespace App\Http\Controllers\Api\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // Validation des fichiers

        // dd($request->all());
        // Vérifiez si des fichiers ont été téléchargés
        if (!$request->hasFile('files')) {
            return response()->json(['message' => 'Aucun fichier téléchargé.'], 400);
        }

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            // Générer un nom unique pour le fichier
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Stocker le fichier
            $file->storeAs('documents', $fileName, 'public');

            // Enregistrer les informations du document dans la base de données
            $uploadedFiles[] = Document::create([
                'nom' => $fileName,
                'fichierdocument' => $fileName,
                'dossier_id' => $request->dossier_id,
            ]);
        }

        return response()->json(['message' => 'Fichiers téléchargés avec succès', 'files' => $uploadedFiles], 200);
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
