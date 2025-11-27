#!/usr/bin/env python3
import subprocess
import json

# Buscar bairros do banco
result = subprocess.run([
    'ssh', '-p', '65002', 'u817008098@212.1.209.49',
    'cd ~/domains/valedabencao.com.br/public_html && php artisan tinker --execute="echo json_encode(App\\\\Models\\\\Celula::where(\'ativo\', true)->pluck(\'bairro\')->unique()->sort()->values()->toArray());"'
], capture_output=True, text=True)

print("=== BAIRROS NO BANCO ===")
print(result.stdout)
print(result.stderr)
