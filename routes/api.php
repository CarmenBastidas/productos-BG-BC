<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'vehiculo'], function () {
        Route::get('/listar', [\App\Http\Controllers\Api\AppController::class, 'searchProductos']);
        Route::put('/preferencias/{codigo}/{estado}', [\App\Http\Controllers\Api\AppController::class, 'saveProductos']);
        Route::get('/listar/preferencias', [\App\Http\Controllers\Api\AppController::class, 'searchProductosPrefer']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
