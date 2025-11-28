/**
 * ===========================================
 * PÁGINA DE CÉLULAS - JavaScript
 * Vale da Bênção Church
 * ===========================================
 */

// Mapeamento de cores por nome de geração
const CORES_GERACOES = {
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
    'Preta': '#333333',
    'Preta e Branca': '#555555',
    'Resgate': '#DC143C',
    'Rosinha': '#FFB6C1',
    'Roxa': '#9370DB',
    'Verde Bandeira': '#009739',
    'Verde e Vinho': '#556B2F',
    'Verde Tifanes': '#00CED1',
};

// Estado global da aplicação
const AppState = {
    map: null,
    geojsonLayer: null,
    markersLayer: null,
    markers: [],
    celulasData: [],
    geracoesData: [],
    filtros: {
        geracao: null,
        bairro: null
    },
    // Geolocalização
    userLocation: null,
    userMarker: null,
    mostrandoProximas: false,
    // Paginação mobile
    paginaAtual: 1,
    itensPorPagina: 5,
    celulasAtuais: []
};

/**
 * Inicialização principal
 */
document.addEventListener('DOMContentLoaded', function() {
    // Carregar dados do PHP (definidos no blade)
    if (typeof celulasData !== 'undefined') {
        AppState.celulasData = celulasData;
    }
    if (typeof geracoesData !== 'undefined') {
        AppState.geracoesData = geracoesData;
    }
    
    // Inicializar mapa
    initMapa();
    
    // Inicializar filtros
    initFiltros();
    
    // Inicializar geolocalização
    initGeolocalizacao();
    
    // Renderizar lista inicial
    renderizarListaCelulas(AppState.celulasData);
    
    // Atualizar contador
    atualizarContador(AppState.celulasData.length);
    
    // Popular dropdown de bairros
    popularDropdownBairros();
});

/**
 * Inicializa o mapa Leaflet
 */
function initMapa() {
    // Centro em Camaçari
    AppState.map = L.map('mapaCelulas').setView([-12.70, -38.33], 12);
    
    // Tile layer escuro
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(AppState.map);
    
    // Layer para marcadores
    AppState.markersLayer = L.layerGroup().addTo(AppState.map);
    
    // Carregar GeoJSON
    carregarGeoJSON();
    
    // Adicionar marcadores das células
    adicionarMarcadores(AppState.celulasData);
}

/**
 * Carrega o GeoJSON dos bairros
 */
function carregarGeoJSON() {
    fetch('/geojson/Camacari.geojson')
        .then(response => response.json())
        .then(data => {
            AppState.geojsonLayer = L.geoJSON(data, {
                style: function(feature) {
                    const bairroGeo = feature.properties.nm_bairro;
                    const temCelulas = verificarCelulasNoBairro(bairroGeo);
                    
                    return {
                        fillColor: temCelulas ? 'rgba(212, 175, 55, 0.3)' : 'rgba(50, 50, 50, 0.2)',
                        weight: 1,
                        opacity: 0.8,
                        color: temCelulas ? '#D4AF37' : '#444',
                        fillOpacity: 0.3
                    };
                },
                onEachFeature: function(feature, layer) {
                    const bairroGeo = feature.properties.nm_bairro;
                    const count = contarCelulasNoBairro(bairroGeo);
                    
                    layer.bindTooltip(`${feature.properties.Name || bairroGeo}<br>${count} célula(s)`, {
                        permanent: false,
                        direction: 'center',
                        className: 'bairro-tooltip'
                    });
                    
                    layer.on({
                        mouseover: function(e) {
                            if (!AppState.filtros.bairro) {
                                e.target.setStyle({
                                    weight: 2,
                                    fillOpacity: 0.5
                                });
                            }
                        },
                        mouseout: function(e) {
                            if (!AppState.filtros.bairro) {
                                AppState.geojsonLayer.resetStyle(e.target);
                            }
                        },
                        click: function(e) {
                            const bairroNome = normalizarBairro(bairroGeo);
                            selecionarBairroNoFiltro(bairroNome);
                        }
                    });
                }
            }).addTo(AppState.map);
        })
        .catch(err => console.error('Erro ao carregar GeoJSON:', err));
}

