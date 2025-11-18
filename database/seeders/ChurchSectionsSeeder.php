<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\SectionContent;
use App\Models\RotationConfig;
use Illuminate\Database\Seeder;

class ChurchSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default rotation configuration
        RotationConfig::create([
            'rotation_type' => 'priority',
            'interval_minutes' => 60,
            'is_active' => true,
        ]);

        // Create church sections
        $sections = [
            [
                'name' => 'Boas-Vindas',
                'slug' => 'boas-vindas',
                'description' => 'Apresentação da igreja e mensagens de boas-vindas',
                'priority' => 10,
                'display_order' => 1,
                'ai_config' => [
                    'enabled' => true,
                    'name' => 'Assistente de Boas-Vindas',
                    'personality' => 'caloroso, acolhedor e informativo',
                    'prompts' => [
                        'system' => 'Você é um assistente virtual da igreja que dá boas-vindas aos visitantes.',
                        'context' => 'Apresente a igreja, seus valores e como os visitantes podem participar.',
                    ],
                ],
                'content' => [
                    'title' => 'Bem-vindo à Igreja Vale da Bênção',
                    'content' => '<p>Seja bem-vindo! Somos uma comunidade de fé que busca viver o evangelho de Cristo com amor e dedicação. Nossa igreja está de portas abertas para você e sua família.</p><p>Venha nos conhecer e fazer parte desta família!</p>',
                ],
            ],
            [
                'name' => 'Eventos e Programação',
                'slug' => 'eventos',
                'description' => 'Calendário de eventos, cultos e programações especiais',
                'priority' => 9,
                'display_order' => 2,
                'ai_config' => [
                    'enabled' => true,
                    'name' => 'Assistente de Eventos',
                    'personality' => 'organizado, informativo e prestativo',
                    'prompts' => [
                        'system' => 'Você é um assistente que ajuda com informações sobre eventos e programações da igreja.',
                        'context' => 'Forneça informações sobre horários de cultos, eventos especiais e como participar.',
                    ],
                ],
                'content' => [
                    'title' => 'Programação Semanal',
                    'content' => '<h3>Cultos Semanais:</h3><ul><li>Domingo - 9h e 19h</li><li>Quarta-feira - 20h (Estudo Bíblico)</li><li>Sexta-feira - 20h (Culto de Libertação)</li></ul>',
                ],
            ],
            [
                'name' => 'Ministérios',
                'slug' => 'ministerios',
                'description' => 'Ministérios disponíveis para participação',
                'priority' => 7,
                'display_order' => 3,
                'ai_config' => [
                    'enabled' => true,
                    'name' => 'Assistente de Ministérios',
                    'personality' => 'incentivador e informativo',
                    'prompts' => [
                        'system' => 'Você ajuda as pessoas a encontrarem o ministério ideal para servir.',
                        'context' => 'Apresente os ministérios disponíveis e como participar.',
                    ],
                ],
                'content' => [
                    'title' => 'Nossos Ministérios',
                    'content' => '<p>Temos diversos ministérios onde você pode servir e desenvolver seus dons:</p><ul><li>Louvor e Adoração</li><li>Infantil</li><li>Jovens</li><li>Evangelismo</li><li>Intercessão</li></ul>',
                ],
            ],
            [
                'name' => 'Estudos Bíblicos',
                'slug' => 'estudos',
                'description' => 'Estudos bíblicos e materiais de ensino',
                'priority' => 8,
                'display_order' => 4,
                'ai_config' => [
                    'enabled' => true,
                    'name' => 'Assistente de Estudos',
                    'personality' => 'sábio, paciente e didático',
                    'prompts' => [
                        'system' => 'Você ajuda com estudos bíblicos e responde dúvidas sobre a Bíblia.',
                        'context' => 'Forneça orientações sobre estudos disponíveis e ajude com dúvidas bíblicas.',
                    ],
                ],
                'content' => [
                    'title' => 'Estudos Bíblicos',
                    'content' => '<p>Oferecemos diversos estudos bíblicos para crescimento espiritual.</p>',
                ],
            ],
            [
                'name' => 'Galeria',
                'slug' => 'galeria',
                'description' => 'Fotos e vídeos de eventos',
                'priority' => 5,
                'display_order' => 5,
                'ai_config' => [
                    'enabled' => false,
                ],
                'content' => [
                    'title' => 'Galeria de Fotos e Vídeos',
                    'content' => '<p>Confira os momentos especiais da nossa igreja.</p>',
                ],
            ],
            [
                'name' => 'Testemunhos',
                'slug' => 'testemunhos',
                'description' => 'Testemunhos de fé e transformação',
                'priority' => 6,
                'display_order' => 6,
                'ai_config' => [
                    'enabled' => false,
                ],
                'content' => [
                    'title' => 'Testemunhos',
                    'content' => '<p>Veja como Deus tem transformado vidas em nossa igreja.</p>',
                ],
            ],
            [
                'name' => 'Contato',
                'slug' => 'contato',
                'description' => 'Informações de contato e localização',
                'priority' => 4,
                'display_order' => 7,
                'ai_config' => [
                    'enabled' => true,
                    'name' => 'Assistente de Contato',
                    'personality' => 'prestativo e eficiente',
                    'prompts' => [
                        'system' => 'Você fornece informações de contato e localização da igreja.',
                        'context' => 'Ajude com endereço, telefone, email e como chegar.',
                    ],
                ],
                'content' => [
                    'title' => 'Entre em Contato',
                    'content' => '<p>Estamos à disposição para atendê-lo!</p>',
                ],
            ],
        ];

        foreach ($sections as $sectionData) {
            $content = $sectionData['content'];
            $aiConfig = $sectionData['ai_config'];
            unset($sectionData['content'], $sectionData['ai_config']);

            $section = Section::create(array_merge($sectionData, [
                'is_active' => true,
                'highlight_duration' => 60,
                'ai_agent_config' => $aiConfig,
            ]));

            // Create initial content for each section
            SectionContent::create([
                'section_id' => $section->id,
                'title' => $content['title'],
                'content' => $content['content'],
                'type' => 'text',
                'is_published' => true,
                'published_at' => now(),
            ]);
        }

        $this->command->info('Church sections seeded successfully!');
    }
}
