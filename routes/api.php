<?php

use App\Http\Controllers\KassabuchungController;
use App\Http\Controllers\TerminController;
use App\Http\Controllers\CalendarSubController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\MitgliederController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GruppenController;
use App\Http\Controllers\InstrumentenController;
use App\Http\Controllers\NotenController;
use App\Http\Controllers\NotenMappenController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\TeilnahmenController;
use App\Http\Controllers\WordPressController;
use App\Http\Controllers\XXXTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnschriftenController;
use App\Http\Controllers\KassabuchController;
use App\Http\Controllers\BewertungenController;
use App\Http\Controllers\KommentarController;
use App\Http\Controllers\UserNotificationController;

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
Route::get('/nextausrueckungpublic', [TerminController::class, 'getNextActualPublic']);
Route::get('/ausrueckungenaktuellpublic', [TerminController::class, 'getActualYearPublic']);
Route::get('/calendarsub', [CalendarSubController::class, 'getSubscription']);
Route::get('/calendarsub/{id}', [CalendarSubController::class, 'getSubscription']);
Route::get('/configs', [ConfigController::class, 'getUiConfigs']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');

//Test Routes
Route::get('/savepost', [WordPressController::class, 'savepost']);

Route::get('/test', [XXXTestController::class, 'testGet']);
Route::post('/test', [XXXTestController::class, 'testPost']);
Route::put('/test', [XXXTestController::class, 'testPut']);
Route::delete('/test', [XXXTestController::class, 'testDelete']);

//To be protected


//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('testsocket', [XXXTestController::class, 'testSocket']);

    Route::post('/configs', [ConfigController::class, 'setUiConfigs']);

    Route::post('/user', [AuthController::class, 'getCurrentUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/deleteuser', [AuthController::class, 'deleteUser']);

    Route::post('/termine/list', [TerminController::class, 'getList']);
    Route::get('/termine/{id}', [TerminController::class, 'getById']);
    Route::post('/termine', [TerminController::class, 'create']);
    Route::put('/termine/{id}', [TerminController::class, 'update']);
    Route::delete('/termine/{id}', [TerminController::class, 'delete']);

    Route::post('/nextausrueckung', [TerminController::class, 'getNextActual']);
    Route::post('/saveterminbyleiter', [TerminController::class, 'saveTerminByGruppenleiter']);

    Route::post('/mitglieder/list', [MitgliederController::class, 'getList']);
    Route::get('/mitglieder/{id}', [MitgliederController::class, 'getById']);
    Route::post('/mitglieder', [MitgliederController::class, 'create']);
    Route::put('/mitglieder/{id}', [MitgliederController::class, 'update']);
    Route::delete('/mitglieder/{id}', [MitgliederController::class, 'delete']);

    Route::get('/mitgliedernextgeb', [MitgliederController::class, 'getNextGeburtstage']);
    Route::post('/mitgliedselbst', [MitgliederController::class, 'updateOwnMitgliedData']);
    Route::get('/mitglieder/search/{name}', [MitgliederController::class, 'search']);
    Route::post('/addmitglied', [MitgliederController::class, 'attachMitgliedToAusrueckung']);
    Route::post('/removemitglied', [MitgliederController::class, 'detachMitgliedFromAusrueckung']);
    Route::post('/addmitgliedgruppe', [MitgliederController::class, 'attachMitgliedToGruppe']);
    Route::post('/removemitgliedgruppe', [MitgliederController::class, 'detachMitgliedFromGruppe']);
    Route::get('/mitgliederausrueckung/{id}', [MitgliederController::class, 'getMitgliederOfAusrueckung']);

    Route::post('/teilnahme', [TeilnahmenController::class, 'updateTeilnahme']);
    Route::post('/teilnahmen', [TeilnahmenController::class, 'getTeilnahmenForTermin']);
    Route::post('/teilnahmeremove', [TeilnahmenController::class, 'removeTeilnahme']);
    Route::post('/teilnahmestatus', [TeilnahmenController::class, 'getTeilnahmeStatus']);

    Route::post('/gruppen/list', [GruppenController::class, 'getList']);
    Route::get('/gruppen/{id}', [GruppenController::class, 'getById']);
    Route::post('/gruppen', [GruppenController::class, 'create']);
    Route::put('/gruppen/{id}', [GruppenController::class, 'update']);
    Route::delete('/gruppen/{id}', [GruppenController::class, 'delete']);
    Route::post('/gruppen/gruppenleiter', [GruppenController::class, 'getGruppenLeiter']);
    Route::post('/gruppen/mitgliederofgruppe', [GruppenController::class, 'getMitgliederOfGruppe']);
    Route::post('/gruppen/gruppenofmitglied', [GruppenController::class, 'getGruppenOfMitglied']);
    Route::post('/gruppen/addmitglied', [GruppenController::class, 'addMitgliedToGruppe']);
    Route::post('/gruppen/removemitglied', [GruppenController::class, 'removeMitgliedFromGruppe']);

    Route::post('/instrumente/list', [InstrumentenController::class, 'getList']);
    Route::get('/instrumente/{id}', [InstrumentenController::class, 'getById']);
    Route::post('/instrumente', [InstrumentenController::class, 'create']);
    Route::put('/instrumente/{id}', [InstrumentenController::class, 'update']);
    Route::delete('/instrumente/{id}', [InstrumentenController::class, 'delete']);
    Route::get('/instrumente/mitglied/{id}', [InstrumentenController::class, 'getInstrumenteOfMitglied']);

    Route::post('/notenmappe/list', [NotenMappenController::class, 'getList']);
    Route::get('/notenmappe/{id}', [NotenMappenController::class, 'getById']);
    Route::post('/notenmappe', [NotenMappenController::class, 'create']);
    Route::put('/notenmappe/{id}', [NotenMappenController::class, 'update']);
    Route::delete('/notenmappe/{id}', [NotenMappenController::class, 'delete']);
    Route::post('/notenmappe/attach', [NotenMappenController::class, 'attach']);
    Route::post('/notenmappe/detach', [NotenMappenController::class, 'detach']);
    Route::post('/notenmappe/noten', [NotenMappenController::class, 'syncNoten']);
    Route::get('/notenmappe/noten/{id}', [NotenMappenController::class, 'getNotenOfMappe']);

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

    Route::post('/anschrift/list', [AnschriftenController::class, 'getList']);
    Route::get('/anschrift/{id}', [AnschriftenController::class, 'getById']);
    Route::post('/anschrift', [AnschriftenController::class, 'create']);
    Route::put('/anschrift/{id}', [AnschriftenController::class, 'update']);
    Route::delete('/anschrift/{id}', [AnschriftenController::class, 'delete']);
    Route::get('/anschrift/search/{string}', [AnschriftenController::class, 'search']);

    Route::post('/noten/list', [NotenController::class, 'getList']);
    Route::get('/noten/{id}', [NotenController::class, 'getById']);
    Route::post('/noten', [NotenController::class, 'create']);
    Route::put('/noten/{id}', [NotenController::class, 'update']);
    Route::delete('/noten/{id}', [NotenController::class, 'delete']);
    Route::post('/noten/attach', [NotenController::class, 'attachNoten']);
    Route::post('/noten/detach', [NotenController::class, 'detachNoten']);
    Route::get('/noten/search/{name}', [NotenController::class, 'search']);
    Route::get('/noten/termin/{id}', [NotenController::class, 'getNotenOfTermin']);
    Route::get('/noten/search/{field}/{name}', [NotenController::class, 'searchFieldValues']);

    Route::post('/kassabuch/list', [KassabuchController::class, 'getList']);
    Route::get('/kassabuch/{id}', [KassabuchController::class, 'getById']);
    Route::post('/kassabuch', [KassabuchController::class, 'create']);
    Route::put('/kassabuch/{id}', [KassabuchController::class, 'update']);
    Route::delete('/kassabuch/{id}', [KassabuchController::class, 'delete']);

    Route::post('/kassabuchung/list', [KassabuchungController::class, 'getList']);
    Route::get('/kassabuchung/{id}', [KassabuchungController::class, 'getById']);
    Route::post('/kassabuchung', [KassabuchungController::class, 'create']);
    Route::put('/kassabuchung/{id}', [KassabuchungController::class, 'update']);
    Route::delete('/kassabuchung/{id}', [KassabuchungController::class, 'delete']);

    Route::post('bewertungen/noten/set', [BewertungenController::class, 'setNotenVote']);
    Route::post('bewertungen/noten/get', [BewertungenController::class, 'getNotenVote']);

    Route::get('usernotifications/get', [UserNotificationController::class, 'getNotifications']);
    Route::get('usernotifications/getunread', [UserNotificationController::class, 'getUnreadNotifications']);
    Route::get('usernotifications/markasread/{id}', [UserNotificationController::class, 'markAsRead']);
    Route::get('usernotifications/markallasread', [UserNotificationController::class, 'markAllAsRead']);

    Route::post('kommentare/list', [KommentarController::class, 'getList']);
    Route::get('kommentare/{id}', [KommentarController::class, 'getById']);
    Route::post('/kommentare', [KommentarController::class, 'create']);
    Route::put('/kommentare/{id}', [KommentarController::class, 'update']);
    Route::delete('/kommentare/{id}', [KommentarController::class, 'delete']);

});
