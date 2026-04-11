<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 { text-align: center; font-size: 20px; margin-bottom: 5px; }
        .info { font-size: 11px; margin-bottom: 15px; color: #666; }
        .info strong { color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th { background-color: #003366; color: white; font-size: 9px; padding: 6px; text-align: center; border: none; }
        td { font-size: 8px; padding: 4px; text-align: center; border-bottom: 1px solid #eee; }
        tr:nth-child(even) td { background-color: #e6f0fa; }
        .goles-highlight { font-weight: bold; color: #009600; }
        .asist-highlight { font-weight: bold; color: #0064c8; }
        .summary-title { font-size: 14px; font-weight: bold; color: #003366; margin-top: 15px; }
        .summary-table { width: 50%; margin: 5px 0; }
        .summary-table td { padding: 5px; font-size: 10px; }
        .summary-table .label { background-color: #e6f0fa; font-weight: bold; width: 40%; }
        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 20px; font-style: italic; }
    </style>
</head>
<body>
    <h1>REPORTE DE RENDIMIENTO DE JUGADORES</h1>
    <div class="info">
        <strong>Equipo:</strong> {{ $equipoNombre }}<br>
        <strong>Periodo:</strong> {{ $req['fecha_desde'] }} al {{ $req['fecha_hasta'] }}<br>
        <strong>Generado:</strong> {{ now()->format('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Jugador</th>
                <th>Posicion</th>
                <th>Dorsal</th>
                <th>PJ</th>
                <th>Minutos</th>
                <th>Goles</th>
                <th>Asist</th>
                <th>T. Amar</th>
                <th>T. Roja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="text-align:left;">{{ $row->jugador }}</td>
                <td>{{ $row->posicion ?? 'N/A' }}</td>
                <td>{{ (int)($row->dorsal ?? 0) }}</td>
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

    <div class="summary-title">RESUMEN DEL EQUIPO</div>
    <table class="summary-table">
        <tr><td class="label">Total Jugadores</td><td>{{ $stats->total_jugadores }}</td></tr>
        <tr><td class="label">Total Goles</td><td>{{ $stats->total_goles }}</td></tr>
        <tr><td class="label">Total Asistencias</td><td>{{ $stats->total_asistencias }}</td></tr>
        <tr><td class="label">Total Minutos</td><td>{{ $stats->total_minutos }}</td></tr>
        <tr><td class="label">Total Partidos</td><td>{{ $stats->total_partidos }}</td></tr>
    </table>

    <div class="footer">FitControl - Sistema de Gestion Deportiva</div>
</body>
</html>
