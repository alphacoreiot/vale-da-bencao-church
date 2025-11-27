<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Buscar bairros únicos das células
$bairrosCelulas = App\Models\Celula::where('ativo', true)
    ->whereNotNull('bairro')
    ->pluck('bairro')
    ->unique()
    ->sort()
    ->values()
    ->toArray();

echo "=== BAIRROS CADASTRADOS NO BANCO (" . count($bairrosCelulas) . ") ===\n";
foreach ($bairrosCelulas as $b) {
    echo "- $b\n";
}

// Ler GeoJSON
$geojsonPath = __DIR__ . '/geojson/Camacari.geojson';
$geojson = json_decode(file_get_contents($geojsonPath), true);

$bairrosGeoJSON = [];
foreach ($geojson['features'] as $feature) {
    $nm = $feature['properties']['nm_bairro'] ?? null;
    if ($nm) {
        $bairrosGeoJSON[$nm] = $nm;
    }
}
ksort($bairrosGeoJSON);

echo "\n=== BAIRROS NO GEOJSON (" . count($bairrosGeoJSON) . ") ===\n";
foreach ($bairrosGeoJSON as $b) {
    echo "- $b\n";
}

// Função para normalizar string
function normalizar($str) {
    $str = mb_strtoupper($str, 'UTF-8');
    $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
    $str = preg_replace('/[^A-Z0-9\s]/', '', $str);
    return trim($str);
}

// Fazer matching
echo "\n=== MATCHING E SUGESTÕES DE CORREÇÃO ===\n";
$updates = [];

foreach ($bairrosCelulas as $bairroCelula) {
    $bairroNorm = normalizar($bairroCelula);
    $encontrado = false;
    $sugestao = null;
    
    // Verificar match exato normalizado
    foreach ($bairrosGeoJSON as $bairroGeo) {
        $geoNorm = normalizar($bairroGeo);
        
        if ($bairroNorm === $geoNorm) {
            $encontrado = true;
            if ($bairroCelula !== $bairroGeo) {
                $sugestao = $bairroGeo;
            }
            break;
        }
    }
    
    // Se não encontrou, tentar match parcial
    if (!$encontrado) {
        $melhorMatch = null;
        $melhorScore = 0;
        
        foreach ($bairrosGeoJSON as $bairroGeo) {
            $geoNorm = normalizar($bairroGeo);
            
            // Verificar se contém
            if (strpos($geoNorm, $bairroNorm) !== false || strpos($bairroNorm, $geoNorm) !== false) {
                similar_text($bairroNorm, $geoNorm, $percent);
                if ($percent > $melhorScore) {
                    $melhorScore = $percent;
                    $melhorMatch = $bairroGeo;
                }
            }
            
            // Verificar similaridade
            similar_text($bairroNorm, $geoNorm, $percent);
            if ($percent > 60 && $percent > $melhorScore) {
                $melhorScore = $percent;
                $melhorMatch = $bairroGeo;
            }
        }
        
        if ($melhorMatch) {
            $sugestao = $melhorMatch;
        }
    }
    
    if ($sugestao) {
        echo "ATUALIZAR: '$bairroCelula' -> '$sugestao'\n";
        $updates[$bairroCelula] = $sugestao;
    } elseif (!$encontrado) {
        echo "NÃO ENCONTRADO: '$bairroCelula'\n";
    } else {
        echo "OK: '$bairroCelula'\n";
    }
}

// Gerar SQL de atualização
echo "\n=== SQL PARA ATUALIZAR ===\n";
foreach ($updates as $old => $new) {
    $oldEsc = addslashes($old);
    $newEsc = addslashes($new);
    echo "UPDATE celulas SET bairro = '$newEsc' WHERE bairro = '$oldEsc';\n";
}

echo "\n=== RESUMO ===\n";
echo "Total de bairros no banco: " . count($bairrosCelulas) . "\n";
echo "Total de bairros no GeoJSON: " . count($bairrosGeoJSON) . "\n";
echo "Atualizações sugeridas: " . count($updates) . "\n";
