<?php

namespace App\Http\Controllers\Api\Commande\Etat;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class EtatCommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function generate($id)
     {
         // Récupérer la facture avec ses articles
         $facture = Facture::with('items')->findOrFail($id);

         // Instancier FPDF
         $pdf = new Fpdf();
         $pdf->AddPage();

         // En-tête de la facture - Titre "COMMANDE"
         $pdf->SetFont('Arial', 'B', 14);
         $pdf->Cell(0, 10, 'COMMANDE', 0, 1, 'C');
         $pdf->Ln(10); // Espacement supplémentaire

         // Bloc Numéro et Date de commande
         $pdf->SetFont('Arial', '', 10);
         $pdf->SetX(10);
         $pdf->Cell(20, 7, 'Numero:', 0, 0, 'L');
         $pdf->Cell(30, 7, $facture->numeroFacture, 0, 0, 'L'); // Utiliser le numéro de la facture

         $pdf->Cell(15, 7, 'Date:', 0, 0, 'L');
         $pdf->Cell(25, 7, $facture->created_at->format('d/m/Y'), 0, 1, 'L'); // Utiliser la date de création de la facture
         $pdf->Ln(5); // Espacement vertical

         // Informations de la société
         $pdf->SetFont('Arial', 'B', 12);
         $pdf->SetXY(110, 30); // Positionner à droite
         $pdf->Cell(90, 10, $facture->client->nom, 1, 1, 'C'); // Cellule avec bordure et centrage

         // Informations de contact
         $pdf->SetFont('Arial', '', 10);
         $pdf->SetXY(110, 40);
         $pdf->Cell(90, 10, '16 bis, rue dragon 13006 France', 0, 1, 'C');
         $pdf->SetXY(110, 50);
         $pdf->Cell(90, 10, 'Fax:  '.  $facture->client->fax, 0, 1, 'C');
         $pdf->Ln(10); // Espacement après l'en-tête

         // Texte introductif
         $pdf->SetFont('Arial', '', 10);
         $pdf->Cell(0, 5, 'Messieurs,', 0, 1);
         $pdf->MultiCell(0, 5, 'Nous vous prions de noter la commande suivante:');
         $pdf->Ln(5);

         // En-tête du tableau
         $pdf->SetFont('Arial', 'B', 10);
         $pdf->Cell(90, 7, 'Désignation', 1, 0, 'C'); // Désignation avant la quantité
         $pdf->Cell(10, 7, 'Qte', 1, 0, 'C'); // Quantité
         $pdf->Cell(25, 7, 'P.U.(FCFA)', 1, 0, 'C');
         $pdf->Cell(10, 7, 'R', 1, 0, 'C');
         $pdf->Cell(35, 7, 'Montant HT', 1, 0, 'C');
         $pdf->Ln();

         // Données des articles
         $pdf->SetFont('Arial', '', 10);
         foreach ($facture->items as $article) {


             // Utilisation de MultiCell pour la colonne "Désignation"
             $x = $pdf->GetX();
             $y = $pdf->GetY();
             $pdf->MultiCell(90, 6, $article->article->designation, 1); // Désignation
             $pdf->SetXY($x + 90, $y); // Remet le curseur à la bonne position

             // Quantité
             $pdf->Cell(10, 6, $article->quantite, 1, 0, 'C');
             $pdf->Cell(25, 6, $article->prix, 1, 0, 'C'); // Prix unitaire
             $pdf->Cell(10, 6, $article->remisearticle, 1, 0, 'C'); // Remise
             $pdf->Cell(35, 6, number_format($article->quantite * $article->prix, 4), 1, 0, 'C'); // Montant HT calculé
             $pdf->Ln();
         }

         // Total HT
         $pdf->SetFont('Arial', 'B', 10);
         $pdf->Cell(135, 7, 'TOTAL HT(E)', 1, 0, 'R');
         $pdf->Cell(35, 7, number_format($facture->montantht, 4), 1, 0, 'C'); // Montant total HT
         $pdf->Ln(10);

         // Texte supplémentaire
         $pdf->SetFont('Arial', '', 10);
         $pdf->MultiCell(0, 5, "Nous vous demandons de nous faire parvenir dans les plus brefs délais, la facture proforma revêtue de votre signature...");
         $pdf->Ln(5);

         // Informations de réception et règlement
         $pdf->MultiCell(0, 5, "Mode de reglement:" . $facture->modereglement->nom);
         $pdf->Ln(10);

         // Signatures
         $pdf->Cell(60, 10, 'Redacteur', 1, 0, 'C');
         $pdf->Cell(60, 10, 'Directeur de departement', 1, 0, 'C');
         $pdf->Cell(60, 10, 'Directeur General', 1, 1, 'C');
         $pdf->Ln(10);

         return response($pdf->Output('S', 'facture.pdf'), 200)
         ->header('Content-Type', 'application/pdf')
         ->header('Content-Disposition', 'attachment; filename="facture.pdf"');


         // Sortie du PDF
         $pdf->Output('I', 'facture.pdf');
         exit;
     }



    //  public function generate($id)
    //  {
    //      // Instancier FPDF
    //      $pdf = new Fpdf();
    //      $pdf->AddPage();

    //      // En-tête de la facture - Titre "COMMANDE"
    //      $pdf->SetFont('Arial', 'B', 14);
    //      $pdf->Cell(0, 10, 'COMMANDE', 0, 1, 'C');
    //      $pdf->Ln(10); // Espacement supplémentaire

    //      // Bloc Numéro et Date de commande
    //      $pdf->SetFont('Arial', '', 10);
    //     // Bloc à gauche pour le numéro de commande
    //     $pdf->SetX(10);
    //     $pdf->Cell(20, 7, 'Numero:', 0, 0, 'L');  // Réduire la largeur de la cellule pour "Numero:"
    //     $pdf->Cell(30, 7, 'CA2300341', 0, 0, 'L');  // Réduire la largeur de la cellule pour le numéro de commande

    //     // Bloc à droite pour la date de commande
    //     $pdf->Cell(15, 7, 'Date:', 0, 0, 'L');  // Réduire la largeur de la cellule pour "Date:"
    //     $pdf->Cell(25, 7, '23/03/2023', 0, 1, 'L');  // Réduire la largeur de la cellule pour la date de commande
    //     $pdf->Ln(5); // Espacement vertical

    //      // Agrandir la partie droite - Informations de la société
    //      $pdf->SetFont('Arial', 'B', 12);
    //      $pdf->SetXY(110, 30); // Positionner à droite
    //      $pdf->Cell(90, 10, 'BLG INTERNATIONAL', 1, 1, 'C'); // Cellule avec bordure et centrage

    //      // Informations de contact sous le titre
    //      $pdf->SetFont('Arial', '', 10); // Taille réduite
    //      $pdf->SetXY(110, 40); // Positionner sous le titre de la société
    //      $pdf->Cell(90, 10, '16 bis, rue dragon 13006 France', 0, 1, 'C');
    //      $pdf->SetXY(110, 50);
    //      $pdf->Cell(90, 10, 'Fax: +33 4 96 17 60 14', 0, 1, 'C');
    //      $pdf->Ln(10); // Espacement après l'en-tête

    //      // Texte introductif pour la commande
    //      $pdf->SetFont('Arial', '', 10);
    //      $pdf->Cell(0, 5, 'Messieurs,', 0, 1);
    //      $pdf->MultiCell(0, 5, 'Nous vous prions de noter la commande suivante:');
    //      $pdf->Ln(5);

    //      // En-tête du tableau avec des bordures et texte centré
    //      $pdf->SetFont('Arial', 'B', 10);
    //      $pdf->Cell(10, 7, 'Qte', 1, 0, 'C');
    //      $pdf->Cell(90, 7, 'Désignation', 1, 0, 'C'); // Augmentation de la largeur
    //      $pdf->Cell(20, 7, 'Unité', 1, 0, 'C');
    //      $pdf->Cell(25, 7, 'P.U.(E)', 1, 0, 'C');
    //      $pdf->Cell(10, 7, 'R', 1, 0, 'C');
    //      $pdf->Cell(35, 7, 'Montant HT', 1, 0, 'C'); // Augmentation de la largeur
    //      $pdf->Ln();

    //      // Données des articles
    //      $pdf->SetFont('Arial', '', 10);
    //      $references = [
    //          ['qte' => '1', 'designation' => 'MATERIEL MECANIQUE', 'unite' => 'Unité', 'pu' => '696.0000', 'r' => '0', 'montant' => '696.0000'],
    //          ['qte' => '1', 'designation' => 'MATERIEL MECANIQUE', 'unite' => 'Unité', 'pu' => '696.0000', 'r' => '0', 'montant' => '696.0000']
    //      ];

    //         // Données des articles
    //     foreach ($references as $ref) {
    //         $pdf->Cell(10, 6, $ref['qte'], 1, 0, 'C');

    //         // Utilisation de MultiCell pour la colonne "Désignation"
    //         $x = $pdf->GetX();
    //         $y = $pdf->GetY();
    //         $pdf->MultiCell(90, 6, $ref['designation'], 1); // Texte sur plusieurs lignes
    //         $pdf->SetXY($x + 90, $y); // Remet le curseur à la bonne position pour la prochaine cellule

    //         $pdf->Cell(20, 6, $ref['unite'], 1, 0, 'C');
    //         $pdf->Cell(25, 6, $ref['pu'], 1, 0, 'C');
    //         $pdf->Cell(10, 6, $ref['r'], 1, 0, 'C');
    //         $pdf->Cell(35, 6, $ref['montant'], 1, 0, 'C'); // Largeur ajustée
    //         $pdf->Ln();
    //     }

    //     // Total HT(E) avec texte centré et ajustement des largeurs
    //     $pdf->SetFont('Arial', 'B', 10);

    //     // Ajustement du total pour correspondre à la largeur des colonnes précédentes
    //     // La somme des largeurs des colonnes précédentes (Qte + Désignation + Unité + PU + R) est de 10 + 90 + 20 + 25 + 10 = 155
    //     $pdf->Cell(155, 7, 'TOTAL HT(E)', 1, 0, 'R'); // Texte aligné à droite dans cette cellule
    //     $pdf->Cell(35, 7, '696.0000', 1, 0, 'C'); // Total aligné avec la cellule de Montant HT
    //     $pdf->Ln(10);


    //      // Texte supplémentaire
    //      $pdf->SetFont('Arial', '', 10);
    //      $pdf->MultiCell(0, 5, "Nous vous demandons de nous faire parvenir dans les plus brefs délais, la facture proforma revêtue de votre signature...");
    //      $pdf->Ln(5);

    //      // Informations de réception et règlement
    //      $pdf->MultiCell(0, 5, "Mode de reception: PAR AVION \nMode de reglement: Virement");
    //      $pdf->Ln(10);

    //      // Signatures
    //      $pdf->Cell(60, 10, 'Redacteur', 1, 0, 'C');
    //      $pdf->Cell(60, 10, 'Directeur de departement', 1, 0, 'C');
    //      $pdf->Cell(60, 10, 'Directeur General', 1, 1, 'C');
    //      $pdf->Ln(10);

    //      // Sortie du PDF
    //      $pdf->Output('I', 'facture.pdf');
    //      exit;
    //  }




    public function generateEtatMensuel()
    {
        // Instancier FPDF en mode paysage (paysable)
        $pdf = new Fpdf('L', 'mm', 'A4');
        $pdf->AddPage();

        // Titre "ÉTAT MENSUEL DES COMMANDES"
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'ÉTAT MENSUEL DES COMMANDES', 0, 1, 'C');
        $pdf->Ln(5);

        // Données de commande par mois
        $commandes = [
            'Janvier 2024' => [
                ['reference' => 'REF001', 'designation' => 'Produit A', 'prix_unitaire' => '100.00', 'quantite' => 3, 'total' => '300.00'],
                ['reference' => 'REF002', 'designation' => 'Produit B', 'prix_unitaire' => '50.00', 'quantite' => 5, 'total' => '250.00'],
            ],
            'Février 2024' => [
                ['reference' => 'REF003', 'designation' => 'Produit C', 'prix_unitaire' => '200.00', 'quantite' => 2, 'total' => '400.00'],
                ['reference' => 'REF004', 'designation' => 'Produit D', 'prix_unitaire' => '150.00', 'quantite' => 4, 'total' => '600.00'],
            ],
            'Mars 2024' => [
                ['reference' => 'REF005', 'designation' => 'Produit E', 'prix_unitaire' => '75.00', 'quantite' => 6, 'total' => '450.00'],
                ['reference' => 'REF006', 'designation' => 'Produit F', 'prix_unitaire' => '120.00', 'quantite' => 3, 'total' => '360.00'],
            ]
        ];

        // Boucle à travers chaque mois pour générer une section
        foreach ($commandes as $mois => $articles) {
            // En-tête pour le mois
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, $mois, 0, 1, 'L');
            $pdf->Ln(2);

            // En-tête du tableau pour chaque section de mois
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 7, 'Reference', 1, 0, 'C');
            $pdf->Cell(100, 7, 'Désignation', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Prix Unitaire', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Quantité', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Total', 1, 1, 'C'); // End of line

            // Remplissage des lignes pour le mois courant
            $pdf->SetFont('Arial', '', 10);
            foreach ($articles as $article) {
                $pdf->Cell(30, 6, $article['reference'], 1, 0, 'C');
                $pdf->Cell(100, 6, $article['designation'], 1, 0, 'L');
                $pdf->Cell(40, 6, $article['prix_unitaire'], 1, 0, 'R');
                $pdf->Cell(30, 6, $article['quantite'], 1, 0, 'C');
                $pdf->Cell(40, 6, $article['total'], 1, 1, 'R'); // End of line
            }

            // Calcul du total pour le mois
            $totalMois = array_sum(array_column($articles, 'total'));
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(170, 7, 'Total pour ' . $mois, 1, 0, 'R');
            $pdf->Cell(40, 7, number_format($totalMois, 2), 1, 1, 'R'); // Total pour le mois
            $pdf->Ln(10); // Espacement entre les sections de mois
        }

        // Total général pour toutes les commandes
        $totalGeneral = 0;
        foreach ($commandes as $articles) {
            $totalGeneral += array_sum(array_column($articles, 'total'));
        }

        // Total général en bas de page
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(210, 10, 'TOTAL GENERAL DES COMMANDES', 1, 0, 'R');
        $pdf->Cell(40, 10, number_format($totalGeneral, 2), 1, 1, 'R');
        $pdf->Ln(5);

        // Remerciements ou informations complémentaires
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(0, 5, "Ceci est un récapitulatif mensuel de vos commandes pour l'année 2024. Merci de votre collaboration.");

        // Sortie du PDF
        $pdf->Output('I', 'etat_mensuel_commandes.pdf');
        exit;
    }






     public function listecommandes()
     {
         // Create instance of FPDF in landscape mode
         $pdf = new FPDF('L', 'mm', 'A4');
         $pdf->AddPage();

         // Set the font for the document
         $pdf->SetFont('Arial', 'B', 16);

         // Add title
         $pdf->Cell(0, 10, utf8_decode('LISTE DES COMMANDES ARTICLES'), 0, 1, 'C');

         // Line break
         $pdf->Ln(10);

         // Service title
         $pdf->SetFont('Arial', '', 12);
         $pdf->Cell(0, 10, 'Service :', 0, 1);

         // Header (longer due to landscape mode)
         $pdf->SetFont('Arial', 'B', 10);
         $pdf->Cell(50, 7, 'Fournisseur', 1);
         $pdf->Cell(50, 7, utf8_decode('Désignation'), 1);
         $pdf->Cell(35, 7, utf8_decode('N°cmde'), 1);
         $pdf->Cell(35, 7, 'Date', 1);
         $pdf->Cell(25, 7, utf8_decode('Quantité'), 1);
         $pdf->Cell(30, 7, 'P.U', 1);
         $pdf->Cell(30, 7, 'Montant HT', 1);
         $pdf->Cell(30, 7, 'Montant TTC', 1);
         $pdf->Ln();

         // Example data
         $pdf->SetFont('Arial', '', 10);
         $data = [
             ['3R TECHNOLOGIE', 'TELEPHONE', 'CA23/00246', '03/03/2023', 1, 242628, 242628, 242628],
             ['D4 TECH', 'LAPTOP', 'CA23/00247', '15/04/2023', 2, 120000, 240000, 288000],
             ['Mega Solutions', 'TABLET', 'CA23/00248', '22/05/2023', 5, 60000, 300000, 360000],
         ];

         foreach ($data as $row) {
             $pdf->Cell(50, 7, utf8_decode($row[0]), 1);
             $pdf->Cell(50, 7, utf8_decode($row[1]), 1);
             $pdf->Cell(35, 7, $row[2], 1);
             $pdf->Cell(35, 7, $row[3], 1);
             $pdf->Cell(25, 7, $row[4], 1);
             $pdf->Cell(30, 7, number_format($row[5], 0, ',', ' '), 1);
             $pdf->Cell(30, 7, number_format($row[6], 0, ',', ' '), 1);
             $pdf->Cell(30, 7, number_format($row[7], 0, ',', ' '), 1);
             $pdf->Ln();
         }

         // Subtotal line
         $pdf->Cell(0, 10, utf8_decode('Sous totaux :'), 0, 1, 'L');

         // Footer
         $pdf->Ln(10);
         $pdf->Cell(0, 10, '05 octobre 2024', 0, 1, 'L');

         // Output the PDF
         $pdf->Output();
         exit;
     }


    // Fonction pour générer l'état de commande
