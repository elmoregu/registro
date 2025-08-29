<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GastoResource\Pages;
use App\Filament\Resources\GastoResource\RelationManagers;
use App\Models\Gasto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput\Mask;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GastoResource extends Resource
{
    // Especifica el modelo asociado a este recurso
    protected static ?string $model = Gasto::class;

    // Ícono que se muestra en el menú de navegación de Filament
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    // Grupo de navegación donde se agrupa este recurso
    protected static ?string $navigationGroup = 'Gestión Financiera';

    // Define el formulario para crear o editar un gasto
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Campo para la descripción del gasto
                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción')
                    ->required()
                    ->autofocus(), // Enfoca este campo al abrir el formulario,

                // Campo para seleccionar la fecha del gasto
                Forms\Components\DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required()
                    ->default(now()) // Valor predeterminado: la fecha actual
                    ->displayFormat('d-m-Y'), // Formato de visualización

                // Campo para agregar observaciones (editor enriquecido)
                Forms\Components\RichEditor::make('obs')
                    ->label('Observaciones')
                    ->columnSpanFull(), // Ocupa todo el ancho del formulario

                // Campo para ingresar el costo del gasto
                Forms\Components\TextInput::make('monto')
                    ->label('Costo')
                    ->numeric() // Solo permite números
                    ->required()
                    ->debounce(950) // Espera 500 ms antes de actualizar el estado
                    ->reactive()
                    //->afterStateUpdated(function ($state, callable $set) {
                    //    $set('monto', number_format($state, 0, '', '.')); // Formatea con separador de miles
                    //})

                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $ganancia = $get('ganancia') ?? 0;
                        $set('venta', round($state * (1 + ($ganancia / 100)), 2));
                    }), // Reactivo para que se pueda usar en cálculos

                // Campo para ingresar el porcentaje de ganancia
                Forms\Components\TextInput::make('ganancia')
                    ->label('% Ganancia')
                    ->numeric()
                    ->required()
                    ->default(15) // Valor predeterminado: 0%
                    ->reactive() // Reactivo para que se pueda usar en cálculos
                    ->debounce(900) // Espera 500 ms antes de actualizar el estado
                    ->suffix('%')

                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $monto = $get('monto') ?? 0;
                        $set('venta', round($monto * (1 + ($state / 100)), 2));
                    }), // Agrega un sufijo visual "%"

                // Campo para calcular automáticamente el precio de venta
                Forms\Components\TextInput::make('venta')
                    ->label('Precio Venta')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->prefix('$')
                    ->disabled() // Deshabilitado para que no sea editable manualmente

                    ->dehydrateStateUsing(function (callable $get) {
                        // Calcula el precio de venta automáticamente
                        $monto = $get('monto') ?? 0; // Obtiene el valor del campo "monto"
                        $ganancia = $get('ganancia') ?? 0; // Obtiene el valor del campo "ganancia"
                        $final = round($monto * (1 + ($ganancia / 100))); // Calcula el precio de venta
                        return number_format($final, 2, '', '.'); // Formatea el valor$final; // Retorna el valor formateado
                    }),

                // Campo para seleccionar la empresa asociada al gasto
                Forms\Components\Select::make('empresa_id')
                    ->label('Empresa')
                    ->relationship('empresa', 'razon_social') // Relación con el modelo Empresa

                    ->required(),

                // Campo para seleccionar el tipo de gasto
                Forms\Components\Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'Accessorios' => 'Accessorios',
                        'Componentes' => 'Componentes',
                        'Equipamiento' => 'Equipamiento',
                    ])
                    ->default('Accessorios') // Valor predeterminado
                    ->required(),

                // Campo para seleccionar el estado del gasto
                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                    ])
                    ->default('pendiente') // Valor predeterminado
                    ->required(),
            ]);
    }

    // Define la tabla para listar los gastos
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columna para mostrar la empresa asociada
                Tables\Columns\TextColumn::make('empresa.razon_social')->label('Empresa'),

                // Columna para mostrar la descripción del gasto
                Tables\Columns\TextColumn::make('descripcion')->label('Descripción'),

                // Columna para mostrar el monto del gasto
                Tables\Columns\TextColumn::make('monto')->label('Monto')->money('CLP'),

                // Columna para mostrar la fecha del gasto
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')->date(),
            ])
            ->filters([
                // Aquí puedes agregar filtros personalizados
            ])
            ->actions([
                // Acción para editar un gasto
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Acciones masivas (por ejemplo, eliminar varios registros)
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Define las relaciones asociadas al recurso (si las hay)
    public static function getRelations(): array
    {
        return [
            // Aquí puedes definir relaciones adicionales
        ];
    }

    // Define las páginas asociadas al recurso
    public static function getPages(): array
    {
        return [
            // Página para listar los gastos
            'index' => Pages\ListGastos::route('/'),

            // Página para crear un nuevo gasto
            'create' => Pages\CreateGasto::route('/create'),

            // Página para editar un gasto existente
            'edit' => Pages\EditGasto::route('/{record}/edit'),
        ];
    }
}
