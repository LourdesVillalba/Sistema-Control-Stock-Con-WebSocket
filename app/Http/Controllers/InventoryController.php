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

    // Muestra la vista del panel de control
    public function manager()
    {
        $products = Product::all();
        return view('manager', compact('products'));
    }

    // Procesa la actualización (¡La magia ocurre aquí!)
// app/Http/Controllers/InventoryController.php

public function updateStock(Request $request, Product $product)
{
    // Validamos:
    // 1. quantity: debe ser un número positivo (ej: 5, 10, 100)
    // 2. operation: solo puede ser 'add' (sumar) o 'subtract' (restar)
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'operation' => 'required|in:add,subtract'
    ]);

    if ($request->operation === 'add') {
        // Sumamos la cantidad ingresada al stock actual
        $product->increment('stock_quantity', $request->quantity);
    } else {
        // Restamos (Verificamos que no quede en negativo)
        if ($product->stock_quantity >= $request->quantity) {
            $product->decrement('stock_quantity', $request->quantity);
        } else {
            return response()->json(['success' => false, 'message' => 'Stock insuficiente'], 400);
        }
    }

    // Al usar increment/decrement, el evento 'updated' se dispara solo.
    // Reverb se encargará del resto.

    return response()->json([
        'success' => true, 
        // Devolvemos el stock resultante para confirmar
        'new_stock' => $product->refresh()->stock_quantity 
    ]);
}

// Guardar nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock_quantity' => 'required|integer|min:0'
        ]);

        // Al crear, el modelo dispara el evento 'product.created' automáticamente
        Product::create([
            'name' => $request->name,
            'stock_quantity' => $request->stock_quantity
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        // El evento 'deleted' se dispara automáticamente aquí
        return response()->json(['success' => true]);
    }
}