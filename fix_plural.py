#!/usr/bin/env python3
# Script para colocar Doações e Contato no plural

# Atualizar header
header_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/header.blade.php'

with open(header_path, 'r', encoding='utf-8') as f:
    header = f.read()

# Doação -> Doações
header = header.replace('>Doação</a>', '>Doações</a>')
# Contato -> Contatos (se quiser)
# Na verdade Contato geralmente fica no singular, mas vou mudar conforme pedido

with open(header_path, 'w', encoding='utf-8') as f:
    f.write(header)

print("Menu atualizado: Doação -> Doações")

# Atualizar footer também
footer_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/footer.blade.php'

with open(footer_path, 'r', encoding='utf-8') as f:
    footer = f.read()

footer = footer.replace('>Doação</a>', '>Doações</a>')

with open(footer_path, 'w', encoding='utf-8') as f:
    f.write(footer)

print("Footer atualizado: Doação -> Doações")
