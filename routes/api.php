<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StatsController;


// Registro e login não requerem autenticação
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

// Todas as rotas abaixo exigem token válido (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('logout', [AuthController::class, 'logout']);

    // CRUD de clientes
    Route::apiResource('customers', CustomerController::class);

    // Estatísticas de vendas
    Route::get('stats/daily-sales',   [StatsController::class, 'dailySales']);
    Route::get('stats/top-customers', [StatsController::class, 'topCustomers']);
});
