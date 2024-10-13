<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Models\TcodeDevise;
use App\Models\TregimeFiscal;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allCodeDevise()
    {
        $listecodedevise = TcodeDevise::all();
        return response()->json($listecodedevise);
    }

    public function allRegimefiscale()
    {
        $listecodedevise = TregimeFiscal::all();
        return response()->json($listecodedevise);
    }


}
