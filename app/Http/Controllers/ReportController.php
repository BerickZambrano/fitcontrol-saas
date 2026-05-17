<?php

namespace App\Http\Controllers;

use App\Models\GeneratedReport;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
    }

    /**
     * Descargar un reporte verificando que pertenece al tenant del usuario.
     * Super admins pueden descargar cualquier reporte.
     */
    public function download(GeneratedReport $report)
    {
        $user = auth()->user();

        if (!$user->hasRole('super_admin') && $report->tenant_id !== $user->tenant_id) {
            abort(403, 'No tienes permiso para descargar este reporte.');
        }

        return $this->reportService->descargar($report);
    }
}
