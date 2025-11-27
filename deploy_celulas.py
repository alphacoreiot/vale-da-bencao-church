#!/usr/bin/env python3
"""
Script para fazer deploy das migrations e seeders de células no servidor.
"""
import subprocess
import sys

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
        print(f"❌ Erro no comando: {cmd}")
        return False
    return True

def main():
    local_path = r"d:\DEV\IGREJA\vale-da-bencao-church"
    
    # Arquivos a serem enviados
    files_to_upload = [
        ("database/migrations/2025_11_27_000001_create_geracoes_table.php", "database/migrations/"),
        ("database/migrations/2025_11_27_000002_create_celulas_table.php", "database/migrations/"),
        ("database/seeders/CelulasSeeder.php", "database/seeders/"),
        ("database/seeders/DatabaseSeeder.php", "database/seeders/"),
        ("app/Models/Geracao.php", "app/Models/"),
        ("app/Models/Celula.php", "app/Models/"),
    ]
    
    print("\n🚀 Iniciando deploy das células...")
    
    # Upload de cada arquivo
    for local_file, remote_dir in files_to_upload:
        full_local = f"{local_path}/{local_file}"
        full_remote = f"{SITE_PATH}/{remote_dir}"
        cmd = f'scp -P {SSH_PORT} "{full_local}" {SSH_HOST}:{full_remote}'
        if not run_cmd(cmd, f"Enviando {local_file}"):
            sys.exit(1)
    
    # Executar migrations
    cmd = f'ssh -p {SSH_PORT} {SSH_HOST} "cd {SITE_PATH} && php artisan migrate --force"'
    if not run_cmd(cmd, "Executando migrations"):
        sys.exit(1)
    
    # Executar seeder
    cmd = f'ssh -p {SSH_PORT} {SSH_HOST} "cd {SITE_PATH} && php artisan db:seed --class=CelulasSeeder --force"'
    if not run_cmd(cmd, "Executando seeder de células"):
        sys.exit(1)
    
    # Limpar cache
    cmd = f'ssh -p {SSH_PORT} {SSH_HOST} "cd {SITE_PATH} && php artisan config:clear && php artisan cache:clear && php artisan view:clear"'
    if not run_cmd(cmd, "Limpando caches"):
        sys.exit(1)
    
    print("\n" + "="*60)
    print("✅ Deploy concluído com sucesso!")
    print("="*60)

if __name__ == "__main__":
    main()
