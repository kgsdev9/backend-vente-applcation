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

         if ($request->has('departement_id'))
         {
             $query->where('tdepartment_id', $request->departement_id);
         }

         // Champs sur lesquels on peut effectuer une recherche
         $critererecherche = ['nom', 'email', 'prenom', 'poste', 'created_at', 'telephone'];

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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Vérifier si l'adresse email existe déjà
        $email = $request->email;
        $baseEmail = explode('@', $email)[0]; // Partie avant le '@'
        $domain = explode('@', $email)[1];   // Domaine après le '@'

        $counter = 1;
        while (User::where('email', $email)->exists()) {
            // Générer une nouvelle adresse email en ajoutant un numéro
            $email = $baseEmail . $counter . '@' . $domain;
            $counter++;
        }

        // Créer l'utilisateur avec l'email unique
        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'password' => Hash::make($request->password) ?? Hash::make(12345),
            'telephone' => $request->telephone,
            'email' => $email,
            'role_id' => $request->role_id,
            'tdepartment_id' => $request->departement_id,
            'poste' => $request->poste
        ]);

        return response()->json('Utilisateur créé avec succès');
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

    public function printall(Request $request)
    {
        // Récupérer les critères de filtre depuis la requête
        $departementId = $request->departement_id;
        $searchQuery = $request->searchQuery;

        // Construire la requête en fonction des filtres
        $query = TDepartement::with(['users' => function ($userQuery) use ($searchQuery)
        {
            if ($searchQuery)
            {
                $userQuery->where(function ($subQuery) use ($searchQuery)
                {
                    $subQuery->where('nom', 'like', '%' . $searchQuery . '%')
                        ->orWhere('prenom', 'like', '%' . $searchQuery . '%')
                        ->orWhere('email', 'like', '%' . $searchQuery . '%')
                        ->orWhere('telephone', 'like', '%' . $searchQuery . '%')
                        ->orWhere('poste', 'like', '%' . $searchQuery . '%');
                });
            }
        }]);


        if ($departementId)
        {
            $query->where('id', $departementId);
        }

        // Récupérer les résultats
        $departements = $query->get();

        // Initialiser FPDF en mode paysage (L)
        $pdf = new Fpdf();
        $pdf->AddPage('L'); // Paysage

        // Titre du document
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Liste des Utilisateurs par Département'), 0, 1, 'C');
        $pdf->Ln(10);

        // Parcourir les départements
        foreach ($departements as $departement) {
            if ($departement->users->isEmpty()) {
                continue; // Passer les départements sans utilisateurs après le filtrage
            }

            // Titre du département
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, utf8_decode($departement->libelledepartement), 0, 1, 'L');
            $pdf->Ln(5);

            // Créer un tableau pour les utilisateurs de ce département
            $pdf->SetFont('Arial', 'B', 12);

            // En-têtes du tableau
            $pdf->Cell(55, 10, utf8_decode('Nom'), 1);
            $pdf->Cell(55, 10, utf8_decode('Prénom'), 1);
            $pdf->Cell(80, 10, 'Email', 1);
            $pdf->Cell(50, 10, utf8_decode('Téléphone'), 1);
            $pdf->Cell(60, 10, utf8_decode('Poste'), 1);
            $pdf->Ln(); // Aller à la ligne suivante après l'en-tête

            // Réinitialiser la police pour les données du tableau
            $pdf->SetFont('Arial', '', 12);

            // Liste des utilisateurs du département
            foreach ($departement->users as $user) {
                $pdf->Cell(55, 10, utf8_decode($user->nom), 1);
                $pdf->Cell(55, 10, utf8_decode($user->prenom), 1);
                $pdf->Cell(80, 10, utf8_decode($user->email), 1);
                $pdf->Cell(50, 10, utf8_decode($user->telephone), 1);
                $pdf->Cell(60, 10, utf8_decode($user->poste), 1);
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
        // Préparer les données à mettre à jour
        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'tdepartment_id' => $request->departement_id,
            'poste' => $request->poste,
        ];

        // Si un mot de passe est fourni, le hacher et l'inclure dans les données
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Mise à jour des données de l'utilisateur
        $updated = User::where('id', $request->iduser)->update($data);

        if ($updated)
         {
            return response()->json([
                'message' => 'Utilisateur modifié avec succès.',
            ]);
        }

        return response()->json([
            'message' => 'Une erreur s\'est produite lors de la mise à jour.',
        ], 500);
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
