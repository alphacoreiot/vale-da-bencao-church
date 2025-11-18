# 🏛️ Igreja Vale da Bênção - Website Institucional

Sistema completo de gerenciamento de site para igreja com Laravel 12, incluindo rotação automática de seções e agentes de IA personalizados.

## 🎯 Características Principais

- ✅ **Gerenciamento de Seções** - CRUD completo para seções do site
- ✅ **Sistema de Conteúdo** - Gestão de textos, vídeos, áudios e galerias
- ✅ **Upload de Mídia** - Suporte para imagens, vídeos e áudio com thumbnails automáticos
- ✅ **Rotação Automática** - 4 algoritmos de rotação de seções em destaque
- ✅ **Agentes de IA** - IA personalizada por seção (OpenAI, Claude, Ollama)
- ✅ **Interface Responsiva** - Bootstrap 5 + Alpine.js via CDN (sem build)
- ✅ **Admin Panel** - Dashboard completo para gerenciamento

## 🎨 Paleta de Cores

```css
Branco:    #FFFFFF
Preto:     #000000
Vermelho:  #9C0505
Ciano:     #D0FBF9
Laranja:   #FF3700
```

## 📋 Requisitos

- PHP 8.2+
- Composer
- MySQL/MariaDB
- Apache/Nginx

## 🚀 Status Atual

### ✅ O projeto já está configurado e rodando!

**Servidor em execução:** http://localhost:8000

### URLs Principais:

- **Frontend:** http://localhost:8000
- **Admin Dashboard:** http://localhost:8000/admin/dashboard
- **Seções:** http://localhost:8000/admin/sections
- **Rotação:** http://localhost:8000/admin/rotation

### Credenciais Admin:
- **Email:** admin@valedabencao.com
- **Senha:** password

## 📚 Documentação Completa

- **[PROJETO_SITE_IGREJA.md](PROJETO_SITE_IGREJA.md)** - Especificação detalhada do projeto
- **[SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md)** - Instruções de instalação passo a passo
- **[IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md)** - Resumo completo da implementação

## 🗃️ O Que Foi Criado

### Database (6 tabelas):
- sections, section_contents, media
- rotation_configs, highlight_logs, ai_conversations

### Models (6):
- Section, SectionContent, Media
- RotationConfig, HighlightLog, AIConversation

### Services (3):
- SectionRotationService (4 algoritmos)
- MediaService (upload e thumbnails)
- AIAgentService (OpenAI, Claude, Ollama)

### Controllers (9):
- Admin: Dashboard, Section, Content, Media, Rotation
- Frontend: Home, Section
- API: AI

### Views:
- Layouts responsivos (Bootstrap 5 + Alpine.js)
- Frontend: home, section
- Admin: dashboard, sections/index

## 🔧 Comandos Principais

```powershell
# Desenvolvimento
php artisan serve              # Iniciar servidor
php artisan route:list         # Listar rotas

# Banco de Dados
php artisan migrate           # Executar migrations
php artisan db:seed          # Popular banco
php artisan migrate:fresh --seed  # Reset completo

# Cache
php artisan config:clear     # Limpar config
php artisan cache:clear      # Limpar cache
php artisan view:clear       # Limpar views

# Scheduler (Rotação Automática)
php artisan schedule:work    # Executar scheduler
```

## 🤖 Configurar IA (Opcional)

Edite `.env` para ativar:

```env
# OpenAI
AI_ENABLED=true
AI_PROVIDER=openai
OPENAI_API_KEY=sk-sua-chave

# Claude
AI_PROVIDER=claude
CLAUDE_API_KEY=sua-chave

# Ollama (Local)
AI_PROVIDER=local
AI_LOCAL_ENDPOINT=http://localhost:11434/api/generate
```

## 🎯 Seções Pré-Configuradas

1. **Boas-Vindas** - Apresentação
2. **Eventos** - Programação
3. **Ministérios** - Grupos
4. **Estudos Bíblicos** - Ensino
5. **Galeria** - Fotos/Vídeos
6. **Testemunhos** - Histórias
7. **Contato** - Informações

## 🛠️ Stack Tecnológico

- Laravel 12 (PHP 8.2+)
- Bootstrap 5 + Alpine.js (CDN)
- MySQL
- OpenAI/Claude/Ollama APIs

---

**Desenvolvido para Igreja Vale da Bênção** | *Novembro 2025*
