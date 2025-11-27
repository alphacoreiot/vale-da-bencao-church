#!/usr/bin/env python3
# Script para corrigir icones do Hadash e Musicos

file_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/section.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Icone atual do Hadash (estrelas) - trocar para dançarina
old_hadash = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Hadash</h3>'''

# Novo icone Hadash - pessoa dancando (silhueta elegante)
new_hadash = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" style="width:48px;height:48px;"><circle cx="12" cy="4" r="2"/><path d="M12 6v3m0 0l-3 4m3-4l3 4m-6 0l-2 5m2-5l4 2m2-2l2 5m-2-5l-4 2m-2 3l-1 3m9-3l1 3"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Hadash</h3>'''

content = content.replace(old_hadash, new_hadash)

# Icone atual do Musicos - trocar para nota musical/guitarra melhor
old_musicos = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V4.5A2.25 2.25 0 0 0 17.25 2.25H15M3.75 18.75a2.25 2.25 0 0 0 2.25 2.25h.75a2.25 2.25 0 0 0 2.25-2.25V12m-6 6.75V4.5A2.25 2.25 0 0 1 5.25 2.25h1.5A2.25 2.25 0 0 1 9 4.5v1.875m-6 12.375v-3.75" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Músicos</h3>'''

# Novo icone Musicos - nota musical classica
new_musicos = '''<div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19c-2.5 0-4-1.5-4-3s1.5-2 4-2v-11l10-2v11c0 1.5-1.5 3-4 3s-4-1.5-4-3 1.5-2 4-2V5l-6 1.2V16c0 1.5-1.5 3-4 3z"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Músicos</h3>'''

content = content.replace(old_musicos, new_musicos)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Icones Hadash e Musicos corrigidos!")
