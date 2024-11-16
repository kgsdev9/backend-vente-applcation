<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait RechercheAndPagination
{
    /**
     * Gérer la recherche et la pagination d'une requête.
     *
     * @param Builder $query
     * @param Request $request
     * @param array $searchableFields
     * @param array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function applySearchAndPagination(Builder $query, Request $request, array $searchableFields = [], array $relations = [])
    {
        // Charger les relations si fournies
        if (!empty($relations))
        {
            $query->with($relations);
        }

       
        // Gérer les critères de recherche
        if ($request->has('search'))
        {
            $searchTerm = $request->search;
            $query->where(function (Builder $subQuery) use ($searchTerm, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $subQuery->orWhere($field, 'like', "%$searchTerm%");
                }
                // Si le champ `created_at` fait partie des champs à rechercher par date exacte
                if (in_array('created_at', $searchableFields))
                {
                    $subQuery->orWhereDate('created_at', '=', $searchTerm);
                }
            });
        }

        return $query->paginate($request->query('per_page', 10));
    }
}
