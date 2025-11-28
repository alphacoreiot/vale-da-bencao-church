# 📋 Especificação: Nova Tela de Células

## 🎯 Objetivo

Recriar a página `/celulas` seguindo o padrão visual do sistema (ex: `/secao/galeria`), com foco em:
- Mapa interativo com pins das células cadastradas
- Filtros dinâmicos entre Bairro e Geração (um filtra o outro)
- Layout limpo e consistente com o design system da igreja

---

## 📊 Fonte de Dados

### Tabela Principal
```
form_celulas_recadastramento
```

### Model Laravel
```php
App\Models\CelulaCadastro
```

### Campos Disponíveis
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | int | ID único |
| `nome_celula` | string | Nome da célula |
| `lider` | string | Nome do(s) líder(es) |
| `geracao_id` | int | FK para tabela `geracoes` |
| `bairro` | string | Bairro da célula |
| `rua` | string | Rua/logradouro |
| `numero` | string | Número |
| `complemento` | string | Complemento |
| `ponto_referencia` | string | Ponto de referência |
| `contato` | string | WhatsApp principal |
| `contato2_nome` | string | Nome contato alternativo |
| `contato2_whatsapp` | string | WhatsApp alternativo |
| `latitude` | float | Coordenada latitude |
| `longitude` | float | Coordenada longitude |
| `status` | enum | 'pendente', 'aprovado', 'rejeitado' |
| `created_at` | datetime | Data de criação |

### Filtro de Status
Exibir apenas células com `status = 'aprovado'`

### Arquivo GeoJSON
```
Camacari.geojson
```
- Localização: `public/geojson/Camacari.geojson`
- Campo do nome do bairro: `nm_bairro`

---

## 🎨 Design System

### Cores Principais
```css
--gold: #D4AF37;
--gold-dark: #B8941F;
--black: #000;
--dark-bg: #0d0d0d;
--card-bg: #1a1a1a;
--text-primary: #fff;
--text-secondary: rgba(255, 255, 255, 0.7);
--text-muted: rgba(255, 255, 255, 0.5);
```

### Padrões Visuais
- **Background**: Gradiente escuro ou sólido `#000`
- **Cards**: Background `#1a1a1a` com borda sutil dourada
- **Botões**: Gradiente dourado com texto preto
- **Ícones**: Lord Icons ou SVGs dourados
- **Border Radius**: 15px para cards, 20-25px para botões
- **Sombras**: `box-shadow: 0 8px 30px rgba(212, 175, 55, 0.3)`

### Tipografia
- **Títulos**: Font-weight 700, cor dourada
- **Subtítulos**: Font-weight 400, cor branca com opacidade
- **Labels**: Uppercase, letter-spacing 1px, fundo dourado

---

## 📐 Layout da Página

