#!/usr/bin/env python3

filepath = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/section.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Mapeamento de ministérios para Lordicons
# Formato: (emoji antigo, lordicon src, trigger)
ministerios = [
    # Professores - livro/educação
    ('📚', 'https://cdn.lordicon.com/kipaqhoz.json'),
    # Intercessão - mãos orando  
    ('🙏', 'https://cdn.lordicon.com/jjoolpwc.json'),
    # Obreiros - cruz
    ('✝️', 'https://cdn.lordicon.com/zpxybbhl.json'),
    # Consolidação - aperto de mãos
    ('🤝', 'https://cdn.lordicon.com/rgxptqmb.json'),
    # Sonorização - alto-falante/som
    ('🔊', 'https://cdn.lordicon.com/xcrjfuzb.json'),
    # Staff Apóstolo - gravata/executivo
    ('👔', 'https://cdn.lordicon.com/hbvgknxo.json'),
    # Produção - claquete/cinema
    ('🎬', 'https://cdn.lordicon.com/akuwjdzh.json'),
    # Introdução - porta/entrada
    ('🚪', 'https://cdn.lordicon.com/cjieiyzp.json'),
    # Mídia - celular/smartphone
    ('📱', 'https://cdn.lordicon.com/fjvfsqea.json'),
    # Multimídia - câmera de vídeo
    ('🎥', 'https://cdn.lordicon.com/vixtkkbk.json'),
    # Libras - mãos
    ('👐', 'https://cdn.lordicon.com/gqdnbnwt.json'),
    # Músicos - guitarra/música
    ('🎸', 'https://cdn.lordicon.com/cyxqicfi.json'),
    # Hadash - dança (já é 💃)
    ('💃', 'https://cdn.lordicon.com/arvmbpwx.json'),
    # Limpeza - vassoura
    ('🧹', 'https://cdn.lordicon.com/ggihhudh.json'),
    # Casais - casal/coração
    ('💑', 'https://cdn.lordicon.com/ohfmmfhn.json'),
    # Batismo - gota d'água
    ('💧', 'https://cdn.lordicon.com/veqxlbfv.json'),
    # Mulheres
    ('👩', 'https://cdn.lordicon.com/fqbvgezn.json'),
    # Homens
    ('👨', 'https://cdn.lordicon.com/fqbvgezn.json'),
    # Teatro - máscara
    ('🎭', 'https://cdn.lordicon.com/akuwjdzh.json'),
    # Jump - adolescentes/grupo
    ('🧑‍🤝‍🧑', 'https://cdn.lordicon.com/bhfjfgqz.json'),
]

# Substituir cada emoji por Lordicon
for emoji, lordicon_src in ministerios:
    old_div = f'<div style="font-size: 3rem; margin-bottom: 15px;">{emoji}</div>'
    new_div = f'<div style="margin-bottom: 15px;"><lord-icon src="{lordicon_src}" trigger="hover" colors="primary:#d4af37,secondary:#ffffff" style="width:70px;height:70px"></lord-icon></div>'
    content = content.replace(old_div, new_div)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print('Todos os icones dos ministerios foram trocados por Lordicons!')
