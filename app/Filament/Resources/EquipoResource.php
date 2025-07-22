<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipoResource\Pages;
use App\Filament\Resources\EquipoResource\RelationManagers;
use App\Filament\Resources\EquipoResource\RelationManagers\HistorialRelationManager;
use App\Models\Empresa;
use App\Models\Equipo;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
//use Filament\Forms\Components\Split;
use Filament\Tables\Columns\Layout\Split;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Resource;
use Filament\Resources\TextInput;
use Filament\Resources\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ImageColumn;

class EquipoResource extends Resource
{
    protected static ?string $model = Equipo::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('serie')
                ->label('Serie del Equipo')
                ->required()
                ->autofocus()
                ->maxLength(255),
            Forms\Components\TextInput::make('descripcion')
                ->label('Descripción')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('procesador')
                ->label('Procesador')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('ram')
                ->label('Ram')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('sistema')
                ->label('Sistema')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('anydesk')
                ->label('Anydesk')
                ->required()
                ->maxLength(255),
            Forms\Components\RichEditor::make('obs')
                ->label('Observaciones')
                ->columnSpanFull(),
            Forms\Components\Select::make('usuario_id')
                ->label('Asignado a')
                ->relationship('usuario', 'nombre')
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('nombre')
                        ->label('Nombre')
                        ->helperText('Escribir nombre Completo del Usuario.!')
                        ->autofocus()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('telefono')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('email')
                        ->label('Correo Electrónico')
                        ->email()
                        ->maxLength(255),
                    FileUpload::make('url_img')
                        ->label('Foto')
                        ->imageEditor()
                        ->disk('public'),
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
                        ])
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Split::make([
                /*
                Tables\Columns\TextColumn::make('usuario.id')
                    ->label('Asignado a')
                    //->grow(false)
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(
                        function ($state, $record) {
                            $usuarioHtml = '';
                            $imageUrl = asset('storage/' . $record->usuario->url_img);
                            $usuarioHtml .= "<div style='text-align: inline; margin-bottom: 4px;'>
                                                <img src='{$imageUrl}' alt='Foto de Usuario' style='width: 40px; height: 40px; border-radius: 50%; position: relative; display:block;'>
                                                <div>{$record->usuario->nombre}</div>
                                                <div>{$record->usuario->empresa->razon_social}</div>
                                              </div>";
                            return $usuarioHtml ?: 'Sin Usuario';
                        }

                    )
                    ->html() // Permite HTML en la columna
                    ->searchable()
                    ->sortable(),
                    */
                //Stack::make([

                ImageColumn::make('usuario.url_img')
                    //->label('Nombre')
                    ->circular()
                    ->square(),
                TextColumn::make('usuario.nombre')
                    ->label('Asignado a')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('usuario.empresa.razon_social')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),



                //]),
                //Stack::make([
                TextColumn::make('serie')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('procesador')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('ram')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sistema')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                //]),

                TextColumn::make('anydesk')
                    ->label('Anydesk')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('obs')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('usuario.telefono')
                    ->label('Teléfono Usuario')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                //])->from('md')
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('empresa')
                    ->label('Empresa')
                    ->options(
                        Empresa::pluck('razon_social', 'id')->toArray()
                    )
                    ->modifyQueryUsing(function ($query, $data) {
                        if (!isset($data['value'])) return $query;

                        return $query->whereHas('usuario', function ($q) use ($data) {
                            $q->where('empresa_id', $data['value']);
                        });
                    }),
            ])
            //->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            HistorialRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipos::route('/'),
            'create' => Pages\CreateEquipo::route('/create'),
            'edit' => Pages\EditEquipo::route('/{record}/edit'),
        ];
    }
}
