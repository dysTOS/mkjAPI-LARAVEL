<?php

namespace App\Http\ViewControllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeViewController::class, 'getHomeData']);
Route::get('/termine', [HomeViewController::class, 'getTermineData']);

Route::get('/kontakt', function()
{
   return view('pages.kontakt');
});
