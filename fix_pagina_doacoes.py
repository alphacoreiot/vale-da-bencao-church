#!/usr/bin/env python3
# Script para criar rota de doações e atualizar links

# 1. Adicionar rota no web.php
routes_path = '/home/u817008098/domains/valedabencao.com.br/public_html/routes/web.php'

with open(routes_path, 'r', encoding='utf-8') as f:
    routes = f.read()

# Adicionar rota de doações após a rota home
old_routes = '''// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/secao/{slug}', [SectionController::class, 'show'])->name('section.show');'''

new_routes = '''// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/doacoes', function() {
    return view('frontend.doacoes');
})->name('doacoes');
Route::get('/secao/{slug}', [SectionController::class, 'show'])->name('section.show');'''

routes = routes.replace(old_routes, new_routes)

with open(routes_path, 'w', encoding='utf-8') as f:
    f.write(routes)

print("Rota /doacoes criada!")

# 2. Atualizar link no header
header_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/header.blade.php'

with open(header_path, 'r', encoding='utf-8') as f:
    header = f.read()

old_link = '''<a href="{{ route('home') }}#doacoes" class="nav-link" data-route="doacoes">Doação</a>'''
new_link = '''<a href="{{ route('doacoes') }}" class="nav-link" data-route="doacoes">Doação</a>'''

header = header.replace(old_link, new_link)

with open(header_path, 'w', encoding='utf-8') as f:
    f.write(header)

print("Link no menu atualizado!")

# 3. Atualizar link no footer
footer_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/footer.blade.php'

with open(footer_path, 'r', encoding='utf-8') as f:
    footer = f.read()

old_footer = '''<a href="/#doacoes" data-route="doacoes">Doação</a>'''
new_footer = '''<a href="/doacoes" data-route="doacoes">Doação</a>'''

footer = footer.replace(old_footer, new_footer)

with open(footer_path, 'w', encoding='utf-8') as f:
    f.write(footer)

print("Link no footer atualizado!")

# 4. Atualizar a página de doações com Lordicon no header
doacoes_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/doacoes.blade.php'

with open(doacoes_path, 'r', encoding='utf-8') as f:
    doacoes = f.read()

# Trocar emoji por Lordicon
old_icon = '''<div style="font-size: clamp(2rem, 5vw, 3rem); margin-bottom: 15px;">💝</div>'''
new_icon = '''<div style="margin-bottom: 15px;">
                <lord-icon
                    src="https://cdn.lordicon.com/ohfmmfhn.json"
                    trigger="loop"
                    delay="2000"
                    colors="primary:#D4AF37,secondary:#ffffff"
                    style="width:80px;height:80px">
                </lord-icon>
            </div>'''

doacoes = doacoes.replace(old_icon, new_icon)

with open(doacoes_path, 'w', encoding='utf-8') as f:
    f.write(doacoes)

print("Página de doações atualizada com Lordicon!")
