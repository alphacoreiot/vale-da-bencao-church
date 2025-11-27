# Deploy Push Notifications to Hostinger
# Usage: .\deploy-push.ps1

$SERVER = "u817008098@212.1.209.49"
$PORT = "65002"
$REMOTE_PATH = "~/domains/valedabencao.com.br/public_html"

Write-Host "========================================" -ForegroundColor Yellow
Write-Host "  Deploy Push Notifications - Hostinger" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "Senha SSH: Mirla1995#" -ForegroundColor Cyan
Write-Host ""

# Lista de arquivos para copiar
$files = @(
    @{local="app/Models/PushSubscription.php"; remote="app/Models/"},
    @{local="app/Services/PushNotificationService.php"; remote="app/Services/"},
    @{local="app/Http/Controllers/PushNotificationController.php"; remote="app/Http/Controllers/"},
    @{local="app/Http/Controllers/Admin/ContentManagerController.php"; remote="app/Http/Controllers/Admin/"},
    @{local="public/js/push-notifications.js"; remote="public/js/"},
    @{local="public/service-worker.js"; remote=""},
    @{local="routes/web.php"; remote="routes/"},
    @{local="config/services.php"; remote="config/"},
    @{local="resources/views/layouts/app.blade.php"; remote="resources/views/layouts/"},
    @{local="database/migrations/2025_11_25_200000_create_push_subscriptions_table.php"; remote="database/migrations/"}
)

Write-Host "Arquivos a serem enviados:" -ForegroundColor Green
foreach ($file in $files) {
    Write-Host "  - $($file.local)" -ForegroundColor White
}

Write-Host ""
Write-Host "Comandos SCP para copiar (execute manualmente):" -ForegroundColor Yellow
Write-Host ""

foreach ($file in $files) {
    $localPath = $file.local -replace "/", "\"
    $remotePath = "$REMOTE_PATH/$($file.remote)"
    Write-Host "scp -P $PORT `"$localPath`" ${SERVER}:$remotePath" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "Ou use este comando para copiar todos de uma vez:" -ForegroundColor Yellow
Write-Host ""
Write-Host "# Conecte via SSH e execute:" -ForegroundColor Green
Write-Host "ssh -p $PORT $SERVER" -ForegroundColor Cyan
Write-Host ""
Write-Host "# No servidor, adicione as chaves VAPID ao .env:" -ForegroundColor Green
Write-Host 'echo "VAPID_PUBLIC_KEY=BPX9zell8781nas1I4szcch4zuq9kd78Pk6D6TkCSndaYWakaiQbyngCT798xgcDlMN792THyAWuaw4uyC-rwQk" >> ~/domains/valedabencao.com.br/public_html/.env' -ForegroundColor Cyan
Write-Host 'echo "VAPID_PRIVATE_KEY=4ZTv3gz4bNPlmS5MIWFdA1bWUS0kyH2XWekGy_rAhsk" >> ~/domains/valedabencao.com.br/public_html/.env' -ForegroundColor Cyan
Write-Host ""
Write-Host "# Criar tabela push_subscriptions via SQL (no PHPMyAdmin ou terminal):" -ForegroundColor Green
Write-Host @"
CREATE TABLE IF NOT EXISTS push_subscriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    endpoint VARCHAR(500) NOT NULL UNIQUE,
    p256dh_key VARCHAR(255) NOT NULL,
    auth_token VARCHAR(255) NOT NULL,
    user_agent VARCHAR(255) NULL,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_active (active)
);
"@ -ForegroundColor Cyan
