<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock System - Inicio</title>
    
    <!-- Tailwind CSS  -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <style>
        /* Animación suave para los gradientes */
        .animate-text {
            background-size: 200% auto;
            animation: textShine 5s ease-in-out infinite alternate;
        }
        @keyframes textShine {
            to { background-position: 200% center; }
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 font-sans selection:bg-blue-500 selection:text-white">

    <section class="w-full min-h-screen flex items-center justify-center">
        <div class="container px-6 py-16 mx-auto text-center">
            
            <div class="max-w-4xl mx-auto">
                <!-- Título Principal con Gradientes -->
                <h1 class="text-5xl font-extrabold lg:text-7xl 2xl:text-8xl tracking-tight">
                    <span class="text-transparent bg-gradient-to-br bg-clip-text from-teal-500 via-indigo-500 to-sky-500 dark:from-teal-200 dark:via-indigo-300 dark:to-sky-500 animate-text">
                        Stock
                    </span>
                    <span class="text-transparent bg-gradient-to-tr bg-clip-text from-blue-500 via-pink-500 to-red-500 dark:from-sky-300 dark:via-pink-300 dark:to-red-500 animate-text">
                        System
                    </span>
                </h1>

                <p class="mt-6 text-lg text-gray-600 dark:text-gray-300 md:text-xl max-w-2xl mx-auto">
                    Control de inventario en tiempo real impulsado por <span class="font-bold text-indigo-500">Laravel Reverb</span>. Selecciona tu rol para ingresar.
                </p>

                <!-- LAS DOS TARJETAS (Cards) -->
                <div class="flex flex-col md:flex-row justify-center items-center gap-6 mt-12 sm:px-10">
                    
                    <!-- CARD 1: MONITOR (Público) -->
                    <a href="/inventory" class="group relative w-full md:w-1/2 max-w-sm">
                        <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 to-sky-500 rounded-2xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative h-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-8 hover:transform hover:-translate-y-1 transition-all duration-300 shadow-xl flex flex-col items-center">
                            
                            <!-- Icono Monitor -->
                            <div class="p-3 rounded-full bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-300 mb-4 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>

                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Monitor Público</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Visualización de stock en pantalla grande para clientes o almacén.</p>
                            
                            <span class="mt-6 inline-flex items-center text-sky-500 font-semibold group-hover:translate-x-1 transition-transform">
                                Ver Stock <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </span>
                        </div>
                    </a>

                    <!-- CARD 2: MANAGER (Admin) -->
                    <a href="/manager" class="group relative w-full md:w-1/2 max-w-sm">
                        <div class="absolute -inset-1 bg-gradient-to-r from-pink-500 to-red-500 rounded-2xl blur opacity-25 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative h-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-8 hover:transform hover:-translate-y-1 transition-all duration-300 shadow-xl flex flex-col items-center">
                            
                            <!-- Icono Ajustes/Admin -->
                            <div class="p-3 rounded-full bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-300 mb-4 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                </svg>
                            </div>

                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Panel Gestor</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Control de inventario, sumas, restas y alta de nuevos productos.</p>
                            
                            <span class="mt-6 inline-flex items-center text-pink-500 font-semibold group-hover:translate-x-1 transition-transform">
                                Administrar <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </span>
                        </div>
                    </a>

                </div>
                
                <p class="mt-12 text-sm text-gray-500 dark:text-gray-500">
                    Sistema desarrollado con Laravel 12 + Reverb Websockets
                </p>

            </div>
        </div>
    </section>

</body>
</html>