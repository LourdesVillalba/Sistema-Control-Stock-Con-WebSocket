<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\StockUpdated; // Importa el evento
use App\Events\ProductCreated;
use App\Events\ProductDeleted;


class Product extends Model
{
    use HasFactory;

    // Campos que se pueden llenar
    protected $fillable = ['name', 'stock_quantity'];

    /**
     * Mapea los eventos de Eloquent a nuestras clases de Eventos.
     * Cuando se dispare el evento interno 'updated' de Eloquent...
     * ...queremos que Laravel dispare nuestro evento 'StockUpdated'.
     */
    protected $dispatchesEvents = [
        'updated' => StockUpdated::class,
        'created' => ProductCreated::class, 
        'deleted' => ProductDeleted::class, 
    ];
}