<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;
use Illuminate\Support\Str;

class EventosSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Section::updateOrCreate(
            ['slug' => 'eventos'],
            [
                'name' => 'Eventos',
                'description' => 'Seção para gerenciar eventos e notícias da igreja exibidos no carrossel Vale News',
                'is_active' => true,
                'priority' => 1,
                'display_order' => 1,
                'highlight_duration' => 60,
            ]
        );

        $this->command->info('Seção Eventos criada/atualizada com sucesso!');
    }
}
