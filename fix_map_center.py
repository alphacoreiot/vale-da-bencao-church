#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Alterar o centro e zoom do mapa, e habilitar zoom
old_map = """    // Inicializar mapa centrado em Camaçari - FIXO, sem zoom ou arrasto
    const map = L.map('mapaCamacari', {
        center: [-12.70, -38.33],
        zoom: 11,
        zoomControl: false,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: false,
        boxZoom: false,
        dragging: false
    });"""

new_map = """    // Inicializar mapa centrado em Camaçari com zoom habilitado
    const map = L.map('mapaCamacari', {
        center: [-12.6978, -38.3246],
        zoom: 12,
        zoomControl: true,
        scrollWheelZoom: true,
        doubleClickZoom: true,
        touchZoom: true,
        boxZoom: true,
        dragging: true,
        minZoom: 10,
        maxZoom: 16
    });"""

content = content.replace(old_map, new_map)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Mapa centralizado em Camaçari com zoom habilitado!")
