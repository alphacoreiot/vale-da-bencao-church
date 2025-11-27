#!/usr/bin/env python3
# Script para remover icones dos cards de doacoes

file_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/home.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Remover Lordicon do card Primicias
old_primicias = '''<lord-icon
                    src="https://cdn.lordicon.com/yeallgsa.json"
                    trigger="loop"
                    delay="2000"
                    colors="primary:#D4AF37,secondary:#ffffff"
                    style="width:60px;height:60px;margin-bottom:15px">
                </lord-icon>
                <h3 style="color: #fff; font-size: clamp(1.3rem, 2.5vw, 1.6rem); font-weight: 700; margin-bottom: 15px;">Primícias</h3>'''

new_primicias = '''<h3 style="color: #fff; font-size: clamp(1.3rem, 2.5vw, 1.6rem); font-weight: 700; margin-bottom: 15px;">Primícias</h3>'''

content = content.replace(old_primicias, new_primicias)

# Remover Lordicon do card Dizimos/Ofertas
old_dizimos = '''<lord-icon
                    src="https://cdn.lordicon.com/qhviklyi.json"
                    trigger="loop"
                    delay="2000"
                    colors="primary:#D4AF37,secondary:#ffffff"
                    style="width:60px;height:60px;margin-bottom:15px">
                </lord-icon>
                <h3 style="color: #fff; font-size: clamp(1.3rem, 2.5vw, 1.6rem); font-weight: 700; margin-bottom: 15px;">Dízimos / Ofertas</h3>'''

new_dizimos = '''<h3 style="color: #fff; font-size: clamp(1.3rem, 2.5vw, 1.6rem); font-weight: 700; margin-bottom: 15px;">Dízimos / Ofertas</h3>'''

content = content.replace(old_dizimos, new_dizimos)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Icones removidos dos cards de doacoes!")
