@extends('layouts.app')

@section('title', 'Células - Vale da Bênção Church')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('css/celulas.css') }}">
@endpush

@section('content')
<!-- Hero Section - Padrão do Sistema -->
<section class="section-content-area" style="padding: 120px 0 10px 0; background: #000;">
    <div class="container">
        <!-- Título da Seção -->
        <div class="section-header" style="text-align: center; margin-bottom: 0; padding: 0 20px;">
            <div style="margin-bottom: 15px;">
                <lord-icon
                    src="https://cdn.lordicon.com/surcxhka.json"
                    trigger="loop"
                    delay="1500"
                    colors="primary:#d4af37,secondary:#ffffff"
                    style="width:80px;height:80px">
                </lord-icon>
            </div>
            <h1 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Células</h1>
            <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">Somos uma igreja em células! Encontre uma célula perto de você e faça parte dessa família.</p>
        </div>
    </div>
</section>

<!-- Filtros Section -->
<section class="filtros-section">
    <div class="filtros-container">
        <div class="filtros-title">Encontre uma célula</div>
        <div class="filtros-wrapper">
            <!-- Filtro por Geração -->
            <div class="filtro-group">
                <label for="filtroGeracao">Filtrar por Geração</label>
                <select id="filtroGeracao" class="filtro-select">
                    <option value="">Todas as Gerações</option>
                    @foreach($geracoes as $geracao)
                        <option value="{{ $geracao->id }}">{{ $geracao->nome }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro por Bairro -->
            <div class="filtro-group">
                <label for="filtroBairro">Filtrar por Bairro</label>
                <select id="filtroBairro" class="filtro-select">
                    <option value="">Todos os Bairros</option>
                    <!-- Opções serão populadas via JavaScript -->
                </select>
            </div>
            
            <!-- Botão Limpar -->
            <button id="btnLimpar" class="btn-limpar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 5px;">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
                Limpar Filtros
            </button>
        </div>
    </div>
</section>

<!-- Mapa Section -->
<section class="mapa-section">
    <div class="mapa-container">
        <h2 class="section-title">Mapa das Células</h2>
        <p class="section-subtitle">Clique em um pin para ver os detalhes ou selecione um bairro no mapa</p>
        
        <div class="mapa-wrapper">
            <!-- Mapa Leaflet -->
            <div id="mapaCelulas"></div>
            
            <!-- Sidebar com lista de células -->
            <div class="celulas-sidebar">
                <div class="sidebar-header">
                    <h3>Células Encontradas</h3>
                    <p class="sidebar-count">Mostrando <span id="countCelulas">{{ $totalCelulas }}</span> células</p>
                </div>
                
                <div id="listaCelulas">
                    <!-- Lista renderizada via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Legenda Section -->
<section class="legenda-section">
    <div class="legenda-container">
        <div class="legenda-title">Legenda por Geração</div>
        <div class="legenda-grid">
            @php
                $coresGeracoes = [
                    'Água Viva' => '#00BFFF',
                    'Azul Celeste' => '#87CEEB',
                    'B e D' => '#4169E1',
                    'Bege' => '#D2B48C',
                    'Branca' => '#FFFFFF',
                    'Branca e Azul' => '#B0C4DE',
                    'Cinza' => '#808080',
                    'Coral' => '#FF7F50',
                    'Dourada' => '#FFD700',
                    'Gaditas' => '#228B22',
                    'Israel' => '#0000CD',
                    'Jeová Makadech' => '#9932CC',
                    'Laranja' => '#FFA500',
                    'Marrom' => '#8B4513',
                    'Mostarda' => '#FFDB58',
                    'Neon' => '#39FF14',
                    'Ouro' => '#DAA520',
                    'Pink' => '#FF69B4',
                    'Prata' => '#C0C0C0',
                    'Preta' => '#333333',
                    'Preta e Branca' => '#555555',
                    'Resgate' => '#DC143C',
                    'Rosinha' => '#FFB6C1',
                    'Roxa' => '#9370DB',
                    'Verde Bandeira' => '#009739',
                    'Verde e Vinho' => '#556B2F',
                    'Verde Tifanes' => '#00CED1',
                ];
            @endphp
            
            @foreach($geracoes as $geracao)
                @php
                    $cor = $geracao->cor ?? ($coresGeracoes[$geracao->nome] ?? '#D4AF37');
                    $isLight = in_array($geracao->nome, ['Branca', 'Bege', 'Neon', 'Prata', 'Rosinha', 'Mostarda']);
                @endphp
                <div class="legenda-item">
                    <span class="legenda-cor" style="background: {{ $cor }}; {{ $isLight ? 'border: 2px solid #666;' : '' }}"></span>
                    <span class="legenda-nome">{{ $geracao->nome }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Dados passados do PHP para JavaScript
    const celulasData = @json($celulasJson);
    const geracoesData = @json($geracoes);
</script>
<script src="{{ asset('js/celulas.js') }}"></script>
@endpush
