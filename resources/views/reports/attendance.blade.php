@extends('reports.layout')

@section('title', 'Reporte de Asistencia - ' . $equipoNombre)

@section('custom-styles')
    .present-cell { background-color: #dcfce7 !important; color: #166534; font-weight: bold; }
    .absent-cell { background-color: #fee2e2 !important; color: #991b1b; font-weight: bold; }
    .date-header { font-size: 8px !important; }
@endsection

@section('meta')
    <table>
        <tr>
            <td width="50%"><strong>REPORTE:</strong> Asistencia a Entrenamientos</td>
            <td width="50%" class="text-right"><strong>EQUIPO:</strong> {{ $equipoNombre }}</td>
        </tr>
        <tr>
            <td><strong>PERIODO:</strong> {{ \Carbon\Carbon::parse($req['fecha_desde'])->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($req['fecha_hasta'])->format('d/m/Y') }}</td>
            <td class="text-right"><strong>GENERADO:</strong> {{ now()->format('d/m/Y') }}</td>
        </tr>
    </table>
@endsection

@section('content')
    <table class="main-table">
        <thead>
            <tr>
                <th width="20">#</th>
                <th class="text-left">Jugador</th>
                @foreach($entrenamientos as $ent)
                    <th class="date-header">{{ \Carbon\Carbon::parse($ent->fecha)->format('d/m') }}</th>
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
                    <td class="text-left bold">{{ $jugador->name }}</td>
                    @foreach($entrenamientos as $ent)
                        @php
                            $key = $jugador->id . '_' . $ent->id;
                            $presente = $asistenciaMap[$key] ?? false;
                        @endphp
                        <td class="{{ $presente ? 'present-cell' : 'absent-cell' }}">
                            {{ $presente ? 'P' : 'A' }}
                        </td>
                    @endforeach
                    <td class="bold">
                        {{ count($entrenamientos) > 0 ? round($presentes * 100 / count($entrenamientos)) : 0 }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; font-size: 10px; color: #64748b;">
        <strong>Leyenda:</strong> <span class="badge badge-success">P</span> Presente | <span class="badge badge-danger">A</span> Ausente
    </div>
@endsection
