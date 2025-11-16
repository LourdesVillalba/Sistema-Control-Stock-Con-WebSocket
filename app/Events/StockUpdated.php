<?php

namespace App\Events;

use App\Models\Product; // Importante
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Usamos 'Now' para tiempo real
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    // Propiedad pública para que el modelo actualizado sea accesible
    public function __construct(public Product $product)
    {
    }

    /**
     * Define el canal al que se enviará el evento.
     */
    public function broadcastOn(): Channel
    {
        // Este es un canal PÚBLICO llamado 'inventory'.
        // Cualquiera que escuche este canal recibirá el evento.
        return new Channel('inventory');
    }

    /**
     * El nombre del evento que escuchará Echo en el frontend.
     */
    public function broadcastAs(): string
    {
        return 'stock.changed';
    }

    /**
     * Los datos que se enviarán al frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'product_id' => $this->product->id,
            'new_quantity' => $this->product->stock_quantity,
        ];
    }
}