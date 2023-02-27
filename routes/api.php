<?php

use App\Http\Controllers\AusrueckungController;
use App\Http\Controllers\CalendarSubController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MitgliederController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GruppenController;
use App\Http\Controllers\NotenController;
use App\Http\Controllers\NotenMappenController;
use App\Http\Controllers\PushNotificationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\TeilnahmenController;
use App\Http\Controllers\WordPressController;
use App\Http\Controllers\XXXTestController;
use Illuminate\Support\Facades\Route;

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

Route::get('/calendarsub', [CalendarSubController::class, 'getSubscription']);
Route::get('/calendarsub/{id}', [CalendarSubController::class, 'getSubscription']);
Route::get('/push', [PushNotificationsController::class, 'push']);

Route::get('/savepost', [WordPressController::class, 'savepost']);

Route::get('/test', [XXXTestController::class, 'testGet']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/test', [XXXTestController::class, 'testPost']);
    Route::put('/test', [XXXTestController::class, 'testPut']);
    Route::delete('/test/{id}', [XXXTestController::class, 'testDelete']);

    Route::post('/user', [AuthController::class, 'getCurrentUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/deleteuser', [AuthController::class, 'deleteUser']);

    Route::get('/reports', [ReportsController::class, 'getAll']);
    Route::post('/reports', [ReportsController::class, 'save']);

    Route::get('/ausrueckungen', [AusrueckungController::class, 'getAll']);
    Route::get('/ausrueckungen/{id}', [AusrueckungController::class, 'getSingle']);
    Route::post('/nextausrueckung', [AusrueckungController::class, 'getNextActual']);
    Route::post('/ausrueckungenfiltered', [AusrueckungController::class, 'getFiltered']);
    Route::post('/ausrueckungen', [AusrueckungController::class, 'create']);
    Route::post('/saveterminbyleiter', [AusrueckungController::class, 'saveTerminByGruppenleiter']);
    Route::put('/ausrueckungen/{id}', [AusrueckungController::class, 'update']);
    Route::delete('/ausrueckungen/{id}', [AusrueckungController::class, 'destroy']);

    Route::get('/mitglieder', [MitgliederController::class, 'getAll']);
    Route::get('/mitgliedernextgeb', [MitgliederController::class, 'getNextGeburtstage']);
    Route::get('/mitgliederaktiv', [MitgliederController::class, 'getAllActive']);
    Route::post('/mitgliedselbst', [MitgliederController::class, 'updateOwnMitgliedData']);
    Route::get('/mitglieder/{id}', [MitgliederController::class, 'getSingle']);
    Route::get('/mitglieder/search/{name}', [MitgliederController::class, 'search']);
    Route::post('/mitglieder', [MitgliederController::class, 'create']);
    Route::put('/mitglieder/{id}', [MitgliederController::class, 'update']);
    Route::delete('/mitglieder/{id}', [MitgliederController::class, 'destroy']);
    Route::post('/addmitglied', [MitgliederController::class, 'attachMitgliedToAusrueckung']);
    Route::post('/removemitglied', [MitgliederController::class, 'detachMitgliedFromAusrueckung']);
    Route::post('/addmitgliedgruppe', [MitgliederController::class, 'attachMitgliedToGruppe']);
    Route::post('/removemitgliedgruppe', [MitgliederController::class, 'detachMitgliedFromGruppe']);
    Route::get('/mitgliederausrueckung/{id}', [MitgliederController::class, 'getMitgliederOfAusrueckung']);

    Route::post('/teilnahme', [TeilnahmenController::class, 'updateTeilnahme']);
    Route::post('/teilnahmen', [TeilnahmenController::class, 'getTeilnahmenForTermin']);
    Route::post('/teilnahmeremove', [TeilnahmenController::class, 'removeTeilnahme']);
    Route::post('/teilnahmestatus', [TeilnahmenController::class, 'getTeilnahmeStatus']);

    Route::post('/gruppen/all', [GruppenController::class, 'getAllGruppen']);
    Route::post('/gruppen/gruppe', [GruppenController::class, 'getGruppe']);
    Route::post('/gruppen/save', [GruppenController::class, 'saveGruppe']);
    Route::delete('/gruppen/{id}', [GruppenController::class, 'deleteGruppe']);
    Route::post('/gruppen/gruppenleiter', [GruppenController::class, 'getGruppenLeiter']);
    Route::post('/gruppen/mitgliederofgruppe', [GruppenController::class, 'getMitgliederOfGruppe']);
    Route::post('/gruppen/gruppenofmitglied', [GruppenController::class, 'getGruppenOfMitglied']);
    Route::post('/gruppen/addmitglied', [GruppenController::class, 'addMitgliedToGruppe']);
    Route::post('/gruppen/removemitglied', [GruppenController::class, 'removeMitgliedFromGruppe']);

    Route::get('/instrumente', [InstrumenteController::class, 'getAll']);
    Route::get('/instrumente/{id}', [InstrumenteController::class, 'getInstrumentById']);
    Route::post('/instrumente', [InstrumenteController::class, 'save']);
    Route::delete('/instrumente/{id}', [InstrumenteController::class, 'destroy']);
    Route::post('/addinstrument', [InstrumenteController::class, 'attachInstrument']);
    Route::post('/removeinstrument', [InstrumenteController::class, 'detachInstrument']);

    Route::get('/noten', [NotenController::class, 'getAll']);
    Route::get('/noten/{id}', [NotenController::class, 'getNotenById']);
    Route::get('/noten/search/{name}', [NotenController::class, 'search']);
    Route::post('/noten', [NotenController::class, 'create']);
    Route::put('/noten/{id}', [NotenController::class, 'update']);
    Route::delete('/noten/{id}', [NotenController::class, 'destroy']);
    Route::post('/addnoten', [NotenController::class, 'attachNoten']);
    Route::post('/removenoten', [NotenController::class, 'detachNoten']);
    Route::get('/notenausrueckung/{id}', [NotenController::class, 'getNotenOfAusrueckung']);
    Route::post('/notenmappe', [NotenMappenController::class, 'getNotenmappe']);
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

    Route::post('/statistik/termine', [StatistikController::class, 'getTermine']);
    Route::post('/statistik/terminegruppen', [StatistikController::class, 'getTermineNachGruppen']);
    Route::get('/statistik/noten', [StatistikController::class, 'getNoten']);
    Route::get('/statistik/mitglieder', [StatistikController::class, 'getMitglieder']);
    Route::get('/statistik/mitgliedergeschlecht', [StatistikController::class, 'getMitgliederGeschlecht']);

    Route::get('/files', [FileController::class, 'getFiles']);
    Route::post('/file', [FileController::class, 'storeFile']);

    Route::post('/pushsub', [PushNotificationsController::class, 'storeSubscription']);
});
