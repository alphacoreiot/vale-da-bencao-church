<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Corrigir Gleba H
DB::table('celulas')->where('bairro', 'Gleba H')->update(['bairro' => 'GLEBA H']);
echo "Gleba H corrigido para GLEBA H!\n";

// Verificar bairros finais
$bairros = App\Models\Celula::where('ativo', true)
    ->whereNotNull('bairro')
    ->pluck('bairro')
    ->unique()
    ->sort()
    ->values()
    ->toArray();

echo "\n=== BAIRROS FINAIS (" . count($bairros) . ") ===\n";
foreach ($bairros as $b) {
    echo "- $b\n";
}