### Estrutura Geral
```
┌─────────────────────────────────────────────────────────────────┐
│                         HEADER (fixo)                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│   ┌─────────────────────────────────────────────────────────┐   │
│   │                    HERO SECTION                          │   │
│   │   🏠 Células                                             │   │
│   │   "Encontre uma célula perto de você"                   │   │
│   │                                                          │   │
│   │   [XX Gerações] [XX Células] [XX Bairros]               │   │
│   └─────────────────────────────────────────────────────────┘   │
│                                                                  │
│   ┌─────────────────────────────────────────────────────────┐   │
│   │                  SEÇÃO DE FILTROS                        │   │
│   │                                                          │   │
│   │  [Dropdown Geração ▼]    [Dropdown Bairro ▼]            │   │
│   │                                                          │   │
│   │  [Limpar Filtros]                                        │   │
│   └─────────────────────────────────────────────────────────┘   │
│                                                                  │
│   ┌─────────────────────────────────────────────────────────┐   │
│   │                      MAPA SECTION                        │   │
│   │                                                          │   │
│   │  ┌──────────────────────────┐  ┌─────────────────────┐  │   │
│   │  │                          │  │  SIDEBAR             │  │   │
│   │  │     MAPA LEAFLET         │  │                      │  │   │
│   │  │     - GeoJSON bairros    │  │  Células filtradas:  │  │   │
│   │  │     - Pins das células   │  │  ┌─────────────────┐ │  │   │
│   │  │                          │  │  │ Card Célula 1   │ │  │   │
│   │  │                          │  │  └─────────────────┘ │  │   │
│   │  │                          │  │  ┌─────────────────┐ │  │   │
│   │  │                          │  │  │ Card Célula 2   │ │  │   │
│   │  └──────────────────────────┘  │  └─────────────────┘ │  │   │
│   │                                 └─────────────────────┘  │   │
│   └─────────────────────────────────────────────────────────┘   │
│                                                                  │
│   ┌─────────────────────────────────────────────────────────┐   │
│   │                   LEGENDA DO MAPA                        │   │
│   │                                                          │   │
│   │  [●] Geração X  [●] Geração Y  [●] Geração Z ...        │   │
│   └─────────────────────────────────────────────────────────┘   │
│                                                                  │
├─────────────────────────────────────────────────────────────────┤
│                         FOOTER                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ Componentes Detalhados

### 1. Hero Section
```html
<section class="celulas-hero">
    <div class="hero-content">
        <!-- Ícone (lord-icon ou emoji) -->
        <lord-icon src="..." trigger="loop" colors="primary:#d4af37"></lord-icon>
        
        <h1>🏠 Células</h1>
        <p>Encontre uma célula perto de você e faça parte dessa família.</p>
        
        <!-- Stats -->
        <div class="stats-row">
            <div class="stat">
                <span class="number">{{ totalGeracoes }}</span>
                <span class="label">Gerações</span>
            </div>
            <div class="stat">
                <span class="number">{{ totalCelulas }}</span>
                <span class="label">Células</span>
            </div>
            <div class="stat">
                <span class="number">{{ totalBairros }}</span>
                <span class="label">Bairros</span>
            </div>
        </div>
    </div>
