<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        h2 { font-size: 14px; color: #003366; margin-top: 15px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .info { font-size: 11px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th { background-color: #cc0000; color: white; font-size: 9px; padding: 5px; text-align: center; }
        td { font-size: 8px; padding: 4px; }
        .no-aptos-title { color: red; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>REPORTE MEDICO</h1>
    <div class="info">
        Club: {{ $tenantNombre }} | Periodo: {{ $req['fecha_desde'] }} al {{ $req['fecha_hasta'] }}
    </div>

    <h2>POR TIPO DE LESION</h2>
    <table style="width:50%;">
        <thead><tr><th>Tipo</th><th>Cantidad</th></tr></thead>
        <tbody>
            @foreach($porTipo as $r)
                <tr><td>{{ $r->tipo_lesion }}</td><td>{{ (int)$r->cantidad }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>POR GRAVEDAD</h2>
    <table style="width:50%;">
        <thead><tr><th>Gravedad</th><th>Cantidad</th></tr></thead>
        <tbody>
            @foreach($porGravedad as $r)
                <tr><td>{{ $r->gravedad }}</td><td>{{ (int)$r->cantidad }}</td></tr>
            @endforeach
        </tbody>
    </table>

    @if(!empty($noAptos))
        <h2 class="no-aptos-title">JUGADORES NO APTOS</h2>
        <table>
            <thead>
                <tr>
                    <th>Jugador</th>
                    <th>Tipo</th>
                    <th>Gravedad</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($noAptos as $r)
                    <tr>
                        <td>{{ $r->jugador }}</td>
                        <td>{{ $r->tipo_lesion }}</td>
                        <td>{{ $r->gravedad }}</td>
                        <td>{{ $r->fecha_inicio }}</td>
                        <td>{{ $r->fecha_fin ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="margin-top:15px; font-size:11px;">Todos los jugadores estan aptos</p>
    @endif
</body>
</html>
