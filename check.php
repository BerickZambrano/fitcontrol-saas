<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ref = new ReflectionClass(Filament\Notifications\Notification::class);
$method = $ref->getMethod('toDatabase');
$filename = $method->getFileName();
$startLine = $method->getStartLine();
$endLine = $method->getEndLine();

$lines = file($filename);
for ($i = $startLine - 1; $i < $endLine; $i++) {
    echo ($i + 1) . ": " . $lines[$i];
}
