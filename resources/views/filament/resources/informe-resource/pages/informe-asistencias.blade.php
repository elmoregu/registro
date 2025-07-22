<x-filament::page>
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6">Informe de Asistencias por Cobrar</h1>

        <a href="{{ route('exportar-informe-pdf') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            Exportar a PDF
        </a>

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
                                {{ '$' . number_format($informe->total_valor, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-red-500">No se encontraron asistencias por cobrar.</p>
        @endif
    </div>
</x-filament::page>