/**
 * Adiciona marcadores no mapa
 */
function adicionarMarcadores(celulas) {
    // Limpar marcadores existentes
    AppState.markersLayer.clearLayers();
    AppState.markers = [];
    
    celulas.forEach(celula => {
        if (celula.tem_coordenadas && celula.latitude && celula.longitude) {
            const cor = getCorGeracao(celula.geracao);
            const icon = criarIconeMarcador(cor);
            
            const marker = L.marker([celula.latitude, celula.longitude], { icon: icon })
                .addTo(AppState.markersLayer);
            
            marker.bindPopup(criarPopupContent(celula, cor));
            
            // Guardar referência
            marker.celulaData = celula;
            AppState.markers.push(marker);
        }
    });
}

/**
 * Cria ícone customizado para marcador
 */
function criarIconeMarcador(cor) {
    return L.divIcon({
        className: 'celula-marker',
        html: `<div class="marker-pin" style="background: ${cor};"></div>`,
        iconSize: [24, 24],
        iconAnchor: [12, 12],
        popupAnchor: [0, -12]
    });
}

/**
 * Cria conteúdo do popup
 */
function criarPopupContent(celula, cor) {
    const temCoordenadas = celula.tem_coordenadas && celula.latitude && celula.longitude;
    const mapsUrl = temCoordenadas ? `https://www.google.com/maps/dir/?api=1&destination=${celula.latitude},${celula.longitude}` : '';
    const uberUrl = temCoordenadas ? `https://m.uber.com/ul/?action=setPickup&dropoff[latitude]=${celula.latitude}&dropoff[longitude]=${celula.longitude}&dropoff[nickname]=${encodeURIComponent(celula.nome || 'Célula')}` : '';
    const whatsapp2Link = celula.contato2_whatsapp ? criarWhatsappLink(celula.contato2_whatsapp) : '';
    
    return `
        <div class="popup-celula">
            <div class="popup-header">
                <span class="popup-geracao" style="background: ${cor}">${celula.geracao}</span>
                <h4>${celula.nome || 'Célula'}</h4>
            </div>
            <div class="popup-body">
                <p><strong>Líder:</strong> ${celula.lider}</p>
                <p><strong>Bairro:</strong> ${celula.bairro}</p>
                ${celula.endereco ? `<p><strong>Endereço:</strong> ${celula.endereco}</p>` : ''}
                ${celula.ponto_referencia ? `<p><strong>Referência:</strong> ${celula.ponto_referencia}</p>` : ''}
                ${celula.contato2_nome ? `<p><strong>2º Contato:</strong> ${celula.contato2_nome}</p>` : ''}
            </div>
            <div class="popup-footer popup-footer-grid">
                ${celula.whatsapp_link ? `
                    <a href="${celula.whatsapp_link}" target="_blank" class="popup-btn popup-whatsapp" title="WhatsApp do Líder">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Líder
                    </a>
                ` : ''}
                ${whatsapp2Link ? `
                    <a href="${whatsapp2Link}" target="_blank" class="popup-btn popup-whatsapp2" title="WhatsApp do 2º Contato">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        2º Contato
                    </a>
                ` : ''}
                ${temCoordenadas ? `
                    <a href="${mapsUrl}" target="_blank" class="popup-btn popup-maps" title="Traçar rota no Google Maps">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        Maps
                    </a>
                    <a href="${uberUrl}" target="_blank" class="popup-btn popup-uber" title="Ir de Uber">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                        Uber
                    </a>
                ` : ''}
            </div>
        </div>
    `;
}

/**
 * Cria link de WhatsApp a partir do número
 */
function criarWhatsappLink(numero) {
    if (!numero) return '';
    // Limpar número - remover tudo que não é dígito
    const numeroLimpo = numero.replace(/\D/g, '');
    // Adicionar código do Brasil se não tiver
    const numeroCompleto = numeroLimpo.startsWith('55') ? numeroLimpo : '55' + numeroLimpo;
    return `https://wa.me/${numeroCompleto}`;
}

/**
 * Inicializa os filtros
 */
