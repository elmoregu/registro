<x-filament::page>

    @vite('resources/css/app.css')

    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6">Informe de Asistencias</h1>

        @if ($this->informes->isNotEmpty())
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Empresa/Tipo</th>
                        <th class="border border-gray-300 px-4 py-2">Total de Asistencias</th>
                        <th class="border border-gray-300 px-4 py-2">Tipo</th>
                        <th class="border border-gray-300 px-4 py-2">Total Valor (UF)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->informes as $informe)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $informe->empresa . ' ' . $informe->tipo_valor }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $informe->total_asistencias }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $informe->tipo_valor }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-right">
                                {{ '$' . number_format($informe->total_valor, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-red-500">No se encontraron asistencias por cobrar.</p>
        @endif

        <br>

        <!-- Formulario para seleccionar la empresa -->
        <form action="{{ route('exportar-informe-pdf') }}" method="GET">
            <label for="empresa" class="block mb-2 text-sm font-medium text-gray-900">Seleccione Empresa</label>
            <select id="empresa" name="empresa_id" class="block w-full p-2 mb-4 border border-gray-300 rounded-lg">
                <option value="" selected>Seleccione una empresa</option>
                @foreach ($this->informes as $informe)
                    <option value="{{ $informe->empresa_id }}">{{ $informe->empresa . ' ' . $informe->tipo_valor }}
                    </option>
                @endforeach
            </select>

            <label for="fecha_inicio" class="block mb-2 text-sm font-medium text-gray-900">Fecha Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio"
                class="block w-full p-2 mb-4 border border-gray-300 rounded-lg">

            <label for="fecha_fin" class="block mb-2 text-sm font-medium text-gray-900">Fecha Fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin"
                class="block w-full p-2 mb-4 border border-gray-300 rounded-lg">

            <button type="submit"
                class="text-black bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Exportar a PDF
            </button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Click Me
            </button>


            <button type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Default</button>
            <button type="button"
                class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Alternative</button>
            <button type="button"
                class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Dark</button>
            <button type="button"
                class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Light</button>
            <button type="button"
                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Green</button>
            <button type="button"
                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Red</button>
            <button type="button"
                class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Yellow</button>
            <button type="button"
                class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Purple</button>

        </form>
    </div>
</x-filament::page>
