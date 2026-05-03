<?php

namespace App\Http\Controllers;

use App\Mail\FitControlMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Import CSV and send emails to all recipients.
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file'    => 'required|file|mimes:csv,txt',
            'subject' => 'required|string',
            'body'    => 'required|string',
        ]);

        $subject = $request->input('subject');
        $body    = $request->input('body');
        
        // Guardar el archivo temporalmente
        $path = $request->file('file')->store('imports/csv', 'local');

        // Despachar el Job en segundo plano
        \App\Jobs\ProcessCsvImportJob::dispatch($path, $subject, $body);

        return response('El archivo CSV se está procesando en segundo plano.', 202);
    }

    /**
     * Send a single email.
     */
    public function sendSingle(Request $request)
    {
        $request->validate([
            'email'   => 'required|email',
            'nombre'  => 'required|string',
            'subject' => 'required|string',
            'body'    => 'required|string',
        ]);

        $email   = $request->input('email');
        $nombre  = $request->input('nombre');
        $subject = $request->input('subject');
        $body    = $request->input('body');

        try {
            \App\Jobs\SendBulkEmail::dispatch($email, $nombre, $subject, $body);

            return response('Correo enviado correctamente (encolado)');
        } catch (\Exception $e) {
            return response("Error: {$e->getMessage()}", 500);
        }
    }

    /**
     * Send batch emails to multiple recipients.
     */
    public function sendMultiple(Request $request)
    {
        $request->validate([
            'subject'   => 'required|string',
            'body'      => 'required|string',
            'recipients' => 'required|array|min:1',
            'recipients.*.email'  => 'required|email',
            'recipients.*.nombre' => 'required|string',
        ]);

        $subject = $request->input('subject');
        $body    = $request->input('body');
        $recipientsList = $request->input('recipients');

        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($recipientsList as $recipient) {
            try {
                $email  = $recipient['email'];
                $nombre = $recipient['nombre'];
                \App\Jobs\SendBulkEmail::dispatch($email, $nombre, $subject, $body);
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
                $errors[] = "{$recipient['email']}: {$e->getMessage()}";
            }
        }

        $message = "Enviados: {$successCount} | Fallidos: {$failCount}";
        if (!empty($errors)) {
            $message .= " | Errores: " . implode('; ', $errors);
        }

        return response($message);
    }
}
