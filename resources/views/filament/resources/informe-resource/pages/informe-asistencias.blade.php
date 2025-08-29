<x-filament::page>
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6">Informe de Asistencias por Cobrar</h1>

        @if ($this->informes->isNotEmpty())
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Empresa</th>
                        <th class="border border-gray-300 px-4 py-2">Total de Asistencias</th>
                        <th class="border border-gray-300 px-4 py-2">Total Valor (UF)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->informes as $informe)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $informe->empresa }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $informe->total_asistencias }}
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
                    <option value="{{ $informe->empresa_id }}">{{ $informe->empresa }}</option>
                @endforeach
            </select>

            <label for="fecha_inicio" class="block mb-2 text-sm font-medium text-gray-900">Fecha Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio"
                class="block w-full p-2 mb-4 border border-gray-300 rounded-lg">

            <label for="fecha_fin" class="block mb-2 text-sm font-medium text-gray-900">Fecha Fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin"
                class="block w-full p-2 mb-4 border border-gray-300 rounded-lg">

            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Exportar a PDF
            </button>
        </form>
    </div>
</x-filament::page>
