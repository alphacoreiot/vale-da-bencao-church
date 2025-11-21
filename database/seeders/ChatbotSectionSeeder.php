<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class ChatbotSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar ou atualizar seção do chatbot
        Section::updateOrCreate(
            ['slug' => 'chatbot-geral'],
            [
                'name' => 'Chatbot Assistente Virtual',
                'description' => 'Chatbot inteligente que responde sobre devocionais, eventos, informações institucionais e cultos da igreja.',
                'is_active' => true,
                'priority' => 5,
                'display_order' => 0,
                'ai_agent_config' => [
                    'enabled' => true,
                    'prompts' => [
                        'system' => 'Você é o assistente virtual da Igreja Vale da Bênção.',
                        'context' => 'Use as informações do banco de dados para responder sobre devocionais, eventos e institucional.',
                    ],
                ],
            ]
        );

        echo "✅ Seção do chatbot criada com sucesso: chatbot-geral\n";
    }
}

