<?php

namespace App\Services;

use App\Models\Section;
use App\Models\Devocional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContextBuilderService
{
    /**
     * Build intelligent context based on user query classification.
     */
    public function buildIntelligentContext(string $userMessage, Section $section): array
    {
        $classification = $this->classifyQuery($userMessage);
        
        $context = [
            'section_name' => $section->name,
            'section_description' => $section->description,
            'query_type' => $classification['type'],
            'timestamp' => now()->format('d/m/Y H:i'),
        ];

        // Add relevant context based on classification
        switch ($classification['type']) {
            case 'devocional':
                $context['devocionais'] = $this->getDevocionalContext($classification['keywords']);
                break;
                
            case 'evento':
                $context['eventos'] = $this->getEventContextPlaceholder();
                break;
                
            case 'institucional':
                $context['institucional'] = $this->getInstitucionalContext($section);
                break;
            
            case 'celulas':
                $context['celulas'] = $this->getCelulasContext($classification['keywords']);
                break;
                
            case 'misto':
                // Busca múltiplos contextos
                $context['devocionais'] = $this->getDevocionalContext($classification['keywords']);
                $context['eventos'] = $this->getEventContextPlaceholder();
                $context['institucional'] = $this->getInstitucionalContext($section);
                $context['celulas'] = $this->getCelulasContext($classification['keywords']);
                break;
        }

        return $context;
    }

    /**
     * Classify user query to determine what type of information they need.
     */
    private function classifyQuery(string $message): array
    {
        $messageLower = Str::lower($message);
        
        // Keywords for each category
        $devocionalKeywords = [
            'devocional', 'devoção', 'reflexão', 'meditação', 'palavra', 
            'versículo', 'bíblia', 'escritura', 'salmo', 'provérbio',
            'evangelho', 'ensinamento', 'mensagem do dia', 'leitura',
            'estudo bíblico', 'texto', 'passagem'
        ];
        
        $eventoKeywords = [
            'evento', 'culto', 'reunião', 'encontro', 'conferência',
            'celebração', 'programação', 'agenda', 'quando', 'horário',
            'próximo', 'data', 'dia', 'semana', 'mês', 'cronograma'
        ];
        
        $institucionalKeywords = [
            'igreja', 'sobre', 'quem somos', 'história', 'missão', 'visão',
            'valores', 'pastor', 'liderança', 'contato', 'endereço',
            'telefone', 'email', 'localização', 'onde fica', 'como chegar'
        ];

        $celulasKeywords = [
            'célula', 'celula', 'células', 'celulas', 'grupo', 'grupos',
            'pequeno grupo', 'rede', 'líder', 'lider', 'geração', 'geracao',
            'bairro', 'perto de mim', 'próximo', 'participar', 'entrar',
            'fazer parte', 'whatsapp', 'contato da célula', 'encontrar célula'
        ];

        $scores = [
            'devocional' => 0,
            'evento' => 0,
            'institucional' => 0,
            'celulas' => 0,
        ];

        $matchedKeywords = [];

        // Score each category
        foreach ($devocionalKeywords as $keyword) {
            if (Str::contains($messageLower, $keyword)) {
                $scores['devocional']++;
                $matchedKeywords[] = $keyword;
            }
        }

        foreach ($eventoKeywords as $keyword) {
            if (Str::contains($messageLower, $keyword)) {
                $scores['evento']++;
            }
        }

        foreach ($institucionalKeywords as $keyword) {
            if (Str::contains($messageLower, $keyword)) {
                $scores['institucional']++;
            }
        }

        foreach ($celulasKeywords as $keyword) {
            if (Str::contains($messageLower, $keyword)) {
                $scores['celulas']++;
                $matchedKeywords[] = $keyword;
            }
        }

        // Determine type based on scores
        $maxScore = max($scores);
        
        if ($maxScore === 0) {
            // Generic query - return mixed context
            return ['type' => 'misto', 'keywords' => []];
        }

        $categoriesWithMaxScore = array_keys($scores, $maxScore);
        
        if (count($categoriesWithMaxScore) > 1) {
            return ['type' => 'misto', 'keywords' => $matchedKeywords];
        }

        return [
            'type' => $categoriesWithMaxScore[0],
            'keywords' => $matchedKeywords
        ];
    }

    /**
     * Get devocional context from database.
     */
    private function getDevocionalContext(array $keywords = []): array
    {
        // Get today's devocional
        $devocionalHoje = Devocional::ativoDoDia()->first();
        
        // Get recent devocionais
        $devocionaisRecentes = Devocional::ativoRecente()
            ->take(5)
            ->get();

        // If keywords provided, try to find relevant devocionais
        $devocionaisRelevantes = collect([]);
        if (!empty($keywords)) {
            $query = Devocional::where('ativo', true);
            
            foreach ($keywords as $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('titulo', 'like', "%{$keyword}%")
                      ->orWhere('descricao', 'like', "%{$keyword}%")
                      ->orWhere('texto', 'like', "%{$keyword}%");
                });
            }
            
            $devocionaisRelevantes = $query->take(3)->get();
        }

        return [
            'devocional_hoje' => $devocionalHoje ? [
                'titulo' => $devocionalHoje->titulo,
                'descricao' => $devocionalHoje->descricao,
                'texto_resumo' => Str::limit(strip_tags($devocionalHoje->texto), 300),
                'data' => $devocionalHoje->data->format('d/m/Y'),
            ] : null,
            'devocionais_recentes' => $devocionaisRecentes ? $devocionaisRecentes->map(function($d) {
                return [
                    'titulo' => $d->titulo,
                    'descricao' => $d->descricao,
                    'data' => $d->data->format('d/m/Y'),
                ];
            })->toArray() : [],
            'devocionais_relevantes' => $devocionaisRelevantes->map(function($d) {
                return [
                    'titulo' => $d->titulo,
                    'descricao' => $d->descricao,
                    'texto_resumo' => Str::limit(strip_tags($d->texto), 200),
                    'data' => $d->data->format('d/m/Y'),
                ];
            })->toArray(),
            'total_devocionais' => Devocional::where('ativo', true)->count(),
        ];
    }

    /**
     * Get event context placeholder (until Event model is created).
     */
    private function getEventContextPlaceholder(): array
    {
        return [
            'eventos_proximos' => [],
            'eventos_mes_atual' => 0,
            'proximo_evento' => null,
            'info' => 'Consulte nossos horários de culto: Domingo 18:30, Quarta 19:00, Quinta (Célula) 19:00',
        ];
    }

    /**
     * Normalizar texto para busca (remove acentos, apóstrofos, etc.)
     */
    private function normalizeForSearch(string $text): string
    {
        // Converter para minúsculas
        $text = Str::lower($text);
        
        // Remover apóstrofos e aspas
        $text = str_replace(["'", "'", "`", "´", "d'"], ['', '', '', '', 'd'], $text);
        
        // Remover acentos
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        
        // Remover caracteres especiais mantendo espaços e letras
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        
        // Remover espaços extras
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        return $text;
    }

    /**
     * Get células context - simplified to just provide link.
     */
    private function getCelulasContext(array $keywords = []): array
    {
        try {
            // Apenas estatísticas básicas
            $totalCelulas = \App\Models\CelulaCadastro::aprovadas()->count();
            $totalBairros = \App\Models\CelulaCadastro::aprovadas()
                ->distinct()
                ->count('bairro');
            
            return [
                'total_celulas' => $totalCelulas,
                'total_bairros' => $totalBairros,
                'link_pagina' => 'https://valedabencao.com.br/celulas',
                'dia_celula' => 'Quinta-feira às 19:00',
            ];
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar células: ' . $e->getMessage());
            return [
                'total_celulas' => 0,
                'link_pagina' => 'https://valedabencao.com.br/celulas',
            ];
        }
    }

    /**
     * Get event context from database.
     */
    private function getEventContext(): array
    {
        $hoje = now();
        
        // Get upcoming events
        $eventosProximos = Event::where('status', 'published')
            ->where('event_date', '>=', $hoje)
            ->orderBy('event_date')
            ->take(5)
            ->get();

        // Get current month events
        $eventosMes = Event::where('status', 'published')
            ->whereYear('event_date', $hoje->year)
            ->whereMonth('event_date', $hoje->month)
            ->orderBy('event_date')
            ->get();

        return [
            'eventos_proximos' => $eventosProximos->map(function($e) {
                return [
                    'titulo' => $e->title,
                    'descricao' => $e->description,
                    'data' => $e->event_date->format('d/m/Y'),
                    'horario' => $e->event_time ? $e->event_time->format('H:i') : null,
                    'local' => $e->location,
                ];
            })->toArray(),
            'eventos_mes_atual' => $eventosMes->count(),
            'proximo_evento' => $eventosProximos->first() ? [
                'titulo' => $eventosProximos->first()->title,
                'data' => $eventosProximos->first()->event_date->format('d/m/Y'),
                'dias_ate' => now()->diffInDays($eventosProximos->first()->event_date),
            ] : null,
        ];
    }

    /**
     * Get institutional context.
     */
    private function getInstitucionalContext(Section $section): array
    {
        return [
            'sobre_secao' => $section->description,
            'conteudos_publicados' => $section->publishedContents()->count(),
            'secao_ativa' => $section->status === 'active',
        ];
    }

    /**
     * Generate greeting with devocional context for returning visitors.
     */
    public function generateGreetingForReturningVisitor(): array
    {
        $devocionalHoje = Devocional::ativoDoDia()->first();
        
        if ($devocionalHoje) {
            return [
                'has_devocional' => true,
                'titulo' => $devocionalHoje->titulo,
                'descricao' => $devocionalHoje->descricao,
                'sugestao' => "Olá! Bem-vindo de volta! Já viu o devocional de hoje? Hoje refletimos sobre \"{$devocionalHoje->titulo}\". Posso ajudar você com alguma dúvida sobre este ou outros devocionais, eventos ou informações da igreja?",
            ];
        }

        $devocionalRecente = Devocional::ativoRecente()->first();
        
        if ($devocionalRecente) {
            return [
                'has_devocional' => true,
                'titulo' => $devocionalRecente->titulo,
                'descricao' => $devocionalRecente->descricao,
                'sugestao' => "Olá! Bem-vindo de volta! Temos novos devocionais disponíveis. O mais recente é sobre \"{$devocionalRecente->titulo}\". Posso ajudar você com alguma informação sobre devocionais, eventos ou a igreja?",
            ];
        }

        return [
            'has_devocional' => false,
            'sugestao' => "Olá! Bem-vindo de volta! Como posso ajudar você hoje? Posso responder sobre nossos devocionais, eventos ou informações da igreja.",
        ];
    }

    /**
     * Build enhanced system prompt based on context.
     */
    public function buildEnhancedSystemPrompt(array $context): string
    {
        $basePrompt = "Você é o assistente virtual da Igreja Vale da Bênção Church. Seu papel é ajudar os visitantes com informações precisas e atualizadas.\n\n";
        
        $basePrompt .= "=== INFORMAÇÕES INSTITUCIONAIS DA IGREJA ===\n\n";
        $basePrompt .= "🏛️ NOME: Igreja Vale da Bênção Church\n";
        $basePrompt .= "✝️ LIDERANÇA: Apóstolo Ary Dallas e Naele Santana\n";
        $basePrompt .= "📍 ENDEREÇO: Rua Dos Buritis, 07 - Parque Das Palmeiras, Camaçari/BA\n";
        $basePrompt .= "📺 CANAL YOUTUBE: @valedabencaochurch\n\n";
        
        $basePrompt .= "📅 HORÁRIOS DOS CULTOS:\n";
        $basePrompt .= "• DOMINGO: 18:30 às 20:30\n";
        $basePrompt .= "• QUARTA-FEIRA: 19:00 às 21:00\n";
        $basePrompt .= "• QUINTA-FEIRA (Célula): 19:00 às 21:00\n\n";
        
        $basePrompt .= "💬 MENSAGEM: Seja cordial ao convite. Focamos no que Jesus ama: Você!\n\n";
        
        $basePrompt .= "=== REGRAS IMPORTANTES ===\n";
        $basePrompt .= "1. Responda APENAS com informações da igreja, eventos e devocionais fornecidos\n";
        $basePrompt .= "2. Use SEMPRE as informações institucionais acima quando perguntarem sobre horários, liderança, endereço\n";
        $basePrompt .= "3. Para perguntas sobre outro assunto, responda: 'Desculpe, só posso ajudar com informações sobre a igreja, devocionais e eventos.'\n";
        $basePrompt .= "4. Use EXATAMENTE as informações do contexto - não invente dados\n";
        $basePrompt .= "5. Seja direto, claro, acolhedor e objetivo nas respostas\n";
        $basePrompt .= "6. Use emojis apropriados mas com moderação: 🙏 📅 📍 📖 ✝️\n";
        $basePrompt .= "7. Mantenha respostas entre 100-300 palavras\n\n";

        $basePrompt .= "DATA ATUAL: " . now()->format('d/m/Y (l)') . "\n\n";

        // Add specific context based on query type
        if (isset($context['devocionais'])) {
            $basePrompt .= $this->buildDevocionalPrompt($context['devocionais']);
        }

        if (isset($context['eventos'])) {
            $basePrompt .= $this->buildEventPrompt($context['eventos']);
        }

        if (isset($context['celulas'])) {
            $basePrompt .= $this->buildCelulasPrompt($context['celulas']);
        }

        $basePrompt .= "\n=== INSTRUÇÕES DE RESPOSTA ===\n";
        $basePrompt .= "- Para HORÁRIOS: Use os horários dos cultos listados acima\n";
        $basePrompt .= "- Para LIDERANÇA/PASTOR/APÓSTOLO: Mencione Apóstolo Ary Dallas e Naele Santana\n";
        $basePrompt .= "- Para ENDEREÇO/LOCALIZAÇÃO: Use o endereço completo acima\n";
        $basePrompt .= "- Para RESUMIR devocional: Resuma o texto fornecido em 3-4 parágrafos destacando mensagem principal\n";
        $basePrompt .= "- Para perguntas sobre EVENTOS: Liste os eventos com datas e horários exatos\n";
        $basePrompt .= "- Para perguntas sobre CÉLULAS: Use as informações de células e sempre indique o link da página\n";
        $basePrompt .= "- NÃO adicione informações que não estão no contexto acima\n";
        $basePrompt .= "- Seja sempre acolhedor e convide a pessoa para conhecer a igreja\n";

        return $basePrompt;
    }

    /**
     * Build devocional-specific prompt.
     */
    private function buildDevocionalPrompt(array $devocionais): string
    {
        $prompt = "=== INFORMAÇÕES SOBRE DEVOCIONAIS ===\n\n";
        
        if ($devocionais['devocional_hoje']) {
            $d = $devocionais['devocional_hoje'];
            $prompt .= "📖 DEVOCIONAL DE HOJE ({$d['data']}):\n";
            $prompt .= "Título: {$d['titulo']}\n";
            $prompt .= "Descrição: {$d['descricao']}\n";
            $prompt .= "Texto Completo:\n{$d['texto_resumo']}\n\n";
            $prompt .= "INSTRUÇÕES: Ao resumir, destaque a mensagem central, aplicação prática e versículo chave.\n\n";
        }

        if (!empty($devocionais['devocionais_relevantes'])) {
            $prompt .= "📚 DEVOCIONAIS RELEVANTES:\n";
            foreach ($devocionais['devocionais_relevantes'] as $d) {
                $prompt .= "\n• {$d['titulo']} ({$d['data']})\n";
                $prompt .= "  Descrição: {$d['descricao']}\n";
                $prompt .= "  Resumo: {$d['texto_resumo']}\n";
            }
            $prompt .= "\n";
        }

        if (!empty($devocionais['devocionais_recentes'])) {
            $prompt .= "🗓️ DEVOCIONAIS RECENTES (últimos 5):\n";
            foreach (array_slice($devocionais['devocionais_recentes'], 0, 3) as $d) {
                $prompt .= "• {$d['titulo']} ({$d['data']}): {$d['descricao']}\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "Total de devocionais disponíveis: {$devocionais['total_devocionais']}\n\n";

        return $prompt;
    }

    /**
     * Build event-specific prompt.
     */
    private function buildEventPrompt(array $eventos): string
    {
        $prompt = "INFORMAÇÕES SOBRE EVENTOS:\n";
        
        if ($eventos['proximo_evento']) {
            $e = $eventos['proximo_evento'];
            $prompt .= "Próximo Evento: {$e['titulo']} em {$e['data']} (daqui a {$e['dias_ate']} dias)\n\n";
        }

        if (!empty($eventos['eventos_proximos'])) {
            $prompt .= "Próximos Eventos:\n";
            foreach ($eventos['eventos_proximos'] as $e) {
                $horario = $e['horario'] ? " às {$e['horario']}" : "";
                $local = $e['local'] ? " - Local: {$e['local']}" : "";
                $prompt .= "- {$e['titulo']}: {$e['data']}{$horario}{$local}\n";
                $prompt .= "  Descrição: {$e['descricao']}\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "Eventos no mês atual: {$eventos['eventos_mes_atual']}\n\n";

        return $prompt;
    }

    /**
     * Build institutional prompt.
     */
    private function buildInstitucionalPrompt(array $institucional): string
    {
        $prompt = "INFORMAÇÕES INSTITUCIONAIS:\n";
        $prompt .= "- Seção: {$institucional['sobre_secao']}\n";
        $prompt .= "- Conteúdos publicados: {$institucional['conteudos_publicados']}\n";
        $prompt .= "- Status: " . ($institucional['secao_ativa'] ? 'Ativa' : 'Inativa') . "\n\n";

        return $prompt;
    }

    /**
     * Build células-specific prompt - simplified to just show link.
     */
    private function buildCelulasPrompt(array $celulas): string
    {
        $prompt = "=== INFORMAÇÕES SOBRE CÉLULAS ===\n\n";
        
        $prompt .= "🏠 SOMOS UMA IGREJA EM CÉLULAS!\n";
        $prompt .= "Células são pequenos grupos que se reúnem semanalmente nas casas para comunhão, oração e estudo da Palavra.\n\n";
        
        $prompt .= "📊 ESTATÍSTICAS:\n";
        $prompt .= "• Total de Células: " . ($celulas['total_celulas'] ?? 'várias') . "\n";
        $prompt .= "• Bairros Atendidos: " . ($celulas['total_bairros'] ?? 'diversos') . "\n";
        $prompt .= "• Dia de Célula: " . ($celulas['dia_celula'] ?? 'Quinta-feira às 19:00') . "\n\n";
        
        $link = $celulas['link_pagina'] ?? 'https://valedabencao.com.br/celulas';
        
        $prompt .= "🔗 PÁGINA INTERATIVA: {$link}\n\n";
        
        $prompt .= "=== INSTRUÇÕES PARA RESPOSTAS SOBRE CÉLULAS ===\n";
        $prompt .= "1. SEMPRE direcione o usuário para a página: {$link}\n";
        $prompt .= "2. Explique que na página há:\n";
        $prompt .= "   - Mapa interativo com localização de todas as células\n";
        $prompt .= "   - Filtros por bairro e geração\n";
        $prompt .= "   - Botão 'Usar minha localização' para encontrar células próximas\n";
        $prompt .= "   - Contato direto via WhatsApp com os líderes\n";
        $prompt .= "   - Botão para traçar rota no Google Maps ou Uber\n";
        $prompt .= "3. NUNCA liste células específicas ou invente dados\n";
        $prompt .= "4. Informe que as células se reúnem às quintas-feiras às 19h\n";
        $prompt .= "5. Convide a pessoa a visitar uma célula\n\n";

        return $prompt;
    }
}
