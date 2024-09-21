<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\FactureController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\FamilleArticleController;
use App\Http\Controllers\Render\ImpressionController;
use App\Http\Controllers\Api\Clients\ClientController;
use App\Http\Controllers\Api\Dossier\DossierController;
use App\Http\Controllers\Api\Rapport\RapportController;
use App\Http\Controllers\Api\Articles\ArticleController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\CodeDevise\CodeDeviseController;
use App\Http\Controllers\Api\Departement\DepartementController;
use App\Http\Controllers\Api\ModeReglement\ModeReglementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
// Route pour récupérer les informations de l'utilisateur authentifié
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);



Route::resource('users', UserController::class);
Route::resource('clients', ClientController::class);
Route::get('/allclients', [ClientController::class, 'allClients']);
Route::resource('modereglements', ModeReglementController::class);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::post('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::resource('/factures', FactureController::class);
Route::resource('/codedevise', CodeDeviseController::class);
Route::resource('/departements', DepartementController::class);
Route::resource('/articles', ArticleController::class);
Route::resource('/dossiers', DossierController::class);
Route::resource('/documents', DocumentController::class);
Route::get('/listearticles', [ArticleController::class, 'listearticles']);
Route::get('/statistique', [StatistiqueController::class, 'index']);
Route::get('download-reference/{id}', [ImpressionController::class, 'impressionsingleFacture']);
Route::resource('/famillearticle', FamilleArticleController::class);
Route::get('/report/facture/export', [RapportController::class, 'exportpdf'])->name('factures.report');
