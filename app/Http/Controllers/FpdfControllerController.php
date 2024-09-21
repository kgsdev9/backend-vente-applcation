<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;


class FpdfControllerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new Fpdf;
    }

    public function generateInvoice()
    {
    	$this->fpdf->SetFont('Arial', 'B', 15);
        $this->fpdf->AddPage("L", ['100', '100']);
        $this->fpdf->Text(10, 10, "Hello World!");

       
        $this->fpdf->Output();

        exit;
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
     * @param  \App\Models\Generate\FpdfController  $fpdfController
     * @return \Illuminate\Http\Response
     */
    public function show(FpdfController $fpdfController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Generate\FpdfController  $fpdfController
     * @return \Illuminate\Http\Response
     */
    public function edit(FpdfController $fpdfController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Generate\FpdfController  $fpdfController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FpdfController $fpdfController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Generate\FpdfController  $fpdfController
     * @return \Illuminate\Http\Response
     */
    public function destroy(FpdfController $fpdfController)
    {
        //
    }
}
