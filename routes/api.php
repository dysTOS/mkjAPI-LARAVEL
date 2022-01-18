<?php

use App\Http\Controllers\AusrueckungController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/deleteUser', [AuthController::class, 'deleteUser']);

Route::get('/ausrueckungen', [AusrueckungController::class, 'index']);
Route::get('/ausrueckungen/{id}', [AusrueckungController::class, 'getSingle']);
Route::get('/nextausrueckung', [AusrueckungController::class, 'getNextActual']);
Route::get('/ausrueckungen/search/{name}', [AusrueckungController::class, 'search']);
Route::get('/ausrueckungenaktuell', [AusrueckungController::class, 'getActualForHomepage']);
Route::post('/ausrueckungenfiltered', [AusrueckungController::class, 'getFiltered']);
Route::post('/ausrueckungen', [AusrueckungController::class, 'create']);
Route::put('/ausrueckungen/{id}', [AusrueckungController::class, 'update']);
Route::delete('/ausrueckungen/{id}', [AusrueckungController::class, 'destroy']);


//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function (){



});