</section>
```

### 2. Seção de Filtros
```html
<section class="filtros-section">
    <div class="container">
        <div class="filtros-wrapper">
            <!-- Dropdown Geração -->
            <div class="filtro-group">
                <label>Filtrar por Geração</label>
                <select id="filtroGeracao">
                    <option value="">Todas as Gerações</option>
                    @foreach($geracoes as $geracao)
                        <option value="{{ $geracao->id }}" 
                                data-cor="{{ $geracao->cor }}">
                            {{ $geracao->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Dropdown Bairro -->
            <div class="filtro-group">
                <label>Filtrar por Bairro</label>
                <select id="filtroBairro">
                    <option value="">Todos os Bairros</option>
                    <!-- Opções populadas dinamicamente -->
                </select>
            </div>
            
            <!-- Botão Limpar -->
            <button id="limparFiltros" class="btn-limpar">
                Limpar Filtros
            </button>
        </div>
    </div>
</section>
```

### 3. Mapa Section
```html
<section class="mapa-section">
    <div class="container">
        <h2 class="section-title">Mapa das Células</h2>
        <p class="section-subtitle">Clique em um pin para ver os detalhes</p>
        
        <div class="mapa-layout">
            <!-- Mapa -->
            <div id="mapaCelulas"></div>
            
            <!-- Sidebar com lista -->
            <div class="celulas-sidebar">
                <h3>Células Encontradas</h3>
                <p class="count">Mostrando <span id="countCelulas">0</span> células</p>
                
                <div id="listaCelulas">
                    <!-- Cards renderizados via JS -->
                </div>
            </div>
        </div>
    </div>
</section>
```

### 4. Card de Célula (Sidebar)
```html
<div class="celula-card" data-geracao="{{ geracao_id }}" data-bairro="{{ bairro }}">
    <div class="card-header" style="border-left-color: {{ cor_geracao }}">
        <span class="geracao-badge" style="background: {{ cor_geracao }}">
            {{ nome_geracao }}
        </span>
        <h4 class="celula-nome">{{ nome_celula }}</h4>
    </div>
    
    <div class="card-body">
        <p class="lider">
            <svg><!-- ícone pessoa --></svg>
            {{ lider }}
        </p>
        <p class="endereco">
            <svg><!-- ícone localização --></svg>
            {{ bairro }} - {{ rua }}, {{ numero }}
        </p>
        @if(ponto_referencia)
        <p class="referencia">
            <svg><!-- ícone referência --></svg>
            {{ ponto_referencia }}
        </p>
        @endif
    </div>
    
    <div class="card-footer">
        @if(contato)
        <a href="https://wa.me/55{{ contato }}" class="btn-whatsapp">
            <svg><!-- ícone whatsapp --></svg>
            WhatsApp
        </a>
        @endif
        <button class="btn-ver-mapa" onclick="centralizarMapa(lat, lng)">
            Ver no Mapa
        </button>
    </div>
</div>
```

### 5. Legenda do Mapa
```html
<div class="legenda-mapa">
    <h4>Legenda por Geração</h4>
    <div class="legenda-items">
        @foreach($geracoes as $geracao)
        <div class="legenda-item">
            <span class="legenda-cor" style="background: {{ $geracao->cor ?? getCorPorNome($geracao->nome) }}"></span>
            <span class="legenda-nome">{{ $geracao->nome }}</span>
        </div>
        @endforeach
    </div>
</div>
```

---

## 🔄 Lógica dos Filtros

### Comportamento Interdependente

1. **Ao selecionar uma Geração:**
   - Filtrar o dropdown de Bairros para mostrar apenas bairros que têm células dessa geração
   - Atualizar pins no mapa (mostrar apenas dessa geração)
   - Atualizar lista na sidebar

2. **Ao selecionar um Bairro:**
   - Filtrar o dropdown de Gerações para mostrar apenas gerações presentes nesse bairro
   - Atualizar pins no mapa (mostrar apenas desse bairro)
   - Atualizar lista na sidebar
   - Centralizar mapa no bairro selecionado (usar polígono GeoJSON)

3. **Ao limpar filtros:**
   - Restaurar todas as opções nos dropdowns
   - Mostrar todos os pins
   - Restaurar zoom e posição inicial do mapa

### Pseudo-código JavaScript
```javascript
// Estado global
let filtros = {
    geracao: null,
    bairro: null
};

// Dados carregados do PHP
const celulasData = @json($celulasJson);
const geracoesData = @json($geracoesJson);

// Ao mudar filtro de geração
document.getElementById('filtroGeracao').addEventListener('change', function() {
    filtros.geracao = this.value || null;
    
    // Atualizar opções de bairro
    atualizarOpcoesBairro();
    
    // Aplicar filtros
    aplicarFiltros();
});

// Ao mudar filtro de bairro
document.getElementById('filtroBairro').addEventListener('change', function() {
    filtros.bairro = this.value || null;
    
    // Atualizar opções de geração
    atualizarOpcoesGeracao();
    
    // Aplicar filtros
    aplicarFiltros();
    
    // Centralizar no bairro se selecionado
    if (filtros.bairro) {
        centralizarNoBairro(filtros.bairro);
    }
});

function aplicarFiltros() {
    const celulasFiltradas = celulasData.filter(celula => {
        const matchGeracao = !filtros.geracao || celula.geracao_id == filtros.geracao;
        const matchBairro = !filtros.bairro || celula.bairro === filtros.bairro;
        return matchGeracao && matchBairro;
    });
    
    // Atualizar mapa
    atualizarMarcadores(celulasFiltradas);
    
    // Atualizar sidebar
    renderizarListaCelulas(celulasFiltradas);
    
    // Atualizar contador
    document.getElementById('countCelulas').textContent = celulasFiltradas.length;
}

function atualizarOpcoesBairro() {
    const bairrosDisponiveis = new Set();
    
    celulasData.forEach(celula => {
        if (!filtros.geracao || celula.geracao_id == filtros.geracao) {
            bairrosDisponiveis.add(celula.bairro);
        }
    });
    
    const selectBairro = document.getElementById('filtroBairro');
    selectBairro.innerHTML = '<option value="">Todos os Bairros</option>';
    
    [...bairrosDisponiveis].sort().forEach(bairro => {
        const option = document.createElement('option');
        option.value = bairro;
        option.textContent = bairro;
        selectBairro.appendChild(option);
    });
}

function atualizarOpcoesGeracao() {
    const geracoesDisponiveis = new Set();
    
    celulasData.forEach(celula => {
        if (!filtros.bairro || celula.bairro === filtros.bairro) {
            geracoesDisponiveis.add(celula.geracao_id);
        }
    });
    
    const selectGeracao = document.getElementById('filtroGeracao');
    const opcoes = selectGeracao.querySelectorAll('option');
    
    opcoes.forEach(option => {
        if (option.value) {
            option.disabled = !geracoesDisponiveis.has(parseInt(option.value));
        }
    });
}
```

---

## 🗺️ Configuração do Mapa

### Inicialização Leaflet
```javascript
// Centro em Camaçari
const map = L.map('mapaCelulas').setView([-12.70, -38.33], 12);

// Tile layer escuro (tema dark)
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap &copy; CARTO',
    subdomains: 'abcd',
    maxZoom: 19
}).addTo(map);
```

### Marcadores por Cor de Geração
```javascript
// Mapeamento de cores por nome de geração (quando cor não está no banco)
const coresGeracoes = {
    'Água Viva': '#00BFFF',
    'Azul Celeste': '#87CEEB',
    'B e D': '#4169E1',
    'Bege': '#D2B48C',
    'Branca': '#FFFFFF',
    'Branca e Azul': '#B0C4DE',
    'Cinza': '#808080',
    'Coral': '#FF7F50',
    'Dourada': '#FFD700',
    'Gaditas': '#228B22',
    'Israel': '#0000CD',
    'Jeová Makadech': '#9932CC',
    'Laranja': '#FFA500',
    'Marrom': '#8B4513',
    'Mostarda': '#FFDB58',
    'Neon': '#39FF14',
    'Ouro': '#DAA520',
    'Pink': '#FF69B4',
    'Prata': '#C0C0C0',
    'Preta': '#1a1a1a',
    'Preta e Branca': '#444444',
    'Resgate': '#DC143C',
    'Rosinha': '#FFB6C1',
    'Roxa': '#9370DB',
    'Verde Bandeira': '#009739',
    'Verde e Vinho': '#556B2F',
    'Verde Tifanes': '#00CED1',
};

