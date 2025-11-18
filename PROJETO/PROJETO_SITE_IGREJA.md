# Projeto: Site da Igreja com Laravel e IA

## 🎨 Paleta de Cores do Projeto

```
Cor Principal (Branco):    #FFFFFF
Cor de Fundo (Preto):      #000000
Cor de Destaque (Vermelho): #9C0505
Cor Secundária (Ciano):    #D0FBF9
Cor de Acento (Laranja):   #FF3700
```

## 📋 Visão Geral

Este documento descreve um projeto completo e tecnicamente viável para criar um site institucional de igreja usando Laravel como backend, com recursos avançados de gerenciamento de conteúdo, rotação automática de seções e agentes de IA personalizados.

## ✅ Viabilidade Técnica

**SIM, TUDO É POSSÍVEL!** Todas as funcionalidades solicitadas são perfeitamente viáveis com as tecnologias disponíveis atualmente.

## 🎯 Funcionalidades Principais

### 1. **Backend Laravel**
- Framework PHP robusto e moderno
- Sistema de autenticação e autorização integrado
- ORM Eloquent para gerenciamento de banco de dados
- Sistema de rotas e controllers organizado
- Suporte nativo a cache e otimização

### 2. **Gerenciador de Conteúdo por Seção**
- **Painel Administrativo** para cada seção do site
- **Seções configuráveis:**
  - Boas-vindas / Home
  - Eventos e Programação
  - Ministérios
  - Galeria de Fotos/Vídeos
  - Estudos Bíblicos
  - Testemunhos
  - Notícias
  - Contato
  - Transmissão ao Vivo
  - Doações/Dízimos

### 3. **Suporte Multimídia**
- Upload e gerenciamento de imagens
- Upload e streaming de vídeos
- Player de áudio para músicas/pregações
- Galeria de fotos responsiva
- Integração com YouTube/Vimeo
- Armazenamento local ou cloud (AWS S3, Cloudinary)

### 4. **Rotação Automática de Seções (Destaque Dinâmico)**
- Sistema de **prioridade/peso** configurável para cada seção
- **Agendador (Laravel Scheduler)** para trocar automaticamente
- **Configuração por seção:**
  - Tempo de destaque (dias/horas)
  - Prioridade (1-10)
  - Período ativo (datas início/fim)
  - Ordem de rotação
- **Algoritmos disponíveis:**
  - Rotação circular (round-robin)
  - Baseado em prioridade
  - Baseado em data/evento
  - Aleatório ponderado

### 5. **Agente de IA por Seção**
- **IA personalizada para cada seção** do site
- **Funcionalidades dos agentes:**
  - Chatbot contextual
  - Respostas sobre a seção específica
  - Sugestões de conteúdo
  - Assistência aos visitantes
  - Busca inteligente dentro da seção
- **Tecnologias sugeridas:**
  - OpenAI API (GPT-4)
  - Anthropic Claude API
  - Google Gemini API
  - Ou modelo local (Ollama)

## 🏗️ Arquitetura Proposta

### Stack Tecnológico

```
Frontend:
├── Blade Templates (Laravel)
├── Alpine.js (via CDN - sem build)
├── Bootstrap 5 (via CDN)
└── JavaScript Vanilla (sem build tools)

Backend:
├── Laravel 10/11 (PHP 8.2+)
├── MySQL ou PostgreSQL
├── Redis (Cache e Queues) - OPCIONAL
└── Laravel Sanctum (API Auth)

Multimídia:
├── Intervention Image (processamento)
├── FFmpeg (vídeos) - se disponível no servidor
└── Cloudinary ou Upload direto

IA:
├── OpenAI PHP Client
├── Laravel HTTP Client
└── Custom AI Service Layer

Infraestrutura:
├── PHP 8.2+ (ou 8.1 mínimo)
├── Composer (para instalar Laravel)
├── Apache/Nginx (mod_rewrite habilitado)
└── ❌ SEM Node.js/NPM necessário
```

### ⚠️ Importante: Ambiente sem Node.js

