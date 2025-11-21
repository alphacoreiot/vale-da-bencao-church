<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class HomeSectionsSeeder extends Seeder
{
    public function run(): void
    {
        // Criar seção pai Home
        $home = Section::create([
            'name' => 'Home',
            'slug' => 'home',
            'description' => 'Página inicial do site',
            'icon' => 'fas fa-home',
            'is_active' => true,
            'order' => 0
        ]);

        // Criar subseções de Home
        $subsections = [
            [
                'name' => 'Hero',
                'slug' => 'hero',
                'description' => 'Banner principal da página inicial',
                'icon' => 'fas fa-image',
                'order' => 1
            ],
            [
                'name' => 'Eventos',
                'slug' => 'eventos-home',
                'description' => 'Próximos eventos em destaque',
                'icon' => 'fas fa-calendar-alt',
                'order' => 2
            ],
            [
                'name' => 'Devocional',
                'slug' => 'devocional',
                'description' => 'Mensagem devocional do dia',
                'icon' => 'fas fa-bible',
                'order' => 3
            ],
            [
                'name' => 'Culto Online',
                'slug' => 'culto-online',
                'description' => 'Transmissão ao vivo e gravações',
                'icon' => 'fas fa-video',
                'order' => 4
            ],
            [
                'name' => 'Localização',
                'slug' => 'localizacao',
                'description' => 'Endereço e mapa da igreja',
                'icon' => 'fas fa-map-marker-alt',
                'order' => 5
            ]
        ];

        foreach ($subsections as $subsection) {
            Section::create([
                'parent_id' => $home->id,
                'name' => $subsection['name'],
                'slug' => $subsection['slug'],
                'description' => $subsection['description'],
                'icon' => $subsection['icon'],
                'is_active' => true,
                'order' => $subsection['order']
            ]);
        }
    }
}
