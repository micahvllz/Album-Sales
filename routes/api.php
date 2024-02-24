<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/artists', ArtistController::class);
    Route::apiResource('/albums', AlbumController::class);

    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('total-sales', 'totalAlbumsSoldPerArtist');
        Route::get('sales-per-artist', 'combinedAlbumSalesPerArtist');
        Route::get('top-artist', 'topSellingArtist');
        Route::get('albums', 'albumsByArtist');
    });
});
