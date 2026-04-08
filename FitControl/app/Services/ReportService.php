<?php

namespace App\Services;

use App\Models\GeneratedReport;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class ReportService
{
    protected Client $client;
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('REPORT_SERVICE_URL', 'http://localhost:8082');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 120,
            'connect_timeout' => 10,
        ]);
    }

    /**
     * Generar un reporte llamando al microservicio Java
     */
    public function generar(array $params): GeneratedReport
    {
        $tenantId = Auth::user()->tenant_id ?? null;
        $userId = Auth::id();

        // Si es super_admin, no tiene tenant_id, puede ver todos
        if (!$tenantId && Auth::user()->hasRole('super_admin')) {
            $tenantId = $params['tenant_id'] ?? null;
        }

        $payload = array_merge($params, [
            'tenant_id' => $tenantId,
            'user_id' => $userId,
        ]);

        try {
            $response = $this->client->post('/api/reports/generate', [
                'json' => $payload,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // Guardar referencia en BD
            $report = GeneratedReport::create([
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'report_type' => $params['report_type'],
                'title' => $this->generarTitulo($params),
                'filename' => $result['filename'],
                'file_format' => $params['format'],
                'file_size' => $result['size'] ?? null,
                'report_params' => $params,
                'report_id_external' => $result['report_id'],
                'status' => 'completed',
            ]);

            return $report;

        } catch (GuzzleException $e) {
            throw new Exception('Error generando reporte: ' . $e->getMessage());
        }
    }

    /**
     * Descargar un reporte generado
     */
    public function descargar(GeneratedReport $report)
    {
        try {
            $response = $this->client->get(
                "/api/reports/{$report->report_id_external}/download",
                ['stream' => true]
            );

            $contentType = match ($report->file_format) {
                'pdf' => 'application/pdf',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'csv' => 'text/csv',
                default => 'application/octet-stream',
            };

            return response(
                $response->getBody()->getContents(),
                200,
                [
                    'Content-Type' => $contentType,
                    'Content-Disposition' => 'attachment; filename="' . $report->filename . '"',
                ]
            );

        } catch (GuzzleException $e) {
            abort(500, 'Error descargando reporte: ' . $e->getMessage());
        }
    }

    /**
     * Generar un título legible para el reporte
     */
    protected function generarTitulo(array $params): string
    {
        $tipos = [
            'performance' => 'Rendimiento',
            'attendance' => 'Asistencia',
            'financial' => 'Financiero',
            'medical' => 'Médico',
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
            'attendance' => '📅 Asistencia a Entrenamientos',
            'financial' => '💰 Financiero / Pagos',
            'medical' => '🏥 Médico / Lesiones',
        ];
    }

    /**
     * Obtener los formatos disponibles
     */
    public static function getFormatos(): array
    {
        return [
            'pdf' => '📄 PDF',
            'xlsx' => '📊 Excel (XLSX)',
        ];
    }
}
