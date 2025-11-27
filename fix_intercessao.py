#!/usr/bin/env python3

filepath = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/section.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Trocar icone da Intercessão por um de oração mais adequado
old_icon = '<div style="margin-bottom: 15px;"><lord-icon src="https://cdn.lordicon.com/jjoolpwc.json" trigger="hover" colors="primary:#d4af37,secondary:#ffffff" style="width:70px;height:70px"></lord-icon></div>'

new_icon = '<div style="margin-bottom: 15px;"><lord-icon src="https://cdn.lordicon.com/hvbgvkbe.json" trigger="hover" colors="primary:#d4af37,secondary:#ffffff" style="width:70px;height:70px"></lord-icon></div>'

content = content.replace(old_icon, new_icon, 1)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print('Icone de Intercessao alterado para oracao!')
