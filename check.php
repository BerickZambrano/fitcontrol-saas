<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenantId = 4; // since from the previous output tenant_id was 4
echo "=== Users in Tenant $tenantId ===\n";
$users = App\Models\User::where('tenant_id', $tenantId)->get();
foreach ($users as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Roles: " . implode(', ', $u->getRoleNames()->toArray()) . "\n";
}

echo "\n=== All Assignments in Tenant $tenantId ===\n";
$assignments = App\Models\EquipoUser::where('tenant_id', $tenantId)->get();
foreach ($assignments as $a) {
    $userName = App\Models\User::find($a->user_id)?->name ?? 'Unknown';
    $teamName = App\Models\Equipo::find($a->equipo_id)?->nombre ?? 'Unknown';
    echo "ID: {$a->id} | Team: {$teamName} (ID: {$a->equipo_id}) | User: {$userName} (ID: {$a->user_id}) | Start: {$a->fecha_inicio} | End: {$a->fecha_fin}\n";
}
