#!/usr/bin/env python3
# Script para corrigir link de doacao no menu

# Atualizar header
header_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/header.blade.php'

with open(header_path, 'r', encoding='utf-8') as f:
    header = f.read()

# Corrigir link para usar route
old_link = '<a href="/#doacoes" class="nav-link" data-route="doacoes">Doação</a>'
new_link = '<a href="{{ route(\'home\') }}#doacoes" class="nav-link" data-route="doacoes">Doação</a>'

header = header.replace(old_link, new_link)

with open(header_path, 'w', encoding='utf-8') as f:
    f.write(header)

print("Link Doação corrigido no menu!")

# Atualizar footer também
footer_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/footer.blade.php'

with open(footer_path, 'r', encoding='utf-8') as f:
    footer = f.read()

old_footer_link = '<a href="/#doacoes" data-route="doacoes">Doação</a>'
new_footer_link = '<a href="/#doacoes" data-route="doacoes">Doação</a>'

# Na verdade o footer já está ok com /#doacoes
print("Footer já está correto!")
