<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock en Tiempo Real</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: system-ui, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px 12px; }

        /* Esta clase la usará JS para el "flash" visual */
        .updated-flash {
            background-color: #fff3cd;
            transition: background-color 0.5s ease;
        }
    </style>
</head>
<body>
    <h1>📦 Monitor de Stock</h1>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Stock Actual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>
                    <span id="stock-{{ $product->id }}">
                        {{ $product->stock_quantity }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script type="module">
        // Esperamos a que la página cargue
        document.addEventListener('DOMContentLoaded', () => {

            // Nos suscribimos al canal 'inventory' que definimos en el Evento
            window.Echo.channel('inventory')

                // Escuchamos el evento '.stock.changed' (el 'broadcastAs')
                .listen('.stock.changed', (data) => {

                    console.log('¡Evento recibido!', data);

                    // 1. Construimos el ID del elemento: "stock-5"
                    const elementId = `stock-${data.product_id}`;
                    const stockElement = document.getElementById(elementId);

                    if (stockElement) {
                        // 2. Actualizamos el número en el HTML
                        stockElement.textContent = data.new_quantity;

                        // 3. (Opcional) Añadimos el "flash" visual
                        // Apuntamos al <td> (el padre del <span>)
                        const cell = stockElement.parentElement; 
                        cell.classList.add('updated-flash');

                        setTimeout(() => {
                            cell.classList.remove('updated-flash');
                        }, 500); // El flash dura medio segundo
                    }
                });
        });
    </script>

</body>
</html>