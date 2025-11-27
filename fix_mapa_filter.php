<?php
// Script para reescrever a seção de scripts com filtro de mapa

$view_path = 'resources/views/frontend/celulas.blade.php';
$content = file_get_contents($view_path);

// Encontrar a posição do @push('scripts') e substituir todo o bloco
$pattern = '/@push\(\'scripts\'\).*?@endpush/s';

$new_scripts = <<<'BLADE'
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const celulasJson = @json($celulasJson);

    // Bairros com células
    const bairrosComCelulas = new Set();
    celulasJson.forEach(celula => {
        if (!celula.bairro) return;
        const bairroNorm = celula.bairro.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        bairrosComCelulas.add(bairroNorm);
    });

    // Mapa
    const map = L.map('mapaCamacari', {
        center: [-12.6978, -38.3246],
        zoom: 11,
        scrollWheelZoom: true
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    // Variáveis globais para o filtro de mapa
    let geoJsonLayer = null;
    let geoJsonData = null;
    let bairroSelecionado = '';

    // Função para normalizar string
    function normalizar(str) {
        return str ? str.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') : '';
    }

    // Função para criar/atualizar camada GeoJSON com filtro
    function criarCamadaGeoJSON(bairroFiltro = '') {
        if (!geoJsonData) return;
        
        if (geoJsonLayer) {
            map.removeLayer(geoJsonLayer);
        }
        
        const bairroFiltroNorm = normalizar(bairroFiltro);
        let bairroLayer = null;
        
        geoJsonLayer = L.geoJSON(geoJsonData, {
            style: function(feature) {
                const nmBairro = feature.properties.nm_bairro || '';
                const nmBairroNorm = normalizar(nmBairro);
                const temCelula = bairrosComCelulas.has(nmBairroNorm);
                
                // Se há filtro de bairro ativo
                if (bairroFiltroNorm) {
                    const ehSelecionado = nmBairroNorm === bairroFiltroNorm;
                    return {
                        fillColor: ehSelecionado ? '#D4AF37' : (temCelula ? '#D4AF37' : '#333'),
                        weight: ehSelecionado ? 3 : 1,
                        opacity: 1,
                        color: ehSelecionado ? '#FFD700' : '#222',
                        fillOpacity: ehSelecionado ? 0.8 : 0.08
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
                const nmBairroNorm = normalizar(nmBairro);
                const temCelula = bairrosComCelulas.has(nmBairroNorm);
                const ehSelecionado = bairroFiltroNorm && nmBairroNorm === bairroFiltroNorm;
                
                // Guardar referência do bairro selecionado para zoom
                if (ehSelecionado) {
                    bairroLayer = layer;
                }
                
                const celulasNoBairro = celulasJson.filter(c => {
                    if (!c.bairro) return false;
                    return normalizar(c.bairro) === nmBairroNorm;
                });

                let popup = '<strong style="color:#D4AF37;">' + nmBairro + '</strong>';
                if (celulasNoBairro.length > 0) {
                    popup += '<br><span style="color:#25D366;">✓ ' + celulasNoBairro.length + ' célula(s)</span>';
                    celulasNoBairro.forEach(c => { popup += '<br><small>• ' + c.lider + '</small>'; });
                } else {
                    popup += '<br><span style="color:#999;">Sem célula</span>';
                }
                layer.bindPopup(popup);

                // Eventos de hover
                layer.on({
                    mouseover: function(e) {
                        if (!ehSelecionado) {
                            e.target.setStyle({ 
                                weight: 3, 
                                color: '#D4AF37', 
                                fillOpacity: temCelula ? 0.8 : 0.4 
                            });
                        }
                    },
                    mouseout: function(e) {
                        if (!ehSelecionado) {
                            const baseOpacity = bairroFiltroNorm ? 0.08 : (temCelula ? 0.6 : 0.2);
                            e.target.setStyle({ 
                                weight: 1, 
                                color: bairroFiltroNorm ? '#222' : '#666', 
                                fillOpacity: baseOpacity 
                            });
                        }
                    },
                    click: function(e) {
                        // Clicar no bairro seleciona no filtro (se tem célula)
                        if (temCelula) {
                            const opcoes = filtroBairro.options;
                            for (let i = 0; i < opcoes.length; i++) {
                                if (normalizar(opcoes[i].value) === nmBairroNorm) {
                                    filtroBairro.value = opcoes[i].value;
                                    filtroBairro.dispatchEvent(new Event('change'));
                                    break;
                                }
                            }
                        }
                    }
                });
            }
        });
        
        geoJsonLayer.addTo(map);
        
        // Zoom no bairro selecionado
        if (bairroLayer) {
            setTimeout(() => {
                map.fitBounds(bairroLayer.getBounds(), { padding: [50, 50], maxZoom: 14 });
            }, 100);
        } else if (!bairroFiltro) {
            // Reset zoom quando limpa filtro
            map.setView([-12.6978, -38.3246], 11);
        }
    }

    // Carregar GeoJSON
    fetch('/geojson/Camacari.geojson')
        .then(response => response.json())
        .then(data => {
            geoJsonData = data;
            criarCamadaGeoJSON('');
        });

    // Filtros
    const filtroBairro = document.getElementById('filtro-bairro');
    const filtroGeracao = document.getElementById('filtro-geracao');
    const totalFiltrado = document.getElementById('total-filtrado');
    const semResultados = document.getElementById('sem-resultados');
    const celulasItems = document.querySelectorAll('.celula-item');

    const todosOsBairros = [...new Set([...celulasItems].map(i => i.dataset.bairro).filter(b => b))].sort();
    const todasAsGeracoes = [...new Set([...celulasItems].map(i => i.dataset.geracao).filter(g => g))].sort();

    function atualizarSelectBairros(geracaoFiltro) {
        const bairroAtual = filtroBairro.value;
        filtroBairro.innerHTML = '<option value="">Todos os bairros</option>';
        let bairros = todosOsBairros;
        if (geracaoFiltro) {
            bairros = [...new Set([...celulasItems].filter(i => i.dataset.geracao === geracaoFiltro).map(i => i.dataset.bairro).filter(b => b))].sort();
        }
        bairros.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b; opt.textContent = b;
            if (b === bairroAtual) opt.selected = true;
            filtroBairro.appendChild(opt);
        });
        if (bairroAtual && !bairros.includes(bairroAtual)) filtroBairro.value = '';
    }

    function atualizarSelectGeracoes(bairroFiltro) {
        const geracaoAtual = filtroGeracao.value;
        filtroGeracao.innerHTML = '<option value="">Todas as gerações</option>';
        let geracoes = todasAsGeracoes;
        if (bairroFiltro) {
            geracoes = [...new Set([...celulasItems].filter(i => i.dataset.bairro === bairroFiltro).map(i => i.dataset.geracao).filter(g => g))].sort();
        }
        geracoes.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g; opt.textContent = g;
            if (g === geracaoAtual) opt.selected = true;
            filtroGeracao.appendChild(opt);
        });
        if (geracaoAtual && !geracoes.includes(geracaoAtual)) filtroGeracao.value = '';
    }

    function aplicarFiltros() {
        const bairro = filtroBairro.value;
        const geracao = filtroGeracao.value;
        let count = 0;
        celulasItems.forEach(item => {
            const matchB = !bairro || item.dataset.bairro === bairro;
            const matchG = !geracao || item.dataset.geracao === geracao;
            if (matchB && matchG) {
                item.classList.remove('hidden');
                item.style.display = 'block';
                count++;
            } else {
                item.classList.add('hidden');
                item.style.display = 'none';
            }
        });
        totalFiltrado.textContent = count;
        semResultados.style.display = count === 0 ? 'block' : 'none';
    }

    filtroBairro.addEventListener('change', function() {
        atualizarSelectGeracoes(this.value);
        aplicarFiltros();
        // Atualizar o mapa para destacar o bairro selecionado
        criarCamadaGeoJSON(this.value);
    });

    filtroGeracao.addEventListener('change', function() {
        atualizarSelectBairros(this.value);
        aplicarFiltros();
    });

    celulasItems.forEach(item => {
        item.addEventListener('click', function() {
            celulasItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endpush
BLADE;

if (preg_match($pattern, $content)) {
    $content = preg_replace($pattern, $new_scripts, $content);
    file_put_contents($view_path, $content);
    echo "✅ Filtro de bairro no mapa implementado!\n\n";
    echo "Funcionalidades:\n";
    echo "• 📍 Selecionar bairro no filtro → destaca no mapa + zoom automático\n";
    echo "• 🖱️ Clicar em bairro no mapa → seleciona no filtro\n";
    echo "• 🔄 Limpar filtro → mapa volta ao estado original\n";
    echo "• 👆 Outros bairros ficam esmaecidos quando há filtro ativo\n";
} else {
    echo "❌ Bloco @push('scripts') não encontrado\n";
}
