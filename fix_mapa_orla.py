#!/usr/bin/env python3
import re

file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Substituir a configuração do mapa - zoom mais afastado para ver orla
old_code = '''    // Limites de Camaçari (área urbana principal)
    const camacariLimites = L.latLngBounds(
        L.latLng(-12.78, -38.42), // Sudoeste
        L.latLng(-12.62, -38.28)  // Nordeste
    );
    
    // Inicializar mapa fixo em Camaçari - zoom bloqueado
    const map = L.map('mapaCamacari', {
        center: [-12.70, -38.34],
        zoom: 13,
        minZoom: 13,
        maxZoom: 13,
        maxBounds: camacariLimites,
        maxBoundsViscosity: 1.0,
        zoomControl: false,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: false,
        boxZoom: false,
        keyboard: false,
        dragging: true
    });
    
    // Tile layer escuro
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '',
        subdomains: 'abcd',
        maxZoom: 13,
        minZoom: 13
    }).addTo(map);'''

new_code = '''    // Limites de Camaçari (incluindo orla)
    const camacariLimites = L.latLngBounds(
        L.latLng(-12.90, -38.50), // Sudoeste
        L.latLng(-12.50, -38.00)  // Nordeste (inclui orla)
    );
    
    // Inicializar mapa fixo em Camaçari - zoom bloqueado
    const map = L.map('mapaCamacari', {
        center: [-12.70, -38.28],
        zoom: 11,
        minZoom: 11,
        maxZoom: 11,
        maxBounds: camacariLimites,
        maxBoundsViscosity: 1.0,
        zoomControl: false,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        touchZoom: false,
        boxZoom: false,
        keyboard: false,
        dragging: false
    });
    
    // Tile layer escuro
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '',
        subdomains: 'abcd',
        maxZoom: 11,
        minZoom: 11
    }).addTo(map);'''

content = content.replace(old_code, new_code)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Mapa afastado para mostrar orla!")
