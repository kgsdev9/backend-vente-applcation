<?php

namespace App\Http\Controllers\API\Factures\Etat;

use App\Http\Controllers\Controller;
use App\Models\TFacture;
use App\Models\TfactureLigne;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class EtatFactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function generateFacture($codefacture)
    {

        $facture  = TFacture::where('numvente', 'like',  $codefacture)->first();
        $factureLigne = TfactureLigne::where('numvente', 'like',  $codefacture)->get();

        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        // Logo
        // $logoPath = public_path('homes-assets/images/PrintHead_SONACO.png');
        // if (file_exists($logoPath))
        // {
        //     $logoWidth = 100;
        //     $pageWidth = 210;
        //     $xPosition = ($pageWidth - $logoWidth) / 2;
        //     $pdf->Image($logoPath, $xPosition, 8, $logoWidth);
        // }

        // Ajouter de l'espace entre le logo et le texte PROFORMA
        $pdf->SetXY(36, 28);  // Position du texte "PROFORMA" (augmenter Y pour de l'espace)
        $pdf->SetFont('Arial', 'B', 25);
        $pdf->Cell(190, 10, 'FACTURE', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 8);

        // Première section
        // Cellule "Numéro"
        $pdf->SetXY(5, 40); // Position initiale
        $pdf->Cell(20, 5, utf8_decode('Numéro'), 1, 0, 'C');

        // Cellule "Date Facture"
        $pdf->Cell(25, 5, utf8_decode('Date Facture'), 1, 0, 'C');

        // Valeurs
        $pdf->SetFont('Arial', '', 8);

        // Valeur "Numéro"
        $pdf->SetXY(5, 45); // Position ajustée pour la valeur du numéro
        $pdf->Cell(20, 5, 'numproformavte', 1, 0, 'C');

        // Valeur "Date Facture"
        $pdf->Cell(25, 5, date('d/m/Y', strtotime($facture->DateFacture)), 1, 0, 'C');

        // Bon de commande et date
        $pdf->SetFont('Arial', 'B', 8);

        // Cellule "Numéro"
        $pdf->SetXY(5, 50); // Position décalée pour éviter de superposer
        $pdf->Cell(20, 5, utf8_decode('Votre Bon n °'), 1, 0, 'C');

        // Cellule "Date Facture"
        $pdf->Cell(25, 5, utf8_decode('Date'), 1, 0, 'C');

        // Valeurs
        $pdf->SetFont('Arial', '', 8);

        // Valeur "Numéro"
        $pdf->SetXY(5, 55); // Position ajustée pour la valeur du numéro
        $pdf->Cell(20, 5, '', 1, 0, 'C');

        // Valeur "Date Facture"
        $pdf->Cell(25, 5, date('d/m/Y', strtotime($facture->created_at)), 1, 0, 'C');

        // num vente et date
        $pdf->SetFont('Arial', 'B', 8);

        // Cellule "Numéro"
        $pdf->SetXY(5, 60); // Position décalée pour éviter de superposer
        $pdf->Cell(20, 5, utf8_decode('Vente n° :'), 1, 0, 'C');

        // Cellule "Date Facture"
        $pdf->Cell(25, 5, utf8_decode('Date'), 1, 0, 'C');


        // Cellule "Numéro"
        $pdf->SetXY(1, 72); // Position décalée pour éviter de superposer
        $pdf->Cell(20, 5, utf8_decode('N° COC :'), 0, 0, 'C');


        // Valeurs
        $pdf->SetFont('Arial', '', 8);

        // Valeur "Numéro"
        $pdf->SetXY(5, 65); // Position ajustée pour la valeur du numéro
        $pdf->Cell(20, 5, $facture->NumVente, 1, 0, 'C');

        // Valeur "Date Facture"
        $pdf->Cell(25, 5, date('d/m/Y', strtotime($facture->DateFacture)), 1, 0, 'C');


        // Bloc principal : Infos Client et Entreprise (remontée)
        $pdf->SetXY(60, 39); // Remontée encore plus haut
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(145, 38, '', 1, 1, 'C');

        $pdf->SetXY(62, 45); // Remontée
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 6, 'N compte client', 1, 1, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(62, 51); // Remontée
        $pdf->Cell(50, 6, $facture->NumCpteClient, 1, 1, 'C');

        $pdf->SetXY(130, 42); // Remontée
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(58, 6, $facture->LibClient, 0, 1, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(130, 63); // Remontée
        $pdf->MultiCell(58, 5, '', 0, 'C');


        // Téléphone et Fax sur la même ligne remonté à 64 px vers le bas
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(130, 64); // Position de départ pour les deux informations


        // $pdf->SetXY(140, 51); // Positionnez le curseur à 10 mm depuis le bord gauche
        // $pdf->Cell(48, 5, utf8_decode('Tél : '. $facture->client->Telephone), 0, 0, 'L');


        // Téléphone
        $pdf->SetXY(140, 60); // Positionnez le curseur à 10 mm depuis le bord gauche
        $pdf->Cell(48, 5, utf8_decode('Tél : ' . $facture->telephone ?? 'rien'), 0, 0, 'L');

        // Ajouter un espace de 10
        $pdf->Cell(10, 5, '', 0, 0, 'C');

        // Fax
        $pdf->SetXY(140, 66); // Positionnez le curseur à 10 mm depuis le bord gauche
        $pdf->Cell(48, 5, 'Fax : ' . $facture->fax ?? 'rien', 0, 1, 'L');


        // Texte en bas
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(150, 70); // Ajuster la position en bas de la page
        $pdf->Cell(12, 5, utf8_decode('Membre adhérent de la structure exportatrice agréée :'), 0, 0, 'C');


        $margeHaute = 2;
        $pdf->SetXY(62, 56 + $margeHaute); // Marge ajoutée avant "N compte contribuable"
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 6, utf8_decode('N° C.C : ' . $facture->NumCpteContribuable), 0, 1, 'C');

        // numero R.C.C.M
        $pdf->SetXY(62, 64); // Marge ajoutée avant "N compte contribuable"
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 6, utf8_decode('R.C.C.M:' . $facture->NumCpteContribuable), 0, 1, 'C');



        $pdf->SetLineWidth(0.1);
        $pdf->Rect(5, 79, 200, 110, "D"); // Bordure générale du tableau
        $pdf->Line(5, 90, 205, 90);

        // Définition des lignes verticales pour séparer les colonnes
        $pdf->Line(25, 79, 25, 189); // Séparation pour "Quantité"
        $pdf->Line(110, 79, 110, 189); // Séparation entre "Référence" et "Élément"
        $pdf->Line(140, 79, 140, 189); // Séparation entre "Élément" et "PU HT"
        $pdf->Line(165, 79, 165, 189); // Séparation entre "PU HT" et "Montant HT"

        // Utiliser une taille de police plus petite pour l'en-tête
        $pdf->SetFont('Arial', 'B', 7);

        // Définition des en-têtes des colonnes
        $pdf->SetXY(5, 80);
        $pdf->Cell(20, 8, utf8_decode("Quantité"), 0, 0, 'C'); // Colonne Quantité (réduite à 20)
        $pdf->SetXY(43, 80);
        $pdf->Cell(45, 8, utf8_decode("Désignation"), 0, 0, 'C'); // Colonne Référence (45)
        $pdf->SetXY(90, 80);
        $pdf->Cell(70, 8, utf8_decode("Catégorie"), 0, 0, 'C'); // Colonne Élément (élargie à 70)
        $pdf->SetXY(140, 80);
        $pdf->Cell(25, 8, "Prix Unit. HT", 0, 0, 'C'); // Colonne PU HT (réduite à 25)
        $pdf->SetXY(165, 80);
        $pdf->Cell(40, 8, "Montant Hors Taxes", 0, 0, 'C'); // Colonne Montant HT (réduite à 40)

        $yPosition = 95;

        foreach ($factureLigne as $detail) {
            // Quantité
            $pdf->SetXY(5, $yPosition);
            $pdf->Cell(20, 8, number_format($detail->quantite, 0, '.', ' '), 0, 0, 'R');

            // Référence
            $pdf->SetXY(25, $yPosition);
            $pdf->Cell(70, 8, utf8_decode($detail->product->libelleproduct), 0, 0, 'L');

            // Élément
            $pdf->SetXY(110, $yPosition);

            $pdf->Cell(70, 8, utf8_decode($detail->product->category->libellecategorieproduct), 0, 0, 'L');

            // Prix Unitaire
            $pdf->SetXY(149, $yPosition);
            $pdf->Cell(25, 8, number_format($detail->prix_unitaire, 0, '.', ' '), 0, 0, 'C');

            // Montant HT
            $pdf->SetXY(180, $yPosition);
            $pdf->Cell(40, 8, number_format($detail->montant_ttc, 0, '.', ' '), 0, 0, 'C');

            // Ajouter une ligne supplémentaire en dessous pour plus de détails (par exemple, RefSONACO)
            $yPosition += 4;
            $pdf->SetXY(25, $yPosition);
            $pdf->Cell(85, 8, utf8_decode($detail->RefSONACO), 0, 0, 'L');

            // Espacement pour la prochaine ligne principale
            $yPosition += 6;
        }



        // Pied de tableau - Première section
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(5, 189); // Position initiale
        $pdf->Cell(20, 6, "Base", 1, 0, 'C');  // Largeur réduite à 20
        $pdf->Cell(20, 6, "Taux", 1, 0, 'C');  // Largeur réduite à 20
        $pdf->Cell(20, 6, "TVA", 1, 0, 'C');   // Largeur réduite à 20

        // Ajout de l'espace de 5 unités
        $pdf->SetX($pdf->GetX() + 5); // Décalage horizontal de 5

        $pdf->Ln(); // Nouvelle ligne
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(5, 195); // Position pour les valeurs
        $pdf->Cell(20, 6, number_format($facture->TotalHT, 0, '.', ' '), 1, 0, 'C');  // Première colonne
        $pdf->Cell(20, 6, "18%", 1, 0, 'C');  // Deuxième colonne
        $pdf->Cell(20, 6, number_format($facture->TotalHT * 0.18, 0, '.', ' '), 1, 0, 'C'); // Troisième colonne



        //pieds du tableau à droite

        $montantTVA =  $facture->TotalHT * 0.18;
        $motantADSI = ($facture->TotalHT + $montantTVA) * 0.05;

        $totalttc =  number_format($montantTVA + $motantADSI + $facture->TotalHT, '0', ' .', ' ');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(140, 189); // Position initiale
        $pdf->Cell(30, 6, "TOTAL HT", 1, 0, 'C');
        // Cellule bordure avec texte aligné à droite
        $pdf->Cell(35, 6, number_format($facture->TotalHT, 0, '.', ' '), 1, 1, 'R');


        $pdf->SetXY(140, 195); // Remontée
        $pdf->Cell(30, 6, "TVA", 1, 0, 'C');
        // Cellule bordure avec texte aligné à droite
        $pdf->Cell(35, 6, number_format($montantTVA, 0, '.', ' '), 1, 1, 'R');

        $pdf->SetXY(140, 201); // Remontée
        $pdf->Cell(30, 6, "TOTAL TTC", 1, 0, 'C');
        // Cellule bordure avec texte aligné à droite
        $pdf->Cell(35, 6, number_format($facture->TotalTTC, 0, '.', ' '), 1, 1, 'R');

        // Nouvelle ligne AIRSI
        $pdf->SetXY(140, 207); // Position ajustée
        $pdf->Cell(30, 6, "AIRSI", 1, 0, 'C');
        // Cellule bordure avec texte aligné à droite
        $pdf->Cell(35, 6, number_format(($facture->TotalHT + $facture->TotalHT) * 0.18 * $facture->TauxTaxe ?? 0, 0, '.', ' '), 1, 1, 'R');

        $pdf->SetXY(140, 213); // Remontée
        $pdf->Cell(30, 6, "NET A PAYER", 1, 0, 'C');
        // Cellule bordure avec texte aligné à droite
        $pdf->Cell(35, 6, number_format(('45.00'), 0, '.', ' '), 1, 1, 'R');

        $borderX = 4;
        $borderY = 221; // Position Y du rectangle remontée de 10
        $borderWidth = 199;
        $borderHeight = 17;

        $pdf->SetLineWidth(0);
        $pdf->Rect($borderX, $borderY, $borderWidth, $borderHeight, 'D');

        $pdf->SetLineWidth(1.5);
        $pdf->Line($borderX + $borderWidth, $borderY, $borderX + $borderWidth, $borderY + $borderHeight);
        $pdf->Line($borderX, $borderY + $borderHeight, $borderX + $borderWidth, $borderY + $borderHeight);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(5, 223); // Remontée de 10 unités
        $pdf->Cell(50, 5, utf8_decode("Mode de  règlement :"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(60, 5, utf8_decode($facture->modereglement->LibReglement ?? ''), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(5); // Remontée de 5 unités
        $pdf->Cell(50, 5, "Regime de vente :", 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60, 5, utf8_decode(''), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(5); // Remontée de 5 unités
        $pdf->Cell(50, 5, utf8_decode("Delai de règlement :"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60, 5, "60 jours date de facture", 0, 1, 'L');

        // exemplaire comptabilité
        $pdf->SetXY(160, 244); // Décalage vers le bas
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 5, "EXEMPLAIRE COMPTABILITE", 0, 0, 'R');

        return response($pdf->Output('S', 'facture.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="facture.pdf"');
        // Sortie du PDF
        $pdf->Output('I', 'facture.pdf');
        exit;



        $fileName = 'Facture' . '.pdf';
        $filePath = storage_path('app/public/proformas/' . $fileName);

        if (!file_exists(storage_path('app/public/proformas'))) {
            mkdir(storage_path('app/public/proformas'), 0777, true);
        }

        $pdf->Output('F', $filePath);

        $fileUrl = asset('storage/proformas/' . $fileName);


        return response()->json([
            'success' => true,
            'message' => 'PDF généré avec succès.',
            'file_url' => $fileUrl, // URL pour accéder au fichier
        ]);
    }




    public function generateListeVenteGroupeParMoisSanstotal()
    {
        // Récupérer les données de la table TfACTURE
        $factures = TFacture::where('numvente', 'like', 'vp%')->get();

        // Initialisation de FPDF
        $pdf = new FPDF();
        $pdf->AddPage('L', 'A4'); // Paysage (Landscape)
        $pdf->SetFont('Arial', 'B', 14);

        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre

        // En-tête principal
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Cell(0, 10, utf8_decode('ETAT DES VENTES'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, utf8_decode('(Commandes)'), 0, 1, 'C');

        // Trait horizontal gris juste après le titre
        $pdf->Ln(3);
        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre
        $pdf->Ln(5); // Ajoute un petit espace après le trait

        // Informations générales sur une seule ligne
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Première ligne
        $pdf->Cell(95, 0, utf8_decode('De CC24/02506 à CC24/02506'), 0, 1, 'L');

        $pdf->Ln(5); // Ajoute un petit espace après la ligne

        // Seconde ligne
        $pdf->Cell(95, 6, utf8_decode('De US BC RD ISSI BIO F2 G. à US BC RD ISSI BIO F2 G.'), 0, 1, 'L');

        // Ajouter un trait horizontal gris juste après
        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Ligne horizontale

        $pdf->Ln(5); // Ajout d'un espace après la ligne horizontale

        // En-tête du tableau (fond gris)
        $pdf->SetFont('Arial', '', 10); // Non gras
        $pdf->SetFillColor(192, 192, 192);  // Gris
        $headers = [
            'Num Vente',
            'Client',
            'Telephone',
            'Montantht',
            'Montanttva',
            'Montantttc',
            'Created_at',
        ];

        // Largeurs des colonnes ajustées
        $widths = [27, 40, 20, 60, 50, 60, 20];

        // Affichage des en-têtes
        foreach ($headers as $i => $header) {
            $pdf->Cell($widths[$i], 7, utf8_decode($header), 1, 0, 'C', true);
        }

        $pdf->Ln(8); // Ajoute un petit espace après l'en-tête

        // Corps du tableau avec les données dynamiques
        $pdf->SetFont('Arial', '', 10);

        // Grouper les factures par mois
        $facturesGroupedByMonth = $factures->groupBy(function ($facture) {
            return $facture->created_at->format('m-Y'); // Groupement par mois et année
        });

        foreach ($facturesGroupedByMonth as $month => $monthlyFactures) {
            // Ajout du titre "Botuique Officielle : PARIS STORE" pour chaque mois
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, utf8_decode('Botuique Officielle : PARIS STORE '), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, utf8_decode('Par mois : ' . $month), 0, 1, 'L'); // Affiche le mois et l'année

            // Remplissage des données dynamiques dans le tableau pour ce mois
            foreach ($monthlyFactures as $facture) {
                $pdf->Cell($widths[0], 7, utf8_decode($facture->numvente), 1, 0, 'C');
                $pdf->Cell($widths[1], 7, utf8_decode(\Str::limit($facture->libelleclient, 12)), 1, 0, 'C');
                $pdf->Cell($widths[2], 7, utf8_decode($facture->telephone), 1, 0, 'C');
                $pdf->Cell($widths[3], 7, utf8_decode((string)$facture->montantht), 1, 0, 'C');
                $pdf->Cell($widths[4], 7, utf8_decode((string)$facture->montanttva), 1, 0, 'C');
                $pdf->Cell($widths[5], 7, utf8_decode((string)$facture->montantttc), 1, 0, 'C');
                $pdf->Cell($widths[6], 7, utf8_decode($facture->created_at->format('d/m/Y')), 1, 0, 'C');  // Format de la date
                $pdf->Ln(); // Nouvelle ligne après chaque facture
            }
        }

        // Trait horizontal juste après la note
        $pdf->Ln(2); // Ajoute un petit espace après le tableau

        // Retourner le PDF pour le téléchargement
        return response($pdf->Output('S', 'facture.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="facture.pdf"');

        // Sortie du PDF
        // $pdf->Output('I', 'facture.pdf');
        // exit;
    }

    public function generateListeVente(Request $request)
    {

        $datevente = $request->input('datevente');
        $searchQuery = $request->input('search');

        $facturesQuery = TFacture::where('numvente', 'like', 'vp%');

        if (!empty($searchQuery)) {
            $facturesQuery->where('numvente', 'like', "%{$searchQuery}%");
        }

        if (!empty($datevente)) {

            $facturesQuery->whereDate('created_at', '=', $datevente);
        }

        // Récupération des factures
        $factures = $facturesQuery->get();



        // Initialisation de FPDF
        $pdf = new FPDF();
        $pdf->AddPage('L', 'A4'); // Paysage (Landscape)
        $pdf->SetFont('Arial', 'B', 14);

        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre

        // En-tête principal
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Cell(0, 10, utf8_decode('ETAT DES VENTES'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, utf8_decode('(Liste des ventes génerées)'), 0, 1, 'C');

        // Trait horizontal gris juste après le titre
        $pdf->Ln(3);
        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre
        $pdf->Ln(5); // Ajoute un petit espace après le trait

        // Informations générales sur une seule ligne
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Première ligne
        $pdf->Cell(95, 0, utf8_decode('Critere der recherche par date  ' . $datevente .  ' par entrée ' . $searchQuery), 0, 1, 'L');
        $pdf->Ln(5); // Ajoute un petit espace après la ligne

        // Seconde ligne
        $pdf->Cell(95, 6, utf8_decode($factures->sum('montantttc')) . ' ' . 'FCFA', 0, 1, 'L');

        // Ajouter un trait horizontal gris juste après
        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Ligne horizontale

        $pdf->Ln(5); // Ajout d'un espace après la ligne horizontale

        // En-tête du tableau (fond gris)
        $pdf->SetFont('Arial', '', 10); // Non gras
        $pdf->SetFillColor(192, 192, 192);  // Gris
        $headers = [
            'Num Vente',
            'Client',
            'Telephone',
            'Montantht',
            'Montanttva',
            'Montantttc',
            'Created_at',
        ];

        // Largeurs des colonnes ajustées
        $widths = [27, 40, 20, 60, 50, 60, 20];

        // Affichage des en-têtes
        foreach ($headers as $i => $header) {
            $pdf->Cell($widths[$i], 7, utf8_decode($header), 1, 0, 'C', true);
        }

        $pdf->Ln(8); // Ajoute un petit espace après l'en-tête

        // Corps du tableau avec les données dynamiques
        $pdf->SetFont('Arial', '', 10);

        // Grouper les factures par mois
        $facturesGroupedByMonth = $factures->groupBy(function ($facture) {
            return $facture->created_at->format('m-Y'); // Groupement par mois et année
        });

        foreach ($facturesGroupedByMonth as $month => $monthlyFactures) {
            // Ajout du titre "Botuique Officielle : PARIS STORE" pour chaque mois
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, utf8_decode('Botuique Officielle : PARIS STORE '), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, utf8_decode('Par mois : ' . $month), 0, 1, 'L'); // Affiche le mois et l'année

            // Initialiser la variable de somme pour chaque mois
            $totalTtcForMonth = 0;

            // Remplissage des données dynamiques dans le tableau pour ce mois
            foreach ($monthlyFactures as $facture) {
                $pdf->Cell($widths[0], 7, utf8_decode($facture->numvente), 1, 0, 'C');
                $pdf->Cell($widths[1], 7, utf8_decode(\Str::limit($facture->libelleclient, 12)), 1, 0, 'C');
                $pdf->Cell($widths[2], 7, utf8_decode($facture->telephone), 1, 0, 'C');
                $pdf->Cell($widths[3], 7, utf8_decode((string)$facture->montantht), 1, 0, 'C');
                $pdf->Cell($widths[4], 7, utf8_decode((string)$facture->montanttva), 1, 0, 'C');
                $pdf->Cell($widths[5], 7, utf8_decode((string)$facture->montantttc), 1, 0, 'C');
                $pdf->Cell($widths[6], 7, utf8_decode($facture->created_at->format('d/m/Y')), 1, 0, 'C');  // Format de la date
                $pdf->Ln(); // Nouvelle ligne après chaque facture

                // Ajouter au total du mois
                $totalTtcForMonth += $facture->montantttc;
            }

            $pdf->SetFont('Arial', 'B', 10);

            // Définir une largeur spécifique pour la cellule (ex. 80 mm ou 100)
            $cellWidth = 70;  // Vous pouvez ajuster cette valeur pour réduire ou agrandir la bordure
            // Déplacer le curseur X vers la droite avant de créer la cellule
            $pdf->SetX(217);  // Ajustez cette valeur en fonction de la position souhaitée (par exemple, 150 mm ou 160 mm)

            // Créer la cellule avec la bordure à une longueur réduite
            $pdf->Cell($cellWidth, 6, utf8_decode('Total' . $month . ' : ' . number_format($totalTtcForMonth, 0, '.', ' ') . ' FCFA'), 1, 1, 'R');
            // Ajouter un petit espace après le total
            $pdf->Ln(5);
        }

        // Trait horizontal juste après la note
        $pdf->Ln(2); // Ajoute un petit espace après le tableau

        // Retourner le PDF pour le téléchargement
        return response($pdf->Output('S', 'facture.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="facture.pdf"');
    }
}