**Solução: Desenvolvimento 100% PHP**
- Usar CDNs para CSS/JS (Bootstrap, Alpine.js)
- Blade templates compilados pelo próprio Laravel
- Assets estáticos sem build process
- Minificação via PHP (se necessário)

## 📊 Estrutura do Banco de Dados

### Principais Tabelas

```sql
-- Seções do Site
sections
├── id
├── name (varchar)
├── slug (varchar)
├── description (text)
├── is_active (boolean)
├── priority (integer)
├── display_order (integer)
├── highlight_duration (integer) -- minutos
├── last_highlighted_at (timestamp)
├── next_highlight_at (timestamp)
├── ai_agent_config (json)
└── timestamps

-- Conteúdo de Seções
section_contents
├── id
├── section_id (foreign)
├── title (varchar)
├── content (longtext)
├── type (enum: text, video, audio, gallery)
├── is_published (boolean)
├── published_at (timestamp)
└── timestamps

-- Multimídia
media
├── id
├── section_id (foreign)
├── content_id (foreign, nullable)
├── type (enum: image, video, audio)
├── path (varchar)
├── thumbnail (varchar)
├── size (integer)
├── mime_type (varchar)
├── alt_text (varchar)
└── timestamps

-- Configuração de Rotação
rotation_config
├── id
├── rotation_type (enum: circular, priority, scheduled, random)
├── interval_minutes (integer)
├── is_active (boolean)
└── timestamps

-- Log de Destaques
highlight_logs
├── id
├── section_id (foreign)
├── started_at (timestamp)
├── ended_at (timestamp)
├── reason (varchar)
└── timestamps

-- Conversas com IA
ai_conversations
├── id
├── section_id (foreign)
├── user_session (varchar)
├── messages (json)
├── context (json)
└── timestamps
```

## 🤖 Sistema de Agentes IA

### Configuração por Seção

```json
{
  "section": "eventos",
  "ai_agent": {
    "name": "Assistente de Eventos",
    "personality": "amigável e informativo",
    "knowledge_base": [
      "calendario_eventos",
      "programacao_semanal",
      "informacoes_cultos"
    ],
    "capabilities": [
      "responder_sobre_horarios",
      "sugerir_eventos",
      "dar_informacoes_localizacao",
      "registrar_interesse"
    ],
    "prompts": {
      "system": "Você é um assistente da igreja que ajuda com informações sobre eventos...",
      "context": "Use apenas informações dos eventos cadastrados no sistema..."
    }
  }
}
```

### Exemplos de Agentes por Seção

1. **Agente de Boas-Vindas**
   - Apresenta a igreja
   - Responde sobre crenças e valores
   - Orienta novos visitantes

2. **Agente de Eventos**
   - Informa horários de cultos
   - Detalha eventos especiais
   - Ajuda com inscrições

3. **Agente de Estudos Bíblicos**
   - Responde dúvidas sobre estudos
   - Sugere materiais
   - Explica temas bíblicos

4. **Agente de Ministérios**
   - Apresenta ministérios disponíveis
   - Ajuda a encontrar o ministério ideal
   - Fornece informações de contato

## ⚙️ Sistema de Rotação de Destaques

### Funcionamento

```php
// Laravel Scheduler (app/Console/Kernel.php)
protected function schedule(Schedule $schedule)
{
    // Executa a cada hora
    $schedule->call(function () {
        app(SectionRotationService::class)->rotate();
    })->hourly();
    
    // Ou configurável
    $schedule->call(function () {
        app(SectionRotationService::class)->rotate();
    })->everyMinutes(config('rotation.interval'));
}
```

### Algoritmo de Rotação

```php
class SectionRotationService
{
    public function rotate()
    {
        $config = RotationConfig::active()->first();
        
        switch ($config->rotation_type) {
            case 'circular':
                return $this->circularRotation();
            case 'priority':
                return $this->priorityBasedRotation();
            case 'scheduled':
                return $this->scheduledRotation();
            case 'random':
                return $this->randomWeightedRotation();
        }
    }
    
    private function priorityBasedRotation()
    {
        // Seleciona seção com maior prioridade
        // que não foi destacada recentemente
        $section = Section::active()
            ->orderBy('priority', 'desc')
            ->orderBy('last_highlighted_at', 'asc')
            ->first();
            
        $this->setHighlight($section);
    }
}
```

