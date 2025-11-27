#!/usr/bin/env python3
# Script para corrigir icone do Teatro - duas mascaras

file_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/section.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Icone atual Teatro (rosto sorrindo)
old_teatro = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Teatro</h3>'''

# Novo icone Teatro - duas mascaras classicas (comedia e tragedia)
new_teatro = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;"><path d="M2 4c0 0 2-2 5-2s5 2 5 2v7c0 2.5-2 5-5 5s-5-2.5-5-5V4z"/><circle cx="5" cy="8" r="0.5" fill="#D4AF37"/><circle cx="9" cy="8" r="0.5" fill="#D4AF37"/><path d="M5 11c0 0 1 1.5 2 1.5s2-1.5 2-1.5"/><path d="M12 6c0 0 2-2 5-2s5 2 5 2v7c0 2.5-2 5-5 5s-5-2.5-5-5V6z"/><circle cx="15" cy="10" r="0.5" fill="#D4AF37"/><circle cx="19" cy="10" r="0.5" fill="#D4AF37"/><path d="M15 14c0 0 1-1.5 2-1.5s2 1.5 2 1.5"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Teatro</h3>'''

content = content.replace(old_teatro, new_teatro)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Icone Teatro corrigido - duas mascaras!")
