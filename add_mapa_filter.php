<?php
// Script para adicionar filtro de bairro no mapa

$view_path = 'resources/views/frontend/celulas.blade.php';
$content = file_get_contents($view_path);

// Código antigo do JavaScript
$old_js = <<<'OLD'
    // Filtros
    const filtroBairro = document.getElementById('filtro-bairro');
    const filtroGeracao = document.getElementById('filtro-geracao');
    const totalFiltrado = document.getElementById('total-filtrado');
    const semResultados = document.getElementById('sem-resultados');
    const celulasItems = document.querySelectorAll('.celula-item');

    const todosOsBairros = [...new Set([...celulasItems].map(i => i.dataset.bairro).filter(b => b))].sort();     
    const todasAsGeracoes = [...new Set([...celulasItems].map(i => i.dataset.geracao).filter(g => g))].sort();
OLD;

$new_js = <<<'NEW'
    // Variável global para a camada GeoJSON
    let geoJsonLayer = null;
    let bairroSelecionado = '';

    // Filtros
    const filtroBairro = document.getElementById('filtro-bairro');
    const filtroGeracao = document.getElementById('filtro-geracao');
    const totalFiltrado = document.getElementById('total-filtrado');
    const semResultados = document.getElementById('sem-resultados');
    const celulasItems = document.querySelectorAll('.celula-item');

    const todosOsBairros = [...new Set([...celulasItems].map(i => i.dataset.bairro).filter(b => b))].sort();     
    const todasAsGeracoes = [...new Set([...celulasItems].map(i => i.dataset.geracao).filter(g => g))].sort();
NEW;

$content = str_replace($old_js, $new_js, $content);

