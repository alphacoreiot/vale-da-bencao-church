#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Substituir o CSS do media query completo
old_css = '''    @media (max-width: 1024px) {
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
            order: 3;
        }
        
        #mapaCamacari {
            height: 400px;
        }
        
        .mapa-legenda {
            order: 2;
        }
    }'''

new_css = '''    @media (max-width: 1024px) {
        .mapa-wrapper {
            grid-template-columns: 1fr;
            display: flex;
            flex-direction: column;
        }
        
        /* No mobile: Filtros/Lista primeiro, depois Mapa */
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
    }'''

content = content.replace(old_css, new_css)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Ordem mobile: Filtros/Lista -> Mapa!")
