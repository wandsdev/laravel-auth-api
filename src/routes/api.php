<?php

use Illuminate\Support\Facades\Route;
use WandsDev\AuthApi\Http\Controllers\AuthController;


Route::prefix('/api')->group(function () {
    Route::get('/auth-api', function() {
        return response()->json(['Pacote carregado com sucesso :).'], 200);
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