function initFiltros() {
    const filtroGeracao = document.getElementById('filtroGeracao');
    const filtroBairro = document.getElementById('filtroBairro');
    const btnLimpar = document.getElementById('btnLimpar');
    
    if (filtroGeracao) {
        filtroGeracao.addEventListener('change', function() {
            AppState.filtros.geracao = this.value || null;
            atualizarOpcoesBairro();
            aplicarFiltros();
        });
    }
    
    if (filtroBairro) {
        filtroBairro.addEventListener('change', function() {
            AppState.filtros.bairro = this.value || null;
            atualizarOpcoesGeracao();
            aplicarFiltros();
            
            // Centralizar no bairro se selecionado
            if (AppState.filtros.bairro) {
                centralizarNoBairro(AppState.filtros.bairro);
            }
        });
    }
    
    if (btnLimpar) {
        btnLimpar.addEventListener('click', limparFiltros);
    }
}

/**
 * Aplica os filtros atuais
 */
function aplicarFiltros() {
    const celulasFiltradas = AppState.celulasData.filter(celula => {
        const matchGeracao = !AppState.filtros.geracao || celula.geracao_id == AppState.filtros.geracao;
        const matchBairro = !AppState.filtros.bairro || normalizarBairro(celula.bairro) === normalizarBairro(AppState.filtros.bairro);
        return matchGeracao && matchBairro;
    });
    
    // Atualizar marcadores
    adicionarMarcadores(celulasFiltradas);
    
    // Atualizar lista
    renderizarListaCelulas(celulasFiltradas);
    
    // Atualizar contador
    atualizarContador(celulasFiltradas.length);
    
    // Atualizar estilo do GeoJSON
    atualizarEstiloGeoJSON();
}

/**
 * Limpa todos os filtros
 */
function limparFiltros() {
    AppState.filtros.geracao = null;
    AppState.filtros.bairro = null;
    
    const filtroGeracao = document.getElementById('filtroGeracao');
    const filtroBairro = document.getElementById('filtroBairro');
    
    if (filtroGeracao) filtroGeracao.value = '';
    if (filtroBairro) filtroBairro.value = '';
    
    // Restaurar opções
    popularDropdownBairros();
    restaurarOpcoesGeracao();
    
    // Aplicar filtros (mostra tudo)
    aplicarFiltros();
    
    // Resetar zoom do mapa
    AppState.map.setView([-12.70, -38.33], 12);
}

/**
 * Atualiza opções do dropdown de bairros
 */
function atualizarOpcoesBairro() {
    const bairrosDisponiveis = new Set();
    
    AppState.celulasData.forEach(celula => {
        if (!AppState.filtros.geracao || celula.geracao_id == AppState.filtros.geracao) {
            if (celula.bairro) {
                bairrosDisponiveis.add(celula.bairro);
            }
        }
    });
    
    const filtroBairro = document.getElementById('filtroBairro');
    if (!filtroBairro) return;
    
    // Salvar valor atual
    const valorAtual = filtroBairro.value;
    
    // Limpar e repopular
    filtroBairro.innerHTML = '<option value="">Todos os Bairros</option>';
    
    [...bairrosDisponiveis].sort().forEach(bairro => {
        const option = document.createElement('option');
        option.value = bairro;
        option.textContent = bairro;
        filtroBairro.appendChild(option);
    });
    
    // Restaurar valor se ainda existe
    if (valorAtual && bairrosDisponiveis.has(valorAtual)) {
        filtroBairro.value = valorAtual;
    } else {
        AppState.filtros.bairro = null;
    }
}

/**
 * Atualiza opções do dropdown de gerações
 */
function atualizarOpcoesGeracao() {
    const geracoesDisponiveis = new Set();
    
    AppState.celulasData.forEach(celula => {
        if (!AppState.filtros.bairro || normalizarBairro(celula.bairro) === normalizarBairro(AppState.filtros.bairro)) {
            if (celula.geracao_id) {
                geracoesDisponiveis.add(celula.geracao_id);
            }
        }
    });
    
    const filtroGeracao = document.getElementById('filtroGeracao');
    if (!filtroGeracao) return;
    
    const opcoes = filtroGeracao.querySelectorAll('option');
    opcoes.forEach(option => {
        if (option.value) {
            const disabled = !geracoesDisponiveis.has(parseInt(option.value));
            option.disabled = disabled;
            option.style.color = disabled ? 'rgba(255,255,255,0.3)' : '';
        }
    });
}

