<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;

class InvoiceController extends Controller
{
    public function generateInvoice()
    {
        $pdf = new Fpdf('P', 'mm', 'A4');
        $pdf->AddPage();

        // En-tête
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(0, 10, utf8_decode('INVOICE'), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Ln(5);

        // Informations générales
        $pdf->Cell(100, 5, 'Date: ' . date('d/m/Y'), 0, 0, 'L');
        $pdf->Cell(100, 5, 'No. Invoice: 12345', 0, 1, 'R');
        $pdf->Ln(5);

        $pdf->Cell(100, 5, utf8_decode('Bill To:'));
        $pdf->Cell(100, 5, utf8_decode('Payment Method:'), 0, 1, 'R');
        $pdf->Cell(100, 5, utf8_decode('123 Anywhere St, Any City, ST 12345'));
        $pdf->Cell(100, 5, utf8_decode('Bank Name: Bancaire Bank'), 0, 1, 'R');
        $pdf->Cell(100, 5, '');
        $pdf->Cell(100, 5, utf8_decode('Account Number: 0123 4567 89'), 0, 1, 'R');
        $pdf->Ln(10);

        // Tableau - En-tête
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 10, utf8_decode('Désignation'), 1, 0, 'C');
        $pdf->Cell(30, 10, utf8_decode('Prix Unitaire'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Quantité'), 1, 0, 'C');
        $pdf->Cell(30, 10, utf8_decode('Montant HT'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('TVA'), 1, 0, 'C');
        $pdf->Cell(30, 10, utf8_decode('Montant TTC'), 1, 1, 'C');

        // Contenu
        $pdf->SetFont('Arial', '', 10);
        $items = [
            ['designation' => 'Produit A', 'pu' => 100, 'qte' => 2, 'montant_ht' => 200, 'tva' => 20, 'montant_ttc' => 240],
            ['designation' => 'Service B', 'pu' => 300, 'qte' => 1, 'montant_ht' => 300, 'tva' => 30, 'montant_ttc' => 330],
        ];

        foreach ($items as $item) {
            $pdf->Cell(70, 10, utf8_decode($item['designation']), 1);
            $pdf->Cell(30, 10, number_format($item['pu'], 2), 1, 0, 'C');
            $pdf->Cell(20, 10, $item['qte'], 1, 0, 'C');
            $pdf->Cell(30, 10, number_format($item['montant_ht'], 2), 1, 0, 'C');
            $pdf->Cell(20, 10, number_format($item['tva'], 2), 1, 0, 'C');
            $pdf->Cell(30, 10, number_format($item['montant_ttc'], 2), 1, 1, 'C');
        }

        // Résumé au bas du tableau (sans bordure)
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 10);

        // Montant HT
        $pdf->Cell(170, 6, utf8_decode('Montant HT : '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 6, number_format(array_sum(array_column($items, 'montant_ht')), 2), 0, 1, 'R');

        // TVA
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(170, 6, utf8_decode('TVA : '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 6, number_format(array_sum(array_column($items, 'tva')), 2), 0, 1, 'R');

        // Montant TTC
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(170, 6, utf8_decode('Montant TTC : '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 6, number_format(array_sum(array_column($items, 'montant_ttc')), 2), 0, 1, 'R');

        // Pied de page sans bordure
        $pdf->Ln(15);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, utf8_decode('THANK YOU!'), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, utf8_decode('Tel: +123-456-7890 | Email: hello@yourwebsite.com | www.yourwebsite.com'), 0, 1, 'C');

        // Générer et retourner le fichier PDF
        return response($pdf->Output('S', 'facture.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="facture.pdf"');
    }
}
