#!/usr/bin/env python3
import re

file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Substituir a parte problemática do JavaScript
old_code = '''    // Dados das células
    const celulasData = @json($geracoes->flatMap(fn($g) => $g->celulas->map(fn($c) => [
        'geracao' => $g->nome,
        'lider' => $c->lider,
        'bairro' => $c->bairro,
        'ponto_referencia' => $c->ponto_referencia,
        'contato' => $c->contato
    ])));'''

new_code = '''    // Dados das células
    const celulasData = @json($celulasJson);'''

content = content.replace(old_code, new_code)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("View corrigida!")
