#!/usr/bin/env python3

filepath = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/radio.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Trocar emoji de musica por Lordicon animado de radio
old_button = '''<!-- Botão Rádio -->
<button class="radio-button" id="radioButton">
    🎵
</button>'''

new_button = '''<!-- Botão Rádio -->
<button class="radio-button" id="radioButton">
    <lord-icon src="https://cdn.lordicon.com/vczuoatt.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:40px;height:40px"></lord-icon>
</button>'''

content = content.replace(old_button, new_button)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print('Botao da radio alterado para icone Lordicon!')
