<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ArtistController::class)->group(function () {
    Route::get('/artists', 'index');
    Route::get('/artists/{id}', 'show');
    Route::post('/artists', 'store');
    Route::put('/artists/{id}', 'update');
    Route::delete('/artists/{id}', 'destroy');
});

Route::controller(AlbumController::class)->group(function () {
    Route::get('/albums', 'index');
    Route::get('/albums/{id}', 'show');
    Route::post('/albums', 'store');
    Route::put('/albums/{id}', 'update');
    Route::delete('/albums/{id}', 'destroy');
});
