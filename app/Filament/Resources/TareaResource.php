<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TareaResource\Pages;
use App\Filament\Resources\TareaResource\RelationManagers;
use App\Models\Tarea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TareaResource extends Resource
{
    protected static ?string $model = Tarea::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->required()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('descripcion')
                    ->label('Descripción')
                    ->columnSpanFull(),

                Forms\Components\Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_progreso' => 'En Progreso',
                        'completado' => 'Completado',
                    ])
                    ->default('pendiente'),

                Forms\Components\Select::make('usuario_id')
                    ->relationship('usuario', 'nombre') // ajusta según tu modelo Usuario
                    ->searchable()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('estado')->badge(),
                Tables\Columns\TextColumn::make('usuario.nombre')->label('Asignado a'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTareas::route('/'),
            'create' => Pages\CreateTarea::route('/create'),
            'edit' => Pages\EditTarea::route('/{record}/edit'),
        ];
    }
}
