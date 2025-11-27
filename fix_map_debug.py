#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Adicionar background ao mapa
old_css = """    #mapaCamacari {
        height: 600px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }"""

new_css = """    #mapaCamacari {
        height: 600px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        background: #1a1a1a;
    }
    
    /* Fix Leaflet z-index */
    .leaflet-pane,
    .leaflet-tile,
    .leaflet-marker-icon,
    .leaflet-marker-shadow,
    .leaflet-tile-container,
    .leaflet-map-pane svg,
    .leaflet-map-pane canvas,
    .leaflet-zoom-box,
    .leaflet-image-layer,
    .leaflet-layer {
        position: absolute;
        left: 0;
        top: 0;
    }
    
    .leaflet-container {
        overflow: hidden;
        background: #1a1a1a;
    }"""

content = content.replace(old_css, new_css)

# Adicionar catch de erro no fetch
old_fetch = """    // Carregar GeoJSON de Camaçari
    fetch('/geojson/Camacari.geojson')
        .then(response => response.json())
        .then(data => {"""

new_fetch = """    // Carregar GeoJSON de Camaçari
    console.log('Carregando GeoJSON...');
    fetch('/geojson/Camacari.geojson')
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) throw new Error('GeoJSON não encontrado');
            return response.json();
        })
        .then(data => {
            console.log('GeoJSON carregado:', data.features?.length, 'features');"""

content = content.replace(old_fetch, new_fetch)

# Adicionar catch no final do fetch
old_end = """            }).addTo(map);
        });

    // Filtro de células"""

new_end = """            }).addTo(map);
            console.log('Mapa renderizado com sucesso!');
        })
        .catch(error => {
            console.error('Erro ao carregar GeoJSON:', error);
            document.getElementById('mapaCamacari').innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#D4AF37;">Erro ao carregar mapa</div>';
        });

    // Filtro de células"""

content = content.replace(old_end, new_end)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Debug e CSS do mapa adicionados!")
