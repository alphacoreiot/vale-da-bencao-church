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
    }
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
            </div>
            ${celula.whatsapp_link ? `
                <div class="popup-footer">
                    <a href="${celula.whatsapp_link}" target="_blank" class="popup-whatsapp">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
            ` : ''}
        </div>
    `;
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
 * Renderiza a lista de células na sidebar
 */
function renderizarListaCelulas(celulas) {
    const container = document.getElementById('listaCelulas');
    if (!container) return;
    
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
    
    container.innerHTML = celulas.map(celula => {
        const cor = getCorGeracao(celula.geracao);
        const temCoordenadas = celula.tem_coordenadas && celula.latitude && celula.longitude;
        
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
                </div>
                <div class="card-footer">
                    ${celula.whatsapp_link ? `
                        <a href="${celula.whatsapp_link}" target="_blank" class="btn-whatsapp">
                            <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                    ` : ''}
                    ${temCoordenadas ? `
                        <button class="btn-ver-mapa" onclick="centralizarNoMarcador(${celula.latitude}, ${celula.longitude}, ${celula.id})">
                            <svg viewBox="0 0 24 24" width="14" height="14"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            Ver no Mapa
                        </button>
                    ` : ''}
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

// Expor funções globalmente para uso no HTML
window.centralizarNoMarcador = centralizarNoMarcador;
window.limparFiltros = limparFiltros;