public function generateEtatBonaussi()
{
    // Instancier FPDF
    $pdf = new Fpdf();
    $pdf->AddPage();

    // Titre "ÉTAT DE COMMANDE"
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'ÉTAT DE COMMANDE', 0, 1, 'C');
    $pdf->Ln(5);

    // Informations du client
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Client :', 0, 1, 'L'); // Titre "Client"

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 7, 'Nom: Jean Dupont', 0, 1, 'L');
    $pdf->Cell(0, 7, 'Adresse: 123 rue de Paris, 75000 Paris, France', 0, 1, 'L');
    $pdf->Cell(0, 7, 'Email: jean.dupont@email.com', 0, 1, 'L');
    $pdf->Cell(0, 7, 'Telephone: +33 6 12 34 56 78', 0, 1, 'L');
    $pdf->Ln(10); // Espacement vertical

    // Bloc pour le numéro et la date de commande
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 7, 'Numero:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(40, 7, 'CA20240001', 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 7, 'Date:', 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(40, 7, '05/10/2024', 0, 1, 'L');
    $pdf->Ln(10); // Espacement vertical

    // En-tête du tableau pour les détails de la commande
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 7, 'Reference', 1, 0, 'C');
    $pdf->Cell(65, 7, 'Désignation', 1, 0, 'C');
    $pdf->Cell(30, 7, 'Prix Unitaire', 1, 0, 'C');
    $pdf->Cell(20, 7, 'Quantité', 1, 0, 'C');
    $pdf->Cell(30, 7, 'Total', 1, 1, 'C'); // End of line

    // Données des articles commandés
    $references = [
        ['reference' => 'REF001', 'designation' => 'Produit A', 'prix_unitaire' => '100.00', 'quantite' => 2, 'total' => '200.00'],
        ['reference' => 'REF002', 'designation' => 'Produit B', 'prix_unitaire' => '150.00', 'quantite' => 1, 'total' => '150.00'],
        ['reference' => 'REF003', 'designation' => 'Produit C', 'prix_unitaire' => '75.00', 'quantite' => 4, 'total' => '300.00'],
    ];

    // Remplissage des lignes du tableau
    $pdf->SetFont('Arial', '', 10);
    foreach ($references as $ref) {
        $pdf->Cell(30, 6, $ref['reference'], 1, 0, 'C');
        $pdf->Cell(65, 6, $ref['designation'], 1, 0, 'L');
        $pdf->Cell(30, 6, $ref['prix_unitaire'], 1, 0, 'R');
        $pdf->Cell(20, 6, $ref['quantite'], 1, 0, 'C');
        $pdf->Cell(30, 6, $ref['total'], 1, 1, 'R'); // End of line
    }

    // Total général
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(145, 7, 'TOTAL', 1, 0, 'R');
    $pdf->Cell(30, 7, '650.00', 1, 1, 'R'); // Total aligné à droite
    $pdf->Ln(10); // Espacement vertical

    // Remerciements
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, "Merci de votre commande. \nVotre commande sera traitée et expédiée dans les plus brefs délais.");

    // Sortie du PDF
    $pdf->Output('I', 'etat_commande.pdf');
    exit;
}