/**
 * Restaura todas as opções de geração
 */
function restaurarOpcoesGeracao() {
    const filtroGeracao = document.getElementById('filtroGeracao');
    if (!filtroGeracao) return;
    
    const opcoes = filtroGeracao.querySelectorAll('option');
    opcoes.forEach(option => {
        option.disabled = false;
        option.style.color = '';
    });
}

/**
 * Popula dropdown de bairros
 */
function popularDropdownBairros() {
    const bairros = new Set();
    
    AppState.celulasData.forEach(celula => {
        if (celula.bairro) {
            bairros.add(celula.bairro);
        }
    });
    
    const filtroBairro = document.getElementById('filtroBairro');
    if (!filtroBairro) return;
    
    filtroBairro.innerHTML = '<option value="">Todos os Bairros</option>';
    
    [...bairros].sort().forEach(bairro => {
        const option = document.createElement('option');
        option.value = bairro;
        option.textContent = bairro;
        filtroBairro.appendChild(option);
    });
}

/**
 * Verifica se está em dispositivo mobile
 */
function isMobile() {
    return window.innerWidth <= 1024;
}

/**
 * Gera o HTML de um card de célula
 */
function gerarCardHTML(celula) {
    const cor = getCorGeracao(celula.geracao);
    const temCoordenadas = celula.tem_coordenadas && celula.latitude && celula.longitude;
    const mapsUrl = temCoordenadas ? `https://www.google.com/maps/dir/?api=1&destination=${celula.latitude},${celula.longitude}` : '';
    const uberUrl = temCoordenadas ? `https://m.uber.com/ul/?action=setPickup&dropoff[latitude]=${celula.latitude}&dropoff[longitude]=${celula.longitude}&dropoff[nickname]=${encodeURIComponent(celula.nome || 'Célula')}` : '';
    const whatsapp2Link = celula.contato2_whatsapp ? criarWhatsappLink(celula.contato2_whatsapp) : '';
    
    return `
        <div class="celula-card" data-id="${celula.id}" data-lat="${celula.latitude || ''}" data-lng="${celula.longitude || ''}" style="border-left-color: ${cor}">
            <div class="card-header">
                <span class="geracao-badge" style="background: ${cor}">${celula.geracao}</span>
            </div>
            <h4 class="celula-nome">${celula.nome || 'Célula'}</h4>
            <div class="card-body">
                <p class="lider">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    ${celula.lider}
                </p>
                <p>
                    <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    ${celula.bairro}${celula.endereco ? ' - ' + celula.endereco : ''}
                </p>
                ${celula.ponto_referencia ? `
                    <p>
                        <svg viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/></svg>
                        ${celula.ponto_referencia}
                    </p>
                ` : ''}
                ${celula.contato2_nome ? `
                    <p class="contato2">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        2º: ${celula.contato2_nome}
                    </p>
                ` : ''}
            </div>
            <div class="card-footer">
                ${celula.whatsapp_link ? `
                    <a href="${celula.whatsapp_link}" target="_blank" class="btn-whatsapp" title="WhatsApp do Líder">
                        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Líder
                    </a>
                ` : ''}
                ${whatsapp2Link ? `
                    <a href="${whatsapp2Link}" target="_blank" class="btn-whatsapp2" title="WhatsApp do 2º Contato">
                        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        2º
                    </a>
                ` : ''}
                ${temCoordenadas ? `
                    <a href="${mapsUrl}" target="_blank" class="btn-maps" title="Traçar rota no Google Maps">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        Rota
                    </a>
                    <a href="${uberUrl}" target="_blank" class="btn-uber" title="Ir de Uber">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                        Uber
                    </a>
                    <button class="btn-ver-mapa" onclick="centralizarNoMarcador(${celula.latitude}, ${celula.longitude}, ${celula.id})">
                        <svg viewBox="0 0 24 24" width="14" height="14"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        Mapa
                    </button>
                ` : ''}
            </div>
        </div>
    `;
}

