#!/usr/bin/env python3
import re

file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Novo conteúdo com header padrão igual às outras páginas
new_content = '''@extends('layouts.app')

@section('title', 'Células - Vale da Benção Church')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Section Content */
    .section-content-area {
        padding: 120px 0 80px 0;
        background: #000;
    }

    /* Mapa Section */
    .mapa-section {
        padding: 40px 20px 60px;
        background: #0d0d0d;
    }

    .mapa-container {
        max-width: 1400px;
        margin: 0 auto;
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
        font-size: 1.1rem;
    }

    .celulas-sidebar input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 8px;
        background: #0d0d0d;
        color: #fff;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .celulas-sidebar input:focus {
        outline: none;
        border-color: #D4AF37;
    }

    .celula-item {
        background: #0d0d0d;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }

    .celula-item:hover {
        border-left-color: #D4AF37;
        transform: translateX(5px);
    }

    .celula-item.active {
        border-left-color: #D4AF37;
        background: rgba(212, 175, 55, 0.1);
    }

    .celula-item h4 {
        color: #fff;
        font-size: 0.95rem;
        margin-bottom: 5px;
    }

    .celula-item p {
        color: rgba(255,255,255,0.6);
        font-size: 0.8rem;
        margin: 2px 0;
    }

    .celula-item .bairro {
        color: #D4AF37;
        font-weight: 500;
    }

    .celula-whatsapp {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #25D366;
        font-size: 0.8rem;
        margin-top: 8px;
        text-decoration: none;
    }

    .celula-whatsapp:hover {
        text-decoration: underline;
    }

    /* Legenda */
    .mapa-legenda {
        margin-top: 20px;
        padding: 15px;
        background: #1a1a1a;
        border-radius: 10px;
    }

    .mapa-legenda h4 {
        color: #D4AF37;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .legenda-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 5px 0;
        color: rgba(255,255,255,0.7);
        font-size: 0.8rem;
    }

    .legenda-cor {
        width: 20px;
        height: 15px;
        border-radius: 3px;
    }

    /* Custom scrollbar */
    .celulas-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .celulas-sidebar::-webkit-scrollbar-track {
        background: #0d0d0d;
    }

    .celulas-sidebar::-webkit-scrollbar-thumb {
        background: #D4AF37;
        border-radius: 3px;
    }
</style>
@endpush

@section('content')
<!-- Header padrão igual às outras páginas -->
<section class="section-content-area">
    <div class="container">
        <!-- Título da Seção -->
        <div class="section-header" style="text-align: center; margin-bottom: 40px; padding: 0 20px;">
            <div style="margin-bottom: 15px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" style="width:80px;height:80px;">
                    <!-- Casa/Célula -->
                    <path d="M32 8 L8 28 L14 28 L14 52 L50 52 L50 28 L56 28 Z" stroke="#D4AF37" stroke-width="2" fill="none"/>
                    <!-- Porta -->
                    <rect x="26" y="36" width="12" height="16" stroke="#D4AF37" stroke-width="2" fill="rgba(212, 175, 55, 0.2)"/>
                    <!-- Janelas -->
                    <rect x="18" y="32" width="6" height="6" stroke="#D4AF37" stroke-width="1.5" fill="rgba(212, 175, 55, 0.3)"/>
                    <rect x="40" y="32" width="6" height="6" stroke="#D4AF37" stroke-width="1.5" fill="rgba(212, 175, 55, 0.3)"/>
                    <!-- Cruz no topo -->
                    <line x1="32" y1="2" x2="32" y2="10" stroke="#D4AF37" stroke-width="2.5"/>
                    <line x1="28" y1="5" x2="36" y2="5" stroke="#D4AF37" stroke-width="2.5"/>
                    <!-- Pessoas (círculos) -->
                    <circle cx="22" cy="48" r="3" fill="#D4AF37" fill-opacity="0.5"/>
                    <circle cx="32" cy="46" r="3" fill="#D4AF37" fill-opacity="0.5"/>
                    <circle cx="42" cy="48" r="3" fill="#D4AF37" fill-opacity="0.5"/>
                </svg>
            </div>
            <h1 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Células</h1>
            <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">Encontre uma célula perto de você e faça parte da nossa família</p>
        </div>
    </div>
</section>

<!-- Mapa Section -->
<section class="mapa-section">
    <div class="mapa-container">
        <div class="mapa-wrapper">
            <!-- Mapa -->
            <div>
                <div id="mapaCamacari"></div>
                <div class="mapa-legenda">
                    <h4>Legenda</h4>
                    <div class="legenda-item">
                        <span class="legenda-cor" style="background: #D4AF37;"></span>
                        Bairro com célula
                    </div>
                    <div class="legenda-item">
                        <span class="legenda-cor" style="background: rgba(100, 100, 100, 0.3);"></span>
                        Bairro sem célula
                    </div>
                </div>
            </div>

            <!-- Sidebar com lista de células -->
            <div class="celulas-sidebar">
                <h3>🏠 {{ $totalCelulas }} Células em Camaçari</h3>
                <input type="text" id="filtro-celula" placeholder="Buscar por bairro ou líder...">
                <div id="lista-celulas">
                    @foreach($geracoes as $geracao)
                        @foreach($geracao->celulas as $celula)
                            <div class="celula-item" data-bairro="{{ $celula->bairro }}">
                                <h4>{{ $celula->nome }}</h4>
                                <p><strong>Líder:</strong> {{ $celula->lider }}</p>
                                <p class="bairro">📍 {{ $celula->bairro }}</p>
                                @if($celula->dia_semana && $celula->horario)
                                    <p>📅 {{ $celula->dia_semana }} às {{ $celula->horario }}</p>
                                @endif
                                @if($celula->contato)
                                    <a href="{{ $celula->whatsappLink() }}" target="_blank" class="celula-whatsapp">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#25D366">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        Entrar em contato
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados das células por bairro
    const celulasData = @json($celulasData);

    // Mapeamento de bairros
    const mapeamentoBairros = @json($mapeamentoBairros);

    // Criar conjunto de bairros com células (normalizado)
    const bairrosComCelulas = new Set();
    celulasData.forEach(celula => {
        const bairroNorm = celula.bairro.toUpperCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');
        bairrosComCelulas.add(bairroNorm);

        // Adicionar mapeamentos
        Object.entries(mapeamentoBairros).forEach(([key, value]) => {
            if (key === bairroNorm) {
                bairrosComCelulas.add(value);
            }
        });
    });

    // Inicializar mapa centrado em Camaçari - FIXO, sem zoom ou arrasto
    const map = L.map('mapaCamacari', {
        center: [-12.70, -38.33],
        zoom: 11,
        zoomControl: false,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: false,
        boxZoom: false,
        dragging: false
    });

    // Carregar GeoJSON de Camaçari
    fetch('/Camacari.geojson')
        .then(response => response.json())
        .then(data => {
            L.geoJSON(data, {
                style: function(feature) {
                    const nmBairro = feature.properties.nm_bairro || '';
                    const nmBairroNorm = nmBairro.toUpperCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');

                    const temCelula = bairrosComCelulas.has(nmBairroNorm);

                    return {
                        fillColor: temCelula ? '#D4AF37' : '#444',
                        weight: 1,
                        opacity: 1,
                        color: '#666',
                        fillOpacity: temCelula ? 0.6 : 0.2
                    };
                },
                onEachFeature: function(feature, layer) {
                    const nmBairro = feature.properties.nm_bairro || 'Sem nome';
                    const nmBairroNorm = nmBairro.toUpperCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');
                    const temCelula = bairrosComCelulas.has(nmBairroNorm);

                    // Encontrar células neste bairro
                    const celulasNoBairro = celulasData.filter(c => {
                        const cBairroNorm = c.bairro.toUpperCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');
                        return cBairroNorm === nmBairroNorm || mapeamentoBairros[cBairroNorm] === nmBairroNorm;
                    });

                    let popupContent = '<div style="min-width: 200px;"><strong style="color: #D4AF37;">' + nmBairro + '</strong>';

                    if (celulasNoBairro.length > 0) {
                        popupContent += '<br><span style="color: #25D366;">✓ ' + celulasNoBairro.length + ' célula(s)</span>';
                        celulasNoBairro.forEach(c => {
                            popupContent += '<br><small>• ' + c.lider + '</small>';
                        });
                    } else {
                        popupContent += '<br><span style="color: #999;">Sem célula cadastrada</span>';
                    }
                    popupContent += '</div>';

                    layer.bindPopup(popupContent);

                    layer.on({
                        mouseover: function(e) {
                            const layer = e.target;
                            layer.setStyle({
                                weight: 3,
                                color: '#D4AF37',
                                fillOpacity: temCelula ? 0.8 : 0.4
                            });
                        },
                        mouseout: function(e) {
                            const layer = e.target;
                            layer.setStyle({
                                weight: 1,
                                color: '#666',
                                fillOpacity: temCelula ? 0.6 : 0.2
                            });
                        }
                    });
                }
            }).addTo(map);
        });

    // Filtro de células
    document.getElementById('filtro-celula').addEventListener('input', function(e) {
        const termo = e.target.value.toLowerCase();
        document.querySelectorAll('.celula-item').forEach(item => {
            const texto = item.textContent.toLowerCase();
            item.style.display = texto.includes(termo) ? 'block' : 'none';
        });
    });

    // Clique na célula para destacar no mapa
    document.querySelectorAll('.celula-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.celula-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endpush
'''

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Header padrão aplicado com sucesso!")
