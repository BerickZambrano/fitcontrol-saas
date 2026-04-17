@extends('reports.layout')

@section('title', 'Reporte Médico')

@section('custom-styles')
    .severity-alta { background-color: #fee2e2 !important; color: #991b1b; font-weight: bold; }
    .severity-media { background-color: #fef3c7 !important; color: #92400e; font-weight: bold; }
    .severity-baja { background-color: #f0f9ff !important; color: #075985; font-weight: bold; }
    .section-title {
        border-left: 4px solid #0f172a;
        padding-left: 10px;
        margin: 20px 0 10px 0;
        font-size: 14px;
        color: #0f172a;
    }
@endsection

@section('meta')
    <table>
        <tr>
            <td width="50%"><strong>REPORTE:</strong> Informe Médico y Lesiones</td>
            <td width="50%" class="text-right"><strong>CLUB:</strong> {{ $tenantNombre }}</td>
        </tr>
        <tr>
            <td><strong>PERIODO:</strong> {{ \Carbon\Carbon::parse($req['fecha_desde'])->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($req['fecha_hasta'])->format('d/m/Y') }}</td>
            <td class="text-right"><strong>ALERTAS:</strong> Críticas y Seguimiento</td>
        </tr>
    </table>
@endsection

@section('content')
    <div style="width: 100%;">
        <div style="width: 48%; float: left;">
            <div class="section-title">RESUMEN POR TIPO</div>
            <table class="main-table">
                <thead><tr><th class="text-left">Tipo de Lesión</th><th>Casos</th></tr></thead>
                <tbody>
                    @foreach($porTipo as $r)
                        <tr><td class="text-left bold">{{ $r->tipo_lesion }}</td><td>{{ (int)$r->cantidad }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="width: 48%; float: right;">
            <div class="section-title">RESUMEN POR GRAVEDAD</div>
            <table class="main-table">
                <thead><tr><th class="text-left">Gravedad</th><th>Casos</th></tr></thead>
                <tbody>
                    @foreach($porGravedad as $r)
                        <tr>
                            <td class="text-left">
                                <span class="badge {{ strtolower($r->gravedad) == 'alta' ? 'badge-danger' : 'badge-info' }}">
                                    {{ $r->gravedad }}
                                </span>
                            </td>
                            <td>{{ (int)$r->cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="clear: both;"></div>
    </div>

    @if(!empty($noAptos) && count($noAptos) > 0)
        <div class="section-title" style="color: #991b1b; border-color: #991b1b;">⚠️ JUGADORES NO APTOS ACTUALMENTE</div>
        <table class="main-table">
            <thead>
                <tr>
                    <th class="text-left">Jugador</th>
                    <th>Tipo</th>
                    <th>Gravedad</th>
                    <th>F. Inicio</th>
                    <th>F. Estimada Fin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($noAptos as $r)
                    <tr class="severity-alta">
                        <td class="text-left">{{ $r->jugador }}</td>
                        <td>{{ $r->tipo_lesion }}</td>
                        <td>{{ $r->gravedad }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->fecha_inicio)->format('d/m/Y') }}</td>
                        <td>{{ $r->fecha_fin ? \Carbon\Carbon::parse($r->fecha_fin)->format('d/m/Y') : 'Pendiente' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="margin-top: 20px; padding: 15px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; color: #166534; font-size: 11px;">
            ✅ No se registran jugadores fuera de servicio en el periodo seleccionado.
        </div>
    @endif

    <div class="section-title">DETALLE CRONOLÓGICO</div>
    <table class="main-table">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left">Jugador</th>
                <th>Lesión</th>
                <th>Gravedad</th>
                <th>Inicio</th>
                <th>Apto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="text-left bold">{{ $r->jugador }}</td>
                    <td>{{ $r->tipo_lesion }}</td>
                    <td>
                        <span class="badge {{ strtolower($r->gravedad) == 'alta' ? 'badge-danger' : (strtolower($r->gravedad) == 'media' ? 'badge-info' : '') }}">
                            {{ $r->gravedad }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($r->fecha_inicio)->format('d/m/Y') }}</td>
                    <td><span class="badge {{ $r->apto ? 'badge-success' : 'badge-danger' }}">{{ $r->apto ? 'Sí' : 'No' }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
