<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inventory', [InventoryController::class, 'index']);

Route::get('/manager', [InventoryController::class, 'manager']);

// Esta es la ruta oculta que recibe los clics de los botones
Route::post('/inventory/{product}/update', [InventoryController::class, 'updateStock']);

Route::post('/inventory', [InventoryController::class, 'store']);

Route::delete('/inventory/{product}', [InventoryController::class, 'destroy']);