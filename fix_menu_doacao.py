#!/usr/bin/env python3
# Script para adicionar Doação no menu e footer

# Atualizar navbar
navbar_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/navbar.blade.php'

with open(navbar_path, 'r', encoding='utf-8') as f:
    navbar = f.read()

# Adicionar link de Doação antes de Contato no navbar
old_navbar = '''<a href="{{ route('section.show', 'galeria') }}" class="nav-link" data-route="galeria">Galeria</a>
        <a href="{{ route('section.show', 'contato') }}" class="nav-link" data-route="contato">Contato</a>'''

new_navbar = '''<a href="{{ route('section.show', 'galeria') }}" class="nav-link" data-route="galeria">Galeria</a>
        <a href="#doacoes" class="nav-link" data-route="doacoes">Doação</a>
        <a href="{{ route('section.show', 'contato') }}" class="nav-link" data-route="contato">Contato</a>'''

navbar = navbar.replace(old_navbar, new_navbar)

with open(navbar_path, 'w', encoding='utf-8') as f:
    f.write(navbar)

print("Link Doação adicionado no menu!")

# Atualizar footer
footer_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/footer.blade.php'

with open(footer_path, 'r', encoding='utf-8') as f:
    footer = f.read()

# Adicionar link de Doação no footer
old_footer = '''<li><a href="/secao/galeria" data-route="galeria">Galeria</a></li>
                    <li><a href="/secao/contato" data-route="contato">Contato</a></li>'''

new_footer = '''<li><a href="/secao/galeria" data-route="galeria">Galeria</a></li>
                    <li><a href="/#doacoes" data-route="doacoes">Doação</a></li>
                    <li><a href="/secao/contato" data-route="contato">Contato</a></li>'''

footer = footer.replace(old_footer, new_footer)

with open(footer_path, 'w', encoding='utf-8') as f:
    f.write(footer)

print("Link Doação adicionado no footer!")

# Adicionar id na seção de doações na home
home_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/home.blade.php'

with open(home_path, 'r', encoding='utf-8') as f:
    home = f.read()

# Adicionar id="doacoes" na seção
old_section = '''<!-- Seção Doações -->
<section class="doacoes-section" style="padding: 80px 0; background: linear-gradient(180deg, #000 0%, #0a0a0a 100%);">'''

new_section = '''<!-- Seção Doações -->
<section id="doacoes" class="doacoes-section" style="padding: 80px 0; background: linear-gradient(180deg, #000 0%, #0a0a0a 100%);">'''

home = home.replace(old_section, new_section)

with open(home_path, 'w', encoding='utf-8') as f:
    f.write(home)

print("ID doacoes adicionado na seção!")
