<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        .info { font-size: 11px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th { background-color: #003366; color: white; font-size: 8px; padding: 4px; text-align: center; }
        td { font-size: 7px; padding: 3px; text-align: center; }
        .present { background-color: #90EE90; }
        .absent { background-color: #FF6347; color: white; }
    </style>
</head>
<body>
    <h1>REPORTE DE ASISTENCIA A ENTRENAMIENTOS</h1>
    <div class="info">
        Equipo: {{ $equipoNombre }} | Periodo: {{ $req['fecha_desde'] }} al {{ $req['fecha_hasta'] }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th style="text-align:left;">Jugador</th>
                @foreach($entrenamientos as $ent)
                    <th>{{ substr($ent->fecha, 0, 10) }}</th>
                @endforeach
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @php
                $asistenciaMap = [];
                foreach($asistencias as $a) {
                    $asistenciaMap[$a->user_id . '_' . $a->entrenamiento_id] = (bool) $a->presente;
                }
            @endphp
            @foreach($jugadores as $i => $jugador)
                @php
                    $presentes = 0;
                    foreach($entrenamientos as $ent) {
                        $key = $jugador->id . '_' . $ent->id;
                        if ($asistenciaMap[$key] ?? false) $presentes++;
                    }
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="text-align:left;">{{ $jugador->name }}</td>
                    @foreach($entrenamientos as $ent)
                        @php
                            $key = $jugador->id . '_' . $ent->id;
                            $presente = $asistenciaMap[$key] ?? false;
                        @endphp
                        <td class="{{ $presente ? 'present' : 'absent' }}">{{ $presente ? 'P' : 'A' }}</td>
                    @endforeach
                    <td>{{ count($entrenamientos) > 0 ? round($presentes * 100 / count($entrenamientos)) : 0 }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
