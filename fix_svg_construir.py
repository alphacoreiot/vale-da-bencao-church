#!/usr/bin/env python3
import re

file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/doacoes.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# SVG de mãos construindo/tijolos - representa ajudar a construir
novo_svg = '''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="#D4AF37" style="width:80px;height:80px;">
                    <!-- Tijolos/Construção -->
                    <rect x="8" y="40" width="20" height="8" rx="1" stroke="#D4AF37" stroke-width="2" fill="none"/>
                    <rect x="36" y="40" width="20" height="8" rx="1" stroke="#D4AF37" stroke-width="2" fill="none"/>
                    <rect x="18" y="30" width="28" height="8" rx="1" stroke="#D4AF37" stroke-width="2" fill="none"/>
                    <rect x="22" y="20" width="20" height="8" rx="1" stroke="#D4AF37" stroke-width="2" fill="none"/>
                    <!-- Mão segurando tijolo -->
                    <path d="M44 12 L52 12 L52 18 L44 18 Z" stroke="#D4AF37" stroke-width="2" fill="#D4AF37" fill-opacity="0.3"/>
                    <path d="M54 14 C56 14 58 16 58 18 L58 22 C58 24 56 26 54 26 L52 26 L52 14 Z" stroke="#D4AF37" stroke-width="2" fill="none"/>
                    <!-- Cruz no topo (igreja) -->
                    <line x1="32" y1="8" x2="32" y2="16" stroke="#D4AF37" stroke-width="2.5"/>
                    <line x1="28" y1="11" x2="36" y2="11" stroke="#D4AF37" stroke-width="2.5"/>
                    <!-- Base -->
                    <line x1="4" y1="50" x2="60" y2="50" stroke="#D4AF37" stroke-width="2"/>
                </svg>'''

# Substituir o SVG anterior
content = re.sub(
    r'<svg[^>]*viewBox="0 0 24 24"[^>]*>.*?</svg>',
    novo_svg,
    content,
    flags=re.DOTALL
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("SVG de construção (tijolos + cruz) aplicado!")
