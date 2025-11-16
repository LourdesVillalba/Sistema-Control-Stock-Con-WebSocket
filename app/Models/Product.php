<?php

namespace App\Models;

use App\Events\StockUpdated; // Importa el evento
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}