public function generateFacture()
{
    // Instancier FPDF
    $pdf = new Fpdf();
    $pdf->AddPage();

    // En-tête de la facture - Titre "COMMANDE"
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'COMMANDE', 0, 1, 'C');
    $pdf->Ln(10); // Espacement supplémentaire

    // Bloc Numéro et Date de commande
    $pdf->SetFont('Arial', '', 10);
   // Bloc à gauche pour le numéro de commande
   $pdf->SetX(10);
   $pdf->Cell(20, 7, 'Numero:', 0, 0, 'L');  // Réduire la largeur de la cellule pour "Numero:"
   $pdf->Cell(30, 7, 'CA2300341', 0, 0, 'L');  // Réduire la largeur de la cellule pour le numéro de commande

   // Bloc à droite pour la date de commande
   $pdf->Cell(15, 7, 'Date:', 0, 0, 'L');  // Réduire la largeur de la cellule pour "Date:"
   $pdf->Cell(25, 7, '23/03/2023', 0, 1, 'L');  // Réduire la largeur de la cellule pour la date de commande
   $pdf->Ln(5); // Espacement vertical

    // Agrandir la partie droite - Informations de la société
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetXY(110, 30); // Positionner à droite
    $pdf->Cell(90, 10, 'BLG INTERNATIONAL', 1, 1, 'C'); // Cellule avec bordure et centrage

    // Informations de contact sous le titre
    $pdf->SetFont('Arial', '', 10); // Taille réduite
    $pdf->SetXY(110, 40); // Positionner sous le titre de la société
    $pdf->Cell(90, 10, '16 bis, rue dragon 13006 France', 0, 1, 'C');
    $pdf->SetXY(110, 50);
    $pdf->Cell(90, 10, 'Fax: +33 4 96 17 60 14', 0, 1, 'C');
    $pdf->Ln(10); // Espacement après l'en-tête

    // Texte introductif pour la commande
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 5, 'Messieurs,', 0, 1);
    $pdf->MultiCell(0, 5, 'Nous vous prions de noter la commande suivante:');
    $pdf->Ln(5);

    // En-tête du tableau avec des bordures et texte centré
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 7, 'Qte', 1, 0, 'C');
    $pdf->Cell(90, 7, 'Désignation', 1, 0, 'C'); // Augmentation de la largeur
    $pdf->Cell(20, 7, 'Unité', 1, 0, 'C');
    $pdf->Cell(25, 7, 'P.U.(E)', 1, 0, 'C');
    $pdf->Cell(10, 7, 'R', 1, 0, 'C');
    $pdf->Cell(35, 7, 'Montant HT', 1, 0, 'C'); // Augmentation de la largeur
    $pdf->Ln();

    // Données des articles
    $pdf->SetFont('Arial', '', 10);
    $references = [
        ['qte' => '1', 'designation' => 'MATERIEL MECANIQUE', 'unite' => 'Unité', 'pu' => '696.0000', 'r' => '0', 'montant' => '696.0000'],
        ['qte' => '1', 'designation' => 'MATERIEL MECANIQUE', 'unite' => 'Unité', 'pu' => '696.0000', 'r' => '0', 'montant' => '696.0000']
    ];

       // Données des articles
   foreach ($references as $ref) {
       $pdf->Cell(10, 6, $ref['qte'], 1, 0, 'C');

       // Utilisation de MultiCell pour la colonne "Désignation"
       $x = $pdf->GetX();
       $y = $pdf->GetY();
       $pdf->MultiCell(90, 6, $ref['designation'], 1); // Texte sur plusieurs lignes
       $pdf->SetXY($x + 90, $y); // Remet le curseur à la bonne position pour la prochaine cellule

       $pdf->Cell(20, 6, $ref['unite'], 1, 0, 'C');
       $pdf->Cell(25, 6, $ref['pu'], 1, 0, 'C');
       $pdf->Cell(10, 6, $ref['r'], 1, 0, 'C');
       $pdf->Cell(35, 6, $ref['montant'], 1, 0, 'C'); // Largeur ajustée
       $pdf->Ln();
   }

   // Total HT(E) avec texte centré et ajustement des largeurs
   $pdf->SetFont('Arial', 'B', 10);

   // Ajustement du total pour correspondre à la largeur des colonnes précédentes
   // La somme des largeurs des colonnes précédentes (Qte + Désignation + Unité + PU + R) est de 10 + 90 + 20 + 25 + 10 = 155
   $pdf->Cell(155, 7, 'TOTAL HT(E)', 1, 0, 'R'); // Texte aligné à droite dans cette cellule
   $pdf->Cell(35, 7, '696.0000', 1, 0, 'C'); // Total aligné avec la cellule de Montant HT
   $pdf->Ln(10);


    // Texte supplémentaire
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, "Nous vous demandons de nous faire parvenir dans les plus brefs délais, la facture proforma revêtue de votre signature...");
    $pdf->Ln(5);

    // Informations de réception et règlement
    $pdf->MultiCell(0, 5, "Mode de reception: PAR AVION \nMode de reglement: Virement");
    $pdf->Ln(10);

    // Signatures
    $pdf->Cell(60, 10, 'Redacteur', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Directeur de departement', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Directeur General', 1, 1, 'C');
    $pdf->Ln(10);

    // Sortie du PDF
    $pdf->Output('I', 'facture.pdf');
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
