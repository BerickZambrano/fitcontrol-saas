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
        $firstLine = true;

        while (($line = fgetcsv($handle)) !== false) {
            // Ignorar la cabecera
            if ($firstLine) {
                $firstLine = false;
                continue;
            }

            if (count($line) < 2) continue;

            $nombre = trim(str_replace("\xEF\xBB\xBF", '', $line[0]));
            $email  = trim($line[1]);

            if (empty($email) || !str_contains($email, '@')) continue;

            try {
                // Despachar el envío individual para cada línea encontrada
                \App\Jobs\SendBulkEmail::dispatch($email, $nombre, $this->subject, $this->body);
            } catch (Exception $e) {
                \Log::error("Error despachando correo a {$email}: {$e->getMessage()}");
            }
        }

        fclose($handle);

        // Eliminar el archivo temporal CSV después de procesar
        Storage::disk('local')->delete($this->filePath);
    }
}
