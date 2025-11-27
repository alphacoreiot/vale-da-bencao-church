#!/usr/bin/env python3

filepath = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/radio.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Trocar por SVG de radio
old_button = '''<!-- Botão Rádio -->
<button class="radio-button" id="radioButton">
    <lord-icon src="https://cdn.lordicon.com/kkvxgpti.json" trigger="loop" delay="1500" colors="primary:#ffffff,secondary:#d4af37" style="width:45px;height:45px"></lord-icon>
</button>'''

new_button = '''<!-- Botão Rádio -->
<button class="radio-button" id="radioButton">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"/>
        <path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"/>
        <circle cx="12" cy="12" r="2"/>
        <path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"/>
        <path d="M19.1 4.9C23 8.8 23 15.1 19.1 19"/>
    </svg>
</button>'''

content = content.replace(old_button, new_button)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print('Botao da radio alterado para SVG!')
