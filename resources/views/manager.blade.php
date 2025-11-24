<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Movimientos</title>
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animación suave para nueva fila */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in {
            animation: slideIn 0.4s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen p-8 font-sans transition-colors duration-300">

    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                    <span class="text-transparent bg-gradient-to-r bg-clip-text from-pink-500 via-red-500 to-yellow-500 animate-text">
                        Control de Stock
                    </span>
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Administración de entradas y salidas</p>
            </div>

            <button onclick="my_modal_1.showModal()" class="btn border-none bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-lg shadow-blue-500/30 gap-2 transition-transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nuevo Producto
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-300 uppercase text-xs font-bold tracking-wider">
                            <th class="py-4 pl-6">Producto</th>
                            <th class="text-center">Stock Actual</th>
                            <th class="text-center">Acciones Rápidas</th>
                            <th class="text-right pr-6">Gestionar</th>
                        </tr>
                    </thead>
                    
                    <tbody id="table-body" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" id="row-{{ $product->id }}">
                                <td class="pl-6">
                                    <div class="font-bold text-lg text-gray-800 dark:text-gray-100">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-400" id="date-{{ $product->id }}">
                                        Últim. act: {{ $product->updated_at->diffForHumans() }}
                                    </div>
                                </td>
                                
                                <td class="text-center">
                                    <div class="inline-block px-4 py-1 rounded-lg bg-gray-100 dark:bg-gray-900 font-mono text-2xl font-bold text-gray-700 dark:text-gray-200 transition-transform duration-300" id="display-stock-{{ $product->id }}">
                                        {{ $product->stock_quantity }}
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="sendMovement({{ $product->id }}, 'subtract')" class="btn btn-sm btn-circle btn-ghost text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 border border-gray-200 dark:border-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                                        </button>

                                        <input type="number" id="input-qty-{{ $product->id }}" placeholder="0" class="input input-sm input-bordered w-16 text-center font-bold focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" />

                                        <button onclick="sendMovement({{ $product->id }}, 'add')" class="btn btn-sm btn-circle btn-ghost text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 border border-gray-200 dark:border-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                        </button>
                                    </div>
                                </td>
                                
                                <td class="text-right pr-6">
                                    <button onclick="deleteProduct({{ $product->id }})" class="btn btn-sm btn-square btn-ghost text-gray-400 hover:text-red-500 transition-colors" title="Eliminar Producto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <dialog id="my_modal_1" class="modal backdrop-blur-sm">
        <div class="modal-box bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-xl mb-6 text-gray-800 dark:text-white flex items-center gap-2">
                <span>✨</span> Agregar Nuevo Producto
            </h3>
            <div class="flex flex-col gap-4">
                <div>
                    <label class="label text-xs font-bold text-gray-500 uppercase">Nombre</label>
                    <input type="text" id="new-name" placeholder="Ej. Teclado Mecánico" class="input input-bordered w-full dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-indigo-500" />
                </div>
                <div>
                    <label class="label text-xs font-bold text-gray-500 uppercase">Stock Inicial</label>
                    <input type="number" id="new-stock" placeholder="Ej. 50" class="input input-bordered w-full dark:bg-gray-900 dark:text-white dark:border-gray-600 focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>
            <div class="modal-action mt-8">
                <form method="dialog">
                    <button class="btn btn-ghost dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Cancelar</button>
                    <button type="button" onclick="createProduct()" class="btn bg-indigo-600 hover:bg-indigo-700 text-white border-none ml-2">Guardar Producto</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            window.Echo.channel('inventory')
                
                // 1. EVENTO: CAMBIO DE STOCK
                .listen('.stock.changed', (data) => {
                    const display = document.getElementById(`display-stock-${data.product_id}`);
                    const dateText = document.getElementById(`date-${data.product_id}`);
                    
                    if (display) {
                        display.textContent = data.new_quantity;
                        // Efecto visual pop (color indigo)
                        display.classList.add('text-indigo-600', 'scale-125');
                        setTimeout(() => display.classList.remove('text-indigo-600', 'scale-125'), 300);
                        
                        if(dateText) {
                            dateText.textContent = "Últim. act: Ahora mismo";
                            dateText.classList.add('text-emerald-500', 'font-bold');
                            setTimeout(() => dateText.classList.remove('text-emerald-500', 'font-bold'), 2000);
                        }
                    }
                })

                // 2. EVENTO: PRODUCTO CREADO
                .listen('.product.created', (data) => {
                    const tbody = document.getElementById('table-body');
                    
                    const newRow = `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors animate-slide-in bg-emerald-50/50 dark:bg-emerald-900/10" id="row-${data.id}">
                        <td class="pl-6">
                            <div class="font-bold text-lg text-gray-800 dark:text-gray-100">${data.name}</div>
                            <div class="text-xs text-gray-400" id="date-${data.id}">Últim. act: Hace un instante</div>
                        </td>
                        <td class="text-center">
                            <div class="inline-block px-4 py-1 rounded-lg bg-gray-100 dark:bg-gray-900 font-mono text-2xl font-bold text-gray-700 dark:text-gray-200 transition-transform duration-300" id="display-stock-${data.id}">${data.stock_quantity}</div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="sendMovement(${data.id}, 'subtract')" class="btn btn-sm btn-circle btn-ghost text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 border border-gray-200 dark:border-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                                </button>
                                <input type="number" id="input-qty-${data.id}" placeholder="0" class="input input-sm input-bordered w-16 text-center font-bold focus:border-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" />
                                <button onclick="sendMovement(${data.id}, 'add')" class="btn btn-sm btn-circle btn-ghost text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 border border-gray-200 dark:border-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                </button>
                            </div>
                        </td>
                        <td class="text-right pr-6">
                            <button onclick="deleteProduct(${data.id})" class="btn btn-sm btn-square btn-ghost text-gray-400 hover:text-red-500 transition-colors" title="Eliminar Producto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </td>
                    </tr>
                    `;
                    
                    tbody.insertAdjacentHTML('beforeend', newRow);
                    
                    // Quitar el color verde de "nuevo" después de 2 segs
                    setTimeout(() => {
                        const row = document.getElementById(`row-${data.id}`);
                        if(row) row.classList.remove('bg-emerald-50/50', 'dark:bg-emerald-900/10');
                    }, 2500);
                })

                // 3. EVENTO: PRODUCTO ELIMINADO
                .listen('.product.deleted', (data) => {
                    const row = document.getElementById(`row-${data.id}`);
                    if (row) {
                        row.style.transition = "all 0.5s";
                        row.style.opacity = "0";
                        row.style.transform = "translateX(50px)";
                        setTimeout(() => row.remove(), 500);
                    }
                });
        });

        // --- Funciones Lógicas ---

        window.deleteProduct = (id) => {
            if(!confirm('¿Eliminar producto definitivamente?')) return;
            fetch(`/inventory/${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            }).then(r => r.json()).then(d => { if(d.success) console.log('Eliminando...'); });
        };

        window.createProduct = () => {
            const name = document.getElementById('new-name').value;
            const stock = document.getElementById('new-stock').value;
            if(!name || !stock) return alert("Completa los campos");

            const btn = event.target;
            const originalText = btn.innerText;
            btn.innerText = 'Guardando...';
            btn.disabled = true;

            fetch('/inventory', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ name, stock_quantity: stock })
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('my_modal_1').close();
                    document.getElementById('new-name').value = '';
                    document.getElementById('new-stock').value = '';
                }
            })
            .finally(() => { btn.innerText = originalText; btn.disabled = false; });
        };

        window.sendMovement = (id, operation) => {
            const input = document.getElementById(`input-qty-${id}`);
            const quantity = input.value;
            if (!quantity || quantity <= 0) return alert("Cantidad inválida");

            fetch(`/inventory/${id}/update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ quantity, operation })
            })
            .then(r => r.json())
            .then(d => { 
                if(d.success) input.value = '';
                else alert(d.message);
            });
        };
    </script>
</body>
</html>