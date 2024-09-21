<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleElement;
use App\Models\Client;
use App\Models\Facture;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $currentYear = date('Y');
        $facturesParMois = Facture::selectRaw('MONTH(created_at) as mois, COUNT(*) as nombre')
            ->whereYear('created_at', $currentYear)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->mois => $item->nombre];
            });

        // Complétez les mois manquants avec 0
        $facturesParMoisComplete = array_replace(
            array_fill(1, 12, 0),
            $facturesParMois->toArray()
        );


        $listearticles = Client::count();
        $articles = Article::count();
        $facture = Facture::count();
        // Calcul du chiffre d'affaires du mois
            $chiffreAffairesMois = ArticleElement::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get()
            ->sum(function ($element) {
                $prix = (float) $element->prix;
                $quantite = (int) $element->quantite;
                $remise = (int) $element->remisearticle;

                $montant = $prix * $quantite;
                $montantAvecRemise = $montant - ($montant * $remise / 100);

                return $montantAvecRemise;
            });

        // Calcul du chiffre d'affaires de l'année (ou autre valeur statique)
        $chiffreAffairesAnnee = 344;

        // Structure des données à envoyer en réponse JSON
        $response = [
            'chiffreAffairesMois' => $chiffreAffairesMois,
            'chiffreAffairesAnnee' => $chiffreAffairesAnnee,
            'facturesParMoisComplete'=> $facturesParMoisComplete,
            'countclient'=>$listearticles,
            'countarticle'=>$articles,
            'countfacture'=> $facture
        ];

        return response()->json($response);
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
