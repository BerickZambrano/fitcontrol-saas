<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaywallController extends Controller
{
    private function getRedirectPath($user): string
    {
        if (!$user) {
            return '/';
        }
        if ($user->hasRole(['super_admin', 'Administrador'])) {
            return '/admin';
        }
        if ($user->hasRole('Entrenador')) {
            return '/entrenador';
        }
        if ($user->hasRole('Jugador')) {
            return '/jugador';
        }
        if ($user->hasRole('Arbitro')) {
            return '/arbitro';
        }
        return '/';
    }

    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/');
        }

        $tenant = $user->tenant;
        if (!$tenant || $tenant->estado_pago === 'pagado') {
            return redirect($this->getRedirectPath($user)); 
        }

        $plans = config('services.wompi.plans');
        $publicKey = config('services.wompi.public_key');

        return view('paywall', compact('tenant', 'plans', 'publicKey'));
    }

    public function prepare(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:mensual,anual',
        ]);

        $user = auth()->user();
        if (!$user || !$user->tenant) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $tenant = $user->tenant;
        $planKey = $request->input('plan');
        $plan = config("services.wompi.plans.{$planKey}");

        if (!$plan) {
            return response()->json(['error' => 'Plan no válido'], 400);
        }

        $amountInCents = $plan['amount_in_cents'];
        $currency = 'COP';
        $reference = 'FC-' . $tenant->id . '-' . $planKey . '-' . time();
        $publicKey = config('services.wompi.public_key');
        $integritySecret = config('services.wompi.integrity_secret');

        $signature = null;
        if ($integritySecret) {
            $signature = hash('sha256', $reference . $amountInCents . $currency . $integritySecret);
        }

        return response()->json([
            'reference' => $reference,
            'amountInCents' => $amountInCents,
            'currency' => $currency,
            'publicKey' => $publicKey,
            'signature' => $signature,
        ]);
    }

    public function callback(Request $request)
    {
        $transactionId = $request->query('id');
        if (!$transactionId) {
            return redirect()->route('paywall.index')->with('error', 'No se proporcionó el ID de transacción.');
        }

        $user = auth()->user();
        if (!$user) {
            return redirect('/');
        }

        $tenant = $user->tenant;
        if (!$tenant) {
            return redirect('/');
        }

        $publicKey = config('services.wompi.public_key');
        $isSandbox = str_starts_with($publicKey, 'pub_test_');
        $baseUrl = $isSandbox ? 'https://sandbox.wompi.co/v1' : 'https://production.wompi.co/v1';

        try {
            $response = Http::timeout(10)->get("{$baseUrl}/transactions/{$transactionId}");

            if ($response->successful()) {
                $data = $response->json('data');
                $status = $data['status'] ?? null;
                $reference = $data['reference'] ?? null;

                if ($status === 'APPROVED') {
                    $parts = explode('-', $reference);
                    if (count($parts) >= 3) {
                        $plan = $parts[2];
                        $tenant->update([
                            'estado_pago' => 'pagado',
                            'plan' => $plan
                        ]);
                    } else {
                        $tenant->update(['estado_pago' => 'pagado']);
                    }

                    return redirect($this->getRedirectPath($user))->with('success', 'Pago verificado exitosamente. Suscripción activa.');
                } elseif ($status === 'PENDING') {
                    return view('paywall_status', [
                        'tenant' => $tenant,
                        'status' => 'PENDING',
                        'transactionId' => $transactionId,
                        'message' => 'Tu pago está en proceso de verificación por Wompi. Esto puede tardar unos minutos.'
                    ]);
                } else {
                    return redirect()->route('paywall.index')->with('error', 'El pago fue rechazado o falló: ' . ($data['status_message'] ?? $status));
                }
            } else {
                Log::error('Error consultando transacción en Wompi', [
                    'id' => $transactionId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->route('paywall.index')->with('error', 'No se pudo verificar el pago con Wompi. Por favor intenta de nuevo.');
            }
        } catch (\Exception $e) {
            Log::error('Excepción al consultar transacción en Wompi', [
                'id' => $transactionId,
                'message' => $e->getMessage()
            ]);
            return redirect()->route('paywall.index')->with('error', 'Error al conectar con Wompi para verificar el pago.');
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Wompi webhook recibido', $payload);

        $event = $payload['event'] ?? null;
        if ($event !== 'transaction.updated') {
            return response()->json(['message' => 'Ignored event'], 200);
        }

        $transaction = $payload['data']['transaction'] ?? null;
        if (!$transaction) {
            return response()->json(['message' => 'No transaction data'], 400);
        }

        $reference = $transaction['reference'] ?? null;
        $status = $transaction['status'] ?? null;

        if (!$reference || !$status) {
            return response()->json(['message' => 'Missing reference or status'], 400);
        }

        $eventSecret = config('services.wompi.events_secret');
        if ($eventSecret) {
            $signature = $payload['signature'] ?? null;
            $timestamp = $payload['timestamp'] ?? null;

            if ($signature && $timestamp) {
                $concat = '';
                foreach ($signature['properties'] as $prop) {
                    $concat .= data_get($payload['data'], $prop);
                }
                $concat .= $timestamp;
                $concat .= $eventSecret;

                $computedHash = hash('sha256', $concat);
                if ($computedHash !== $signature['checksum']) {
                    Log::warning('Firma de webhook Wompi inválida', [
                        'computed' => $computedHash,
                        'received' => $signature['checksum']
                    ]);
                    return response()->json(['error' => 'Invalid signature'], 400);
                }
            }
        }

        $parts = explode('-', $reference);
        if (count($parts) >= 2 && $parts[0] === 'FC') {
            $tenantId = $parts[1];
            $plan = $parts[2] ?? null;
            $tenant = \App\Models\Tenant::find($tenantId);

            if ($tenant) {
                if ($status === 'APPROVED') {
                    $updateData = ['estado_pago' => 'pagado'];
                    if ($plan) {
                        $updateData['plan'] = $plan;
                    }
                    $tenant->update($updateData);
                    Log::info("Tenant {$tenantId} marcado como pagado via webhook Wompi (Plan: {$plan})");
                } else {
                    Log::info("Transacción Wompi para Tenant {$tenantId} con estado: {$status}");
                }
                return response()->json(['message' => 'Success'], 200);
            }
        }

        return response()->json(['message' => 'Tenant not found'], 404);
    }

    public function simulatePayment(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $tenant = $user->tenant;
            if ($tenant) {
                $tenant->update(['estado_pago' => 'pagado']);
            }
            return redirect($this->getRedirectPath($user))->with('success', 'Pago procesado exitosamente. Funciones desbloqueadas.');
        }
        return redirect('/');
    }
}

