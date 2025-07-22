<?php

namespace App\Filament\Resources\EquipoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportEvents\HandlesEvents;
use Livewire\Attributes\On;
use Livewire\Livewire;

class HistorialRelationManager extends RelationManager
{
    //use HandlesEvents;
    protected static string $relationship = 'historial';




    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('fecha')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('fecha')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                -> label('#'),
                Tables\Columns\TextColumn::make('fecha'),
                Tables\Columns\TextColumn::make('usuario.nombre'),
                Tables\Columns\TextColumn::make('equipo.descripcion'),

                Tables\Columns\TextColumn::make('accion'),

            ])->defaultSort('fecha', 'desc')

            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->poll('5000ms'); // Actualiza la tabla cada 5 segundos
    }

    /*
    #[On('historialUpdated')]
    public function refreshHistorial()
    {
        $this->resetTable(); // Recarga la tabla cuando se recibe el evento
    }
        */


    //protected $listeners = ['historialUpdated' => 'refreshHistorial'];

    public function refreshHistorial()
    {
        $this->resetTable(); // Recarga la tabla cuando se recibe el evento
    }
}
