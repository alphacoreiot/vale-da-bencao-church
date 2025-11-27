<?php
// Script para atualizar o mapeamento de cores das gerações

$view_path = 'resources/views/frontend/celulas.blade.php';
$content = file_get_contents($view_path);

// Novo mapeamento de cores baseado nos nomes reais das gerações
$novo_mapeamento = <<<'PHP'
@php
                        // Mapeamento de cores baseado nos nomes das gerações
                        $coresGeracoes = [
                            // Cores diretas
                            'azul celeste' => '#87CEEB',
                            'bege' => '#F5F5DC',
                            'branca' => '#FFFFFF',
                            'branca e azul' => '#B0C4DE',
                            'cinza' => '#808080',
                            'coral' => '#FF7F50',
                            'dourada' => '#FFD700',
                            'laranja' => '#FF8C00',
                            'marrom' => '#8B4513',
                            'mostarda' => '#FFDB58',
                            'neon' => '#39FF14',
                            'ouro' => '#DAA520',
                            'pink' => '#FF69B4',
                            'prata' => '#C0C0C0',
                            'preta' => '#1a1a1a',
                            'preta e branca' => '#4a4a4a',
                            'rosinha' => '#FFB6C1',
                            'roxa' => '#8B008B',
                            'verde bandeira' => '#009739',
                            'verde e vinho' => '#228B22',
                            'verde tifanes' => '#40E0D0',
                            // Nomes especiais
                            'água viva' => '#00CED1',
                            'b e d' => '#6495ED',
                            'gaditas' => '#B8860B',
                            'israel' => '#4169E1',
                            'jeová makadech' => '#9932CC',
                            'resgate' => '#DC143C',
                            'porta do secreto' => '#4B0082',
                        ];
                        
                        $nomeLower = strtolower($geracao->nome);
                        $corEncontrada = '#D4AF37'; // Dourado padrão
                        
                        foreach ($coresGeracoes as $chave => $cor) {
                            if (str_contains($nomeLower, $chave)) {
                                $corEncontrada = $cor;
                                break;
                            }
                        }
                    @endphp
PHP;

// Encontrar e substituir o bloco @php existente das cores
$pattern = '/@php\s+\/\/ Mapeamento de cores.*?@endphp/s';

if (preg_match($pattern, $content)) {
    $content = preg_replace($pattern, $novo_mapeamento, $content);
    file_put_contents($view_path, $content);
    echo "✅ Cores das gerações atualizadas!\n\n";
    echo "Mapeamento:\n";
    echo "- Azul Celeste: #87CEEB (azul claro)\n";
    echo "- Bege: #F5F5DC\n";
    echo "- Branca: #FFFFFF\n";
    echo "- Branca e Azul: #B0C4DE\n";
    echo "- Cinza: #808080\n";
    echo "- Coral: #FF7F50\n";
    echo "- Dourada: #FFD700\n";
    echo "- Laranja: #FF8C00\n";
    echo "- Marrom: #8B4513\n";
    echo "- Mostarda: #FFDB58\n";
    echo "- Neon: #39FF14\n";
    echo "- Ouro: #DAA520\n";
    echo "- Pink: #FF69B4\n";
    echo "- Prata: #C0C0C0\n";
    echo "- Preta: #1a1a1a\n";
    echo "- Preta e Branca: #4a4a4a\n";
    echo "- Rosinha: #FFB6C1\n";
    echo "- Roxa: #8B008B\n";
    echo "- Verde Bandeira: #009739\n";
    echo "- Verde e Vinho: #228B22\n";
    echo "- Verde Tifanes: #40E0D0 (turquesa)\n";
    echo "- Água Viva: #00CED1 (ciano)\n";
    echo "- B e D: #6495ED (azul)\n";
    echo "- Gaditas: #B8860B (bronze)\n";
    echo "- Israel: #4169E1 (azul royal)\n";
    echo "- Jeová Makadech: #9932CC (roxo escuro)\n";
    echo "- Resgate: #DC143C (vermelho)\n";
    echo "- Porta do Secreto: #4B0082 (índigo)\n";
} else {
    echo "❌ Bloco de cores não encontrado\n";
}
