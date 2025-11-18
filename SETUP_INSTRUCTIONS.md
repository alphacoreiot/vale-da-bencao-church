# Setup do Projeto - Igreja Vale da Bênção

## 🚀 Passos para Inicializar o Projeto

### 1. Verificar Requisitos
- PHP 8.2+ instalado
- Composer instalado
- MySQL/MariaDB rodando
- Apache/Nginx configurado

### 2. Configurar Banco de Dados
Crie um banco de dados MySQL:
```sql
CREATE DATABASE igreja_vale_bencao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Configurar .env
O arquivo `.env` já está configurado. Verifique:
- `DB_DATABASE=igreja_vale_bencao`
- `DB_USERNAME=root`
- `DB_PASSWORD=` (adicione sua senha se necessário)

### 4. Executar Migrações e Seeders
```powershell
# Rodar migrations
php artisan migrate

# Rodar seeders (cria seções iniciais)
php artisan db:seed
```

### 5. Criar Storage Link
```powershell
php artisan storage:link
```

### 6. Limpar Cache
```powershell
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 7. Iniciar Servidor de Desenvolvimento
```powershell
php artisan serve
```

Acesse: http://localhost:8000

## 📂 Estrutura Criada

### Models
- ✅ Section
- ✅ SectionContent
- ✅ Media
- ✅ RotationConfig
- ✅ HighlightLog
- ✅ AIConversation

### Services
- ✅ SectionRotationService
- ✅ MediaService
- ✅ AIAgentService

### Controllers
**Admin:**
- ✅ DashboardController
- ✅ SectionController
- ✅ ContentController
- ✅ MediaController
- ✅ RotationController

**Frontend:**
- ✅ HomeController
- ✅ SectionController

**API:**
- ✅ AIController

### Views
- ✅ layouts/app.blade.php (Frontend)
- ✅ layouts/admin.blade.php (Admin)
- ✅ frontend/home.blade.php
- ✅ frontend/section.blade.php
- ✅ admin/dashboard.blade.php
- ✅ admin/sections/index.blade.php

### Configurations
- ✅ config/ai.php
- ✅ config/rotation.php
- ✅ config/media.php

### Routes
- ✅ Web routes (frontend + admin)
- ✅ API routes (AI chat)

### Scheduler
- ✅ Rotação automática de seções configurada

## 🎨 Paleta de Cores

```
Branco:    #FFFFFF
Preto:     #000000
Vermelho:  #9C0505
Ciano:     #D0FBF9
Laranja:   #FF3700
```

## 🔐 Usuário Admin Padrão

Após rodar `php artisan db:seed`:
- **Email:** admin@valedabencao.com
- **Senha:** password

## 📍 URLs Importantes

### Frontend
- Home: http://localhost:8000/
- Seções: http://localhost:8000/secao/{slug}

### Admin
- Dashboard: http://localhost:8000/admin/dashboard
- Seções: http://localhost:8000/admin/sections
- Rotação: http://localhost:8000/admin/rotation

### API
- AI Chat: http://localhost:8000/api/ai/chat/{sectionSlug}

## ⚙️ Configurar Rotação Automática

Para ativar a rotação automática, adicione ao agendador de tarefas do Windows:

1. Abra o Agendador de Tarefas do Windows
2. Crie nova tarefa
3. Configure para executar a cada hora:
```powershell
cd d:\DEV\IGREJA\vale-da-bencao-church; php artisan schedule:run
```

Ou rode manualmente:
```powershell
php artisan schedule:work
```

## 🤖 Configurar IA (Opcional)

Para ativar os agentes de IA, adicione ao `.env`:

### Usando OpenAI:
```env
AI_ENABLED=true
AI_PROVIDER=openai
OPENAI_API_KEY=sua_chave_aqui
```

### Usando Claude:
```env
AI_ENABLED=true
AI_PROVIDER=claude
CLAUDE_API_KEY=sua_chave_aqui
```

### Usando Modelo Local (Ollama):
```env
AI_ENABLED=true
AI_PROVIDER=local
AI_LOCAL_ENDPOINT=http://localhost:11434/api/generate
```

## 📝 Próximos Passos

### Views Admin Faltantes (criar conforme necessário):
- admin/sections/create.blade.php
- admin/sections/edit.blade.php
- admin/contents/index.blade.php
- admin/contents/create.blade.php
- admin/contents/edit.blade.php
- admin/media/index.blade.php
- admin/media/create.blade.php
- admin/rotation/index.blade.php

### Funcionalidades Adicionais:
- [ ] Sistema de autenticação (Laravel Breeze)
- [ ] Upload de mídia com preview
- [ ] Editor WYSIWYG para conteúdo
- [ ] Chat AI com interface modal
- [ ] Dashboard de estatísticas
- [ ] Gerenciamento de usuários
- [ ] Permissões e roles

## 🛠️ Comandos Úteis

```powershell
# Ver rotas
php artisan route:list

# Criar nova migration
php artisan make:migration nome_da_migration

# Criar novo model
php artisan make:model NomeModel -m

# Criar novo controller
php artisan make:controller NomeController

# Rodar testes
php artisan test

# Verificar schedule
php artisan schedule:list
```

## ❓ Troubleshooting

### Erro "Class not found"
```powershell
composer dump-autoload
```

### Erro de permissão storage/cache
```powershell
# Windows (PowerShell como Admin)
icacls "storage" /grant Users:F /T
icacls "bootstrap\cache" /grant Users:F /T
```

### Erro de conexão com banco
Verifique:
1. MySQL está rodando
2. Banco de dados existe
3. Credenciais no .env estão corretas

---

**Projeto criado com base no documento PROJETO_SITE_IGREJA.md**
