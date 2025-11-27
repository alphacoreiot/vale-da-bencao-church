#!/usr/bin/env python3
# Script para trocar Lordicon para construcao civil

doacoes_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/doacoes.blade.php'

with open(doacoes_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Lordicon atual
old_icon = '''<div style="margin-bottom: 15px;">
                <lord-icon
                    src="https://cdn.lordicon.com/qhgmphtg.json"
                    trigger="loop"
                    delay="2000"
                    colors="primary:#D4AF37,secondary:#ffffff"
                    style="width:80px;height:80px">
                </lord-icon>
            </div>'''

# Lordicon de construção civil - guindaste/crane
new_icon = '''<div style="margin-bottom: 15px;">
                <lord-icon
                    src="https://cdn.lordicon.com/kbtmbyzy.json"
                    trigger="loop"
                    delay="2000"
                    colors="primary:#D4AF37,secondary:#ffffff"
                    style="width:80px;height:80px">
                </lord-icon>
            </div>'''

content = content.replace(old_icon, new_icon)

with open(doacoes_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Lordicon trocado para construção civil!")