// Criar ícone customizado por cor
function criarIconeCelula(cor) {
    return L.divIcon({
        className: 'celula-marker',
        html: `<div style="
            background: ${cor};
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.5);
        "></div>`,
        iconSize: [26, 26],
        iconAnchor: [13, 13]
    });
}
```

### Popup do Marcador
```javascript
marker.bindPopup(`
    <div class="popup-celula">
        <div class="popup-header" style="border-bottom: 2px solid ${cor}">
            <span class="popup-geracao" style="background: ${cor}">${geracao}</span>
            <h4>${nome_celula || 'Célula'}</h4>
        </div>
        <div class="popup-body">
            <p><strong>Líder:</strong> ${lider}</p>
            <p><strong>Bairro:</strong> ${bairro}</p>
            <p><strong>Endereço:</strong> ${endereco}</p>
            ${ponto_referencia ? `<p><strong>Referência:</strong> ${ponto_referencia}</p>` : ''}
        </div>
        <div class="popup-footer">
            ${whatsapp_link ? `
                <a href="${whatsapp_link}" target="_blank" class="popup-whatsapp">
                    WhatsApp
                </a>
            ` : ''}
        </div>
    </div>
`);
```

### GeoJSON dos Bairros
```javascript
// Carregar GeoJSON
fetch('/geojson/Camacari.geojson')
    .then(response => response.json())
    .then(data => {
        geojsonLayer = L.geoJSON(data, {
            style: function(feature) {
                const temCelulas = verificarCelulasNoBairro(feature.properties.nm_bairro);
                return {
                    fillColor: temCelulas ? 'rgba(212, 175, 55, 0.3)' : 'rgba(50, 50, 50, 0.2)',
                    weight: 1,
                    opacity: 0.8,
                    color: temCelulas ? '#D4AF37' : '#444',
                    fillOpacity: 0.3
                };
            },
            onEachFeature: function(feature, layer) {
                layer.on('click', function() {
                    // Selecionar bairro no filtro
                    const bairroNome = feature.properties.nm_bairro;
                    document.getElementById('filtroBairro').value = bairroNome;
                    filtros.bairro = bairroNome;
                    aplicarFiltros();
                });
            }
        }).addTo(map);
    });
