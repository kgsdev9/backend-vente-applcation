<?php

namespace App\Http\Controllers\Api\Articles;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    public function listearticles(Request $request)
    {
        $query = Article::orderByDesc('created_at')->with('famillearticle');
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function (Builder $query) use ($searchTerm) {
                $query->where('designation', 'like', "%$searchTerm%")
                      ->orWhere('prixuniataire', 'like', "%$searchTerm%")
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $codearticle =  "SKU". rand(1000, 300400);
        Article::create([
            'designation' => $request->designation,
            'code' => $codearticle,
            'description' => $request->designation,
            'prixuniataire' => $request->prix,
            'famillearticle_id'=> $request->famillearticleid,

        ]);
        return response()->json(['message' => 'Article cré avec success']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return response()->json([
            'article' => $article
        ]);
    }


    public function update(Request $request, $id)
    {
        Article::where('id', '=' ,$id)->update([
            'designation' => $request->designation,
            'description' => $request->designation,
            'prixuniataire' => $request->prix,
            'famillearticle_id'=> $request->famillearticleid,
        ]);
        
        return response()->json('article modifé avec success');
    }


    public function destroy($id)
    {
       $client =  Article::find($id);
       $client->delete();
       return response()->json('article supprimé avec success');
    }


}
