#!/usr/bin/env python3
# Script para trocar Lordicon por SVG de mãos com coração

doacoes_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/doacoes.blade.php'

with open(doacoes_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Lordicon atual
old_icon = '''<div style="margin-bottom: 15px;">
                <lord-icon
                    src="https://cdn.lordicon.com/cvwrvyjv.json"
                    trigger="loop"
                    delay="2000"
                    colors="primary:#D4AF37,secondary:#ffffff"
                    style="width:80px;height:80px">
                </lord-icon>
            </div>'''

# SVG de mãos segurando coração - amor/doação
new_icon = '''<div style="margin-bottom: 15px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:80px;height:80px;">
                    <!-- Coração -->
                    <path d="M12 7.5c-1.5-2.5-4.5-3-6-1.5s-1.5 4.5 0 6l6 6 6-6c1.5-1.5 1.5-4.5 0-6s-4.5-1-6 1.5z" fill="#D4AF37" stroke="#D4AF37"/>
                    <!-- Mãos abertas embaixo -->
                    <path d="M4 20c0-2 1.5-3 3-3h2c.5 0 1 .2 1.5.5L12 19l1.5-1.5c.5-.3 1-.5 1.5-.5h2c1.5 0 3 1 3 3" stroke="#D4AF37" fill="none"/>
                    <path d="M7 17v-2c0-.5.5-1 1-1h1" stroke="#D4AF37"/>
                    <path d="M17 17v-2c0-.5-.5-1-1-1h-1" stroke="#D4AF37"/>
                </svg>
            </div>'''

content = content.replace(old_icon, new_icon)

with open(doacoes_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("SVG de mãos com coração aplicado!")
