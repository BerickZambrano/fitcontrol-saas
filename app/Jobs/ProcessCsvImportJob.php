<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProcessCsvImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;
    public $subject;
    public $body;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $subject, $body)
    {
        $this->filePath = $filePath;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Obtener la ruta absoluta del archivo guardado en local
        $fullPath = Storage::disk('local')->path($this->filePath);

        if (!file_exists($fullPath)) {
            \Log::error("CSV de importación no encontrado: {$fullPath}");
            return;
        }

        $handle = fopen($fullPath, 'r');
        $headers = fgetcsv($handle);

        if ($headers === false) {
            \Log::warning("CSV vacío en importación masiva: {$fullPath}");
            fclose($handle);
            unlink($fullPath);
            return;
        }

        $headers = array_map(function($h) {
            return strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h)));
        }, $headers);

        $emailIndex = -1;
        $nameIndex = -1;

        foreach ($headers as $index => $header) {
            if (str_contains($header, 'email') || str_contains($header, 'correo')) {
                $emailIndex = $index;
            }
            if (str_contains($header, 'nombre') || str_contains($header, 'name')) {
                $nameIndex = $index;
            }
        }

        if ($emailIndex === -1) {
            $emailIndex = 1;
            $nameIndex = 0;
            if (isset($headers[1]) && str_contains($headers[1], '@')) {
                fseek($handle, 0); 
            }
        }

        $sentCount = 0;
        $errorCount = 0;

        while (($line = fgetcsv($handle)) !== false) {
            if (!isset($line[$emailIndex])) continue;

            $email = trim($line[$emailIndex]);
            $nombre = isset($line[$nameIndex]) ? trim(str_replace("\xEF\xBB\xBF", '', $line[$nameIndex])) : 'Usuario';

            if (empty($email) || !str_contains($email, '@')) {
                continue;
            }

            try {
                SendBulkEmail::dispatch($email, $nombre, $this->subject, $this->body);
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error("Error despachando correo a {$email}: " . $e->getMessage());
                $errorCount++;
            }
        }
        
        fclose($handle);

        // Eliminar el archivo temporal CSV después de procesar
        Storage::disk('local')->delete($this->filePath);
    }
}
