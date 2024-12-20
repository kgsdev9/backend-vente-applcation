<?php

namespace App\Http\Controllers\Api\Departement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TDepartement;
use Illuminate\Database\Eloquent\Builder;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users =TDepartement::orderByDesc('created_at')->get();
        return response()->json($users);
    }

    public function fetchDepartementAllWithPagination(Request $request)
    {
        $query = TDepartement::orderByDesc('created_at');
        // Gérer les critères de recherche
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function (Builder $query) use ($searchTerm) {
                $query->where('libelledepartement', 'like', "%$searchTerm%");

            });
        }
        $departement = $query->paginate($request->query('per_page', 10));
        return response()->json($departement);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        TDepartement::create([
            'libelledepartement'=>  $request->libelledepartement,
        ]);
        return response()->json('departement créé avec success');
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        TDepartement::where('id', '=' ,$request->id)->update([
            'libelledepartement'=>  $request->libelledepartement,
        ]);
       

        return response()->json([
            'message' => 'Departement modifié avec succes',
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
        $client =  TDepartement::find($id);
        $client->delete();
       return response()->json('Departement supprimé avec succes');
    }
}
