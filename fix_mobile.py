#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Atualizar o CSS do media query para reordenar no mobile
old_css = '''    @media (max-width: 1024px) {
        .mapa-wrapper {
            grid-template-columns: 1fr;
        }
    }'''

new_css = '''    @media (max-width: 1024px) {
        .mapa-wrapper {
            grid-template-columns: 1fr;
            display: flex;
            flex-direction: column;
        }
        
        /* No mobile: Sidebar (filtros) primeiro, depois mapa */
        .celulas-sidebar {
            order: 1;
            max-height: none;
        }
        
        .mapa-col {
            order: 2;
        }
        
        #mapaCamacari {
            height: 400px;
        }
        
        .mapa-legenda {
            order: 3;
        }
    }'''

content = content.replace(old_css, new_css)

# Adicionar classe mapa-col ao div do mapa
old_html = '''            <!-- Mapa -->
            <div>
                <div id="mapaCamacari"></div>
                <div class="mapa-legenda">'''

new_html = '''            <!-- Mapa -->
            <div class="mapa-col">
                <div id="mapaCamacari"></div>
                <div class="mapa-legenda">'''

content = content.replace(old_html, new_html)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Layout mobile ajustado: Filtros -> Mapa -> Lista!")
