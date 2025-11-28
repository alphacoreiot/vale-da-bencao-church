<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$geracoes = App\Models\Geracao::orderBy('id')->pluck('nome', 'id')->toArray();
echo json_encode($geracoes);
