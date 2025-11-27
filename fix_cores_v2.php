<?php
// Script para atualizar o mapeamento de cores das gerações

$view_path = 'resources/views/frontend/celulas.blade.php';
$content = file_get_contents($view_path);

// Bloco antigo a ser substituído
$old_block = <<<'OLD'
@php
                                    $coresGeracoes = [
                                        'default' => '#D4AF37',
                                        'vermelho' => '#E74C3C',
                                        'azul' => '#3498DB',
                                        'verde' => '#27AE60',
                                        'roxo' => '#9B59B6',
                                        'laranja' => '#E67E22',
                                        'rosa' => '#E91E63',
                                        'amarelo' => '#F1C40F',
                                        'ciano' => '#00BCD4',
                                        'marrom' => '#795548',
                                    ];
                                    $nomeNorm = strtolower($geracao->nome);
                                    $corGeracao = $coresGeracoes['default'];
                                    foreach($coresGeracoes as $cor => $hex) {
                                        if (str_contains($nomeNorm, $cor)) {
                                            $corGeracao = $hex;
                                            break;
                                        }
                                    }
                                    // Se geração tiver campo cor, usar ele
                                    if (!empty($geracao->cor)) {
                                        $corGeracao = $geracao->cor;
                                    }
                                @endphp
OLD;

// Novo bloco com todas as cores mapeadas
$new_block = <<<'NEW'
@php
                                    // Mapeamento completo baseado nos nomes reais das 28 gerações
                                    $coresGeracoes = [
                                        // Cores diretas pelo nome
                                        'azul celeste' => '#87CEEB',
                                        'bege' => '#F5DEB3',
                                        'branca e azul' => '#B0E0E6',
                                        'branca' => '#F8F8FF',
                                        'cinza' => '#808080',
                                        'coral' => '#FF7F50',
                                        'dourada' => '#FFD700',
                                        'laranja' => '#FF8C00',
                                        'marrom' => '#8B4513',
                                        'mostarda' => '#DAA520',
                                        'neon' => '#39FF14',
                                        'ouro' => '#DAA520',
                                        'pink' => '#FF69B4',
                                        'prata' => '#C0C0C0',
                                        'preta e branca' => '#404040',
                                        'preta' => '#2C2C2C',
                                        'rosinha' => '#FFB6C1',
                                        'roxa' => '#8B008B',
                                        'verde tifanes' => '#40E0D0',
                                        'verde e vinho' => '#2E8B57',
                                        'verde bandeira' => '#009739',
                                        // Nomes especiais
                                        'água viva' => '#00CED1',
                                        'b e d' => '#6495ED',
                                        'gaditas' => '#CD853F',
                                        'israel' => '#4169E1',
                                        'jeová makadech' => '#9932CC',
                                        'resgate' => '#DC143C',
                                        'porta do secreto' => '#4B0082',
                                    ];
                                    
                                    $nomeNorm = mb_strtolower($geracao->nome);
                                    $corGeracao = '#D4AF37'; // Dourado padrão
                                    
                                    // Primeiro tenta combinações mais específicas (com espaço)
                                    foreach($coresGeracoes as $chave => $hex) {
                                        if (str_contains($chave, ' ') && str_contains($nomeNorm, $chave)) {
                                            $corGeracao = $hex;
                                            break;
                                        }
                                    }
                                    
                                    // Se não encontrou, tenta chaves simples
                                    if ($corGeracao === '#D4AF37') {
                                        foreach($coresGeracoes as $chave => $hex) {
                                            if (!str_contains($chave, ' ') && str_contains($nomeNorm, $chave)) {
                                                $corGeracao = $hex;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
NEW;

if (str_contains($content, $old_block)) {
    $content = str_replace($old_block, $new_block, $content);
    file_put_contents($view_path, $content);
    echo "✅ Cores das gerações atualizadas!\n\n";
    echo "28 gerações mapeadas:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $cores = [
        'Água Viva' => '#00CED1 (ciano escuro)',
        'Azul Celeste' => '#87CEEB (azul claro)',
        'B e D' => '#6495ED (azul cornflower)',
        'Bege' => '#F5DEB3 (trigo)',
        'Branca' => '#F8F8FF (ghost white)',
        'Branca e Azul' => '#B0E0E6 (powder blue)',
        'Cinza' => '#808080',
        'Coral' => '#FF7F50',
        'Dourada' => '#FFD700 (gold)',
        'Gaditas' => '#CD853F (peru/bronze)',
        'Israel' => '#4169E1 (royal blue)',
        'Jeová Makadech' => '#9932CC (dark orchid)',
        'Laranja' => '#FF8C00 (dark orange)',
        'Marrom' => '#8B4513 (saddle brown)',
        'Mostarda' => '#DAA520 (goldenrod)',
        'Neon' => '#39FF14 (verde neon)',
        'Ouro' => '#DAA520 (goldenrod)',
        'Pink' => '#FF69B4 (hot pink)',
        'Prata' => '#C0C0C0 (silver)',
        'Preta' => '#2C2C2C',
        'Preta e Branca' => '#404040',
        'Resgate' => '#DC143C (crimson)',
        'Rosinha' => '#FFB6C1 (light pink)',
        'Roxa' => '#8B008B (dark magenta)',
        'Verde Bandeira' => '#009739',
        'Verde e Vinho' => '#2E8B57 (sea green)',
        'Verde Tifanes' => '#40E0D0 (turquoise)',
        'Porta do Secreto' => '#4B0082 (indigo)',
    ];
    foreach ($cores as $nome => $cor) {
        echo "• $nome: $cor\n";
    }
} else {
    echo "❌ Bloco de cores não encontrado na forma esperada\n";
    echo "Tentando localizar...\n";
    if (str_contains($content, '$coresGeracoes')) {
        echo "✓ Variável \$coresGeracoes existe no arquivo\n";
    }
}
