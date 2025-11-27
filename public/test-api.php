<?php
// Teste direto da API
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: application/json');

echo json_encode([
    'publicKey' => config('services.vapid.public_key'),
    'class_exists' => class_exists('App\\Http\\Controllers\\PushNotificationController'),
]);
