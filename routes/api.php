<?php

use App\Http\Controllers\AusrueckungController;
use App\Http\Controllers\MitgliederController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotenController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Contracts\HasAbilities;

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


//Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/nextausrueckungpublic', [AusrueckungController::class, 'getNextActualPublic']);
Route::get('/ausrueckungenaktuellpublic', [AusrueckungController::class, 'getActualYearPublic']);


//Protected Standard Routes
Route::group(['middleware' => ['auth:sanctum', 'ability:mitglied']], function (){
    Route::get('/ausrueckungen', [AusrueckungController::class, 'getAll']);
    Route::get('/ausrueckungen/{id}', [AusrueckungController::class, 'getSingle']);
    Route::get('/nextausrueckung', [AusrueckungController::class, 'getNextActual']);
    Route::get('/ausrueckungen/search/{name}', [AusrueckungController::class, 'search']);
    Route::post('/ausrueckungenfiltered', [AusrueckungController::class, 'getFiltered']);

    Route::get('/noten', [NotenController::class, 'getAll']);
    Route::get('/noten/search/{name}', [NotenController::class, 'search']);

    Route::post('/user', [AuthController::class, 'getCurrentUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

//Protected Admin Only Routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function (){
    Route::post('/deleteuser', [AuthController::class, 'deleteUser']);

    Route::get('/roles', [RoleController::class, 'getAll']);
    Route::post('/getrolesformitglied', [RoleController::class, 'getRolesForMitglied']);
    Route::post('/getrolesforuser', [RoleController::class, 'getRolesForUser']);
    Route::post('/roles', [RoleController::class, 'create']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    Route::post('/addrole', [RoleController::class, 'attachRole']);
    Route::post('/removerole', [RoleController::class, 'detachRole']);
});

//Protected Ausschuss Routes
Route::group(['middleware' => ['auth:sanctum', 'ability:ausschuss,admin']], function (){
    Route::post('/ausrueckungen', [AusrueckungController::class, 'create']);
    Route::put('/ausrueckungen/{id}', [AusrueckungController::class, 'update']);
    Route::post('/ausrueckungduplicate', [AusrueckungController::class, 'duplicate']);
    Route::delete('/ausrueckungen/{id}', [AusrueckungController::class, 'destroy']);

    Route::get('/mitglieder', [MitgliederController::class, 'getAll']);
    Route::get('/mitgliederaktiv', [MitgliederController::class, 'getAllActive']);
    Route::get('/mitglieder/{id}', [MitgliederController::class, 'getSingle']);
    Route::get('/mitglieder/search/{name}', [MitgliederController::class, 'search']);
    Route::post('/mitglieder', [MitgliederController::class, 'create']);
    Route::put('/mitglieder/{id}', [MitgliederController::class, 'update']);
    Route::delete('/mitglieder/{id}', [MitgliederController::class, 'destroy']);
    Route::post('/addmitglied', [MitgliederController::class, 'attachMitglied']);
    Route::post('/removemitglied', [MitgliederController::class, 'detachMitglied']);
    Route::get('/mitgliederausrueckung/{id}', [MitgliederController::class, 'getMitgliederOfAusrueckung']);

    Route::post('/noten', [NotenController::class, 'create']);
    Route::put('/noten/{id}', [NotenController::class, 'update']);
    Route::delete('/noten/{id}', [NotenController::class, 'destroy']);
    Route::post('/addnoten', [NotenController::class, 'attachNoten']);
    Route::post('/removenoten', [NotenController::class, 'detachNoten']);
    Route::get('/notenausrueckung/{id}', [NotenController::class, 'getNotenOfAusrueckung']);

});
