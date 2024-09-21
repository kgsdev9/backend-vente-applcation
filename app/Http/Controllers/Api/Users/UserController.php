<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::orderByDesc('created_at')->with('role', 'departement');
        // Gérer les critères de recherche
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function (Builder $query) use ($searchTerm) {
                $query->where('nom', 'like', "%$searchTerm%")
                      ->orWhere('email', 'like', "%$searchTerm%")
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
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        User::create([
            'name'=>  $request->name,
            'email'=> $request->email,
            'password'=> $request->password
        ]);

        return response()->json('utilisateur créé avec success');
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
