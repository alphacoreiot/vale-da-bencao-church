# 🚀 Deploy & Configurações - Vale da Bênção Church

## ⚠️ ARQUIVO CONFIDENCIAL - NÃO COMPARTILHAR ⚠️

---

## 🖥️ Servidor Hostinger

### SSH
```bash
ssh -p 65002 u817008098@212.1.209.49
```
| Parâmetro | Valor |
|-----------|-------|
| Host | `212.1.209.49` |
| Porta | `65002` |
| Usuário | `u817008098` |
| Senha | `Mirla1995#` |

### Banco de Dados MySQL
| Parâmetro | Valor |
|-----------|-------|
| Host | `srv691.hstgr.io` |
| IP Alternativo | `212.1.209.1` |
| Porta | `3306` |
| Database | `u817008098_valedabencao` |
| Usuário | `u817008098_valedabencao` |
| Senha | `QL95yuee3k?` |

---

## 🔑 Chaves VAPID (Push Notifications)

```env
VAPID_PUBLIC_KEY=BPX9zell8781nas1I4szcch4zuq9kd78Pk6D6TkCSndaYWakaiQbyngCT798xgcDlMN792THyAWuaw4uyC-rwQk
VAPID_PRIVATE_KEY=4ZTv3gz4bNPlmS5MIWFdA1bWUS0kyH2XWekGy_rAhsk
```

---

## 📂 Caminhos no Servidor

| Descrição | Caminho |
|-----------|---------|
| Raiz do site | `/home/u817008098/domains/valedabencao.com.br/public_html` |
| Arquivo .env | `/home/u817008098/domains/valedabencao.com.br/public_html/.env` |

---

## 🛠️ Comandos Úteis

### Conectar via SSH
```bash
ssh -p 65002 u817008098@212.1.209.49
```

### No servidor (após conectar via SSH)
```bash
# Navegar até a pasta do projeto
cd ~/domains/valedabencao.com.br/public_html

# Rodar migrations
php artisan migrate

# Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Ver logs de erro
tail -f storage/logs/laravel.log
```

---

## 📋 SQL para criar tabela Push Notifications

```sql
CREATE TABLE push_subscriptions (
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
```

---

## 📁 Arquivos para Upload (Push Notifications)

- `app/Http/Controllers/PushNotificationController.php`
- `app/Models/PushSubscription.php`
- `app/Services/PushNotificationService.php`
- `public/js/push-notifications.js`
- `public/service-worker.js`
- `config/services.php`
- `routes/web.php`
- `resources/views/layouts/app.blade.php`
- `app/Http/Controllers/Admin/ContentManagerController.php`

---

## ✅ Checklist de Deploy

- [ ] Upload dos arquivos novos/modificados
- [ ] Atualizar `.env` com as chaves VAPID
- [ ] Criar tabela `push_subscriptions` no banco
- [ ] Limpar cache: `php artisan config:clear`
- [ ] Testar: acessar `/api/push/test`

---

*Última atualização: 25 de Novembro de 2025*
