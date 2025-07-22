<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsuarioResource\Pages;
use App\Filament\Resources\UsuarioResource\RelationManagers;
use App\Models\Empresa;
use App\Models\Usuario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;

class UsuarioResource extends Resource
{
    protected static ?string $model = Usuario::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->label('Nombre')
                ->autofocus()
                //->hint('Nombres y Apellidos.')
                ->helperText('Nombres y Apellidos')
                ->placeholder('Escriba su nombre...')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label('Correo Electrónico')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('cargo')
                ->label('Cargo')

                ->required()
                ->maxLength(30),
            Forms\Components\TextInput::make('telefono')
                ->label('Teléfono')
                ->tel()
                ->maxLength(20),
            Forms\Components\Select::make('empresa_id')
                ->label('Empresa')
                ->relationship('empresa', 'razon_social')
                ->required()
                ->preload()
                ->searchable()
                ->createOptionForm([
                    Forms\Components\TextInput::make('razon_social')
                        ->label('Razón Social')
                        ->helperText('Escribir la Razón Social o el nombre de Fantasía de la Empresa.!')
                        ->autofocus()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('rut')
                        ->label('Rut')
                        ->required()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('direccion')
                        ->label('Dirección')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('telefono')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('correo')
                        ->label('Correo Electrónico')
                        ->email()
                        ->maxLength(255)
                ]),
            FileUpload::make('url_img')
                ->label('Foto')
                ->imageEditor()
                ->disk('public'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable(),
                //Split::make([
                //Stack::make([
                ImageColumn::make('url_img')
                    ->label('Nombre')
                    ->circular()
                    ->square(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                //]),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->sortable()
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email Copiado!')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->icon('heroicon-m-phone')
                    ->sortable(),

                Tables\Columns\TextColumn::make('empresa.razon_social')
                    ->label('Empresa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // ])->from('md'),
            ])
            ->filters([
                SelectFilter::make('empresa_id')
                    ->label('Empresa')
                    ->options(Empresa::all()->pluck('razon_social', 'id')->toArray()),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'equipos' => RelationManagers\EquiposRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsuarios::route('/'),
            'create' => Pages\CreateUsuario::route('/create'),
            'edit' => Pages\EditUsuario::route('/{record}/edit'),
        ];
    }
}
