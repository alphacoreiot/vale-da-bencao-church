#!/usr/bin/env python3

filepath = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/section.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Reverter Lordicons para emojis originais
lordicons_to_emoji = [
    ('https://cdn.lordicon.com/kipaqhoz.json', '📚'),
    ('https://cdn.lordicon.com/jjoolpwc.json', '🙏'),
    ('https://cdn.lordicon.com/zpxybbhl.json', '✝️'),
    ('https://cdn.lordicon.com/rgxptqmb.json', '🤝'),
    ('https://cdn.lordicon.com/xcrjfuzb.json', '🔊'),
    ('https://cdn.lordicon.com/hbvgknxo.json', '👔'),
    ('https://cdn.lordicon.com/akuwjdzh.json', '🎬'),
    ('https://cdn.lordicon.com/cjieiyzp.json', '🚪'),
    ('https://cdn.lordicon.com/fjvfsqea.json', '📱'),
    ('https://cdn.lordicon.com/vixtkkbk.json', '🎥'),
    ('https://cdn.lordicon.com/gqdnbnwt.json', '👐'),
    ('https://cdn.lordicon.com/cyxqicfi.json', '🎸'),
    ('https://cdn.lordicon.com/arvmbpwx.json', '💃'),
    ('https://cdn.lordicon.com/ggihhudh.json', '🧹'),
    ('https://cdn.lordicon.com/ohfmmfhn.json', '💑'),
    ('https://cdn.lordicon.com/veqxlbfv.json', '💧'),
    ('https://cdn.lordicon.com/fqbvgezn.json', '👩'),
    ('https://cdn.lordicon.com/bhfjfgqz.json', '🧑‍🤝‍🧑'),
]

# Substituir cada Lordicon por emoji
for lordicon_src, emoji in lordicons_to_emoji:
    old_div = f'<div style="margin-bottom: 15px;"><lord-icon src="{lordicon_src}" trigger="hover" colors="primary:#d4af37,secondary:#ffffff" style="width:70px;height:70px"></lord-icon></div>'
    new_div = f'<div style="font-size: 3rem; margin-bottom: 15px;">{emoji}</div>'
    content = content.replace(old_div, new_div)

# Corrigir casos especiais (homens usa mesmo ícone que mulheres)
# E teatro usa mesmo ícone que produção
content = content.replace(
    '<div style="font-size: 3rem; margin-bottom: 15px;">👩</div>\n                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Homens</h3>',
    '<div style="font-size: 3rem; margin-bottom: 15px;">👨</div>\n                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Homens</h3>'
)

content = content.replace(
    '<div style="font-size: 3rem; margin-bottom: 15px;">🎬</div>\n                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Teatro</h3>',
    '<div style="font-size: 3rem; margin-bottom: 15px;">🎭</div>\n                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Teatro</h3>'
)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print('Emojis restaurados!')
