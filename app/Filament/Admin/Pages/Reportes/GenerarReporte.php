<?php

namespace App\Filament\Admin\Pages\Reportes;

use App\Models\Equipo;
use App\Services\ReportService;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class GenerarReporte extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'Generar Reporte';
    protected static string|UnitEnum|null $navigationGroup = 'Reportes';

    public ?array $data = [];

    public function getView(): string
    {
        return 'filament.admin.pages.reportes.generar-reporte';
    }

    public function mount(): void
    {
        $this->form->fill([
            'format' => 'pdf',
        ]);
    }

    public function form(Schema $form): Schema
    {
        $tenantId = Auth::user()->tenant_id;

        // Si es super_admin, puede ver todos los tenants
        $equipos = [];
        if ($tenantId) {
            $equipos = Equipo::where('tenant_id', $tenantId)
                ->orderBy('nombre')
                ->pluck('nombre', 'id')
                ->toArray();
        } else {
            // super_admin: todos los equipos
            $equipos = Equipo::orderBy('nombre')
                ->get()
                ->mapWithKeys(fn($e) => [$e->id => "{$e->nombre} ({$e->tenant->nombre})"])
                ->toArray();
        }

        return $form
            ->schema([
                Select::make('report_type')
                    ->label('Tipo de reporte')
                    ->options(ReportService::getTiposReporte())
                    ->required()
                    ->live()
                    ->default('performance'),

                Select::make('equipo_id')
                    ->label('Equipo')
                    ->options($equipos)
                    ->searchable()
                    ->required()
                    ->helperText('Selecciona el equipo para el reporte'),

                Select::make('tenant_id')
                    ->label('Club / Tenant')
                    ->options(fn() => Auth::user()->hasRole('super_admin')
                        ? \App\Models\Tenant::orderBy('nombre')->pluck('nombre', 'id')
                        : [])
                    ->visible(fn() => Auth::user()->hasRole('super_admin'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) use ($equipos) {
                        if ($state) {
                            $equiposTenant = Equipo::where('tenant_id', $state)
                                ->orderBy('nombre')
                                ->pluck('nombre', 'id')
                                ->toArray();
                            $set('equipo_options', $equiposTenant);
                        }
                    }),

                DatePicker::make('fecha_desde')
                    ->label('Desde')
                    ->required()
                    ->default(now()->subMonth()),

                DatePicker::make('fecha_hasta')
                    ->label('Hasta')
                    ->required()
                    ->default(now())
                    ->after('fecha_desde'),

                Select::make('format')
                    ->label('Formato de exportación')
                    ->options(ReportService::getFormatos())
                    ->default('pdf')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function generarReporte(ReportService $reportService): void
    {
        $formData = $this->form->getState();

        // Obtener nombre del equipo para el título
        $equipo = Equipo::find($formData['equipo_id']);
        if ($equipo) {
            $formData['equipo_nombre'] = $equipo->nombre;
        }

        try {
            $report = $reportService->generar($formData);

            Notification::make()
                ->title('✅ Reporte generado exitosamente')
                ->body("{$report->title} ({$report->file_format})")
                ->success()
                ->send();

            // Descargar automáticamente
            $this->redirect(route('reportes.descargar', $report->id));

        } catch (\Exception $e) {
            Notification::make()
                ->title('❌ Error generando el reporte')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
