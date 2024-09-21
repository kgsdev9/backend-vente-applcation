<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}


// app/Http/Controllers/InvoiceController.php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Invoice;
// use App\Models\InvoiceItem;

// class InvoiceController extends Controller
// {
//     public function store(Request $request)
//     {
//         // Validez et enregistrez les données
//         $data = $request->validate([
//             'nom' => 'required|string',
//             'email' => 'required|email',
//             'adresse' => 'required|string',
//             'items' => 'required|array',
//             'items.*.designation' => 'required|string',
//             'items.*.prix' => 'required|numeric',
//             'items.*.quantite' => 'required|integer',
//             'remise' => 'nullable|numeric',
//         ]);

//         // Créez une nouvelle facture
//         $invoice = Invoice::create([
//             'nom' => $data['nom'],
//             'email' => $data['email'],
//             'adresse' => $data['adresse'],
//             'remise' => $data['remise'],
//         ]);

//         // Enregistrez les articles de la facture
//         foreach ($data['items'] as $itemData) {
//             $item = new InvoiceItem([
//                 'designation' => $itemData['designation'],
//                 'prix' => $itemData['prix'],
//                 'quantite' => $itemData['quantite'],
//             ]);
//             $invoice->items()->save($item);
//         }

//         // Retournez une réponse JSON ou effectuez d'autres actions nécessaires
//         return response()->json(['message' => 'Facture enregistrée avec succès'], 200);
//     }
// }
