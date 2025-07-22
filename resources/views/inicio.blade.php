<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Test</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md space-y-4">
        <h1 class="text-xl font-bold text-gray-900">Hello, Tailwind!</h1>
        <p class="text-gray-600">Este es un ejemplo básico para probar Tailwind CSS.</p>
    </div>
    <form action="{{ route('buscarAsistencia') }}" method="POST" class="w-full max-w-sm">
        @csrf
        <div class="md:flex md:items-center mb-6">
            <div class="md:w-1/3">
                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="empresa">
                    Empresa
                </label>
            </div>
            <div class="md:w-2/3">
                <input
                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                    id="empresa" name="empresa" type="text" placeholder="Nombre de la Empresa" required>
            </div>
        </div>
        <div class="md:flex md:items-center mb-6">
            <div class="md:w-1/3">
                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="ticket">
                    Número de Ticket
                </label>
            </div>
            <div class="md:w-2/3">
                <input
                    class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                    id="ticket" name="ticket" type="text" placeholder="12345" required>
            </div>
        </div>
        <div class="md:flex md:items-center">
            <div class="md:w-1/3"></div>
            <div class="md:w-2/3">
                <button
                    class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                    type="submit">
                    Buscar
                </button>
            </div>
        </div>
    </form>
    @if (isset($asistencias) && $asistencias->isNotEmpty())
        <div class="mt-6">
            <h2 class="text-lg font-bold">Resultados de la búsqueda:</h2>
            @php
                $asistencia = $asistencias->first(); // Obtén el primer registro
            @endphp
            <p><strong>ID:</strong> {{ $asistencia->id }}</p>
            <p><strong>Fecha:</strong> {{ $asistencia->fecha }}</p>
            <p><strong>Notas:</strong> {{ $asistencia->notas }}</p>
            <p><strong>Estado:</strong> {{ $asistencia->status }}</p>
            <p><strong>Creado:</strong> {{ $asistencia->created_at }}</p>
            <p><strong>Descripción:</strong> {{ $asistencia->descripcion }}</p>
            <p><strong>Valor:</strong> {{ $asistencia->valor }}</p>
            <p><strong>Empresa:</strong> {{ $asistencia->razon_social }}</p>
        </div>
    @elseif(isset($asistencias))
        <p class="mt-6 text-red-500">No se encontraron resultados para la búsqueda.</p>
    @endif
</body>

</html>
