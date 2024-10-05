// Fonction pour générer l'état de commande mensuel
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
