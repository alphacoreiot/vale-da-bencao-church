<?php
require __DIR__ . '/vendor/autoload.php';

$class = 'App\\Http\\Controllers\\PushNotificationController';

echo "Testando classe: $class\n";
echo "Existe: " . (class_exists($class) ? 'SIM' : 'NAO') . "\n";

$file = __DIR__ . '/app/Http/Controllers/PushNotificationController.php';
echo "Arquivo existe: " . (file_exists($file) ? 'SIM' : 'NAO') . "\n";

if (file_exists($file)) {
    echo "Tamanho: " . filesize($file) . " bytes\n";
}
