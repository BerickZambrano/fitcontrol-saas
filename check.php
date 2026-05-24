<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Notifications in Database ===\n";
$notifs = DB::table('notifications')->get();
foreach ($notifs as $n) {
    echo "ID: {$n->id} | Type: {$n->type} | Notifiable ID: {$n->notifiable_id} | Data: {$n->data}\n";
}
