<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
            'nom'=>  $request->nom,
            'prenom'=> $request->prenom,
            'password'=> Hash::make($request->password) ?? 12345,
            'telephone'=> $request->telephone,
            'email'=> $request->email,
            'role_id'=> $request->role_id,
            'tdepartment_id'=> $request->departement_id,
            'poste'=> $request->poste
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
        $users = User::findOrFail($id); // Utilisation de findOrFail pour gérer les cas où la facture n'est pas trouvée


        return response()->json([
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        User::where('id', '=' ,$request->iduser)->update([
            'nom'=>  $request->nom,
            'prenom'=> $request->prenom,
            'password'=> Hash::make($request->password) ?? 12345,
            'telephone'=> $request->telephone,
            'email'=> $request->email,
            'role_id'=> $request->role_id,
            'tdepartment_id'=> $request->departement_id,
            'poste'=> $request->poste
        ]);

        return response()->json([
            'message' => 'Utilisateur modifié avec succes',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client =  User::find($id);
        $client->delete();
       return response()->json('Utilisateur supprimé avec succes');
    }
}
