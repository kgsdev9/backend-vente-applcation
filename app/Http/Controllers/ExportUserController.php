<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport; // Assurez-vous de créer cette classe d'exportation

class ExportUserController extends Controller
{
    public function export(Request $request)
    {
        // Construire la requête pour récupérer les utilisateurs filtrés
        $query = User::orderByDesc('created_at');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%$searchTerm%");
        }

        $users = $query->get();

        // Exporter les utilisateurs vers un fichier Excel
        return Excel::download(new UsersExport($users), 'users.xlsx');
    }
}
