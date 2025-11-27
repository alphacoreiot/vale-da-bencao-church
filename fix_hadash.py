#!/usr/bin/env python3
# Script para corrigir icone do Hadash - mulher dancando

file_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/section.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Icone atual do Hadash
old_hadash = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" style="width:48px;height:48px;"><circle cx="12" cy="4" r="2"/><path d="M12 6v3m0 0l-3 4m3-4l3 4m-6 0l-2 5m2-5l4 2m2-2l2 5m-2-5l-4 2m-2 3l-1 3m9-3l1 3"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Hadash</h3>'''

# Novo icone Hadash - mulher dancando/bailarina elegante
new_hadash = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;"><circle cx="16" cy="4" r="2"/><path d="M3 9l4-1 5 1"/><path d="M12 9l4-3"/><path d="M14 13l-3 8"/><path d="M16 6l-2 7 5 8"/><path d="M7 8l-2 4"/><path d="M5 12l6 1"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Hadash</h3>'''

content = content.replace(old_hadash, new_hadash)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Icone Hadash corrigido - mulher dancando!")
