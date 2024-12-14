<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TProduct;
use Illuminate\Http\Request;
use App\Traits\RechercheAndPagination;

class ProductController extends Controller
{

    use RechercheAndPagination;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->has('arg') == 1) {
            $query = TProduct::query()->orderByDesc('created_at');

            if ($request->has('category_id')) {
                $query->where('category_id', $request->client_id);
            }

            // Champs sur lesquels on peut effectuer une recherche
            $critererecherche = ['libelleproduct', 'created_at'];

            // Relations à charger
            $relations = ['category'];

            // Appliquer la recherche et la pagination via le trait
            $clients = $this->applySearchAndPagination($query, $request, $critererecherche, $relations);

            // Formater l'URL de l'image
            foreach ($clients as $client) {
                if ($client->image) {
                    $client->image_url = asset('storage/' . $client->image);
                }
            }

            return response()->json($clients);
        } else {
            $allprdoucts = TProduct::all();

            // Formater l'URL de l'image
            foreach ($allprdoucts as $product) {
                if ($product->image) {
                    $product->image_url = asset('storage/' . $product->image);
                }
            }

            return response()->json($allprdoucts);
        }
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


    // public function store(Request $request)
    // {
    //     // Vérifiez si productId est présent, sinon créez un nouveau produit
    //     if ($request->productid)
    //     {
    //         // Si productId est fourni, mettez à jour le produit
    //         $product = TProduct::find($request->productid);

    //         if (!$product)
    //         {
    //             return response()->json(['message' => 'Produit non trouvé!'], 404);
    //         }
    //     } else {
    //         // Sinon, créez un nouveau produit
    //         $product = new TProduct();
    //     }

    //     // Mise à jour ou création des autres champs
    //     $product->libelleproduct = $request->libelleproduct;
    //     $product->prixachat = $request->prixachat;
    //     $product->prixvente = $request->prixvente;
    //     $product->codeproduct = $request->codeproduct;
    //     $product->qtedisponible = $request->qtedisponible;
    //     $product->tcategorieproduct_id = $request->tcategorieproduct_id;
    //     $product->description = $request->description;

    //     // Traitement de l'image si elle est présente
    //     // if ($request->hasFile('image')) {
    //         // Si une nouvelle image est envoyée, téléchargez-la
    //         $imagePath = $request->file('image')->store('images', 'public');
    //         $product->image = $imagePath;
    //     // }

    //     // Enregistrez le produit (création ou mise à jour)
    //     $product->save();

    //     return response()->json(['message' => $request->productid ? 'Produit mis à jour avec succès!' : 'Produit créé avec succès!'], 201);
    // }



    public function store(Request $request)
    {
        // Si un productid est fourni, essayez de trouver le produit
        if ($request->productid) {
            // Rechercher le produit par productid
            $product = TProduct::find($request->productid);

            // Si le produit n'existe pas, créez un nouveau produit avec les données envoyées
            if (!$product) {
                $product = new TProduct();
            }
        } else {
            // Si productid n'est pas fourni, créez un nouveau produit
            $product = new TProduct();
        }

        // Mise à jour ou création des autres champs
        $product->libelleproduct = $request->libelleproduct;
        $product->prixachat = $request->prixachat;
        $product->prixvente = $request->prixvente;
        $product->codeproduct = $request->codeproduct;
        $product->qtedisponible = $request->qtedisponible;
        $product->tcategorieproduct_id = $request->tcategorieproduct_id;
        $product->description = $request->description;

        // Traitement de l'image si elle est présente
        if ($request->hasFile('image')) {
            // Si une nouvelle image est envoyée, téléchargez-la
            $imagePath = $request->file('image')->store('images', 'public');
            $product->image = $imagePath;
        }

        // Enregistrez le produit (création ou mise à jour)
        $product->save();

        // Retourne un message approprié
        return response()->json(['message' => $request->productid ? 'Produit mis à jour avec succès!' : 'Produit créé avec succès!'], 201);
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
