<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionManagerSettingController extends Controller
{
    /**
     * Manager les permissions et les gates avec laravel .
     *
     * @return \Illuminate\Http\Response
     */
    public function managerPermissions()
    {
        return response()->json([
            'can_view_dashboard' => Gate::allows('view-dashboard'),
        ]);
    }

}