## 🔒 Segurança

### Práticas Implementadas

1. **Autenticação e Autorização**
   - Laravel Breeze ou Fortify
   - Roles e permissões (Spatie Permission)
   - 2FA opcional

2. **Proteção de Dados**
   - CSRF Protection (nativo Laravel)
   - XSS Protection
   - SQL Injection Prevention (Eloquent ORM)
   - Rate Limiting

3. **Upload Seguro**
   - Validação de tipos de arquivo
   - Limite de tamanho
   - Sanitização de nomes
   - Armazenamento fora do public

4. **API IA Segura**
   - API Keys em .env
   - Rate limiting
   - Input sanitization
   - Content moderation

## 📁 Estrutura de Diretórios Laravel

```
projeto-igreja/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── SectionController.php
│   │   │   │   ├── ContentController.php
│   │   │   │   ├── MediaController.php
│   │   │   │   └── AIConfigController.php
│   │   │   └── Frontend/
│   │   │       ├── HomeController.php
│   │   │       └── SectionController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Section.php
│   │   ├── SectionContent.php
│   │   ├── Media.php
│   │   ├── RotationConfig.php
│   │   └── AIConversation.php
│   ├── Services/
│   │   ├── SectionRotationService.php
│   │   ├── MediaService.php
│   │   └── AIAgentService.php
│   └── Console/
│       └── Commands/
│           └── RotateSections.php
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   │   ├── sections/
│   │   │   ├── content/
│   │   │   └── media/
│   │   └── frontend/
│   │       ├── layouts/
│   │       ├── sections/
│   │       └── components/
│   └── js/
│       └── ai-chat.js
├── routes/
│   ├── web.php
│   ├── api.php
│   └── admin.php
├── database/
│   ├── migrations/
│   └── seeders/
└── config/
    ├── ai.php
    ├── rotation.php
    └── media.php
```

## 🚀 Plano de Implementação

### Fase 1: Setup Inicial (1-2 semanas)
- [ ] Instalação Laravel via Composer
- [ ] Configuração do ambiente (sem Node.js)
- [ ] Setup do banco de dados
- [ ] Sistema de autenticação
- [ ] Layout base com CDNs (Bootstrap + Alpine.js)

### Fase 2: Gerenciamento de Seções (2-3 semanas)
- [ ] CRUD de seções
- [ ] CRUD de conteúdo
- [ ] Sistema de permissões
- [ ] Interface admin

### Fase 3: Sistema Multimídia (2 semanas)
- [ ] Upload de imagens
- [ ] Upload de vídeos
- [ ] Galeria
- [ ] Players de mídia

### Fase 4: Rotação Automática (1-2 semanas)
- [ ] Configuração de rotação
- [ ] Scheduler
- [ ] Algoritmos de rotação
- [ ] Dashboard de monitoramento

### Fase 5: Integração IA (2-3 semanas)
- [ ] Setup API IA
- [ ] Service layer para IA
- [ ] Chatbot interface
- [ ] Configuração por seção
- [ ] Treinamento/contexto

### Fase 6: Testes e Deploy (1-2 semanas)
- [ ] Testes funcionais
- [ ] Testes de segurança
- [ ] Otimização
- [ ] Deploy em produção

## 💰 Estimativa de Custos

### Hospedagem PHP (sem necessidade de Node.js)
- Básico (Hostinger, HostGator): R$ 20-50/mês
  - PHP 8.1+, MySQL, 10-20GB
  - Suficiente para começar
- Intermediário (UmbleHost, Locaweb): R$ 80-150/mês
  - PHP 8.2+, MySQL, 50GB, SSL grátis
  - Recomendado para produção
- Avançado (VPS): R$ 200-400/mês
  - Controle total, escalável
  - Para igrejas grandes

### APIs de IA (estimativa)
- OpenAI GPT-4: ~$0.03 por 1K tokens
- Claude API: ~$0.015 por 1K tokens
- Alternativa gratuita: Modelo local (Ollama)

