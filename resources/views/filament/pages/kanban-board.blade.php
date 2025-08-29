<x-filament::page>
    @vite('resources/css/app.css')

    <div class="flex gap-4 overflow-x-auto">
        @foreach ([
        'pendiente' => ['label' => 'Pendiente', 'color' => 'bg-red-100 border-red-400'],
        'en_progreso' => ['label' => 'En Progreso', 'color' => 'bg-yellow-100 border-yellow-400'],
        'completado' => ['label' => 'Completado', 'color' => 'bg-green-100 border-green-400'],
    ] as $estado => $config)
            <div class="flex-1 min-w-[300px] {{ $config['color'] }} border-2 rounded-lg p-4 shadow">
                <h2 class="font-bold mb-4 text-center uppercase">{{ $config['label'] }}</h2>
                <div class="kanban-column min-h-[300px]" data-estado="{{ $estado }}">
                    @foreach (\App\Models\Tarea::where('estado', $estado)->get() as $tarea)
                        <div class="kanban-item bg-white p-3 mb-3 rounded shadow cursor-move border-l-4
                            @if ($estado === 'pendiente') border-red-500
                            @elseif($estado === 'en_progreso') border-yellow-500
                            @else border-green-500 @endif"
                            data-id="{{ $tarea->id }}">
                            <strong>{{ $tarea->titulo }}</strong>
                            @if ($tarea->descripcion)
                                <p class="text-sm text-gray-600">{{ $tarea->descripcion }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.querySelectorAll('.kanban-column').forEach(column => {
            new Sortable(column, {
                group: 'kanban',
                animation: 150,
                onEnd: async (evt) => {
                    let tareaId = evt.item.dataset.id;
                    let nuevoEstado = evt.to.dataset.estado;

                    await fetch("{{ route('kanban.update') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: tareaId,
                            estado: nuevoEstado
                        })
                    });
                }
            });
        });
    </script>
</x-filament::page>
