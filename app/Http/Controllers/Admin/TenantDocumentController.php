<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TenantDocumentController extends Controller
{
    /**
     * Securely serve a tenant document to authorized super administrators.
     */
    public function show(Tenant $tenant, string $field): Response
    {
        // 1. Authorize: only authenticated super_admin users can access these documents.
        if (!auth()->check() || !auth()->user()->hasRole('super_admin')) {
            abort(403, 'No autorizado.');
        }

        // 2. Validate the field being requested
        if (!in_array($field, ['rut_document', 'camara_comercio'])) {
            abort(404, 'Campo no válido.');
        }

        $path = $tenant->getAttribute($field);

        // 3. Verify file exists
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'Archivo no encontrado.');
        }

        // 4. Get file content and MIME type
        $file = Storage::disk('local')->get($path);
        $mimeType = Storage::disk('local')->mimeType($path);

        // 5. Return the file as an inline preview response
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }
}
