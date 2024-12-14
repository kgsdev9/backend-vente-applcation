<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\Api\Users\UserController;
use App\Http\Controllers\FamilleArticleController;
use App\Http\Controllers\Api\Clients\ClientController;
use App\Http\Controllers\Api\Dossier\DossierController;
use App\Http\Controllers\Api\Rapport\RapportController;
use App\Http\Controllers\Api\Articles\ArticleController;
use App\Http\Controllers\Api\CategorieProduct\CategorieProductController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\CodeDevise\CodeDeviseController;
use App\Http\Controllers\Api\Commande\Etat\EtatCommandeController;
use App\Http\Controllers\Api\Commandes\CommandesClient\CommandeClientController;
use App\Http\Controllers\Api\Departement\DepartementController;
use App\Http\Controllers\Api\EtudeClient\EtudeClientController;
use App\Http\Controllers\API\Factures\Etat\EtatFactureController;
use App\Http\Controllers\Api\Factures\FactureController;
use App\Http\Controllers\Api\ModeReglement\ModeReglementController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Roles\RoleController;
use App\Http\Controllers\Api\Settings\PermissionManagerSettingController;
use App\Http\Controllers\Api\Settings\SettingController;
use App\Http\Controllers\Api\Ventes\VenteProductController;

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




Route::resource('clients', ClientController::class);
Route::get('/allclients', [ClientController::class, 'allClients']);
Route::resource('modereglements', ModeReglementController::class);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::resource('/codedevise', CodeDeviseController::class);
Route::resource('/departements', DepartementController::class);
Route::get('/fetch/desginationwithpagination', [DepartementController::class, 'fetchDepartementAllWithPagination']);
Route::resource('/articles', ArticleController::class);
Route::resource('/dossiers', DossierController::class);
Route::resource('/documents', DocumentController::class);
Route::resource('/etudeclient', EtudeClientController::class);
// Route::middleware('auth:sanctum')->get('/etudeclient', [EtudeClientController::class, 'index']);

Route::get('/listearticles', [ArticleController::class, 'listearticles']);
Route::get('/statistique', [StatistiqueController::class, 'index']);
Route::resource('/famillearticle', FamilleArticleController::class);
Route::get('/report/facture/export', [RapportController::class, 'exportpdf'])->name('factures.report');
Route::resource('roles', RoleController::class);
Route::get('/invoice/{id}/generate', [EtatCommandeController::class, 'generate']);





Route::prefix('users')->name('users.')->group(function () {
    // Route personnalisée pour printall
    Route::get('/printall', [UserController::class, 'printall'])->name('printall');

    // Route personnalisée pour exportusers
    Route::get('/exportusers', [UserController::class, 'exportusers'])->name('exportusers');
});

Route::resource('users', UserController::class);



Route::prefix('settings')->name('settings.')->group(function () {
    // Route personnalisée pour printall
    Route::get('/regimefiascal', [SettingController::class, 'allRegimefiscale'])->name('regme.fiscale');
    Route::get('/allcodedevise', [SettingController::class, 'allCodeDevise'])->name('code.devise');

    // Route personnalisée pour exportusers

});



Route::prefix('settings')->name('settings.')->group(function () {

    Route::get('/managerPermissions', [PermissionManagerSettingController::class, 'managerPermissions'])->name('manager.permissions');
});



Route::resource('/commandeclient', CommandeClientController::class);


Route::prefix('cmdeclient')->name('cmdeclient.')->group(function () {
    Route::get('/referenceclient/{id}', [CommandeClientController::class, 'getAllReferenceClient']);
    Route::get('/printall', [CommandeClientController::class, 'printall'])->name('printall');
    Route::get('/change/status/cmde', [CommandeClientController::class, 'actionSurCmde']);
});




Route::resource('/factures', FactureController::class);
Route::resource('/product', ProductController::class);
Route::resource('/categorie', CategorieProductController::class);
Route::resource('/ventes', VenteProductController::class);




Route::get('/generate/facture/{id}', [EtatFactureController::class, 'generateFacture']);


Route::get('/generateListeVente', [EtatFactureController::class, 'generateListeVente']);
