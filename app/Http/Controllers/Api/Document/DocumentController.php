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

        foreach ($request->file('files') as $file)
        {
           $fileName =  $file->getClientOriginalName();
            $file->storeAs('documents', $fileName, 'public');
            Document::create([
                'nom'=> $fileName,
                'fichierdocument' => $fileName,
                'dossier_id'=> $request->dossier_id,
            ]);
        }

        return response()->json(['message' => 'Fichiers téléchargés avec succès'], 200);

        // dd($request->all());

        // $dossierId = $request->input('dossier_id');
        // $files = $request->file('files');

        // // if (!is_array($files)) {
        // //     $files = [$files];
        // // }

        // foreach ($files as $file)
        // {
        //     $filePath = $file->store('uploads', 'public');
        //     Document::create(['dossier_id' => $dossierId, 'fichierdocument' => $filePath, 'nom'=>rand(100, 400)]);
        // }

        return response()->json(['message' => 'Fichiers téléchargés avec succès'], 200);
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
