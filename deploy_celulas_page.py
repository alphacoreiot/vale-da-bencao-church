#!/usr/bin/env python3
"""
Script para fazer deploy da página de células no servidor.
"""
import subprocess
import sys
import shutil
import os

# Configurações do servidor
SSH_HOST = "u817008098@212.1.209.49"
SSH_PORT = "65002"
SITE_PATH = "~/domains/valedabencao.com.br/public_html"

def run_cmd(cmd, description):
    print(f"\n{'='*60}")
    print(f"🔄 {description}")
    print(f"{'='*60}")
    result = subprocess.run(cmd, shell=True, capture_output=True, text=True)
    if result.stdout:
        print(result.stdout)
    if result.stderr:
        print(result.stderr)
    if result.returncode != 0:
        print(f"Erro no comando: {cmd}")
        return False
    return True

def main():
    local_path = r"d:\DEV\IGREJA\vale-da-bencao-church"
    
    # Arquivos a serem enviados
    files_to_upload = [
        ("app/Http/Controllers/Frontend/CelulasController.php", "app/Http/Controllers/Frontend/"),
        ("resources/views/frontend/celulas.blade.php", "resources/views/frontend/"),
        ("resources/views/components/header.blade.php", "resources/views/components/"),
        ("routes/web.php", "routes/"),
        ("Camacari.geojson", "public/geojson/"),
    ]
    
    print("\n Iniciando deploy da pagina de celulas...")
    
    # Criar pasta geojson no servidor
    cmd = f'ssh -p {SSH_PORT} {SSH_HOST} "mkdir -p {SITE_PATH}/public/geojson"'
    run_cmd(cmd, "Criando pasta geojson")
    
    # Upload de cada arquivo
    for local_file, remote_dir in files_to_upload:
        full_local = f"{local_path}/{local_file}"
        full_remote = f"{SITE_PATH}/{remote_dir}"
        
        # Verificar se arquivo existe
        if not os.path.exists(full_local):
            print(f"Arquivo nao encontrado: {full_local}")
            continue
            
        cmd = f'scp -P {SSH_PORT} "{full_local}" {SSH_HOST}:{full_remote}'
        if not run_cmd(cmd, f"Enviando {local_file}"):
            print(f"Erro ao enviar {local_file}, continuando...")
    
    # Limpar cache
    cmd = f'ssh -p {SSH_PORT} {SSH_HOST} "cd {SITE_PATH} && php artisan route:clear && php artisan config:clear && php artisan cache:clear && php artisan view:clear"'
    if not run_cmd(cmd, "Limpando caches"):
        sys.exit(1)
    
    print("\n" + "="*60)
    print("Deploy concluido com sucesso!")
    print("Acesse: https://valedabencao.com.br/celulas")
    print("="*60)

if __name__ == "__main__":
    main()
