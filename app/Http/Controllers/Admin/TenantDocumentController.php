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

        // 3. Verify file exists with fallback to older storage structure
        $disk = Storage::disk('local');
        $exists = $disk->exists($path);
        $fileContent = null;
        $mimeType = null;

        if ($exists) {
            $fileContent = $disk->get($path);
            $mimeType = $disk->mimeType($path);
        } else {
            // Fallback 1: Check if the file is in storage/app/ (the old Laravel local disk root)
            $oldPath = storage_path('app/' . $path);
            if (file_exists($oldPath)) {
                $fileContent = file_get_contents($oldPath);
                $mimeType = mime_content_type($oldPath);
                $exists = true;
            } else {
                // Fallback 2: Check if the file is in storage/app/private/ directly
                $privatePath = storage_path('app/private/' . $path);
                if (file_exists($privatePath)) {
                    $fileContent = file_get_contents($privatePath);
                    $mimeType = mime_content_type($privatePath);
                    $exists = true;
                }
            }
        }

        if (!$path || !$exists || !$fileContent) {
            abort(404, 'Archivo no encontrado.');
        }

        // 5. Return the file as an inline preview response
        return response($fileContent, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }
}
