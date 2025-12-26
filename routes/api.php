<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {

    Route::prefix('article')->middleware([])->group(function() {
        Route::get('/', [\App\Http\Controllers\ArticleController::class, 'datatable']);
        Route::post('/', [\App\Http\Controllers\ArticleController::class, 'create']);
        Route::put('/{id}', [\App\Http\Controllers\ArticleController::class, 'update']);
        Route::get('/{id}', [\App\Http\Controllers\ArticleController::class, 'findById']);
        Route::delete('/{id}', [\App\Http\Controllers\ArticleController::class, 'delete']);
    });
});