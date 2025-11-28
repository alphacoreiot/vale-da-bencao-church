@extends('layouts.app')

@section('title', 'Células - Vale da Bênção Church')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('css/celulas.css') }}?v={{ time() }}">
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
            
            <!-- Botão Minha Localização -->
            <button id="btnLocalizacao" class="btn-localizacao">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 5px;">
                    <path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/>
                </svg>
                <span id="btnLocalizacaoText">Usar minha localização</span>
            </button>
            
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
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Dados passados do PHP para JavaScript
    const celulasData = @json($celulasJson);
    const geracoesData = @json($geracoes);
</script>
<script src="{{ asset('js/celulas.js') }}?v={{ time() }}"></script>
@endpush
