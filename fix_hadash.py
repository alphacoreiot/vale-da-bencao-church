#!/usr/bin/env python3
import re

filepath = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/section.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Trocar icone do Hadash de estrela para dancarina
content = content.replace('>✨</div>', '>💃</div>', 1)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print('Icone do Hadash alterado com sucesso!')
