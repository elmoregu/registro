<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ValorAsistenciaResource\Pages;
use App\Filament\Resources\ValorAsistenciaResource\RelationManagers;
use App\Models\ValorAsistencia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ValorAsistenciaResource extends Resource
{
    protected static ?string $model = ValorAsistencia::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('moneda')
                    ->label('Tipo Moneda')
                    ->required(),

                Forms\Components\TextInput::make('valor')
                    ->label('Valor')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('empresa_id')
                    ->label('Empresa')
                    ->relationship('empresas', 'razon_social')

                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('tipo')
                    ->label('Tipo de Asistencia')
                    ->options([
                        'Presencial' => 'Presencial',
                        'Remota' => 'Remota',
                    ])
                    ->preload()
                    ->searchable()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('empresa.razon_social')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('moneda')
                    ->label('Moneda')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor')
                    ->label('Monto')
                    ->sortable()
                    ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageValorAsistencias::route('/'),
        ];
    }
}
