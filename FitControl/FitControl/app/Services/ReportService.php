<?php

namespace App\Services;

use App\Models\GeneratedReport;
use App\Models\Rendimiento;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Partido;
use App\Models\Entrenamiento;
use App\Models\AsistenciaEntrenamiento;
use App\Models\Pago;
use App\Models\Tenant;
use App\Models\HistorialMedico;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font as SpreadsheetFont;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Exception;
use Illuminate\Support\Str;

class ReportService
{
    protected string $storagePath;

    public function __construct()
    {
        $this->storagePath = storage_path('app/reportes');
    }

    /**
     * Generar un reporte nativamente en Laravel
     */
    public function generar(array $params): GeneratedReport
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $userId = Auth::id();

        if (!$tenantId && Auth::user()->hasRole('super_admin')) {
            $tenantId = $params['tenant_id'] ?? null;
        }

        $params['tenant_id'] = $tenantId;
        $params['user_id'] = $userId;

        $reportId = Str::random(12);
        $format = $params['format'] ?? 'pdf';
        $extension = strtolower($format) === 'pdf' ? 'pdf' : 'xlsx';

        match ($params['report_type']) {
            'performance' => $filename = $this->generatePerformanceReport($params, $reportId, $extension),
            'attendance'  => $filename = $this->generateAttendanceReport($params, $reportId, $extension),
            'financial'   => $filename = $this->generateFinancialReport($params, $reportId, $extension),
            'medical'     => $filename = $this->generateMedicalReport($params, $reportId, $extension),
            default       => throw new Exception('Tipo de reporte desconocido: ' . $params['report_type']),
        };

        $filePath = $this->storagePath . '/' . $filename;
        $size = file_exists($filePath) ? filesize($filePath) : 0;

        $report = GeneratedReport::create([
            'tenant_id'        => $tenantId,
            'user_id'          => $userId,
            'report_type'      => $params['report_type'],
            'title'            => $this->generarTitulo($params),
            'filename'         => $filename,
            'file_format'      => $extension,
            'file_size'        => $size,
            'report_params'    => $params,
            'report_id_external' => $reportId,
            'status'           => 'completed',
        ]);

