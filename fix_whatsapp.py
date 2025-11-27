#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Corrigir a chamada do accessor
content = content.replace('whatsappLink()', 'whatsapp_link')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Corrigido whatsappLink() para whatsapp_link!")
