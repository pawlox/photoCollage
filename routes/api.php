<?php

use App\Http\Controllers\API\PhotoCollageController;
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

Route::middleware('auth:api')->group(function () {
    Route::get('/photo-collages', [PhotoCollageController::class, 'index']);
    Route::post('/photo-collages', [PhotoCollageController::class, 'store']);
    Route::get('/photo-collages/{id}', [PhotoCollageController::class, 'show']);
    Route::put('/photo-collages/{id}', [PhotoCollageController::class, 'update']);
    Route::delete('/photo-collages/{id}', [PhotoCollageController::class, 'destroy']);
});
