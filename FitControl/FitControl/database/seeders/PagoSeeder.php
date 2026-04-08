<?php

namespace Database\Seeders;

use App\Models\Pago;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        $pagos = [
            // Tenant 1 - Users 1, 2, 3, 10
            ['tenant_id' => 1, 'user_id' => 1, 'monto' => 500000.00, 'estado' => 'pagado', 'fecha' => '2026-03-01'],
            ['tenant_id' => 1, 'user_id' => 2, 'monto' => 500000.00, 'estado' => 'pagado', 'fecha' => '2026-03-01'],
            ['tenant_id' => 1, 'user_id' => 3, 'monto' => 450000.00, 'estado' => 'pendiente', 'fecha' => '2026-03-15'],
            ['tenant_id' => 1, 'user_id' => 10, 'monto' => 450000.00, 'estado' => 'pagado', 'fecha' => '2026-03-10'],
            // Tenant 2 - Users 4, 5, 6
            ['tenant_id' => 2, 'user_id' => 4, 'monto' => 350000.00, 'estado' => 'pagado', 'fecha' => '2026-03-05'],
            ['tenant_id' => 2, 'user_id' => 5, 'monto' => 350000.00, 'estado' => 'pendiente', 'fecha' => '2026-03-20'],
            ['tenant_id' => 2, 'user_id' => 6, 'monto' => 300000.00, 'estado' => 'pagado', 'fecha' => '2026-03-12'],
            // Tenant 3 - Users 7, 8, 9
            ['tenant_id' => 3, 'user_id' => 7, 'monto' => 800000.00, 'estado' => 'pagado', 'fecha' => '2026-03-01'],
            ['tenant_id' => 3, 'user_id' => 8, 'monto' => 800000.00, 'estado' => 'pendiente', 'fecha' => '2026-04-01'],
            ['tenant_id' => 3, 'user_id' => 9, 'monto' => 750000.00, 'estado' => 'pagado', 'fecha' => '2026-03-15'],
        ];

        foreach ($pagos as $pago) {
            Pago::create($pago);
        }
    }
}
