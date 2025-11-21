<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Devocional;
use Carbon\Carbon;

class DevocionalTestSeeder extends Seeder
{
    public function run(): void
    {
        // Criar devocional para hoje
        Devocional::create([
            'titulo' => 'Fé que Move Montanhas',
            'descricao' => 'Reflexão sobre a importância da fé inabalável em Deus',
            'texto' => '**Hebreus 11:1** - "A fé é a certeza daquilo que esperamos e a prova das coisas que não vemos."

## Reflexão

A fé verdadeira não é apenas acreditar que Deus existe, mas confiar plenamente em Suas promessas mesmo quando as circunstâncias parecem impossíveis. 

Quando enfrentamos montanhas em nossa vida - sejam problemas financeiros, relacionamentos quebrados, ou desafios de saúde - é a fé que nos mantém firmes.

## Aplicação Prática

1. **Confie nas promessas de Deus** - Ele nunca falha
2. **Ore com expectativa** - Acredite que Deus responderá
3. **Aja com fé** - Dê passos mesmo sem ver o caminho completo

## Oração

Senhor, aumenta minha fé! Ajuda-me a confiar em Ti mesmo quando não entendo Teus caminhos. Que minha fé mova as montanhas que parecem impossíveis. Amém!',
            'data' => Carbon::today(),
            'ativo' => true,
        ]);

        echo "✅ Devocional de teste criado para hoje!\n";
    }
}
