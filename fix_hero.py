#!/usr/bin/env python3

file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Remover o hero section personalizado
old_hero = '''<!-- Hero Section -->
<section class="celulas-hero">
    <div class="celulas-hero-content">
        <h1>🏠 Células</h1>
        <p>Somos uma igreja em células! Encontre uma célula perto de você e faça parte dessa família.</p>
        
        <div class="stats-container">
            <div class="stat-item">
                <span class="stat-number">{{ $geracoes->count() }}</span>
                <span class="stat-label">Gerações</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $totalCelulas }}</span>
                <span class="stat-label">Células</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $geracoes->sum(fn($g) => $g->celulas->pluck('bairro')->unique()->count()) }}</span>
                <span class="stat-label">Bairros</span>
            </div>
        </div>
    </div>
</section>

<!-- Mapa Section -->'''

new_hero = '''<!-- Mapa Section -->'''

content = content.replace(old_hero, new_hero)

# Remover estilos do hero
old_styles = '''    .celulas-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        padding: 120px 20px 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .celulas-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23D4AF37' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .celulas-hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .celulas-hero h1 {
        font-size: 3rem;
        color: #D4AF37;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .celulas-hero p {
        font-size: 1.2rem;
        color: rgba(255,255,255,0.8);
        margin-bottom: 30px;
    }
    
    .stats-container {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 3rem;
        color: #D4AF37;
        font-weight: 700;
        display: block;
    }
    
    .stat-label {
        font-size: 1rem;
        color: rgba(255,255,255,0.7);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Mapa Section */'''

new_styles = '''    /* Mapa Section */'''

content = content.replace(old_styles, new_styles)

# Ajustar padding do mapa section para compensar o header
content = content.replace(
    '''    .mapa-section {
        padding: 60px 20px;
        background: #0d0d0d;
    }''',
    '''    .mapa-section {
        padding: 120px 20px 60px;
        background: #0d0d0d;
    }'''
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Hero removido, usando layout padrão!")
