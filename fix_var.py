#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Corrigir o nome da variável de celulasData para celulasJson
content = content.replace('$celulasData', '$celulasJson')
content = content.replace('celulasData.forEach', 'celulasJson.forEach')
content = content.replace('celulasData.filter', 'celulasJson.filter')
content = content.replace('const celulasData =', 'const celulasJson =')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Variável corrigida de celulasData para celulasJson!")