        return $report;
    }

    /**
     * Descargar un reporte generado
     */
    public function descargar(GeneratedReport $report)
    {
        $filePath = $this->storagePath . '/' . $report->filename;

        if (!file_exists($filePath)) {
            abort(404, 'Reporte no encontrado');
        }

        $contentType = match ($report->file_format) {
            'pdf'  => 'application/pdf',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv'  => 'text/csv',
            default => 'application/octet-stream',
        };

        return response()->download($filePath, $report->filename, [
            'Content-Type' => $contentType,
        ]);
    }

    // ================================================================
    // REPORTE 1: RENDIMIENTO DE JUGADORES
    // ================================================================
    protected function generatePerformanceReport(array $req, string $reportId, string $ext): string
    {
        $equipoId = $req['equipo_id'];
        $tenantId = $req['tenant_id'];
        $fechaDesde = $req['fecha_desde'];
        $fechaHasta = $req['fecha_hasta'];

        $rows = Rendimiento::query()
            ->selectRaw('
                users.name as jugador,
                jugador_perfiles.posicion,
                jugador_perfiles.dorsal,
                COUNT(rendimientos.id) as partidos_jugados,
                COALESCE(SUM(rendimientos.minutos_jugados), 0) as minutos,
                COALESCE(SUM(rendimientos.goles), 0) as goles,
                COALESCE(SUM(rendimientos.asistencias), 0) as asistencias,
                COALESCE(SUM(rendimientos.tarjetas_amarillas), 0) as tarjetas_amarillas,
                COALESCE(SUM(rendimientos.tarjetas_rojas), 0) as tarjetas_rojas
            ')
            ->join('users', 'rendimientos.user_id', '=', 'users.id')
            ->leftJoin('jugador_perfiles', 'jugador_perfiles.user_id', '=', 'users.id')
            ->join('partidos', 'rendimientos.partido_id', '=', 'partidos.id')
            ->where(function($query) use ($equipoId) {
                $query->where('partidos.equipo_local_id', $equipoId)
                      ->orWhere('partidos.equipo_visitante_id', $equipoId);
            })
            ->whereBetween('partidos.fecha', [$fechaDesde, $fechaHasta])
            ->groupBy('users.id', 'users.name', 'jugador_perfiles.posicion', 'jugador_perfiles.dorsal')
            ->orderByRaw('goles DESC, asistencias DESC')
            ->get();

        $stats = Rendimiento::query()
            ->selectRaw('
                COUNT(DISTINCT rendimientos.user_id) as total_jugadores,
                COALESCE(SUM(rendimientos.goles), 0) as total_goles,
                COALESCE(SUM(rendimientos.asistencias), 0) as total_asistencias,
                COALESCE(SUM(rendimientos.minutos_jugados), 0) as total_minutos,
                COUNT(DISTINCT partidos.id) as total_partidos
            ')
            ->join('partidos', 'rendimientos.partido_id', '=', 'partidos.id')
            ->where(function($query) use ($equipoId) {
                $query->where('partidos.equipo_local_id', $equipoId)
                      ->orWhere('partidos.equipo_visitante_id', $equipoId);
            })
            ->whereBetween('partidos.fecha', [$fechaDesde, $fechaHasta])
            ->first();

        $equipoNombre = Equipo::where('id', $equipoId)->value('nombre') ?? 'Equipo';
        $equipoNombre = $equipoNombre ?? 'Equipo';

        if ($ext === 'pdf') {
            return $this->generatePerformancePdf($rows, $stats, $equipoNombre, $req, $reportId);
        }

        return $this->generatePerformanceExcel($rows, $stats, $equipoNombre, $req, $reportId);
    }

    protected function generatePerformanceExcel(array $rows, object $stats, string $equipoNombre, array $req, string $reportId): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rendimiento');

        // Styles
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0000FF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $titleStyle = ['font' => ['bold' => true, 'size' => 14]];

        // Title
        $sheet->setCellValue('A1', 'REPORTE DE RENDIMIENTO DE JUGADORES');
        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        $sheet->mergeCells('A1:J1');

        // Info row
        $sheet->setCellValue('A2', 'Equipo: ' . $equipoNombre);
        $sheet->setCellValue('D2', 'Periodo: ' . $req['fecha_desde'] . ' al ' . $req['fecha_hasta']);
        $sheet->setCellValue('G2', 'Generado: ' . now()->format('d/m/Y'));

        // Headers
        $headers = ['#', 'Jugador', 'Posicion', 'Dorsal', 'PJ', 'Minutos', 'Goles', 'Asistencias', 'T. Amarillas', 'T. Rojas'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $col++;
        }
        $sheet->getStyle('A4:J4')->applyFromArray($headerStyle);

        // Data
        $rowNum = 5;
        $counter = 1;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $rowNum, $counter++);
            $sheet->setCellValue('B' . $rowNum, $row->jugador);
            $sheet->setCellValue('C' . $rowNum, $row->posicion ?? 'N/A');
            $sheet->setCellValue('D' . $rowNum, (int) ($row->dorsal ?? 0));
            $sheet->setCellValue('E' . $rowNum, (int) $row->partidos_jugados);
            $sheet->setCellValue('F' . $rowNum, (int) $row->minutos);
            $sheet->setCellValue('G' . $rowNum, (int) $row->goles);
            $sheet->setCellValue('H' . $rowNum, (int) $row->asistencias);
            $sheet->setCellValue('I' . $rowNum, (int) $row->tarjetas_amarillas);
            $sheet->setCellValue('J' . $rowNum, (int) $row->tarjetas_rojas);
            $rowNum++;
        }

        // Summary
        $summaryRow = $rowNum + 2;
        $sheet->setCellValue('A' . $summaryRow, 'RESUMEN DEL EQUIPO');
        $sheet->getStyle('A' . $summaryRow)->applyFromArray($titleStyle);
        $sheet->setCellValue('A' . ($summaryRow + 1), 'Total jugadores: ' . $stats->total_jugadores);
        $sheet->setCellValue('A' . ($summaryRow + 2), 'Total goles: ' . $stats->total_goles);
        $sheet->setCellValue('A' . ($summaryRow + 3), 'Total asistencias: ' . $stats->total_asistencias);
        $sheet->setCellValue('A' . ($summaryRow + 4), 'Total partidos: ' . $stats->total_partidos);

        // Auto-size
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'rendimiento_' . preg_replace('/\s+/', '_', $equipoNombre) . '_' . $reportId . '.xlsx';
        $this->ensureStorageDir();
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->storagePath . '/' . $filename);

        return $filename;
    }

    protected function generatePerformancePdf(array $rows, object $stats, string $equipoNombre, array $req, string $reportId): string
    {
        $html = view('reports.performance', compact('rows', 'stats', 'equipoNombre', 'req'))->render();

        $filename = 'rendimiento_' . preg_replace('/\s+/', '_', $equipoNombre) . '_' . $reportId . '.pdf';
        $this->ensureStorageDir();

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        $pdf->save($this->storagePath . '/' . $filename);

        return $filename;
    }

    // ================================================================
    // REPORTE 2: ASISTENCIA
    // ================================================================
    protected function generateAttendanceReport(array $req, string $reportId, string $ext): string
    {
        $equipoId = $req['equipo_id'];
        $tenantId = $req['tenant_id'];
        $fechaDesde = $req['fecha_desde'];
        $fechaHasta = $req['fecha_hasta'];

        $entrenamientos = Entrenamiento::query()
            ->select('id', 'nombre', 'fecha')
            ->where('equipo_id', $equipoId)
            ->whereBetween('fecha', [$fechaDesde, $fechaHasta])
            ->orderBy('fecha')
            ->get();

        $jugadores = User::query()
            ->select('users.id', 'users.name')
            ->join('equipo_user', 'equipo_user.user_id', '=', 'users.id')
            ->where('equipo_user.equipo_id', $equipoId)
            ->whereNull('equipo_user.fecha_fin')
            ->orderBy('users.name')
            ->get();

        $asistencias = AsistenciaEntrenamiento::query()
            ->select('user_id', 'entrenamiento_id', 'presente')
            ->whereIn('entrenamiento_id', $entrenamientos->pluck('id'))
            ->get();

        $equipoNombre = Equipo::where('id', $equipoId)->value('nombre') ?? 'Equipo';
        $equipoNombre = $equipoNombre ?? 'Equipo';

        if ($ext === 'pdf') {
            return $this->generateAttendancePdf($jugadores, $entrenamientos, $asistencias, $equipoNombre, $req, $reportId);
        }

        return $this->generateAttendanceExcel($jugadores, $entrenamientos, $asistencias, $equipoNombre, $req, $reportId);
    }

    protected function generateAttendanceExcel(array $jugadores, array $entrenamientos, array $asistencias, string $equipoNombre, array $req, string $reportId): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Asistencia');

        $headerStyle = [
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '008000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $titleStyle = ['font' => ['bold' => true, 'size' => 14]];
        $presentStyle = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00FF00']],
        ];
        $absentStyle = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF0000']],
            'font' => ['color' => ['rgb' => 'FFFFFF']],
        ];

        // Title
        $totalCols = 2 + count($entrenamientos);
        $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIA A ENTRENAMIENTOS');
        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        $lastColLetter = $this->getColumnLetter($totalCols);
        $sheet->mergeCells('A1:' . $lastColLetter . '1');

        $sheet->setCellValue('A2', 'Equipo: ' . $equipoNombre);
        $sheet->setCellValue('D2', 'Periodo: ' . $req['fecha_desde'] . ' al ' . $req['fecha_hasta']);

        // Headers
        $sheet->setCellValue('A4', '#');
        $sheet->setCellValue('B4', 'Jugador');
        $sheet->getStyle('A4')->applyFromArray($headerStyle);
        $sheet->getStyle('B4')->applyFromArray($headerStyle);

        foreach ($entrenamientos as $i => $ent) {
            $colLetter = $this->getColumnLetter($i + 2);
            $fecha = substr($ent->fecha, 0, 10);
            $sheet->setCellValue($colLetter . '4', $fecha);
            $sheet->getStyle($colLetter . '4')->applyFromArray($headerStyle);
        }

        $pctCol = $this->getColumnLetter(count($entrenamientos) + 2);
        $sheet->setCellValue($pctCol . '4', '% Asist.');
        $sheet->getStyle($pctCol . '4')->applyFromArray($headerStyle);

        // Build asistencia map
        $asistenciaMap = [];
        foreach ($asistencias as $a) {
            $key = $a->user_id . '_' . $a->entrenamiento_id;
            $asistenciaMap[$key] = (bool) $a->presente;
        }

        // Data
        $rowNum = 5;
        $counter = 1;
        foreach ($jugadores as $jugador) {
            $userId = $jugador->id;
            $sheet->setCellValue('A' . $rowNum, $counter++);
            $sheet->setCellValue('B' . $rowNum, $jugador->name);

            $presentes = 0;
            foreach ($entrenamientos as $i => $ent) {
                $colLetter = $this->getColumnLetter($i + 2);
                $key = $userId . '_' . $ent->id;
                $presente = $asistenciaMap[$key] ?? false;

                if ($presente) {
                    $sheet->setCellValue($colLetter . $rowNum, 'P');
                    $sheet->getStyle($colLetter . $rowNum)->applyFromArray($presentStyle);
                    $presentes++;
                } else {
                    $sheet->setCellValue($colLetter . $rowNum, 'A');
                    $sheet->getStyle($colLetter . $rowNum)->applyFromArray($absentStyle);
                }
            }

            $pct = count($entrenamientos) > 0 ? round(($presentes * 100.0 / count($entrenamientos))) : 0;
            $sheet->setCellValue($pctCol . $rowNum, $pct . '%');
            $sheet->getStyle($pctCol . $rowNum)->applyFromArray($headerStyle);

            $rowNum++;
        }

        foreach (range('A', $pctCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'asistencia_' . preg_replace('/\s+/', '_', $equipoNombre) . '_' . $reportId . '.xlsx';
        $this->ensureStorageDir();
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->storagePath . '/' . $filename);

        return $filename;
    }

    protected function generateAttendancePdf(array $jugadores, array $entrenamientos, array $asistencias, string $equipoNombre, array $req, string $reportId): string
    {
        $html = view('reports.attendance', compact('jugadores', 'entrenamientos', 'asistencias', 'equipoNombre', 'req'))->render();

        $filename = 'asistencia_' . preg_replace('/\s+/', '_', $equipoNombre) . '_' . $reportId . '.pdf';
        $this->ensureStorageDir();

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        $pdf->save($this->storagePath . '/' . $filename);

        return $filename;
    }

    // ================================================================
    // REPORTE 3: FINANCIERO
    // ================================================================
    protected function generateFinancialReport(array $req, string $reportId, string $ext): string
    {
        $tenantId = $req['tenant_id'];
        $fechaDesde = $req['fecha_desde'];
        $fechaHasta = $req['fecha_hasta'];

        $summary = Pago::query()
            ->selectRaw('estado, COUNT(*) as cantidad, COALESCE(SUM(monto), 0) as total')
            ->whereBetween('fecha', [$fechaDesde, $fechaHasta])
            ->groupBy('estado')
            ->get();

        $detail = Pago::query()
            ->select('users.name as jugador', 'pagos.monto', 'pagos.estado', 'pagos.fecha', 'pagos.created_at')
            ->join('users', 'pagos.user_id', '=', 'users.id')
            ->whereBetween('pagos.fecha', [$fechaDesde, $fechaHasta])
            ->orderBy('pagos.fecha', 'DESC')
            ->get();

        $tenantNombre = Tenant::where('id', $tenantId)->value('nombre') ?? 'Club';
        $tenantNombre = $tenantNombre ?? 'Club';

        if ($ext === 'pdf') {
            return $this->generateFinancialPdf($summary, $detail, $tenantNombre, $req, $reportId);
        }

        return $this->generateFinancialExcel($summary, $detail, $tenantNombre, $req, $reportId);
    }

    protected function generateFinancialExcel(array $summary, array $detail, string $tenantNombre, array $req, string $reportId): string
    {
        $spreadsheet = new Spreadsheet();

        // Summary Sheet
        $summarySheet = $spreadsheet->getActiveSheet();
        $summarySheet->setTitle('Resumen Financiero');

        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00008B']],
        ];
        $titleStyle = ['font' => ['bold' => true, 'size' => 14]];

        $summarySheet->setCellValue('A1', 'REPORTE FINANCIERO');
        $summarySheet->getStyle('A1')->applyFromArray($titleStyle);
        $summarySheet->mergeCells('A1:D1');

        $summarySheet->setCellValue('A2', 'Club: ' . $tenantNombre);
        $summarySheet->setCellValue('A3', 'Periodo: ' . $req['fecha_desde'] . ' al ' . $req['fecha_hasta']);

        $summarySheet->setCellValue('A5', 'Estado');
        $summarySheet->setCellValue('B5', 'Cantidad');
        $summarySheet->setCellValue('C5', 'Total (COP)');
        $summarySheet->getStyle('A5:C5')->applyFromArray($headerStyle);

        $rowNum = 6;
        $totalGeneral = 0;
        foreach ($summary as $row) {
            $summarySheet->setCellValue('A' . $rowNum, $row->estado);
            $summarySheet->setCellValue('B' . $rowNum, (int) $row->cantidad);
            $monto = (int) $row->total;
            $summarySheet->setCellValue('C' . $rowNum, $monto);
            $totalGeneral += $monto;
            $rowNum++;
        }

        $summarySheet->setCellValue('A' . ($rowNum + 1), 'TOTAL GENERAL');
        $summarySheet->getStyle('A' . ($rowNum + 1))->applyFromArray($titleStyle);
        $summarySheet->setCellValue('C' . ($rowNum + 1), $totalGeneral);
        $summarySheet->getStyle('C' . ($rowNum + 1))->applyFromArray($headerStyle);

        // Detail Sheet
        $detailSheet = $spreadsheet->createSheet(1);
        $detailSheet->setTitle('Detalle de Pagos');

        $detailSheet->setCellValue('A1', 'DETALLE DE PAGOS');
        $detailSheet->getStyle('A1')->applyFromArray($titleStyle);

        $detHeaders = ['#', 'Jugador', 'Monto (COP)', 'Estado', 'Fecha'];
        foreach ($detHeaders as $i => $h) {
            $col = $this->getColumnLetter($i);
            $detailSheet->setCellValue($col . '3', $h);
        }
        $detailSheet->getStyle('A3:E3')->applyFromArray($headerStyle);

        $detRowNum = 4;
        $counter = 1;
        foreach ($detail as $row) {
            $detailSheet->setCellValue('A' . $detRowNum, $counter++);
            $detailSheet->setCellValue('B' . $detRowNum, $row->jugador);
            $detailSheet->setCellValue('C' . $detRowNum, (int) $row->monto);
            $detailSheet->setCellValue('D' . $detRowNum, $row->estado);
            $detailSheet->setCellValue('E' . $detRowNum, $row->fecha);
            $detRowNum++;
        }

        foreach (range('A', 'E') as $col) {
            $summarySheet->getColumnDimension($col)->setAutoSize(true);
            $detailSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'financiero_' . preg_replace('/\s+/', '_', $tenantNombre) . '_' . $reportId . '.xlsx';
        $this->ensureStorageDir();
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->storagePath . '/' . $filename);

        return $filename;
    }

    protected function generateFinancialPdf(array $summary, array $detail, string $tenantNombre, array $req, string $reportId): string
    {
        $html = view('reports.financial', compact('summary', 'detail', 'tenantNombre', 'req'))->render();

        $filename = 'financiero_' . preg_replace('/\s+/', '_', $tenantNombre) . '_' . $reportId . '.pdf';
        $this->ensureStorageDir();

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        $pdf->save($this->storagePath . '/' . $filename);

        return $filename;
    }

    // ================================================================
    // REPORTE 4: MEDICO
    // ================================================================
    protected function generateMedicalReport(array $req, string $reportId, string $ext): string
    {
        $tenantId = $req['tenant_id'];
        $fechaDesde = $req['fecha_desde'];
        $fechaHasta = $req['fecha_hasta'];

        $detail = HistorialMedico::query()
            ->select('users.name as jugador', 'historial_medico.tipo_lesion', 'historial_medico.gravedad', 'historial_medico.descripcion',
                   'historial_medico.fecha_inicio', 'historial_medico.fecha_fin', 'historial_medico.apto')
            ->join('users', 'historial_medico.user_id', '=', 'users.id')
            ->whereBetween('historial_medico.fecha_inicio', [$fechaDesde, $fechaHasta])
            ->orderBy('historial_medico.fecha_inicio', 'DESC')
            ->get();

        $porTipo = HistorialMedico::query()
            ->selectRaw('tipo_lesion, COUNT(*) as cantidad')
            ->whereBetween('fecha_inicio', [$fechaDesde, $fechaHasta])
            ->groupBy('tipo_lesion')
            ->get();

        $porGravedad = HistorialMedico::query()
            ->selectRaw('gravedad, COUNT(*) as cantidad')
            ->whereBetween('fecha_inicio', [$fechaDesde, $fechaHasta])
            ->groupBy('gravedad')
            ->get();

        $porApto = HistorialMedico::query()
            ->selectRaw('apto, COUNT(*) as cantidad')
            ->whereBetween('fecha_inicio', [$fechaDesde, $fechaHasta])
            ->groupBy('apto')
            ->get();

        $noAptos = HistorialMedico::query()
            ->selectRaw('DISTINCT users.name, historial_medico.tipo_lesion, historial_medico.gravedad, historial_medico.fecha_inicio, historial_medico.fecha_fin')
            ->join('users', 'historial_medico.user_id', '=', 'users.id')
            ->where('historial_medico.apto', false)
            ->orderBy('historial_medico.fecha_inicio', 'DESC')
            ->get();

        $tenantNombre = Tenant::where('id', $tenantId)->value('nombre') ?? 'Club';
        $tenantNombre = $tenantNombre ?? 'Club';

        if ($ext === 'pdf') {
            return $this->generateMedicalPdf($detail, $porTipo, $porGravedad, $porApto, $noAptos, $tenantNombre, $req, $reportId);
        }

        return $this->generateMedicalExcel($detail, $porTipo, $porGravedad, $porApto, $noAptos, $tenantNombre, $req, $reportId);
    }

    protected function generateMedicalExcel(array $detail, array $porTipo, array $porGravedad, array $porApto, array $noAptos, string $tenantNombre, array $req, string $reportId): string
    {
        $spreadsheet = new Spreadsheet();

        $headerStyle = [
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF0000']],
        ];
        $titleStyle = ['font' => ['bold' => true, 'size' => 14]];

        // Summary Sheet
        $summarySheet = $spreadsheet->getActiveSheet();
        $summarySheet->setTitle('Resumen Medico');

        $summarySheet->setCellValue('A1', 'REPORTE MEDICO');
        $summarySheet->getStyle('A1')->applyFromArray($titleStyle);
        $summarySheet->mergeCells('A1:D1');

        $summarySheet->setCellValue('A2', 'Club: ' . $tenantNombre);
        $summarySheet->setCellValue('A3', 'Periodo: ' . $req['fecha_desde'] . ' al ' . $req['fecha_hasta']);

        // Por tipo
        $row = 5;
        $summarySheet->setCellValue('A' . $row, 'POR TIPO DE LESION');
        $summarySheet->getStyle('A' . $row)->applyFromArray($titleStyle);
        $row++;
        $summarySheet->setCellValue('A' . $row, 'Tipo');
        $summarySheet->setCellValue('B' . $row, 'Cantidad');
        $summarySheet->getStyle('A' . $row . ':B' . $row)->applyFromArray($headerStyle);
        $row++;
        foreach ($porTipo as $r) {
            $summarySheet->setCellValue('A' . $row, $r->tipo_lesion);
            $summarySheet->setCellValue('B' . $row, (int) $r->cantidad);
            $row++;
        }

        // Por gravedad
        $row += 2;
        $summarySheet->setCellValue('A' . $row, 'POR GRAVEDAD');
        $summarySheet->getStyle('A' . $row)->applyFromArray($titleStyle);
        $row++;
        $summarySheet->setCellValue('A' . $row, 'Gravedad');
        $summarySheet->setCellValue('B' . $row, 'Cantidad');
        $summarySheet->getStyle('A' . $row . ':B' . $row)->applyFromArray($headerStyle);
        $row++;
        foreach ($porGravedad as $r) {
            $summarySheet->setCellValue('A' . $row, $r->gravedad);
            $summarySheet->setCellValue('B' . $row, (int) $r->cantidad);
            $row++;
        }

        // No aptos
        $row += 2;
        $summarySheet->setCellValue('A' . $row, 'JUGADORES NO APTOS ACTUALMENTE');
        $summarySheet->getStyle('A' . $row)->applyFromArray($titleStyle);
        $row++;

        if (!empty($noAptos)) {
            $noAptoHeaders = ['Jugador', 'Tipo Lesion', 'Gravedad', 'Fecha Inicio', 'Fecha Fin'];
            foreach ($noAptoHeaders as $i => $h) {
                $col = $this->getColumnLetter($i);
                $summarySheet->setCellValue($col . $row, $h);
            }
            $summarySheet->getStyle('A' . $row . ':E' . $row)->applyFromArray($headerStyle);
            $row++;
            foreach ($noAptos as $r) {
                $summarySheet->setCellValue('A' . $row, $r->jugador);
                $summarySheet->setCellValue('B' . $row, $r->tipo_lesion);
                $summarySheet->setCellValue('C' . $row, $r->gravedad);
                $summarySheet->setCellValue('D' . $row, $r->fecha_inicio);
                $summarySheet->setCellValue('E' . $row, $r->fecha_fin ?? 'N/A');
                $row++;
            }
        } else {
            $summarySheet->setCellValue('A' . $row, 'Todos los jugadores estan aptos');
        }

        // Detail Sheet
        $detailSheet = $spreadsheet->createSheet(1);
        $detailSheet->setTitle('Detalle Medico');

        $detailSheet->setCellValue('A1', 'DETALLE DE REGISTROS MEDICOS');
        $detailSheet->getStyle('A1')->applyFromArray($titleStyle);

        $detHeaders = ['#', 'Jugador', 'Tipo Lesion', 'Gravedad', 'Descripcion', 'Fecha Inicio', 'Fecha Fin', 'Apto'];
        foreach ($detHeaders as $i => $h) {
            $col = $this->getColumnLetter($i);
            $detailSheet->setCellValue($col . '3', $h);
        }
        $detailSheet->getStyle('A3:H3')->applyFromArray($headerStyle);

        $detRowNum = 4;
        $counter = 1;
        foreach ($detail as $r) {
            $detailSheet->setCellValue('A' . $detRowNum, $counter++);
            $detailSheet->setCellValue('B' . $detRowNum, $r->jugador);
            $detailSheet->setCellValue('C' . $detRowNum, $r->tipo_lesion);
            $detailSheet->setCellValue('D' . $detRowNum, $r->gravedad);
            $detailSheet->setCellValue('E' . $detRowNum, $r->descripcion ?? '');
            $detailSheet->setCellValue('F' . $detRowNum, $r->fecha_inicio);
            $detailSheet->setCellValue('G' . $detRowNum, $r->fecha_fin ?? 'N/A');
            $detailSheet->setCellValue('H' . $detRowNum, (bool) $r->apto ? 'Si' : 'No');
            $detRowNum++;
        }

        foreach (range('A', 'H') as $col) {
            $summarySheet->getColumnDimension($col)->setAutoSize(true);
            $detailSheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'medico_' . preg_replace('/\s+/', '_', $tenantNombre) . '_' . $reportId . '.xlsx';
        $this->ensureStorageDir();
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->storagePath . '/' . $filename);

        return $filename;
    }

    protected function generateMedicalPdf(array $detail, array $porTipo, array $porGravedad, array $porApto, array $noAptos, string $tenantNombre, array $req, string $reportId): string
    {
        $html = view('reports.medical', compact('detail', 'porTipo', 'porGravedad', 'porApto', 'noAptos', 'tenantNombre', 'req'))->render();

        $filename = 'medico_' . preg_replace('/\s+/', '_', $tenantNombre) . '_' . $reportId . '.pdf';
        $this->ensureStorageDir();

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        $pdf->save($this->storagePath . '/' . $filename);

        return $filename;
    }

    // ================================================================
    // HELPERS
    // ================================================================
    protected function ensureStorageDir(): void
    {
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    protected function getColumnLetter(int $index): string
    {
        $letter = '';
        while ($index >= 0) {
            $letter = chr($index % 26 + 65) . $letter;
            $index = (int) ($index / 26) - 1;
        }
        return $letter;
    }

    /**
     * Generar un título legible para el reporte
     */
    protected function generarTitulo(array $params): string
    {
        $tipos = [
            'performance' => 'Rendimiento',
            'attendance'  => 'Asistencia',
            'financial'   => 'Financiero',
            'medical'     => 'Médico',
        ];

        $tipo = $tipos[$params['report_type']] ?? 'Reporte';
        $equipo = $params['equipo_nombre'] ?? '';
        $desde = $params['fecha_desde'] ?? '';
        $hasta = $params['fecha_hasta'] ?? '';

        $partes = [$tipo];
        if ($equipo) $partes[] = $equipo;
        if ($desde && $hasta) $partes[] = "$desde al $hasta";

        return implode(' - ', $partes);
    }

    /**
     * Obtener los tipos de reporte disponibles
     */
    public static function getTiposReporte(): array
    {
        return [
            'performance' => '📈 Rendimiento de Jugadores',
            'attendance'  => '📅 Asistencia a Entrenamientos',
            'financial'   => '💰 Financiero / Pagos',
            'medical'     => '🏥 Médico / Lesiones',
        ];
    }

    /**
     * Obtener los formatos disponibles
     */
    public static function getFormatos(): array
    {
        return [
            'pdf'  => '📄 PDF',
            'xlsx' => '📊 Excel (XLSX)',
        ];
    }
}