### Armazenamento Multimídia
- Cloudinary: Plano gratuito até 25GB
- AWS S3: Pay-as-you-go (~$0.023/GB)

## 📚 Recursos e Documentação

### Laravel
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Scheduler](https://laravel.com/docs/scheduling)
- [Laravel File Storage](https://laravel.com/docs/filesystem)

### IA
- [OpenAI PHP Client](https://github.com/openai-php/client)
- [Laravel OpenAI Package](https://github.com/openai-php/laravel)

### Multimídia
- [Intervention Image](http://image.intervention.io/)
- [Laravel FFmpeg](https://github.com/pbmedia/laravel-ffmpeg)

## 🎨 Sugestões de Design

### Frontend
- Design limpo e acolhedor
- Cores institucionais da igreja
- Responsivo (mobile-first)
- Acessibilidade (WCAG)
- Loading otimizado

### Componentes Principais
- Hero section rotativa
- Card de eventos
- Player de transmissão ao vivo
- Chat IA flutuante
- Galeria de fotos
- Formulário de contato

### Layout Templates (via CDN)
```html
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap 5 via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Alpine.js via CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- CSS customizado (direto no Blade ou arquivo static) -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    @yield('content')
    
    <!-- Bootstrap JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript customizado -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
```

## 🔧 Configurações Recomendadas

### PHP
```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 256M
```

### Laravel (.env)
```env
APP_NAME="Igreja [Nome]"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://suaigreja.com.br

# Rotação
ROTATION_ENABLED=true
ROTATION_INTERVAL=60 # minutos

# IA
OPENAI_API_KEY=sua_chave_aqui
AI_MODEL=gpt-4
AI_MAX_TOKENS=500

# Mídia
FILESYSTEM_DISK=s3
AWS_BUCKET=sua-igreja-media
```

## ✅ Próximos Passos

1. **Aprovar o projeto e escopo**
2. **Definir identidade visual**
3. **Escolher provider de IA**
4. **Configurar ambiente de desenvolvimento (apenas PHP + Composer)**
5. **Iniciar implementação**

## 🛠️ Setup sem Node.js

### Instalação Laravel (apenas Composer)

```bash
# 1. Instalar Laravel via Composer
composer create-project laravel/laravel site-igreja

# 2. Configurar .env
cd site-igreja
cp .env.example .env
php artisan key:generate

# 3. Configurar banco de dados no .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=igreja_db
# DB_USERNAME=root
# DB_PASSWORD=senha

# 4. Rodar migrations
php artisan migrate

# 5. Iniciar servidor (desenvolvimento local)
php artisan serve
```

### Assets sem Build (CDN)

**Todos os recursos virão via CDN:**
- ✅ Bootstrap 5
- ✅ Alpine.js
- ✅ jQuery (se necessário)
- ✅ Font Awesome
- ✅ Animate.css
- ✅ Swiper (carrossel)

**CSS/JS customizado:** Arquivos estáticos em `public/`

### Hospedagem PHP Tradicional

**Estrutura de upload para servidor:**
```
public_html/
├── index.php (Laravel public/index.php)
├── .htaccess (Laravel public/.htaccess)
├── css/
├── js/
├── images/
└── storage/ (link simbólico)

app/ (fora do public_html - mais seguro)
├── vendor/
├── app/
├── config/
├── database/
├── resources/
└── routes/
```

---

## 📞 Considerações Finais

Este projeto é **100% viável** e utiliza tecnologias modernas e confiáveis. O Laravel é perfeito para este caso de uso, oferecendo:

- ✅ Segurança robusta
- ✅ Facilidade de manutenção
- ✅ Escalabilidade
- ✅ Comunidade ativa
- ✅ Documentação excelente

A combinação de gerenciamento de conteúdo dinâmico + rotação automática + IA por seção criará uma experiência única e engajante para os membros e visitantes da igreja.

**Deseja que eu comece a implementar o projeto?** Posso criar toda a estrutura inicial do Laravel com as configurações necessárias!

---

*Documento criado em: 16 de novembro de 2025*
*Versão: 1.0*
