#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Adicionar tile layer após inicializar o mapa
old_code = """    // Inicializar mapa centrado em Camaçari - FIXO, sem zoom ou arrasto
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

    // Carregar GeoJSON de Camaçari"""

new_code = """    // Inicializar mapa centrado em Camaçari - FIXO, sem zoom ou arrasto
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

    // Adicionar tile layer (fundo do mapa)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    // Carregar GeoJSON de Camaçari"""

content = content.replace(old_code, new_code)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Tile layer adicionado ao mapa!")
