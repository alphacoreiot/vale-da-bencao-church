<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$geracoes = App\Models\Geracao::select('id', 'nome')->orderBy('nome')->get();

foreach ($geracoes as $g) {
    echo $g->id . ' - ' . $g->nome . "\n";
}
