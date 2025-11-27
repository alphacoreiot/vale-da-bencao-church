#!/usr/bin/env python3
# Script para colocar Contato no plural

# Atualizar header
header_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/header.blade.php'

with open(header_path, 'r', encoding='utf-8') as f:
    header = f.read()

header = header.replace('>Contato</a>', '>Contatos</a>')

with open(header_path, 'w', encoding='utf-8') as f:
    f.write(header)

print("Menu: Contato -> Contatos")

# Atualizar footer
footer_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/footer.blade.php'

with open(footer_path, 'r', encoding='utf-8') as f:
    footer = f.read()

footer = footer.replace('>Contato</a>', '>Contatos</a>')

with open(footer_path, 'w', encoding='utf-8') as f:
    f.write(footer)

print("Footer: Contato -> Contatos")
