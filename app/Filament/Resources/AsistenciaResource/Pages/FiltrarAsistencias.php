<?php

namespace App\Filament\Resources\AsistenciaResource\Pages;

use App\Models\Asistencia;
use Filament\Resources\Pages\Page as ResourcePage; // Asegúrate que esta es la clase base correcta
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AsistenciaResource;
use App\Models\Empresa;

// Ya no necesitas: use Filament\Navigation\NavigationItem;

class FiltrarAsistencias extends ResourcePage implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = AsistenciaResource::class;

    // Propiedades para controlar la navegación (reemplazan getNavigationItems())
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Filtrar Asistencias';
    protected static ?int $navigationSort = 2; // Define el orden en el menú
    // Opcional: Define el grupo de navegación si es diferente al del recurso
    // protected static ?string $navigationGroup = 'NombreDelGrupo';

    protected static ?string $slug = 'filtrar-asistencias';

    // Título que se mostrará en la página y posiblemente en breadcrumbs/pestaña del navegador
    protected static ?string $title = 'Filtrar Asistencias';


    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->displayFormat('d-m-Y')
                    ->required()
                    ->reactive()
                    // Usar el operador nullsafe por si getTable() devuelve null temporalmente
                    ->afterStateUpdated(fn() => $this->getTable()?->resetPage()),
                DatePicker::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->displayFormat('d-m-Y')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn() => $this->getTable()?->resetPage()),
            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Asistencia::query()
                    ->when(
                        $this->data['fecha_inicio'] ?? null,
                        fn(Builder $query, $date) => $query->where('fecha', '>=', $date),
                    )
                    ->when(
                        $this->data['fecha_fin'] ?? null,
                        fn(Builder $query, $date) => $query->where('fecha', '<=', $date),
                    )
            )
            ->columns([
                TextColumn::make('descripcion')
                    ->getStateUsing(fn($record) => empty($record->descripcion) ? 'Asistencia de Equipo' : $record->descripcion)
                    ->label('Descripción')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('usuario.nombre')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('valorAsistencia.tipo')
                    ->label('Tipo')
                    ->sortable(),
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->sortable(),
                // Agrega aquí las demás columnas que necesites mostrar
            ])
            ->filters([
                // Puedes añadir filtros adicionales si lo deseas
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
                    })
            ])
            ->actions([
                // Puedes añadir acciones a cada fila si lo deseas
            ])
            ->bulkActions([
                // Puedes añadir acciones masivas si lo deseas
            ]);
    }

    // SE HA ELIMINADO EL MÉTODO getNavigationItems()

    // Puedes mantener o eliminar getBreadcrumbs y getTitle/getHeading
    // si la funcionalidad por defecto de la clase base es suficiente.
    // A menudo, definir `protected static ?string $title` es suficiente.
    // Filament\Resources\Pages\Page ya tiene una implementación de getBreadcrumbs.
    // Si no necesitas una personalización mayor, puedes eliminar tu método getBreadcrumbs.

    // Si mantienes getBreadcrumbs, una forma común es:
    /*
    public function getBreadcrumbs(): array
    {
        return [
            // AsistenciaResource::getUrl('index') => AsistenciaResource::getPluralModelLabel(),
            // $this->getTitle(),
            // O, si la clase padre ya genera los breadcrumbs del recurso:
            ...parent::getBreadcrumbs(),
            $this->getTitle() // Añade el título de la página actual (no como enlace)
        ];
    }
    */

    // getTitle() se usa para el título de la pestaña del navegador y como fallback para getHeading().
    // getHeading() es para el título H1 en la página.
    // Definir `protected static ?string $title` suele ser suficiente, pero puedes sobrescribir los métodos si necesitas lógica.
    public function getTitle(): string
    {
        // Usa la propiedad estática $title, o $navigationLabel como fallback.
        return static::$title ?? static::$navigationLabel ?? parent::getTitle();
    }

    // Si también quieres controlar el encabezado H1 de la página:
    /*
    public function getHeading(): string
    {
        return static::$title ?? static::$navigationLabel ?? 'Filtrar Asistencias';
    }
    */
}
