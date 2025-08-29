<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Asistencia</title>
    <!-- Incluye Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Define la fuente Inter por defecto */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <!-- Incluye Font Awesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Encabezado de la página -->
    <header class="bg-white shadow-sm p-4">
        <div class="container mx-auto flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-search text-purple-500 mr-2"></i>
                Consulta de Asistencia Técnica
            </h1>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="container mx-auto p-4 mt-8">
        <!-- Tarjeta del formulario de búsqueda -->
        <div class="bg-white rounded-lg shadow-xl p-8 mb-8 w-full max-w-2xl mx-auto">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Buscar Asistencia</h2>
            <!-- El formulario sigue la misma estructura, pero aquí se muestra como un ejemplo estático -->
            <form action="{{ route('buscarAsistencia') }}" method="POST">
                @csrf
                <!-- Se omite el token CSRF para este ejemplo visual -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="empresa">
                            Empresa
                        </label>
                        <input
                            class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            id="empresa" name="empresa" type="text" placeholder="Nombre de la Empresa" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="ticket">
                            Número de Ticket
                        </label>
                        <input
                            class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            id="ticket" name="ticket" type="text" placeholder="12345" required>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <button
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                        type="submit">
                        <i class="fas fa-search mr-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>

        <!-- Bloque con datos de ejemplo para mostrar los resultados -->


        <!-- Sección de resultados de búsqueda -->
        @if ($asistencias->isNotEmpty())
            @php
                $asistencia = $asistencias->first();
            @endphp
            <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl mx-auto">
                <h2 class="text-xl font-semibold text-gray-700 mb-6 flex items-center">
                    <i class="fas fa-file-alt text-purple-500 mr-2"></i>
                    Detalle de Asistencia
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <p><strong>ID:</strong> <span class="text-purple-600">{{ $asistencia->id }}</span></p>
                    <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($asistencia->fecha)) }}</p>
                    <p><strong>Estado:</strong> <span class="font-bold text-green-600">{{ $asistencia->status }}</span>
                    </p>
                    <p><strong>Empresa:</strong> {{ $asistencia->razon_social }}</p>
                    <p class="md:col-span-2"><strong>Descripción:</strong> {{ $asistencia->descripcion }}</p>
                    <p class="md:col-span-2"><strong>Notas:</strong> {{ $asistencia->notas }}</p>
                    {{-- <p><strong>Valor:</strong> ${{ number_format($asistencia->valor, 2, ',', '.') }}</p> --}}
                    <p><strong>Creado:</strong> {{ date('d/m/Y H:i', strtotime($asistencia->created_at)) }}</p>
                </div>
            </div>
        @elseif(isset($asistencias))
            <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-2xl mx-auto text-center">
                <p class="text-red-500 text-lg font-medium">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    No se encontraron resultados para la búsqueda.
                </p>
            </div>
        @endif
    </main>

</body>

</html>
<script>
    // Configuración de Tailwind CSS
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    purple: {
                        600: '#6b46c1',
                        700: '#553c9a',
                    },
                },
            },
        },
    };
</script>
