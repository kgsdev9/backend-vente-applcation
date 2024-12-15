<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\TFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function statistiqueBoutique()
    {
        $facturesByMonth = TFacture::select(DB::raw('DATE_FORMAT(created_at, "%m-%Y") as month'), DB::raw('COUNT(*) as total_ventes'))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();


        $listerecentevetne = TFacture::where('numvente', 'like',  'vp%')->take(10)->get();

        // Retourne les mois et le nombre de ventes
        $months = [];
        $totalVentes = [];

        foreach ($facturesByMonth as $facture) {
            $months[] = $facture->month;  // Ex: "01-2024"
            $totalVentes[] = $facture->total_ventes;
        }

        return response()->json([
            'months' => $months,
            'total_ventes' => $totalVentes,
            'allRecentlyvente' => $listerecentevetne
        ]);
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
