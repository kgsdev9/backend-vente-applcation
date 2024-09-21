<?php

namespace App\Http\Controllers\Api\Rapport;

use App\Exports\RapportExport;
use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RapportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportpdf(Request $request)
    {
        $debut = $request->input('debut');
        $fin = $request->input('fin');

        $factures = Facture::whereBetween('created_at', [$debut, $fin])
            ->with('items')
            ->get();

        $total = $factures->flatMap(function ($facture) {
            return $facture->items;
        })->reduce(function ($carry, $item) {
            return $carry + ($item->quantite * $item->prix - $item->remisearticle);
        }, 0);

        $pdf = PDF::loadView('listerapport', ['factures' => $factures, 'debut' => $debut, 'fin' => $fin, 'total' => $total]);
        return $pdf->download('factures_report.pdf');
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

    public function exportExcell(Request $request)
    {
    $debut = $request->input('debut');
    $fin = $request->input('fin');

    return Excel::download(new RapportExport($debut, $fin), 'factures_report.xlsx');
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
