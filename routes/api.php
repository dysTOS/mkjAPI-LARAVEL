<?php

use App\Http\Controllers\AusrueckungController;
use App\Http\Controllers\AuthController;
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
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/deleteuser', [AuthController::class, 'deleteUser']);


Route::get('/nextausrueckungpublic', [AusrueckungController::class, 'getNextActualPublic']);
Route::get('/ausrueckungenaktuellpublic', [AusrueckungController::class, 'getActualYearPublic']);


//Protected Routes
Route::group(['middleware' => ['auth:sanctum','abilites:see-single']], function (){


    Route::get('/ausrueckungen', [AusrueckungController::class, 'getAll']);
    Route::get('/ausrueckungen/{id}', [AusrueckungController::class, 'getSingle']);
    Route::get('/nextausrueckung', [AusrueckungController::class, 'getNextActual']);
    Route::get('/ausrueckungen/search/{name}', [AusrueckungController::class, 'search']);
    Route::post('/ausrueckungenfiltered', [AusrueckungController::class, 'getFiltered']);
    Route::post('/ausrueckungen', [AusrueckungController::class, 'create']);
    Route::put('/ausrueckungen/{id}', [AusrueckungController::class, 'update']);
    Route::delete('/ausrueckungen/{id}', [AusrueckungController::class, 'destroy']);


});
