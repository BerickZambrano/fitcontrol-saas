<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class PagosPorMes extends ChartWidget
{
    use HasWidgetShield;

    protected int | string | array $columnSpan = '2';
    protected ?string $heading = 'Pagos por mes';

    protected function getData(): array
    {
        $pagos = Pago::select(
                DB::raw('EXTRACT(MONTH FROM created_at) as mes'),
                DB::raw('SUM(monto) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total de pagos',
                    'data' => $pagos->pluck('total'),
                    'color' => '#3b82f6',
                ],
            ],
            'labels' => $pagos->pluck('mes')->map(fn ($mes) => match ((int) $mes) {
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre',
            }),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}