```

---

## 📱 Responsividade

### Breakpoints
```css
/* Desktop */
@media (min-width: 1024px) {
    .mapa-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }
}

/* Tablet */
@media (max-width: 1023px) {
    .mapa-layout {
        display: flex;
        flex-direction: column;
    }
    
    #mapaCelulas {
        height: 400px;
    }
    
    .celulas-sidebar {
        max-height: 500px;
    }
}

/* Mobile */
@media (max-width: 768px) {
    .filtros-wrapper {
        flex-direction: column;
    }
    
    .stats-row {
        flex-direction: column;
        gap: 20px;
    }
    
    #mapaCelulas {
        height: 300px;
    }
}
```

---

## 📁 Arquivos a Criar/Modificar

### Arquivos Novos
| Arquivo | Descrição |
|---------|-----------|
| `resources/views/frontend/celulas-v2.blade.php` | Nova view da página |
| `public/css/celulas.css` | Estilos específicos da página |
| `public/js/celulas.js` | JavaScript da página |

### Arquivos a Modificar
| Arquivo | Modificação |
|---------|-------------|
| `app/Http/Controllers/Frontend/CelulasController.php` | Ajustar dados retornados |
| `routes/web.php` | Manter rota existente |

---

## ✅ Checklist de Implementação

- [ ] Criar estrutura HTML base seguindo padrão section.blade.php
- [ ] Implementar Hero Section com estatísticas
- [ ] Criar seção de filtros com dropdowns estilizados
- [ ] Configurar mapa Leaflet com GeoJSON
- [ ] Adicionar marcadores coloridos por geração
- [ ] Implementar sidebar com lista de células
- [ ] Criar lógica de filtros interdependentes
- [ ] Adicionar legenda do mapa
- [ ] Implementar responsividade mobile
- [ ] Testar integração com dados reais
- [ ] Fazer deploy no servidor

---

## 🔗 Referências

- **Página de Galeria**: https://valedabencao.com.br/secao/galeria
- **Leaflet.js**: https://leafletjs.com/
- **Carto Dark Tiles**: https://carto.com/basemaps/

---

## 📝 Notas Adicionais

1. **Performance**: Usar clustering de marcadores se houver muitas células próximas
2. **SEO**: Manter meta tags e estrutura semântica
3. **Acessibilidade**: Garantir navegação por teclado nos filtros
4. **Cache**: Considerar cache do GeoJSON e dados de células
5. **Analytics**: Rastrear cliques nos botões de WhatsApp

---

*Documento criado em: 28/11/2024*
*Versão: 1.0*