// Atualizar o código do GeoJSON para usar a variável global
$old_geojson = <<<'OLD'
    // GeoJSON
    fetch('/geojson/Camacari.geojson')
        .then(response => response.json())
        .then(data => {
            L.geoJSON(data, {
OLD;

$new_geojson = <<<'NEW'
    // GeoJSON - Função para criar/atualizar camada
    function criarCamadaGeoJSON(geoData, bairroFiltro = '') {
        if (geoJsonLayer) {
            map.removeLayer(geoJsonLayer);
        }
        
        const bairroFiltroNorm = bairroFiltro ? bairroFiltro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') : '';
        
        geoJsonLayer = L.geoJSON(geoData, {
            style: function(feature) {
                const nmBairro = feature.properties.nm_bairro || '';
                const nmBairroNorm = nmBairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                const temCelula = bairrosComCelulas.has(nmBairroNorm);
                
                // Se há filtro de bairro ativo
                if (bairroFiltroNorm) {
                    const ehBairroSelecionado = nmBairroNorm === bairroFiltroNorm;
                    return {
                        fillColor: ehBairroSelecionado ? '#D4AF37' : (temCelula ? '#D4AF37' : '#444'),
                        weight: ehBairroSelecionado ? 3 : 1,
                        opacity: 1,
                        color: ehBairroSelecionado ? '#FFD700' : '#333',
                        fillOpacity: ehBairroSelecionado ? 0.8 : 0.1
                    };
                }
                
                // Sem filtro - estilo normal
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
                const nmBairroNorm = nmBairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                const temCelula = bairrosComCelulas.has(nmBairroNorm);
                const celulasNoBairro = celulasJson.filter(c => {
                    if (!c.bairro) return false;
                    return c.bairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') === nmBairroNorm;
                });

                let popup = '<strong style="color:#D4AF37;">' + nmBairro + '</strong>';
                if (celulasNoBairro.length > 0) {
                    popup += '<br><span style="color:#25D366;">✓ ' + celulasNoBairro.length + ' célula(s)</span>';
                    celulasNoBairro.forEach(c => { popup += '<br><small>• ' + c.lider + '</small>'; });
                } else {
                    popup += '<br><span style="color:#999;">Sem célula</span>';
                }
                layer.bindPopup(popup);

                // Hover effects
                const ehBairroSelecionado = bairroFiltroNorm && nmBairroNorm === bairroFiltroNorm;
                
                layer.on({
                    mouseover: function(e) {
                        if (!ehBairroSelecionado) {
                            e.target.setStyle({ weight: 3, color: '#D4AF37', fillOpacity: temCelula ? 0.8 : 0.4 });
                        }
                    },
                    mouseout: function(e) {
                        if (!ehBairroSelecionado) {
                            const baseOpacity = bairroFiltroNorm ? 0.1 : (temCelula ? 0.6 : 0.2);
                            e.target.setStyle({ weight: 1, color: bairroFiltroNorm ? '#333' : '#666', fillOpacity: baseOpacity });
                        }
                    },
                    click: function(e) {
                        // Ao clicar no bairro, selecionar no filtro
                        if (temCelula) {
                            // Encontrar o bairro correspondente no select
                            const opcoes = filtroBairro.options;
                            for (let i = 0; i < opcoes.length; i++) {
                                const opcaoNorm = opcoes[i].value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                                if (opcaoNorm === nmBairroNorm) {
                                    filtroBairro.value = opcoes[i].value;
                                    filtroBairro.dispatchEvent(new Event('change'));
                                    break;
                                }
                            }
                        }
                    }
                });
                
                // Zoom no bairro selecionado
                if (bairroFiltroNorm && nmBairroNorm === bairroFiltroNorm) {
                    setTimeout(() => {
                        map.fitBounds(layer.getBounds(), { padding: [50, 50], maxZoom: 14 });
                    }, 100);
                }
            }
        });
        
        geoJsonLayer.addTo(map);
        return geoJsonLayer;
    }
    
    // Variável para armazenar os dados do GeoJSON
    let geoJsonData = null;
    
    // Carregar GeoJSON
    fetch('/geojson/Camacari.geojson')
        .then(response => response.json())
        .then(data => {
            geoJsonData = data;
            criarCamadaGeoJSON(data, '');
        });
    
    // Função para atualizar o mapa com filtro
    function atualizarMapaComFiltro(bairro) {
        if (geoJsonData) {
            criarCamadaGeoJSON(geoJsonData, bairro);
            
            // Se não há filtro, resetar o zoom
            if (!bairro) {
                map.setView([-12.6978, -38.3246], 11);
            }
        }
    }

    // Código antigo removido - agora usa criarCamadaGeoJSON
    // (a parte abaixo foi substituída pela função acima)
OLD;

// Remover o código duplicado do GeoJSON antigo
$pattern_geojson = '/    \/\/ GeoJSON\s+fetch.*?\.addTo\(map\);\s+\}\);\s+\}\);/s';

// Primeiro adicionar o novo código
$content = str_replace($old_geojson, $new_geojson, $content);

// Agora atualizar o filtro de bairro para atualizar o mapa também
$old_filtro_bairro = <<<'OLD'
    filtroBairro.addEventListener('change', function() {
        atualizarSelectGeracoes(this.value);
        aplicarFiltros();
    });
OLD;

$new_filtro_bairro = <<<'NEW'
    filtroBairro.addEventListener('change', function() {
        atualizarSelectGeracoes(this.value);
        aplicarFiltros();
        // Atualizar o mapa para destacar o bairro selecionado
        atualizarMapaComFiltro(this.value);
    });
NEW;

$content = str_replace($old_filtro_bairro, $new_filtro_bairro, $content);

// Remover o código duplicado/antigo do onEachFeature e style que ficou
$old_remaining = <<<'OLD'
                style: function(feature) {
                    const nmBairro = feature.properties.nm_bairro || '';
                    const nmBairroNorm = nmBairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
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
                    const nmBairroNorm = nmBairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                    const temCelula = bairrosComCelulas.has(nmBairroNorm);
                    const celulasNoBairro = celulasJson.filter(c => {
                        if (!c.bairro) return false;
                        return c.bairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') === nmBairroNorm;
                    });

                    let popup = '<strong style="color:#D4AF37;">' + nmBairro + '</strong>';
                    if (celulasNoBairro.length > 0) {
                        popup += '<br><span style="color:#25D366;">✓ ' + celulasNoBairro.length + ' célula(s)</span>';
                        celulasNoBairro.forEach(c => { popup += '<br><small>• ' + c.lider + '</small>'; });
                    } else {
                        popup += '<br><span style="color:#999;">Sem célula</span>';
                    }
                    layer.bindPopup(popup);

                    layer.on({
                        mouseover: function(e) {
                            e.target.setStyle({ weight: 3, color: '#D4AF37', fillOpacity: temCelula ? 0.8 : 0.4 });
                        },
                        mouseout: function(e) {
                            e.target.setStyle({ weight: 1, color: '#666', fillOpacity: temCelula ? 0.6 : 0.2 }); 
                        }
                    });
                }
            }).addTo(map);
        });
OLD;

$content = str_replace($old_remaining, '', $content);

file_put_contents($view_path, $content);

echo "✅ Filtro de bairro no mapa implementado!\n\n";
echo "Funcionalidades:\n";
echo "• Ao selecionar um bairro no filtro, o mapa destaca apenas esse bairro\n";
echo "• Zoom automático no bairro selecionado\n";
echo "• Ao clicar em um bairro no mapa, ele é selecionado no filtro\n";
echo "• Ao limpar o filtro, o mapa volta ao estado normal\n";
