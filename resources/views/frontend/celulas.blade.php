@extends('layouts.app')

@section('title', 'Células - Vale da Benção Church')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .celulas-hero {
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
    
    /* Mapa Section */
    .mapa-section {
        padding: 60px 20px;
        background: #0d0d0d;
    }
    
    .mapa-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .section-title {
        text-align: center;
        color: #D4AF37;
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .section-subtitle {
        text-align: center;
        color: rgba(255,255,255,0.6);
        margin-bottom: 40px;
    }
    
    .mapa-wrapper {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
        align-items: start;
    }
    
    @media (max-width: 1024px) {
        .mapa-wrapper {
            grid-template-columns: 1fr;
        }
    }
    
    #mapaCamacari {
        height: 600px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    
    .celulas-sidebar {
        background: #1a1a1a;
        border-radius: 15px;
        padding: 20px;
        max-height: 600px;
        overflow-y: auto;
    }
    
    .celulas-sidebar h3 {
        color: #D4AF37;
        margin-bottom: 15px;
        font-size: 1.2rem;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(212, 175, 55, 0.3);
    }
    
    .sidebar-info {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        margin-bottom: 15px;
    }
    
    .bairro-selecionado {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(212, 175, 55, 0.1));
        border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        display: none;
    }
    
    .bairro-selecionado.active {
        display: block;
    }
    
    .bairro-selecionado h4 {
        color: #D4AF37;
        margin-bottom: 10px;
    }
    
    .celula-card-sidebar {
        background: rgba(255,255,255,0.05);
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        border-left: 3px solid #D4AF37;
    }
    
    .celula-card-sidebar .nome-celula {
        color: #D4AF37;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 5px;
    }
    
    .celula-card-sidebar .lider {
        color: #fff;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .celula-card-sidebar .geracao {
        color: #D4AF37;
        font-size: 0.8rem;
        margin-bottom: 5px;
    }
    
    .celula-card-sidebar .endereco {
        color: rgba(255,255,255,0.6);
        font-size: 0.85rem;
    }
    
    .celula-card-sidebar .whatsapp-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #25D366;
        color: #fff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        text-decoration: none;
        margin-top: 8px;
        transition: all 0.3s;
    }
    
    .celula-card-sidebar .whatsapp-btn:hover {
        background: #128C7E;
        transform: scale(1.05);
    }

    /* Marcador de célula no mapa */
    .celula-marker {
        background: transparent !important;
        border: none !important;
    }
    
    /* Gerações Section */
    .geracoes-section {
        padding: 60px 20px;
        background: linear-gradient(180deg, #0d0d0d 0%, #1a1a1a 100%);
    }
    
    .geracoes-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .geracoes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }
    
    .geracao-card {
        background: linear-gradient(145deg, #1e1e1e, #2a2a2a);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        transition: all 0.3s;
    }
    
    .geracao-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
    }
    
    .geracao-header {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.3), rgba(212, 175, 55, 0.1));
        padding: 20px;
        border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    }
    
    .geracao-nome {
        color: #D4AF37;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .geracao-responsaveis {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
    }
    
    .geracao-responsaveis svg {
        width: 16px;
        height: 16px;
        margin-right: 5px;
        vertical-align: middle;
        fill: #D4AF37;
    }
    
    .celulas-list {
        padding: 15px 20px;
    }
    
    .celula-item {
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .celula-item:last-child {
        border-bottom: none;
    }
    
    .celula-lider {
        color: #fff;
        font-weight: 500;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .celula-lider svg {
        width: 18px;
        height: 18px;
        fill: #D4AF37;
    }
    
    .celula-bairro {
        color: rgba(255,255,255,0.6);
        font-size: 0.85rem;
        margin-left: 26px;
    }
    
    .celula-bairro svg {
        width: 14px;
        height: 14px;
        fill: rgba(255,255,255,0.4);
        margin-right: 5px;
        vertical-align: middle;
    }
    
    .celula-contato {
        margin-left: 26px;
        margin-top: 8px;
    }
    
    .celula-contato a {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #25D366;
        color: #fff;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .celula-contato a:hover {
        background: #128C7E;
    }
    
    .celula-contato svg {
        width: 14px;
        height: 14px;
        fill: #fff;
    }
    
    /* Leaflet custom styles */
    .leaflet-popup-content-wrapper {
        background: #1a1a1a;
        color: #fff;
        border-radius: 10px;
    }
    
    .leaflet-popup-tip {
        background: #1a1a1a;
    }
    
    .leaflet-popup-content {
        margin: 15px;
    }
    
    .popup-title {
        color: #D4AF37;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .popup-celulas-count {
        color: rgba(255,255,255,0.8);
        font-size: 0.9rem;
    }
    
    /* Scrollbar */
    .celulas-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .celulas-sidebar::-webkit-scrollbar-track {
        background: #1a1a1a;
    }
    
    .celulas-sidebar::-webkit-scrollbar-thumb {
        background: #D4AF37;
        border-radius: 3px;
    }
    
    /* Filter */
    .filter-container {
        margin-bottom: 20px;
    }
    
    .filter-input {
        width: 100%;
        padding: 12px 15px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 8px;
        color: #fff;
        font-size: 1rem;
    }
    
    .filter-input::placeholder {
        color: rgba(255,255,255,0.5);
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #D4AF37;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
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
                <span class="stat-number">{{ $totalBairros }}</span>
                <span class="stat-label">Bairros</span>
            </div>
        </div>
    </div>
</section>

<!-- Mapa Section -->
<section class="mapa-section">
    <div class="mapa-container">
        <h2 class="section-title">Mapa das Células</h2>
        <p class="section-subtitle">Clique em um bairro para ver as células disponíveis</p>
        
        <div class="mapa-wrapper">
            <div id="mapaCamacari"></div>
            
            <div class="celulas-sidebar">
                <h3>Células no Bairro</h3>
                <p class="sidebar-info">Selecione um bairro no mapa para ver as células disponíveis.</p>
                
                <div class="bairro-selecionado" id="bairroInfo">
                    <h4 id="bairroNome">-</h4>
                    <div id="celulasBairro"></div>
                </div>
                
                <div class="filter-container">
                    <input type="text" class="filter-input" id="filterBairro" placeholder="🔍 Buscar por bairro ou líder...">
                </div>
                
                <div id="todasCelulas">
                    <!-- Células serão inseridas via JS -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gerações Section -->
<section class="geracoes-section">
    <div class="geracoes-container">
        <h2 class="section-title">Nossas Gerações</h2>
        <p class="section-subtitle">Cada geração é liderada por um casal ou líder responsável</p>
        
        <div class="geracoes-grid">
            @foreach($geracoes as $geracao)
            <div class="geracao-card">
                <div class="geracao-header">
                    <div class="geracao-nome">{{ $geracao->nome }}</div>
                    @if($geracao->responsaveis)
                    <div class="geracao-responsaveis">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        {{ $geracao->responsaveis }}
                    </div>
                    @endif
                </div>
                <div class="celulas-list">
                    @foreach($geracao->celulas as $celula)
                    <div class="celula-item" data-bairro="{{ $celula->bairro }}" data-lider="{{ $celula->lider }}">
                        <div class="celula-lider">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            {{ $celula->lider }}
                        </div>
                        @if($celula->bairro)
                        <div class="celula-bairro">
                            <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            {{ $celula->bairro }}
                            @if($celula->ponto_referencia)
                                - {{ $celula->ponto_referencia }}
                            @endif
                        </div>
                        @endif
                        @if($celula->contato)
                        <div class="celula-contato">
                            @php
                                $contato = explode('/', $celula->contato)[0];
                                $contato = preg_replace('/[^0-9]/', '', $contato);
                                if(strlen($contato) == 11) $contato = '55' . $contato;
                            @endphp
                            <a href="https://wa.me/{{ $contato }}" target="_blank">
                                <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados das células (vindos do formulário de cadastro - apenas aprovadas)
    const celulasData = @json($celulasJson);
    
    // Mapeamento de bairros
    const mapeamentoBairros = @json($mapeamentoBairros);
    
    // Inverter mapeamento para lookup
    const bairroGeoToCelula = {};
    Object.entries(mapeamentoBairros).forEach(([celulaBairro, geoBairro]) => {
        if (!bairroGeoToCelula[geoBairro]) {
            bairroGeoToCelula[geoBairro] = [];
        }
        bairroGeoToCelula[geoBairro].push(celulaBairro);
    });
    
    // Inicializar mapa
    const map = L.map('mapaCamacari').setView([-12.70, -38.33], 11);
    
    // Tile layer escuro
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);
    
    // Variáveis para controle
    let geojsonLayer = null;
    let selectedFeature = null;
    let markersLayer = L.layerGroup().addTo(map);
    
    // Ícone personalizado para células
    const celulaIcon = L.divIcon({
        className: 'celula-marker',
        html: '<div style="background: #D4AF37; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.5);"></div>',
        iconSize: [16, 16],
        iconAnchor: [8, 8]
    });
    
    // Adicionar marcadores para células com coordenadas
    celulasData.forEach(celula => {
        if (celula.tem_coordenadas && celula.latitude && celula.longitude) {
            const marker = L.marker([celula.latitude, celula.longitude], { icon: celulaIcon })
                .addTo(markersLayer);
            
            marker.bindPopup(`
                <div style="min-width: 200px;">
                    <strong style="color: #D4AF37; font-size: 1.1em;">${celula.nome || 'Célula'}</strong><br>
                    <strong>Líder:</strong> ${celula.lider}<br>
                    <strong>Geração:</strong> ${celula.geracao}<br>
                    <strong>Bairro:</strong> ${celula.bairro}<br>
                    ${celula.endereco ? `<strong>Endereço:</strong> ${celula.endereco}<br>` : ''}
                    ${celula.ponto_referencia ? `<strong>Referência:</strong> ${celula.ponto_referencia}<br>` : ''}
                    ${celula.whatsapp_link ? `<a href="${celula.whatsapp_link}" target="_blank" style="display: inline-block; background: #25D366; color: #fff; padding: 5px 10px; border-radius: 15px; margin-top: 8px; text-decoration: none; font-size: 0.85em;"><i class="fab fa-whatsapp"></i> WhatsApp</a>` : ''}
                </div>
            `);
        }
    });
    
    // Função para contar células por bairro geo
    function contarCelulasPorBairroGeo(bairroGeo) {
        let count = 0;
        const geoNorm = bairroGeo.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        
        celulasData.forEach(celula => {
            if (celula.bairro) {
                const celulaBairroNorm = celula.bairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                if (celulaBairroNorm === geoNorm || 
                    celulaBairroNorm.includes(geoNorm) || 
                    geoNorm.includes(celulaBairroNorm)) {
                    count++;
                }
            }
        });
        
        return count;
    }
    
    // Função para encontrar células por bairro geo
    function encontrarCelulasPorBairroGeo(bairroGeo) {
        const celulasEncontradas = [];
        const geoNorm = bairroGeo.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        
        celulasData.forEach(celula => {
            if (celula.bairro) {
                const celulaBairroNorm = celula.bairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                if (celulaBairroNorm === geoNorm || 
                    celulaBairroNorm.includes(geoNorm) || 
                    geoNorm.includes(celulaBairroNorm)) {
                    celulasEncontradas.push(celula);
                }
            }
        });
        
        return celulasEncontradas;
    }
        
        return celulasEncontradas;
    }
    
    // Carregar GeoJSON
    fetch('/geojson/Camacari.geojson?v=' + Date.now())
        .then(response => response.json())
        .then(data => {
            console.log('GeoJSON carregado - Total de bairros:', data.features.length);
            geojsonLayer = L.geoJSON(data, {
                style: function(feature) {
                    // Todos os bairros com o mesmo estilo dourado forte
                    return {
                        fillColor: '#D4AF37',
                        weight: 1,
                        opacity: 1,
                        color: '#D4AF37',
                        fillOpacity: 0.6
                    };
                },
                onEachFeature: function(feature, layer) {
                    const bairroGeo = feature.properties.nm_bairro;
                    const celulasCount = contarCelulasPorBairroGeo(bairroGeo);
                    
                    layer.bindPopup(`
                        <div class="popup-title">${feature.properties.Name || bairroGeo}</div>
                        <div class="popup-celulas-count">${celulasCount} célula(s) neste bairro</div>
                    `);
                    
                    layer.on({
                        mouseover: function(e) {
                            const layer = e.target;
                            layer.setStyle({
                                weight: 3,
                                fillOpacity: 0.6
                            });
                        },
                        mouseout: function(e) {
                            if (selectedFeature !== e.target) {
                                geojsonLayer.resetStyle(e.target);
                            }
                        },
                        click: function(e) {
                            if (selectedFeature) {
                                geojsonLayer.resetStyle(selectedFeature);
                            }
                            selectedFeature = e.target;
                            
                            const bairroGeo = feature.properties.nm_bairro;
                            const celulas = encontrarCelulasPorBairroGeo(bairroGeo);
                            
                            // Atualizar sidebar
                            const bairroInfo = document.getElementById('bairroInfo');
                            const bairroNome = document.getElementById('bairroNome');
                            const celulasBairro = document.getElementById('celulasBairro');
                            
                            bairroNome.textContent = feature.properties.Name || bairroGeo;
                            
                            if (celulas.length > 0) {
                                bairroInfo.classList.add('active');
                                celulasBairro.innerHTML = celulas.map(c => `
                                    <div class="celula-card-sidebar">
                                        ${c.nome ? `<div class="nome-celula">${c.nome}</div>` : ''}
                                        <div class="lider">${c.lider}</div>
                                        <div class="geracao">${c.geracao}</div>
                                        <div class="endereco">${c.endereco || c.ponto_referencia || c.bairro}</div>
                                        ${c.whatsapp_link ? `<a href="${c.whatsapp_link}" target="_blank" class="whatsapp-btn">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            WhatsApp
                                        </a>` : ''}
                                    </div>
                                `).join('');
                            } else {
                                bairroInfo.classList.add('active');
                                celulasBairro.innerHTML = '<p style="color: rgba(255,255,255,0.5);">Nenhuma célula cadastrada neste bairro ainda.</p>';
                            }
                            
                            map.fitBounds(e.target.getBounds());
                        }
                    });
                }
            }).addTo(map);
            
            // Ajustar mapa para mostrar todo o território de Camaçari
            map.fitBounds(geojsonLayer.getBounds(), { padding: [20, 20] });
        })
        .catch(err => console.error('Erro ao carregar GeoJSON:', err));
    
    // Filtro de busca
    const filterInput = document.getElementById('filterBairro');
    filterInput.addEventListener('input', function() {
        const termo = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        
        document.querySelectorAll('.celula-item').forEach(item => {
            const bairro = (item.dataset.bairro || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const lider = (item.dataset.lider || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            
            if (bairro.includes(termo) || lider.includes(termo)) {
                item.style.display = '';
                item.closest('.geracao-card').style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
        
        // Esconder cards de geração sem células visíveis
        document.querySelectorAll('.geracao-card').forEach(card => {
            const visibleCelulas = card.querySelectorAll('.celula-item:not([style*="display: none"])');
            card.style.display = visibleCelulas.length > 0 ? '' : 'none';
        });
    });
});
</script>
@endpush
