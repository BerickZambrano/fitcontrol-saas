@extends('reports.layout')

@section('title', 'Reporte de Rendimiento - ' . $equipoNombre)

@section('custom-styles')
    .goles-highlight { color: #15803d; font-weight: bold; }
    .asist-highlight { color: #1d4ed8; font-weight: bold; }
    .summary-box {
        margin-top: 25px;
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }
    .summary-header {
        background-color: #f8fafc;
        padding: 10px;
        border-bottom: 1px solid #e2e8f0;
        font-weight: bold;
        color: #0f172a;
        font-size: 12px;
    }
    .summary-content {
        padding: 15px;
    }
    .summary-grid {
        width: 100%;
    }
    .summary-item {
        padding: 10px;
        text-align: center;
    }
    .summary-value {
        font-size: 18px;
        font-weight: bold;
        color: #0f172a;
        display: block;
    }
    .summary-label {
        font-size: 9px;
        color: #64748b;
        text-transform: uppercase;
    }
@endsection

@section('meta')
    <table>
        <tr>
            <td width="50%"><strong>REPORTE:</strong> Rendimiento de Jugadores</td>
            <td width="50%" class="text-right"><strong>EQUIPO:</strong> {{ $equipoNombre }}</td>
        </tr>
        <tr>
            <td><strong>PERIODO:</strong> {{ \Carbon\Carbon::parse($req['fecha_desde'])->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($req['fecha_hasta'])->format('d/m/Y') }}</td>
            <td class="text-right"><strong>GENERADO POR:</strong> Admin FitControl</td>
        </tr>
    </table>
@endsection

@section('content')
    <table class="main-table">
        <thead>
            <tr>
                <th width="30">#</th>
                <th class="text-left">Jugador</th>
                <th>Pos.</th>
                <th>Dorsal</th>
                <th>PJ</th>
                <th>Min.</th>
                <th>Goles</th>
                <th>Asist.</th>
                <th>T.A.</th>
                <th>T.R.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-left bold">{{ $row->jugador }}</td>
                <td>{{ $row->posicion ?? 'N/A' }}</td>
                <td><span class="badge badge-info">{{ (int)($row->dorsal ?? 0) }}</span></td>
                <td>{{ (int)$row->partidos_jugados }}</td>
                <td>{{ (int)$row->minutos }}</td>
                <td class="{{ (int)$row->goles > 0 ? 'goles-highlight' : '' }}">{{ (int)$row->goles }}</td>
                <td class="{{ (int)$row->asistencias > 0 ? 'asist-highlight' : '' }}">{{ (int)$row->asistencias }}</td>
                <td>{{ (int)$row->tarjetas_amarillas }}</td>
                <td>{{ (int)$row->tarjetas_rojas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-header">RESUMEN DEL EQUIPO</div>
        <div class="summary-content">
            <table class="summary-grid">
                <tr>
                    <td class="summary-item">
                        <span class="summary-value">{{ $stats->total_jugadores }}</span>
                        <span class="summary-label">Jugadores</span>
                    </td>
                    <td class="summary-item">
                        <span class="summary-value">{{ $stats->total_goles }}</span>
                        <span class="summary-label">Total Goles</span>
                    </td>
                    <td class="summary-item">
                        <span class="summary-value">{{ $stats->total_asistencias }}</span>
                        <span class="summary-label">Total Asist.</span>
                    </td>
                    <td class="summary-item" style="border-right: none;">
                        <span class="summary-value">{{ $stats->total_partidos }}</span>
                        <span class="summary-label">Partidos</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
