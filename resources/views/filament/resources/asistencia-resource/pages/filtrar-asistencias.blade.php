<x-filament-panels::page>
    <form wire:submit.prevent="submit" class="mb-4">
        {{ $this->form }}

        <div class="mt-4">
            {{-- Puedes añadir botones o cualquier otro elemento del formulario aquí --}}
        </div>
    </form>

    {{ $this->table }}
</x-filament-panels::page>
