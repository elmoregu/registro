<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class KanbanBoard extends Page
{
    protected static ?string $navigationLabel = 'Kanban Tareas'; // ← Cambia aquí

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.kanban-board';

    public function getTitle(): string
    {
        return 'Tareas';
    }
}
