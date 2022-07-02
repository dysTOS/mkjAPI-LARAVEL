<?php

use App\Http\Controllers\AusrueckungController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MitgliederController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotenController;
use App\Http\Controllers\NotenMappenController;
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
Route::get('/calendarsub', [\App\Http\Controllers\CalendarSubController::class, 'getSubscription']);


//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/user', [AuthController::class, 'getCurrentUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/deleteuser', [AuthController::class, 'deleteUser']);

    Route::get('/ausrueckungen', [AusrueckungController::class, 'getAll']);
    Route::get('/ausrueckungen/{id}', [AusrueckungController::class, 'getSingle']);
    Route::get('/nextausrueckung', [AusrueckungController::class, 'getNextActual']);
    Route::get('/ausrueckungen/search/{name}', [AusrueckungController::class, 'search']);
    Route::post('/ausrueckungenfiltered', [AusrueckungController::class, 'getFiltered']);
    Route::post('/ausrueckungen', [AusrueckungController::class, 'create']);
    Route::put('/ausrueckungen/{id}', [AusrueckungController::class, 'update']);
    Route::delete('/ausrueckungen/{id}', [AusrueckungController::class, 'destroy']);

    Route::get('/mitglieder', [MitgliederController::class, 'getAll']);
    Route::get('/mitgliederaktiv', [MitgliederController::class, 'getAllActive']);
    Route::post('/mitgliedselbst', [MitgliederController::class, 'updateOwnMitgliedData']);
    Route::get('/mitglieder/{id}', [MitgliederController::class, 'getSingle']);
    Route::get('/mitglieder/search/{name}', [MitgliederController::class, 'search']);
    Route::post('/mitglieder', [MitgliederController::class, 'create']);
    Route::put('/mitglieder/{id}', [MitgliederController::class, 'update']);
    Route::delete('/mitglieder/{id}', [MitgliederController::class, 'destroy']);
    Route::post('/addmitglied', [MitgliederController::class, 'attachMitglied']);
    Route::post('/removemitglied', [MitgliederController::class, 'detachMitglied']);
    Route::get('/mitgliederausrueckung/{id}', [MitgliederController::class, 'getMitgliederOfAusrueckung']);

    Route::get('/noten', [NotenController::class, 'getAll']);
    Route::get('/noten/search/{name}', [NotenController::class, 'search']);
    Route::post('/noten', [NotenController::class, 'create']);
    Route::put('/noten/{id}', [NotenController::class, 'update']);
    Route::delete('/noten/{id}', [NotenController::class, 'destroy']);
    Route::post('/addnoten', [NotenController::class, 'attachNoten']);
    Route::post('/removenoten', [NotenController::class, 'detachNoten']);
    Route::get('/notenausrueckung/{id}', [NotenController::class, 'getNotenOfAusrueckung']);
    Route::get('/notenmappen', [NotenMappenController::class, 'getNotenmappen']);
    Route::post('/notenmappen', [NotenMappenController::class, 'createNotenmappe']);
    Route::put('/notenmappen/{id}', [NotenMappenController::class, 'updateNotenmappe']);
    Route::delete('/notenmappen/{id}', [NotenMappenController::class, 'destroyNotenmappe']);
    Route::post('/notenmappenattach', [NotenMappenController::class, 'notenmappeAttach']);
    Route::post('/notenmappendetach', [NotenMappenController::class, 'notenmappeDetach']);

    Route::get('/roles', [RoleController::class, 'getAllRoles']);
    Route::get('/roles/{id}', [RoleController::class, 'getUserRoles']);
    Route::get('/permissions', [RoleController::class, 'getAllPermissions']);
    Route::get('/permissions/{id}', [RoleController::class, 'getUserPermissions']);
    Route::get('permissions/{id}', [RoleController::class, 'getPermissionsForRole']);
    Route::post('/role', [RoleController::class, 'createRole']);
    Route::put('role/{id}', [RoleController::class, 'updateRole']);
    Route::delete('role/{id}', [RoleController::class, 'deleteRole']);
    Route::post('roles/assign/{id}', [RoleController::class, 'assignRolesToUser']);

    Route::get('/files', [FileController::class, 'getFiles']);
    Route::post('/file', [FileController::class, 'storeFile']);
});
