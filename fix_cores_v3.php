<?php
// Script para substituir bloco de cores

$view_path = 'resources/views/frontend/celulas.blade.php';
$content = file_get_contents($view_path);

// Novo bloco completo de cores
$novo_php = <<<'BLADE'
@php
                                    // Mapeamento completo das 28 gerações
                                    $coresGeracoes = [
                                        'água viva' => '#00CED1',
                                        'azul celeste' => '#87CEEB',
                                        'b e d' => '#6495ED',
                                        'bege' => '#F5DEB3',
                                        'branca e azul' => '#B0E0E6',
                                        'branca' => '#F8F8FF',
                                        'cinza' => '#808080',
                                        'coral' => '#FF7F50',
                                        'dourada' => '#FFD700',
                                        'gaditas' => '#CD853F',
                                        'israel' => '#4169E1',
                                        'jeová makadech' => '#9932CC',
                                        'laranja' => '#FF8C00',
                                        'marrom' => '#8B4513',
                                        'mostarda' => '#DAA520',
                                        'neon' => '#39FF14',
                                        'ouro' => '#DAA520',
                                        'pink' => '#FF69B4',
                                        'prata' => '#C0C0C0',
                                        'preta e branca' => '#404040',
                                        'preta' => '#2C2C2C',
                                        'resgate' => '#DC143C',
                                        'rosinha' => '#FFB6C1',
                                        'roxa' => '#8B008B',
                                        'verde bandeira' => '#009739',
                                        'verde e vinho' => '#2E8B57',
                                        'verde tifanes' => '#40E0D0',
                                        'porta do secreto' => '#4B0082',
                                    ];
                                    
                                    $nomeNorm = mb_strtolower($geracao->nome);
                                    $corGeracao = '#D4AF37';
                                    
                                    // Primeiro chaves compostas
                                    foreach($coresGeracoes as $k => $v) {
                                        if (str_contains($k, ' ') && str_contains($nomeNorm, $k)) {
                                            $corGeracao = $v;
                                            break;
                                        }
                                    }
                                    // Depois chaves simples
                                    if ($corGeracao === '#D4AF37') {
                                        foreach($coresGeracoes as $k => $v) {
                                            if (!str_contains($k, ' ') && str_contains($nomeNorm, $k)) {
                                                $corGeracao = $v;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
BLADE;

// Usar regex para encontrar o bloco @php com $coresGeracoes até @endphp
$pattern = '/@php\s+\$coresGeracoes = \[.*?\];\s+\$nomeNorm.*?@endphp/s';

if (preg_match($pattern, $content, $matches)) {
    $content = preg_replace($pattern, $novo_php, $content, 1);
    file_put_contents($view_path, $content);
    echo "✅ Cores atualizadas com sucesso!\n\n";
    echo "Gerações mapeadas:\n";
    $lista = [
        '🌊 Água Viva → Ciano',
        '💙 Azul Celeste → Azul Claro', 
        '🔵 B e D → Cornflower Blue',
        '🟤 Bege → Trigo',
        '⚪ Branca → Branco Fantasma',
        '🔷 Branca e Azul → Powder Blue',
        '⬜ Cinza → Cinza',
        '🟠 Coral → Coral',
        '🟡 Dourada → Ouro',
        '🟤 Gaditas → Bronze',
        '🔵 Israel → Royal Blue',
        '💜 Jeová Makadech → Orquídea',
        '🟧 Laranja → Dark Orange',
        '🟫 Marrom → Saddle Brown',
        '🟨 Mostarda → Goldenrod',
        '💚 Neon → Verde Neon',
        '🟡 Ouro → Goldenrod',
        '💖 Pink → Hot Pink',
        '⬜ Prata → Prata',
        '⬛ Preta → Quase Preto',
        '◼️ Preta e Branca → Cinza Escuro',
        '❤️ Resgate → Crimson',
        '🌸 Rosinha → Light Pink',
        '💜 Roxa → Dark Magenta',
        '💚 Verde Bandeira → Verde Brasil',
        '🌿 Verde e Vinho → Sea Green',
        '🩵 Verde Tifanes → Turquesa',
        '🟣 Porta do Secreto → Índigo',
    ];
    foreach($lista as $item) {
        echo "$item\n";
    }
} else {
    echo "❌ Padrão não encontrado\n";
    // Debug: mostrar o que foi encontrado
    if (preg_match('/@php.*?\$coresGeracoes/s', $content, $m)) {
        echo "Encontrado início: " . substr($m[0], 0, 100) . "\n";
    }
}
