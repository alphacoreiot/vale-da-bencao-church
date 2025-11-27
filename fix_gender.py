#!/usr/bin/env python3
# Script para corrigir icones de Homens e Mulheres

file_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/section.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Icone atual Mulheres (pessoa generica)
old_mulheres = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Mulheres</h3>'''

# Novo icone Mulheres - simbolo feminino elegante (venus)
new_mulheres = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;"><circle cx="12" cy="9" r="5"/><path d="M12 14v7"/><path d="M9 18h6"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Mulheres</h3>'''

content = content.replace(old_mulheres, new_mulheres)

# Icone atual Homens (pessoa em circulo)
old_homens = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Homens</h3>'''

# Novo icone Homens - simbolo masculino (marte/escudo com seta)
new_homens = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;"><circle cx="10" cy="14" r="5"/><path d="M14 10l6-6"/><path d="M16 4h4v4"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Homens</h3>'''

content = content.replace(old_homens, new_homens)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Icones Homens e Mulheres corrigidos!")
