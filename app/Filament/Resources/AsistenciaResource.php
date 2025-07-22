<?php

namespace App\Filament\Resources;

use App\Exports\AsistenciasExport;
use App\Filament\Resources\AsistenciaResource\Pages;
use App\Filament\Resources\AsistenciaResource\RelationManagers;
use App\Models\Asistencia;
use App\Models\Empresa;
use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\ValorAsistencia;
use Filament\Actions\SelectAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Select;
use Filament\Forms\Components\Grid;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Maatwebsite\Excel\Facades\Excel;

//use App\Filament\Resources\AsistenciaResource\Pages\ViewAsistencia;

class AsistenciaResource extends Resource
{
    protected static ?string $model = Asistencia::class;

    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';

    protected static ?string $slug = 'asistencias';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Forms\Components\Select::make('usuario_id')
                        ->label('Usuario')
                        ->options(Usuario::pluck('nombre', 'id'))
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(
                            fn($state, callable $set) => [
                                $set('empresa_id', Usuario::find($state)?->empresa_id),
                                $set('equipo_id', null), // Limpia el campo equipo_id al cambiar de usuario
                            ]
                        ),

                    Forms\Components\Select::make('empresa_id')
                        ->label('Empresa')
                        ->options(Empresa::pluck('razon_social', 'id'))
                        ->disabled(), // Para que no sea editable manualmente
                ]),


                Forms\Components\Select::make('equipo_id')
                    ->label('Equipo')
                    /*
                    Analizando el código:
->options(fn($get) => ...): Esta parte define una función anónima que se ejecutará para generar las opciones del Select de equipos.
$get('usuario_id'): Dentro de la función anónima, $get es una función que te permite acceder al valor actual del campo usuario_id en el formulario.
Equipo::where('usuario_id', $get('usuario_id'))->pluck('descripcion', 'id'): Esta línea realiza una consulta a la tabla Equipo para obtener todos los equipos que pertenecen al usuario seleccionado.
where('usuario_id', $get('usuario_id')): Filtra los equipos para que solo se muestren los que pertenecen al usuario actual.
pluck('descripcion', 'id'): Extrae la descripción del equipo como el valor del select y el ID como la clave, creando un array asociativo que se utilizará para alimentar las opciones del Select.
                    */
                    ->options(
                        fn($get) =>
                        Equipo::where('usuario_id', $get('usuario_id'))->pluck('descripcion', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->nullable()
                    ->afterStateUpdated(fn(callable $set) => $set('descripcion', null)),
                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción')
                    ->placeholder('Describe la asistencia si no hay equipo')
                    ->reactive()
                    ->hidden(fn(Get $get) => $get('equipo_id') !== null),

                Forms\Components\DatePicker::make('fecha')
                    ->label('Fecha de asistencia')
                    ->default(now())
                    ->required(),

                Forms\Components\RichEditor::make('notas')
                    ->label('Notas')
                    ->columnSpan(2),

                Forms\Components\Select::make('tipo')
                    ->label('Tipo de Asistencia')
                    ->options([
                        'presencial' => 'Presencial',
                        'remota' => 'Remota',
                    ])
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(
                        fn(callable $set, callable $get) =>
                        $set('valor', self::obtenerValorAsistencia($get('empresa_id'), $get('tipo'))),

                    )
                    ->afterStateUpdated(
                        fn(callable $set, callable $get) =>
                        $set('valor_asistencias_id', self::obtenerValorAsistenciaId($get('empresa_id'), $get('tipo')))
                    ),
                Forms\Components\TextInput::make('valor_asistencias_id')
                 //->hidden(),
                ,
                Forms\Components\TextInput::make('valor')
                    ->label('Valor')
                    ->reactive()
                    ->required()
                    /*
                    ->afterStateUpdated(
                        fn($state, Set $set, Get $get) =>
                        $set('valor', ValorAsistencia::where('usuario.empresa_id', $get('empresa_id'))
                            ->where('tipo', $state)
                            ->value('valor'))
                    )
                    */
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descripcion')
                    ->getStateUsing(fn($record) => empty($record->descripcion) ? 'Asistencia de Equipo' : $record->descripcion)
                    ->label('Descripción')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('usuario.nombre')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('valorAsistencia.tipo')
                    ->label('Tipo')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'Cobrar' => 'Cobrar',
                        'Pagado' => 'Pagado',
                    ])

                    ->rules(['required'])
                    ->getStateUsing(fn($record) => $record->status === 'Pagado' ? 'Pagado' : $record->status)
                    ->disabled(fn($record) => $record->status === 'Pagado') // Desactiva si el estado es "Pagado"
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->date()
            ])
            ->filters([
                //
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
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('export')
                    ->label('Excel')

                ->action(function (array $records, array $filters) {
                    return Excel::download(new AsistenciasExport($filters), 'asistencias.xlsx');
                }),
            //->icon('heroicon-o-download'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->poll('3s'); // Actualiza la tabla automáticamente cada 500ms

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

            'index' => Pages\ListAsistencias::route('/'),
            'create' => Pages\CreateAsistencia::route('/create'),
            'edit' => Pages\EditAsistencia::route('/{record}/edit'),
            'view' => Pages\VerAsistencia::route('/{record}'),
            'filtrar-asistencias' => Pages\FiltrarAsistencias::route('/filtrar-asistencias'),
            //'filtrar-asistencias' => Pages\FiltrarAsistencias::route('/filtrar-asistencias'),
            //'index' => ListRecords::route('/'),
            //'create' => CreateRecord::route('/create'),


        ];
    }

    protected static function obtenerValorAsistencia($empresa_id, $tipo)
    {
        $empresa = Empresa::find($empresa_id);
        return ValorAsistencia::where('empresa_id', $empresa->id)
            ->where('tipo', $tipo)
            ->value('valor');
    }

    protected static function obtenerValorAsistenciaId($empresa_id, $tipo)
    {
        $empresa = Empresa::find($empresa_id);
        return ValorAsistencia::where('empresa_id', $empresa->id)
            ->where('tipo', $tipo)
            ->value('id');
    }
}
