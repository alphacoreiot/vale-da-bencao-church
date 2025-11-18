# Vale da Benção Church - Site Oficial

## 🎨 Sobre o Projeto

Site institucional da **Vale da Benção Church** desenvolvido com Laravel e recursos avançados de gerenciamento de conteúdo, rotação automática de seções e agentes de IA personalizados.

## 🚀 Tecnologias

### Frontend
- HTML5, CSS3, JavaScript Vanilla
- Google Fonts (Encode Sans Condensed, Exo)
- Responsivo (Mobile First)
- Animações CSS puras
- Vídeo background (YouTube)

### Backend (Em desenvolvimento)
- Laravel 10/11
- PHP 8.2+
- MySQL/PostgreSQL
- Redis (opcional)

## 🎨 Paleta de Cores

```
Branco:    #FFFFFF (Principal)
Preto:     #000000 (Fundo)
Vermelho:  #9C0505 (Destaque)
Ciano:     #D0FBF9 (Secundária)
Laranja:   #FF3700 (Acento)
```

## 📁 Estrutura Atual

```
PROJETO/
├── assets/
│   ├── background.png
│   └── logo.png
├── index.html          # Hero section com animações
├── style.css           # Estilos com variáveis CSS
├── script.js           # Animações e interações
├── PROJETO_SITE_IGREJA.md  # Documentação completa
└── README.md
```

## ✨ Funcionalidades Implementadas

### Frontend
- ✅ Hero section com vídeo de fundo (YouTube)
- ✅ Animação de texto sequencial ("Humildade", "Justiça", "Misericórdia", "Você")
- ✅ Menu responsivo com hambúrguer mobile
- ✅ Header fixo com logo
- ✅ Chat IA flutuante (interface)
- ✅ Footer com informações
- ✅ Design 100% responsivo
- ✅ Paleta de cores institucional aplicada

## 🔜 Próximos Passos

### Fase 1: Laravel Setup
1. Instalar Laravel via Composer
2. Migrar frontend para Blade templates
3. Configurar rotas e controllers
4. Setup do banco de dados

### Fase 2: CMS
1. CRUD de seções do site
2. CRUD de conteúdo por seção
3. Upload de mídia
4. Painel administrativo

### Fase 3: Rotação Automática
1. Sistema de prioridades
2. Laravel Scheduler
3. Algoritmos de rotação

### Fase 4: IA
1. Integração OpenAI/Claude
2. Chatbot contextual por seção
3. Respostas personalizadas

## 🛠️ Como Executar (Desenvolvimento)

### Versão Atual (HTML Puro)
```bash
# Abrir index.html no navegador
# OU usar servidor local
php -S localhost:8000
```

### Versão Laravel (Em breve)
```bash
# Instalar dependências
composer install

# Configurar .env
cp .env.example .env
php artisan key:generate

# Rodar migrations
php artisan migrate

# Iniciar servidor
php artisan serve
```

## 📝 Documentação Completa

Consulte `PROJETO_SITE_IGREJA.md` para documentação técnica completa incluindo:
- Arquitetura detalhada
- Estrutura do banco de dados
- Sistema de IA por seção
- Rotação automática
- Estimativas de custo
- Guias de deployment

## 👥 Contato

**Vale da Benção Church**
- Email: contato@igreja.com.br
- Telefone: (11) 98765-4321
- Horários: Domingo 9h/19h, Quarta 20h

## 📄 Licença

© 2025 Vale da Benção Church. Todos os direitos reservados.
