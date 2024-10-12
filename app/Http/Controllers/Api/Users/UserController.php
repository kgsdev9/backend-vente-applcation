<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TDepartement;
use App\Traits\RechercheAndPagination;
use Illuminate\Support\Facades\Hash;
use Codedge\Fpdf\Fpdf\Fpdf;


class UserController extends Controller
{

    use RechercheAndPagination;
    /**
     * Affichage de la liste des utilisateurs.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
         // Créer la requête pour les utilisateurs (instance de Builder)
         $query = User::query()->orderByDesc('created_at');

         // Champs sur lesquels on peut effectuer une recherche
         $critererecherche = ['nom', 'email', 'prenom', 'poste', 'created_at'];

         // Relations à charger
         $relations = ['role', 'departement'];

         // Appliquer la recherche et la pagination via le trait
         $users = $this->applySearchAndPagination($query, $request, $critererecherche, $relations);

         return response()->json($users);
     }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        dd('sss');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        User::create([
            'nom'=>  $request->nom,
            'prenom'=> $request->prenom,
            'password'=> Hash::make($request->password) ?? 12345,
            'telephone'=> $request->telephone,
            'email'=> $request->email,
            'role_id'=> $request->role_id,
            'tdepartment_id'=> $request->departement_id,
            'poste'=> $request->poste
        ]);

        return response()->json('utilisateur créé avec success');
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
        $users = User::findOrFail($id); // Utilisation de findOrFail pour gérer les cas où la facture n'est pas trouvée


        return response()->json([
            'users' => $users,
        ]);
    }

    public function printall()
    {
    // Récupérer tous les utilisateurs, groupés par département
    $departements = TDepartement::with('users')->get();

    // Initialiser FPDF en mode paysage (L)
    $pdf = new Fpdf();
    $pdf->AddPage('L'); // Paysage

    // Titre du document
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, utf8_decode('Liste des Utilisateurs par Département'), 0, 1, 'C');
    $pdf->Ln(10);

    // Parcourir les départements
    foreach ($departements as $departement) {
        // Titre du département
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode($departement->libelledepartement), 0, 1, 'L');
        $pdf->Ln(5);

        // Créer un tableau pour les utilisateurs de ce département
        $pdf->SetFont('Arial', 'B', 12);

        // En-têtes du tableau
        $pdf->Cell(55, 10, utf8_decode('Nom'), 1);
        $pdf->Cell(55, 10, utf8_decode('Prénom'), 1); // Corriger ici
        $pdf->Cell(80, 10, 'Email', 1); // Pas besoin d'utf8_decode pour des textes sans accents
        $pdf->Cell(50, 10, utf8_decode('Téléphone'), 1);
        $pdf->Cell(60, 10, utf8_decode('Poste'), 1);
        $pdf->Ln(); // Aller à la ligne suivante après l'en-tête

        // Réinitialiser la police pour les données du tableau
        $pdf->SetFont('Arial', '', 12);

        // Liste des utilisateurs du département
        foreach ($departement->users as $user) {
            // Ajuster les tailles des colonnes et corriger l'encodage pour les champs avec accents
            $pdf->Cell(55, 10, utf8_decode($user->nom), 1);
            $pdf->Cell(55, 10, utf8_decode($user->prenom), 1); // Corriger ici
            $pdf->Cell(80, 10, utf8_decode($user->email), 1);
            $pdf->Cell(50, 10, utf8_decode($user->telephone), 1);
            $pdf->Cell(60, 10, utf8_decode($user->poste), 1); // Corriger ici si nécessaire
            $pdf->Ln(); // Aller à la ligne suivante pour chaque utilisateur
        }

        // Ajouter un espace après chaque département
        $pdf->Ln(10);
    }

    // Retourner le fichier PDF en réponse HTTP
    return response($pdf->Output('S', 'liste_utilisateurs.pdf'), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="liste_utilisateurs.pdf"');
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
         // Récupère les données de l'utilisateur à mettre à jour
         $data = [
             'nom' => $request->nom,
             'prenom' => $request->prenom,
             'telephone' => $request->telephone,
             'email' => $request->email,
             'role_id' => $request->role_id,
             'tdepartment_id' => $request->departement_id,
             'poste' => $request->poste
         ];

         // Si un nouveau mot de passe est fourni, on le hache et on l'ajoute aux données
         if ($request->filled('password')) {
             $data['password'] = Hash::make($request->password);
         }
         
         // Mise à jour de l'utilisateur avec les nouvelles données
         User::where('id', '=', $request->iduser)->update($data);

         // Réponse après mise à jour
         return response()->json([
             'message' => 'Utilisateur modifié avec succès',
         ]);
     }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client =  User::find($id);
        $client->delete();
       return response()->json('Utilisateur supprimé avec succes');
    }
}
