#!/usr/bin/env python3
import re

file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/routes/web.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Adicionar rota de doações antes da rota de secao
if "Route::get('/doacoes'" not in content:
    content = content.replace(
        "Route::get('/secao/{slug}'",
        "Route::get('/doacoes', function() { return view('frontend.doacoes'); })->name('doacoes');\nRoute::get('/secao/{slug}'"
    )
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print("Rota de doações adicionada!")
else:
    print("Rota já existe!")
