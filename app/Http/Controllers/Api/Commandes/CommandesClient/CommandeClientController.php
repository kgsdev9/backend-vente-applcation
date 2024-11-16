<?php

namespace App\Http\Controllers\Api\Commandes\CommandesClient;

use App\Http\Controllers\Controller;
use App\Models\TClient;
use App\Models\TCommandeClient;
use App\Models\TCommandeLigneDetailClient;
use App\Models\TReferenceClient;
use App\Traits\RechercheAndPagination;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class CommandeClientController extends Controller
{

    use RechercheAndPagination;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // Créer la requête pour les utilisateurs (instance de Builder)
        $query = TCommandeClient::query()
                                ->orderByDesc('created_at');

        if ($request->has('client_id'))
        {
            $query->where('client_id', $request->client_id);
        }
        // Champs sur lesquels on peut effectuer une recherche
        $critererecherche = ['codecommande', 'datecmde'];

        // Relations à charger
        $relations = ['client'];
        // Appliquer la recherche et la pagination via le trait
        $clients = $this->applySearchAndPagination($query, $request, $critererecherche, $relations);
        return response()->json($clients);
    }

    public function printall(Request $request)
    {
        // Récupérer les paramètres de la requête
        $client_id = $request->input('client_id');
        $searchQuery = $request->input('searchQuery');

        $commandes = TCommandeClient::with(['client', 'referenceclients'])
            ->when($client_id, function ($query, $client_id) {
                return $query->where('client_id', $client_id);
            })
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('codecommande', 'like', '%' . $searchQuery . '%');
            })->orderBy('created_at', 'desc')
             ->get();

        // Initialiser FPDF en mode paysage (L)
        $pdf = new Fpdf();
        $pdf->AddPage('L'); // Paysage

        // Titre du document
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Liste des Commandes des Clients'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, utf8_decode('Code Cmde'), 1);
        $pdf->Cell(60, 10, utf8_decode('Client'), 1);
        $pdf->Cell(35, 10, utf8_decode('Date Cmde'), 1);
        $pdf->Cell(30, 10, utf8_decode('Qte Cmde'), 1);
        $pdf->Cell(30, 10, utf8_decode('Mnt (HT)'), 1);
        $pdf->Cell(30, 10, utf8_decode('Statut'), 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);

        foreach ($commandes as $commande) {
            $pdf->Cell(40, 10, utf8_decode($commande->codecommande), 1);
            $pdf->Cell(60, 10, utf8_decode($commande->client->libtiers ?? 'N/A'), 1); // Afficher le nom du client
            $pdf->Cell(35, 10, utf8_decode($commande->datecmde), 1);
            $pdf->Cell(30, 10, utf8_decode($commande->quantitecmde), 1);
            $pdf->Cell(30, 10, utf8_decode(number_format($commande->montantht, 2)), 1);

            $statut = '';
            switch ($commande->statutcmde) {
                case 0:
                    $statut = 'En cours';
                    break;
                case 1:
                    $statut = 'Confirmé';
                    break;
                case 2:
                    $statut = 'Annulé';
                    break;
                default:
                    $statut = 'Inconnu';
                    break;
            }
            $pdf->Cell(30, 10, utf8_decode($statut), 1);
            $pdf->Ln();

            if ($commande->referenceclients->isNotEmpty()) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(60, 8, utf8_decode('Référence'), 1);
                $pdf->Cell(35, 8, utf8_decode('Qte Cmde'), 1);
                $pdf->Cell(30, 8, utf8_decode('Prix Unitaire'), 1);
                $pdf->Cell(30, 8, utf8_decode('Montant HT'), 1);
                $pdf->Ln();

                // Remplir les détails de commande
                $pdf->SetFont('Arial', '', 11);
                foreach ($commande->referenceclients as $detail) {
                    $pdf->Cell(60, 8, utf8_decode($detail->reference), 1);
                    $pdf->Cell(35, 8, utf8_decode($detail->quantitecmde), 1);
                    $pdf->Cell(30, 8, utf8_decode(number_format($detail->prixunitaire, 2)), 1);
                    $pdf->Cell(30, 8, utf8_decode(number_format($detail->montantht, 2)), 1);
                    $pdf->Ln();
                }
                $pdf->Ln(5);
            }
        }

        // Retourner le fichier PDF en réponse HTTP
        return response($pdf->Output('S', 'liste_commandes.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="liste_commandes.pdf"');
    }


    public function getAllReferenceClient($id)
    {
        $client = TClient::with('references')->findOrFail($id);
        return response()->json(['references' => $client->references]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Créer la commande principale
        $commande = TCommandeClient::create([
            'client_id' => $request->client_id,
            'codecommande' => 'CM-' . time(),
            'montantadsci' => $request->montantadsci,
            'montanttva' => $request->montantTVA,
            'datecmde' =>  $request->datecmde ?? now(),
            'montantht' => $request->montantHT,
            'montanttc' => $request->montantTTC,
        ]);

        // Enregistrer les détails de la commande
        foreach ($request->items as $item) {
            $commande->referenceclients()->create([
                'codecommande' => $commande->codecomde,
                'reference' => $item['designation'],
                'numligne' => $commande->referenceclients()->count() + 1,
                'prixunitaire' => $item['prix'],
                'quantitecmde' => $item['quantite'],
                'remiseligne' => $item['remiseArticle'] ?? 0,
                'montantht' => $item['montant'],
            ]);
        }

        // Retourner une réponse appropriée
        return response()->json(['message' => 'Commande créée avec succès.', 'commande' => $commande->codecmde], 201);
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
        $cmdeclient = TCommandeClient::findOrFail($id);
        $itemsCmdes = TCommandeLigneDetailClient::where('codecommande', $cmdeclient->codecommande)->get();
        $listereferenceclient = TReferenceClient::where('t_client_id', $cmdeclient->client_id)->get();

        return response()->json([
            'cmdeclient'=> $cmdeclient,
            'itemscmdes'=> $itemsCmdes,
            'listeferencesclient'=> $listereferenceclient,
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
        // Trouver la commande principale existante
        $commande = TCommandeClient::where('codecommande', $request->codecommande)->firstOrFail();

        // Mettre à jour les informations de la commande principale
        $commande->update([
            'client_id' => $request->client_id,
            'datecmde' => $request->datecmde,
            'montanttva' => $request->montantva,
            'montantht' => $request->montantht,
            'montanttc' => $request->montantttc,
        ]);

        // Supprimer les anciens détails de la commande
        $commande->referenceclients()->delete();

        // Réinsérer les détails de la commande
        foreach ($request->items as $item)
        {
            $commande->referenceclients()->create([
                'codecommande' => $commande->codecommande,
                'reference' => $item['reference'],
                'numligne' => $commande->referenceclients()->count() + 1,
                'prixunitaire' => $item['prixunitaire'],
                'quantitecmde' => $item['quantite'],
                'remiseligne' => $item['remiseligne'] ?? 0,
                'montantht' => $item['montantht'],
            ]);
        }

        // Retourner une réponse appropriée
        return response()->json(['message' => 'Commande mise à jour avec succès.', 'commande' => $commande->codecommande], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client =  TCommandeClient::find($id);
        $client->delete();
       return response()->json('Suppression de la cmde');
    }
}
