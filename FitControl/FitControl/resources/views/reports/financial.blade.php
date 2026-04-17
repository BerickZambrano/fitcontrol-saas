@extends('reports.layout')

@section('title', 'Reporte Financiero')

@section('custom-styles')
    .amount { font-family: 'Courier New', monospace; font-weight: bold; font-size: 11px; }
    .status-pagado { color: #15803d; }
    .status-pendiente { color: #b45309; }
    .summary-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
    }
    .total-big {
        font-size: 24px;
        color: #0f172a;
        font-weight: bold;
        text-align: right;
    }
@endsection

@section('meta')
    <table>
        <tr>
            <td width="50%"><strong>REPORTE:</strong> Estado Financiero / Pagos</td>
            <td width="50%" class="text-right"><strong>CLUB:</strong> {{ $tenantNombre }}</td>
        </tr>
        <tr>
            <td><strong>PERIODO:</strong> {{ \Carbon\Carbon::parse($req['fecha_desde'])->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($req['fecha_hasta'])->format('d/m/Y') }}</td>
            <td class="text-right"><strong>ESTADO:</strong> General</td>
        </tr>
    </table>
@endsection

@section('content')
    <div class="summary-card">
        <table width="100%">
            <tr>
                <td>
                    <h3 style="margin: 0; font-size: 14px; color: #0f172a;">RESUMEN DE INGRESOS</h3>
                    <table style="margin-top: 10px;" width="300">
                        @php $totalGeneral = 0; @endphp
                        @foreach($summary as $row)
                            @php $totalGeneral += (int)$row->total; @endphp
                            <tr>
                                <td class="text-left" style="font-size: 11px;">{{ $row->estado }}:</td>
                                <td class="text-right bold" style="font-size: 11px;">{{ number_format($row->total, 0, ',', '.') }} COP</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td class="text-right" valign="bottom">
                    <span style="font-size: 10px; color: #64748b; text-transform: uppercase;">Total General Captado</span>
                    <div class="total-big">{{ number_format($totalGeneral, 0, ',', '.') }} <small style="font-size: 12px;">COP</small></div>
                </td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 12px; color: #0f172a; margin-bottom: 10px; text-transform: uppercase;">Detalle de Transacciones</h3>
    <table class="main-table">
        <thead>
            <tr>
                <th width="30">#</th>
                <th class="text-left">Jugador</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Fecha Pago</th>
                <th>Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="text-left bold">{{ $row->jugador }}</td>
                    <td class="amount">{{ number_format($row->monto, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $row->estado == 'Pagado' ? 'badge-success' : 'badge-info' }}">
                            {{ $row->estado }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($row->fecha)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
