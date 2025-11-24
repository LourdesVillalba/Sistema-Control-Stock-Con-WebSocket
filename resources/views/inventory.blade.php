<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de Stock - En Vivo</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animación del Título  */
        .animate-text {
            background-size: 200% auto;
            animation: textShine 5s ease-in-out infinite alternate;
        }
        @keyframes textShine {
            to { background-position: 200% center; }
        }

        /* Animación de entrada de tarjetas */
        @keyframes bounceIn {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-bounce-in {
            animation: bounceIn 0.4s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen p-8 font-sans transition-colors duration-300">

    @php
        $maxCapacidad = 200;
        $stockCritico = 20;
        $stockBajo = 80;
    @endphp

    <div class="max-w-7xl mx-auto">
        
        <div class="text-center mb-12">
            <h1 class="text-5xl font-extrabold lg:text-6xl tracking-tight mb-2">
                <span class="text-transparent bg-gradient-to-br bg-clip-text from-teal-500 via-indigo-500 to-sky-500 dark:from-teal-200 dark:via-indigo-300 dark:to-sky-500 animate-text">
                    Monitor
                </span>
                <span class="text-transparent bg-gradient-to-tr bg-clip-text from-blue-500 via-pink-500 to-red-500 dark:from-sky-300 dark:via-pink-300 dark:to-red-500 animate-text">
                    En Tiempo Real
                </span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Visualización de inventario en vivo</p>
        </div>

        <div id="inventory-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            @foreach($products as $product)
                @php
                    $color = 'success'; // Verde (DaisyUI class)
                    // Para Tailwind texto usamos colores específicos
                    $textColor = 'text-emerald-500'; 
                    
                    if($product->stock_quantity < $stockCritico) {
                        $color = 'error'; 
                        $textColor = 'text-rose-500';
                    } elseif($product->stock_quantity < $stockBajo) {
                        $color = 'warning';
                        $textColor = 'text-amber-500';
                    }
                @endphp

                <div id="card-{{ $product->id }}" class="relative group bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-500">
                    
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-gray-200 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl blur opacity-0 group-hover:opacity-20 transition duration-500"></div>

                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 truncate pr-2" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h2>
                            <div class="p-2 rounded-full bg-gray-100 dark:bg-gray-700/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 dark:text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"/><path d="M16 2v4a2 2 0 0 0 2 2h4"/></svg>
                            </div>
                        </div>

                        <div class="flex items-baseline gap-1 mb-4">
                            <span id="stock-value-{{ $product->id }}" class="text-5xl font-extrabold {{ $textColor }} transition-colors duration-300">
                                {{ $product->stock_quantity }}
                            </span>
                            <span class="text-sm text-gray-400 font-medium">unidades</span>
                        </div>

                        <div class="w-full">
                            <div class="flex justify-between text-xs text-gray-400 mb-1">
                                <span id="date-{{ $product->id }}" class="italic">Act: {{ $product->updated_at->diffForHumans() }}</span>
                                <span>Meta: {{ $maxCapacidad }}</span>
                            </div>
                            
                            <progress 
                                id="progress-{{ $product->id }}"
                                class="progress progress-{{ $color }} w-full h-2.5 bg-gray-100 dark:bg-gray-700" 
                                value="{{ $product->stock_quantity }}" 
                                max="{{ $maxCapacidad }}">
                            </progress>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <script type="module">
        // --- CONFIGURACIÓN ---
        const MAX_CAPACIDAD = 200;
        const STOCK_CRITICO = 20; 
        const STOCK_BAJO = 80;

        // Función auxiliar para obtener la clase de color de texto
        function getTextColorClass(status) {
            if (status === 'error') return 'text-rose-500';
            if (status === 'warning') return 'text-amber-500';
            return 'text-emerald-500';
        }

        // Función auxiliar para obtener estado (DaisyUI)
        function getStockStatus(quantity) {
            if (quantity < STOCK_CRITICO) return 'error';
            if (quantity < STOCK_BAJO) return 'warning';
            return 'success';
        }

        document.addEventListener('DOMContentLoaded', () => {

            window.Echo.channel('inventory')
                
                // 1. ACTUALIZACIÓN DE STOCK
                .listen('.stock.changed', (data) => {
                    const card = document.getElementById(`card-${data.product_id}`);
                    
                    if (card) {
                        const newQty = parseInt(data.new_quantity);
                        const newStatus = getStockStatus(newQty); // success, warning, error
                        const newTextClass = getTextColorClass(newStatus); // text-emerald-500, etc.
                        
                        // Elementos
                        const valueText = document.getElementById(`stock-value-${data.product_id}`);
                        const progressBar = document.getElementById(`progress-${data.product_id}`);
                        const dateText = document.getElementById(`date-${data.product_id}`);
                        
                        // Actualizar Datos
                        valueText.textContent = newQty;
                        progressBar.value = newQty;
                        progressBar.max = MAX_CAPACIDAD; 
                        dateText.textContent = "Act: Hace un instante";

                        // Actualizar Colores
                        // Limpiamos clases de texto antiguas
                        valueText.classList.remove('text-emerald-500', 'text-amber-500', 'text-rose-500');
                        valueText.classList.add(newTextClass);

                        // Limpiamos clases de barra antiguas
                        progressBar.classList.remove('progress-success', 'progress-warning', 'progress-error');
                        progressBar.classList.add(`progress-${newStatus}`);

                        // Animación sutil de "latido"
                        card.classList.add('scale-[1.02]', 'ring-2', 'ring-indigo-500/20');
                        setTimeout(() => card.classList.remove('scale-[1.02]', 'ring-2', 'ring-indigo-500/20'), 300);
                    }
                })

                // 2. CREACIÓN DE PRODUCTO
                .listen('.product.created', (data) => {
                    const grid = document.getElementById('inventory-grid');
                    const status = getStockStatus(data.stock_quantity);
                    const textClass = getTextColorClass(status);
                    
                    const newCardHTML = `
                    <div id="card-${data.id}" class="relative group bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-2xl transition-all duration-500 animate-bounce-in">
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div class="flex justify-between items-start mb-4">
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 truncate pr-2">${data.name}</h2>
                                <div class="p-2 rounded-full bg-gray-100 dark:bg-gray-700/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 dark:text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"/><path d="M16 2v4a2 2 0 0 0 2 2h4"/></svg>
                                </div>
                            </div>

                            <div class="flex items-baseline gap-1 mb-4">
                                <span id="stock-value-${data.id}" class="text-5xl font-extrabold ${textClass} transition-colors duration-300">
                                    ${data.stock_quantity}
                                </span>
                                <span class="text-sm text-gray-400 font-medium">unidades</span>
                            </div>

                            <div class="w-full">
                                <div class="flex justify-between text-xs text-gray-400 mb-1">
                                    <span id="date-${data.id}" class="italic">Act: Recién creado</span>
                                    <span>Meta: ${MAX_CAPACIDAD}</span>
                                </div>
                                <progress id="progress-${data.id}" class="progress progress-${status} w-full h-2.5 bg-gray-100 dark:bg-gray-700" value="${data.stock_quantity}" max="${MAX_CAPACIDAD}"></progress>
                            </div>
                        </div>
                    </div>
                    `;
                    grid.insertAdjacentHTML('beforeend', newCardHTML);
                })

                // 3. ELIMINACIÓN
                .listen('.product.deleted', (data) => {
                    const card = document.getElementById(`card-${data.id}`);
                    if (card) {
                        card.style.transform = "scale(0.95)";
                        card.style.opacity = "0";
                        setTimeout(() => card.remove(), 400);
                    }
                });
        });
    </script>
</body>
</html>