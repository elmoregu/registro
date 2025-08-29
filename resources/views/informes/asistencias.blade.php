<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6">Informe de Asistencias por Cobrarrr</h1>

        @if ($informes->isNotEmpty())
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Empresa</th>
                        <th class="border border-gray-300 px-4 py-2">Total de Asistencias</th>
                        <th class="border border-gray-300 px-4 py-2">Total Valor (UF)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($informes as $informe)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $informe->empresa }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $informe->total_asistencias }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-right">
                                {{ number_format($informe->total_valor, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-red-500">No se encontraron asistencias por cobrar.</p>
        @endif
    </div>
</body>

</html>
