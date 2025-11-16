<?php

namespace App\Http\Controllers;

use App\Models\Product; // Importa el modelo
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        // Obtenemos todos los productos para la carga inicial
        $products = Product::all();
        return view('inventory', compact('products'));
    }
}