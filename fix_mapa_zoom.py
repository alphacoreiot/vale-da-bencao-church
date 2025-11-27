#!/usr/bin/env python3
import re

file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Substituir a configuração do mapa
old_code = '''    // Limites de Camaçari (bounding box)
    const camacariLimites = L.latLngBounds(
        L.latLng(-12.95, -38.55), // Sudoeste
        L.latLng(-12.40, -38.05)  // Nordeste
    );
    
    // Inicializar mapa fixo em Camaçari
    const map = L.map('mapaCamacari', {
        center: [-12.70, -38.33],
        zoom: 11,
        minZoom: 10,
        maxZoom: 16,
        maxBounds: camacariLimites,
        maxBoundsViscosity: 1.0 // Impede arrastar para fora dos limites
    });
    
    // Tile layer escuro
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 16,
        minZoom: 10
    }).addTo(map);'''

new_code = '''    // Limites de Camaçari (área urbana principal)
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

content = content.replace(old_code, new_code)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Mapa enquadrado e zoom bloqueado!")
