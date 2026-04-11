<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        .info { font-size: 11px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th { background-color: #003366; color: white; font-size: 10px; padding: 6px; text-align: center; }
        td { font-size: 9px; padding: 4px; text-align: center; }
        .summary-table { width: 80%; margin: 0 auto; }
        .total-general { font-size: 16px; font-weight: bold; text-align: center; margin: 15px 0; }
    </style>
</head>
<body>
    <h1>REPORTE FINANCIERO</h1>
    <div class="info">
        Club: {{ $tenantNombre }} | Periodo: {{ $req['fecha_desde'] }} al {{ $req['fecha_hasta'] }}
    </div>

    <h2 style="text-align:center; font-size:14px;">Resumen por Estado</h2>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Estado</th>
                <th>Cantidad</th>
                <th>Total (COP)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeneral = 0; @endphp
            @foreach($summary as $row)
                @php $totalGeneral += (int)$row->total; @endphp
                <tr>
                    <td>{{ $row->estado }}</td>
                    <td>{{ (int)$row->cantidad }}</td>
                    <td>{{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-general">TOTAL GENERAL: {{ number_format($totalGeneral, 0, ',', '.') }} COP</div>

    <h2 style="text-align:center; font-size:14px;">Detalle de Pagos</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Jugador</th>
                <th>Monto (COP)</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="text-align:left;">{{ $row->jugador }}</td>
                    <td>{{ number_format($row->monto, 0, ',', '.') }}</td>
                    <td>{{ $row->estado }}</td>
                    <td>{{ $row->fecha }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
