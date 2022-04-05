<?php

use App\Http\Controllers\anwbApiController;
use App\Http\Controllers\IncidentsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/incidents/get',[IncidentsController::class,"getIncidents"]);
//Route::get('/anwb/getData',[anwbApiController::class,"getData"]);
