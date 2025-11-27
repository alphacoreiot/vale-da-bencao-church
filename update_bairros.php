<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Mapeamento completo - automatizados + manuais
$updates = [
    // Automáticos (com match)
    '2 De Julho' => 'DOIS DE JULHO',
    'Alpha Ville' => 'ALPHAVILLE',
    'Alphaville' => 'ALPHAVILLE',
    'Alto da Cruz' => 'ALTO DA CRUZ',
    'Arembepe' => 'AREMBEPE (ABRANTES)',
    'Bairro Gleba B' => 'GLEBA B',
    'Bairro Terras Alphaville' => 'ALPHAVILLE',
    'Camaçari de Dentro' => 'CAMACARI DE DENTRO',
    'Camaçari de dentro' => 'CAMACARI DE DENTRO',
    'Ficam 2' => 'FICAM',
    'Fican 2' => 'FICAM',
    'Gleba A' => 'GLEBA A',
    'Gleba B' => 'GLEBA B',
    'Gleba C' => 'GLEBA C',
    'Gravata' => 'GRAVATA',
    'Gravata / Vila Goiânia' => 'GRAVATA', // Manter em Gravatá (mais específico)
    'Gravatá' => 'GRAVATA',
    'Jardim Limoeiro' => 'JARDIM LIMOEIRO',
    'Lama Preta' => 'LAMA PRETA',
    'Lama preta' => 'LAMA PRETA',
    'Limoeiro' => 'JARDIM LIMOEIRO',
    'Natal' => 'NATAL',
    'Novo Horizonte' => 'NOVO HORIZONTE',
    'Novo horizonte' => 'NOVO HORIZONTE',
    'Paque verde 2' => 'PARQUE VERDE II',
    'Parafuso' => 'PARAFUSO',
    'Parq das Palmeiras' => 'PARQUE DAS PALMEIRAS',
    'Parque Satélite' => 'PARQUE SATELITE',
    'Parque Verde' => 'PARQUE VERDE II',
    'Parque Verde 1' => 'PARQUE VERDE II',
    'Parque das Mangabas' => 'PARQUE DAS MANGABAS',
    'Parque das Palmeiras' => 'PARQUE DAS PALMEIRAS',
    'Parque satélite' => 'PARQUE SATELITE',
    'Piaçaveira' => 'PIACAVEIRA',
    'Ponto Certo' => 'PONTO CERTO',
    'Ponto certo' => 'PONTO CERTO',
    'Pq Verde 1' => 'PARQUE VERDE II',
    'Pq. das Palmeiras' => 'PARQUE DAS PALMEIRAS',
    'Santa Maria' => 'SANTA MARIA',
    'Santo Antônio 1' => 'SANTO ANTONIO',
    'Verde Horizonte' => 'VERDES HORIZONTES',
    'Verdes Horizonte' => 'VERDES HORIZONTES',
    'Vivendas' => 'VIVEA',
    'Gleba H' => 'Gleba H', // Já está correto (case match)
    
    // Manuais - bairros que não tiveram match automático
    'Algarobas 3' => 'NOVO HORIZONTE', // Algarobas fica próximo a Novo Horizonte
    'Av Camaçari' => 'CENTRO', // Av Camaçari é no Centro
    'Bairro Novo' => 'NOVO HORIZONTE', // Bairro Novo = Novo Horizonte
    'Bairro novo' => 'NOVO HORIZONTE',
    'Bairro Phoc 2' => 'RENASCER - PHOC II',
    'Cond Villa Bella' => 'JARDIM LIMOEIRO', // Condomínio em Jardim Limoeiro
    'Dias D\' Villa' => 'CENTRO', // Dias D'Ávila fica próximo ao Centro
    'Inocoop' => 'CENTRO', // Conjunto habitacional próximo ao Centro
    'Phoc 2' => 'RENASCER - PHOC II',
    'Phoc 3' => 'TANCREDO NEVES - PHOC III',
];

echo "=== INICIANDO ATUALIZAÇÃO DOS BAIRROS ===\n\n";

$totalAtualizado = 0;

foreach ($updates as $old => $new) {
    if ($old === $new) continue; // Pular se já está correto
    
    $affected = DB::table('celulas')
        ->where('bairro', $old)
        ->update(['bairro' => $new]);
    
    if ($affected > 0) {
        echo "✅ '$old' -> '$new' ($affected registros)\n";
        $totalAtualizado += $affected;
    }
}

echo "\n=== RESUMO ===\n";
echo "Total de registros atualizados: $totalAtualizado\n";

// Verificar bairros únicos após atualização
$bairrosAtualizados = App\Models\Celula::where('ativo', true)
    ->whereNotNull('bairro')
    ->pluck('bairro')
    ->unique()
    ->sort()
    ->values()
    ->toArray();

echo "\n=== BAIRROS APÓS ATUALIZAÇÃO (" . count($bairrosAtualizados) . ") ===\n";
foreach ($bairrosAtualizados as $b) {
    echo "- $b\n";
}

// Limpar cache
Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "\n✅ Cache limpo!\n";