/**
 * Carrega mais células (paginação mobile)
 */
function carregarMaisCelulas() {
    AppState.paginaAtual++;
    renderizarListaCelulas(AppState.celulasAtuais, true);
}

/**
 * Renderiza a lista de células na sidebar
 */
function renderizarListaCelulas(celulas, appendMode = false) {
    const container = document.getElementById('listaCelulas');
    if (!container) return;
    
    // Resetar página se não for modo append
    if (!appendMode) {
        AppState.paginaAtual = 1;
        AppState.celulasAtuais = celulas;
    }
    
    if (celulas.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                <h4>Nenhuma célula encontrada</h4>
                <p>Tente ajustar os filtros</p>
            </div>
        `;
        return;
    }
    
    // Verificar se é mobile para usar paginação
    const usarPaginacao = isMobile();
    let celulasParaExibir;
    let temMais = false;
    
    if (usarPaginacao) {
        const inicio = 0;
        const fim = AppState.paginaAtual * AppState.itensPorPagina;
        celulasParaExibir = celulas.slice(inicio, fim);
        temMais = fim < celulas.length;
    } else {
        celulasParaExibir = celulas;
    }
    
    // Gerar HTML dos cards
    const cardsHTML = celulasParaExibir.map(celula => gerarCardHTML(celula)).join('');
    
    // Botão "Ver mais" para mobile
    const btnVerMaisHTML = temMais ? `
        <button class="btn-ver-mais-celulas" onclick="carregarMaisCelulas()">
            <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/></svg>
            Ver mais células (${celulas.length - celulasParaExibir.length} restantes)
        </button>
    ` : '';
    
    container.innerHTML = cardsHTML + btnVerMaisHTML;
    
    // Adicionar eventos de clique nos cards
    container.querySelectorAll('.celula-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button')) return;
            
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            const id = parseInt(this.dataset.id);
            
            if (lat && lng) {
                centralizarNoMarcador(lat, lng, id);
            }
        });
    });
}

/**
 * Centraliza o mapa em um marcador específico
 */
function centralizarNoMarcador(lat, lng, celulaId) {
    AppState.map.setView([lat, lng], 16);
    
    // Encontrar e abrir o popup do marcador
    AppState.markers.forEach(marker => {
        if (marker.celulaData && marker.celulaData.id === celulaId) {
            marker.openPopup();
        }
    });
    
    // Destacar card na sidebar
    document.querySelectorAll('.celula-card').forEach(card => {
        card.classList.remove('active');
        if (parseInt(card.dataset.id) === celulaId) {
            card.classList.add('active');
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });
}

/**
 * Centraliza o mapa em um bairro específico
 */
function centralizarNoBairro(bairroNome) {
    if (!AppState.geojsonLayer) return;
    
    const bairroNorm = normalizarBairro(bairroNome);
    
    AppState.geojsonLayer.eachLayer(layer => {
        const featureBairro = normalizarBairro(layer.feature.properties.nm_bairro);
        
        if (featureBairro === bairroNorm || featureBairro.includes(bairroNorm) || bairroNorm.includes(featureBairro)) {
            AppState.map.fitBounds(layer.getBounds(), { padding: [50, 50] });
        }
    });
}

/**
 * Seleciona um bairro no filtro a partir do clique no mapa
 */
function selecionarBairroNoFiltro(bairroNome) {
    const filtroBairro = document.getElementById('filtroBairro');
    if (!filtroBairro) return;
    
    // Encontrar a opção correspondente
    const opcoes = filtroBairro.querySelectorAll('option');
    let encontrado = false;
    
    opcoes.forEach(option => {
        if (normalizarBairro(option.value) === normalizarBairro(bairroNome)) {
            filtroBairro.value = option.value;
            AppState.filtros.bairro = option.value;
            encontrado = true;
        }
    });
    
    if (encontrado) {
        atualizarOpcoesGeracao();
        aplicarFiltros();
    }
}

/**
 * Atualiza estilo do GeoJSON baseado nos filtros
 */
function atualizarEstiloGeoJSON() {
    if (!AppState.geojsonLayer) return;
    
    AppState.geojsonLayer.eachLayer(layer => {
        const bairroGeo = layer.feature.properties.nm_bairro;
        const bairroNorm = normalizarBairro(bairroGeo);
        
        let temCelulas = false;
        let isSelecionado = false;
        
        if (AppState.filtros.bairro) {
            isSelecionado = normalizarBairro(AppState.filtros.bairro) === bairroNorm ||
                           bairroNorm.includes(normalizarBairro(AppState.filtros.bairro)) ||
                           normalizarBairro(AppState.filtros.bairro).includes(bairroNorm);
        }
        
        // Verificar se tem células com filtros aplicados
        AppState.celulasData.forEach(celula => {
            const celulaBairroNorm = normalizarBairro(celula.bairro);
            const matchBairro = celulaBairroNorm === bairroNorm || 
                               celulaBairroNorm.includes(bairroNorm) || 
                               bairroNorm.includes(celulaBairroNorm);
            
            const matchGeracao = !AppState.filtros.geracao || celula.geracao_id == AppState.filtros.geracao;
            
            if (matchBairro && matchGeracao) {
                temCelulas = true;
            }
        });
        
        layer.setStyle({
            fillColor: isSelecionado ? '#D4AF37' : (temCelulas ? 'rgba(212, 175, 55, 0.3)' : 'rgba(50, 50, 50, 0.2)'),
            weight: isSelecionado ? 3 : 1,
            opacity: 0.8,
            color: isSelecionado ? '#D4AF37' : (temCelulas ? '#D4AF37' : '#444'),
            fillOpacity: isSelecionado ? 0.5 : 0.3
        });
    });
}

/**
 * Atualiza o contador de células
 */
function atualizarContador(count) {
    const contador = document.getElementById('countCelulas');
    if (contador) {
        contador.textContent = count;
    }
}

/**
 * Retorna a cor da geração
 */
function getCorGeracao(geracaoNome) {
    // Primeiro tentar match exato
    if (CORES_GERACOES[geracaoNome]) {
        return CORES_GERACOES[geracaoNome];
    }
    
    // Tentar match parcial
    for (const [nome, cor] of Object.entries(CORES_GERACOES)) {
        if (geracaoNome.toLowerCase().includes(nome.toLowerCase()) ||
            nome.toLowerCase().includes(geracaoNome.toLowerCase())) {
            return cor;
        }
    }
    
    // Cor padrão
    return '#D4AF37';
}

/**
 * Normaliza string de bairro para comparação
 */
function normalizarBairro(bairro) {
    if (!bairro) return '';
    return bairro.toUpperCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();
}

/**
 * Verifica se há células em um bairro do GeoJSON
 */
function verificarCelulasNoBairro(bairroGeo) {
    return contarCelulasNoBairro(bairroGeo) > 0;
}

/**
 * Conta células em um bairro do GeoJSON
 */
function contarCelulasNoBairro(bairroGeo) {
    const geoNorm = normalizarBairro(bairroGeo);
    let count = 0;
    
    AppState.celulasData.forEach(celula => {
        if (celula.bairro) {
            const celulaNorm = normalizarBairro(celula.bairro);
            if (celulaNorm === geoNorm || 
                celulaNorm.includes(geoNorm) || 
                geoNorm.includes(celulaNorm)) {
                count++;
            }
        }
    });
    
    return count;
}

/* ===========================================
   GEOLOCALIZAÇÃO
   =========================================== */

/**
 * Inicializa o botão de geolocalização
 */
function initGeolocalizacao() {
    const btnLocalizacao = document.getElementById('btnLocalizacao');
    console.log('initGeolocalizacao - botão encontrado:', btnLocalizacao);
    
    if (btnLocalizacao) {
        btnLocalizacao.addEventListener('click', handleLocalizacaoClick);
        console.log('Event listener adicionado ao botão de localização');
    }
}

/**
 * Handler do clique no botão de localização
 */
function handleLocalizacaoClick() {
    console.log('=== Botão de localização clicado! ===');
    
    const btn = document.getElementById('btnLocalizacao');
    const btnText = document.getElementById('btnLocalizacaoText');
    
    if (!btn || !btnText) {
        console.error('Elementos do botão não encontrados');
        return;
    }
    
    // Se já está mostrando células próximas, desativar
    if (AppState.mostrandoProximas) {
        console.log('Desativando localização...');
        desativarLocalizacao();
        return;
    }
    
    // Verificar suporte
    if (!navigator.geolocation) {
        alert('Seu navegador não suporta geolocalização.');
        return;
    }
    
    console.log('Protocolo:', location.protocol, '| Host:', location.hostname);
    
    // Mostrar loading
    btn.disabled = true;
    btnText.textContent = 'Localizando...';
    
    console.log('Solicitando permissão de localização...');
    
    // Obter localização com timeout maior
    navigator.geolocation.getCurrentPosition(
        function(position) {
            console.log('=== Localização obtida com sucesso! ===');
            console.log('Latitude:', position.coords.latitude);
            console.log('Longitude:', position.coords.longitude);
            
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            AppState.userLocation = { lat: lat, lng: lng };
            
            // Adicionar marcador do usuário
            adicionarMarcadorUsuario(lat, lng);
            
            // Ordenar células por distância
            mostrarCelulasProximas();
            
            // Atualizar botão
            btn.disabled = false;
            btn.classList.add('ativo');
            btnText.textContent = 'Desativar localização';
            AppState.mostrandoProximas = true;
            
            console.log('=== Localização ativada! ===');
        },
        function(error) {
            console.error('=== Erro de geolocalização ===');
            console.error('Código:', error.code);
            console.error('Mensagem:', error.message);
            
            btn.disabled = false;
            btnText.textContent = 'Usar minha localização';
            
            let msg = 'Não foi possível obter sua localização.';
            if (error.code === 1) {
                msg = 'Permissão de localização negada. Habilite nas configurações do navegador.';
            } else if (error.code === 2) {
                msg = 'Localização indisponível. Verifique se o GPS está ativo.';
            } else if (error.code === 3) {
                msg = 'Tempo esgotado. Tente novamente.';
            }
            alert(msg);
        },
        {
            enableHighAccuracy: false,
            timeout: 30000,
            maximumAge: 300000
        }
    );
}

/**
 * Adiciona marcador da localização do usuário
 */
function adicionarMarcadorUsuario(lat, lng) {
    // Remover marcador anterior se existir
    if (AppState.userMarker) {
        AppState.map.removeLayer(AppState.userMarker);
    }
    
    // Criar ícone customizado
    const userIcon = L.divIcon({
        className: 'user-location-marker-container',
        html: '<div class="user-location-marker"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    
    // Adicionar marcador
    AppState.userMarker = L.marker([lat, lng], { icon: userIcon })
        .addTo(AppState.map)
        .bindPopup('<strong>Você está aqui!</strong>')
        .openPopup();
    
    // Centralizar no mapa
    AppState.map.setView([lat, lng], 14);
}

/**
 * Calcula distância entre dois pontos (Haversine formula)
 */
function calcularDistancia(lat1, lng1, lat2, lng2) {
    const R = 6371; // Raio da Terra em km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c; // Distância em km
}

/**
 * Formata distância para exibição
 */
function formatarDistancia(distanciaKm) {
    if (distanciaKm < 1) {
        return Math.round(distanciaKm * 1000) + ' m';
    }
    return distanciaKm.toFixed(1) + ' km';
}

/**
 * Mostra células ordenadas por proximidade
 */
function mostrarCelulasProximas() {
    if (!AppState.userLocation) return;
    
    const { lat, lng } = AppState.userLocation;
    
    // Calcular distância para cada célula
    const celulasComDistancia = AppState.celulasData
        .filter(c => c.tem_coordenadas && c.latitude && c.longitude)
        .map(celula => ({
            ...celula,
            distancia: calcularDistancia(lat, lng, celula.latitude, celula.longitude)
        }))
        .sort((a, b) => a.distancia - b.distancia);
    
    // Renderizar lista com distâncias
    renderizarListaCelulasComDistancia(celulasComDistancia);
    
    // Atualizar contador
    atualizarContador(celulasComDistancia.length);
    
    // Atualizar marcadores (mostrar todos, mas destacar os próximos)
    adicionarMarcadores(celulasComDistancia);
}

/**
 * Renderiza lista de células com distância
 */
function renderizarListaCelulasComDistancia(celulas) {
    const container = document.getElementById('listaCelulas');
    if (!container) return;
    
    if (celulas.length === 0) {
        container.innerHTML = `
            <div class="lista-vazia">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="rgba(255,255,255,0.3)">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                </svg>
                <p>Nenhuma célula encontrada com coordenadas.</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = celulas.map((celula, index) => {
        const cor = getCorGeracao(celula.geracao);
        const distanciaFormatada = formatarDistancia(celula.distancia);
        const isProxima = index < 5; // Destacar as 5 mais próximas
        const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${celula.latitude},${celula.longitude}`;
        const uberUrl = `https://m.uber.com/ul/?action=setPickup&dropoff[latitude]=${celula.latitude}&dropoff[longitude]=${celula.longitude}&dropoff[nickname]=${encodeURIComponent(celula.nome || 'Célula')}`;
        const whatsapp2Link = celula.contato2_whatsapp ? criarWhatsappLink(celula.contato2_whatsapp) : '';
        
        return `
            <div class="celula-card ${isProxima ? 'celula-proxima' : ''}" data-lat="${celula.latitude}" data-lng="${celula.longitude}">
                <div class="celula-card-header">
                    <span class="geracao-badge" style="background: ${cor}">${celula.geracao}</span>
                    <span class="celula-distancia">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        ${distanciaFormatada}
                    </span>
                </div>
                <h4 class="celula-nome">${celula.nome || 'Célula'}</h4>
                <div class="celula-info">
                    <p><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg> ${celula.lider}</p>
                    <p><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> ${celula.bairro}</p>
                    ${celula.contato2_nome ? `<p><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg> 2º: ${celula.contato2_nome}</p>` : ''}
                </div>
                <div class="card-footer card-footer-grid">
                    ${celula.whatsapp_link ? `
                        <a href="${celula.whatsapp_link}" target="_blank" class="btn-whatsapp" onclick="event.stopPropagation()" title="WhatsApp do Líder">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Líder
                        </a>
                    ` : ''}
                    ${whatsapp2Link ? `
                        <a href="${whatsapp2Link}" target="_blank" class="btn-whatsapp2" onclick="event.stopPropagation()" title="WhatsApp do 2º Contato">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            2º
                        </a>
                    ` : ''}
                    <a href="${mapsUrl}" target="_blank" class="btn-maps" onclick="event.stopPropagation()" title="Traçar rota no Google Maps">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        Rota
                    </a>
                    <a href="${uberUrl}" target="_blank" class="btn-uber" onclick="event.stopPropagation()" title="Ir de Uber">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                        Uber
                    </a>
                </div>
            </div>
        `;
    }).join('');
    
    // Adicionar eventos de clique nos cards
    container.querySelectorAll('.celula-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('a') || e.target.closest('button')) return;
            
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            
            if (lat && lng) {
                centralizarNoMarcador(lat, lng);
            }
        });
    });
}

/**
 * Desativa modo de localização
 */
function desativarLocalizacao() {
    const btn = document.getElementById('btnLocalizacao');
    const btnText = document.getElementById('btnLocalizacaoText');
    
    // Remover marcador do usuário
    if (AppState.userMarker) {
        AppState.map.removeLayer(AppState.userMarker);
        AppState.userMarker = null;
    }
    
    // Resetar estado
    AppState.userLocation = null;
    AppState.mostrandoProximas = false;
    
    // Atualizar botão
    btn.classList.remove('ativo');
    btnText.textContent = 'Usar minha localização';
    
    // Voltar para lista normal
    aplicarFiltros();
    
    // Resetar zoom
    AppState.map.setView([-12.70, -38.33], 12);
}

// Expor funções globalmente para uso no HTML
window.centralizarNoMarcador = centralizarNoMarcador;
window.limparFiltros = limparFiltros;
