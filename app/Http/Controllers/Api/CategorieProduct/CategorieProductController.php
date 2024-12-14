<?php

namespace App\Http\Controllers\Api\CategorieProduct;

use App\Http\Controllers\Controller;
use App\Models\TCategorieArticle;
use App\Models\TCategorieProduct;
use App\Traits\RechercheAndPagination;
use Illuminate\Http\Request;

class CategorieProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     use RechercheAndPagination;

     public function index(Request $request)
     {
           // Créer la requête pour les utilisateurs (instance de Builder)
             $query = TCategorieProduct::query()
             ->orderByDesc('libellecategorieproduct');

            //  if ($request->has('client_id'))
            //  {
            //     $query->where('client_id', $request->client_id);
            //  }
             // Champs sur lesquels on peut effectuer une recherche
             $critererecherche = ['libellecategorieproduct', 'created_at'];

             // Relations à charger
             $relations = [];

             // Appliquer la recherche et la pagination via le trait
            //  $listecategorie = $this->applySearchAndPagination($query, $request, $critererecherche, $relations);
            $listecategorie = $query->get();
             return response()->json($listecategorie);
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
        //
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
