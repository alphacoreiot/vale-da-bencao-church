<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\AIAgentService;
use App\Services\ContextBuilderService;
use App\Models\Section;

$section = Section::where('slug', 'chatbot-geral')->first();

if (!$section) {
    die("Seção não encontrada\n");
}

$contextBuilder = new ContextBuilderService();
$aiService = new AIAgentService($contextBuilder);

try {
    $result = $aiService->chat(
        $section,
        'Resuma o devocional de hoje',
        'test-session-' . time(),
        true
    );
    
    echo "✅ Sucesso!\n\n";
    echo "Resposta: " . $result['message'] . "\n";
} catch (\Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
