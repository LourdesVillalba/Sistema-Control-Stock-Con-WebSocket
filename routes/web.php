<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\InventoryController;

Route::get('/inventory', [InventoryController::class, 'index']);