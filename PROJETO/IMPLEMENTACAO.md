# Guia de Implementação - Vale da Benção Church

## 📋 Checklist de Desenvolvimento

### ✅ Fase 0: Protótipo Frontend (CONCLUÍDO)
- [x] Design e layout responsivo
- [x] Hero section com vídeo background
- [x] Animações de texto
- [x] Menu responsivo (desktop/mobile)
- [x] Paleta de cores aplicada
- [x] Tipografia (Encode Sans Condensed, Exo)
- [x] Chat IA (interface)
- [x] Footer

### 🔄 Fase 1: Setup Laravel (PRÓXIMO)

#### 1.1 Instalação Base
```bash
# Criar projeto Laravel
composer create-project laravel/laravel vale-da-bencao-church

# Entrar no diretório
cd vale-da-bencao-church

# Configurar .env
cp .env.example .env
php artisan key:generate
```

#### 1.2 Configurar Banco de Dados
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=igreja_db
DB_USERNAME=root
DB_PASSWORD=
```

#### 1.3 Instalar Pacotes Essenciais
```bash
# Laravel Breeze (Autenticação)
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate

# Spatie Permission (Roles)
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# Intervention Image (Manipulação de imagens)
composer require intervention/image
```

#### 1.4 Migrar Frontend Atual
- [ ] Copiar index.html para `resources/views/welcome.blade.php`
- [ ] Mover style.css para `public/css/app.css`
- [ ] Mover script.js para `public/js/app.js`
- [ ] Atualizar caminhos de assets
- [ ] Criar layout base Blade

### 📂 Fase 2: Estrutura de Diretórios

```
vale-da-bencao-church/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Frontend/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── EventController.php
│   │   │   │   ├── MinistryController.php
│   │   │   │   └── GalleryController.php
│   │   │   └── Admin/
│   │   │       ├── SectionController.php
│   │   │       ├── ContentController.php
│   │   │       └── MediaController.php
│   │   └── Middleware/
│   │       └── CheckAdmin.php
│   ├── Models/
│   │   ├── Section.php
│   │   ├── Content.php
│   │   ├── Media.php
│   │   └── RotationConfig.php
│   └── Services/
│       ├── SectionRotationService.php
│       ├── MediaService.php
│       └── AIService.php
├── database/
│   └── migrations/
│       ├── 2025_11_16_000001_create_sections_table.php
│       ├── 2025_11_16_000002_create_contents_table.php
│       ├── 2025_11_16_000003_create_media_table.php
│       └── 2025_11_16_000004_create_rotation_configs_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── admin.blade.php
│       │   └── partials/
│       │       ├── header.blade.php
│       │       ├── footer.blade.php
│       │       └── chat.blade.php
│       ├── frontend/
│       │   ├── home.blade.php
│       │   ├── events.blade.php
│       │   ├── ministries.blade.php
│       │   └── gallery.blade.php
│       └── admin/
│           ├── dashboard.blade.php
│           ├── sections/
│           └── contents/
└── public/
    ├── css/
    │   └── app.css
    ├── js/
    │   └── app.js
    └── assets/
        ├── logo.png
        └── background.png
```

### 🗃️ Fase 3: Migrations

#### sections_table.php
```php
Schema::create('sections', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->integer('priority')->default(1);
    $table->integer('display_order')->default(0);
    $table->integer('highlight_duration')->default(60);
    $table->timestamp('last_highlighted_at')->nullable();
    $table->timestamp('next_highlight_at')->nullable();
    $table->json('ai_agent_config')->nullable();
    $table->timestamps();
});
```

#### contents_table.php
```php
Schema::create('contents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('section_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->longText('content');
    $table->enum('type', ['text', 'video', 'audio', 'gallery'])->default('text');
    $table->boolean('is_published')->default(false);
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

### 🎯 Fase 4: Models

#### Section.php
```php
class Section extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'is_active', 
        'priority', 'display_order', 'highlight_duration',
        'last_highlighted_at', 'next_highlight_at', 'ai_agent_config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_highlighted_at' => 'datetime',
        'next_highlight_at' => 'datetime',
        'ai_agent_config' => 'array',
    ];

    public function contents()
    {
        return $this->hasMany(Content::class);
    }
}
```

### 🛣️ Fase 5: Rotas

#### web.php
```php
// Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/eventos', [EventController::class, 'index'])->name('events');
Route::get('/ministerios', [MinistryController::class, 'index'])->name('ministries');
Route::get('/galeria', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contato', [ContactController::class, 'index'])->name('contact');

// Admin (protegido)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('sections', SectionController::class);
    Route::resource('contents', ContentController::class);
    Route::resource('media', MediaController::class);
});
```

### 🤖 Fase 6: Sistema de IA

#### AIService.php
```php
class AIService
{
    public function chat(string $sectionId, string $message, array $context = [])
    {
        $section = Section::findOrFail($sectionId);
        $config = $section->ai_agent_config;
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => $config['prompts']['system']],
                ['role' => 'user', 'content' => $message],
            ],
        ]);
        
        return $response->json();
    }
}
```

### ⚙️ Fase 7: Rotação Automática

#### SectionRotationService.php
```php
class SectionRotationService
{
    public function rotate()
    {
        $config = RotationConfig::where('is_active', true)->first();
        
        if (!$config) return;
        
        switch ($config->rotation_type) {
            case 'priority':
                $this->priorityBasedRotation();
                break;
            case 'circular':
                $this->circularRotation();
                break;
        }
    }
    
    private function priorityBasedRotation()
    {
        $section = Section::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->orderBy('last_highlighted_at', 'asc')
            ->first();
            
        if ($section) {
            $section->update([
                'last_highlighted_at' => now(),
                'next_highlight_at' => now()->addMinutes($section->highlight_duration),
            ]);
        }
    }
}
```

#### Kernel.php (Scheduler)
```php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        app(SectionRotationService::class)->rotate();
    })->hourly();
}
```

### 🎨 Fase 8: Blade Templates

#### layouts/app.blade.php
```blade
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vale da Benção Church')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&family=Exo:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')
    
    @yield('content')
    
    @include('partials.footer')
    @include('partials.chat')
    
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
```

## 📝 Notas de Implementação

### Ordem Recomendada
1. ✅ Frontend base (FEITO)
2. Setup Laravel
3. Autenticação e roles
4. Models e migrations
5. CRUD básico (admin)
6. Frontend dinâmico
7. Upload de mídia
8. Rotação automática
9. Integração IA
10. Testes e otimização

### Ferramentas Úteis
- **Laravel Debugbar**: `composer require barryvdh/laravel-debugbar --dev`
- **Laravel IDE Helper**: `composer require barryvdh/laravel-ide-helper --dev`
- **Laravel Telescope**: `composer require laravel/telescope`

### Configurações de Produção
```bash
# Otimizar autoload
composer install --optimize-autoloader --no-dev

# Cache de configuração
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrations
php artisan migrate --force
```

## 🚀 Deploy

### Checklist de Deploy
- [ ] Configurar .env de produção
- [ ] SSL/HTTPS ativado
- [ ] Banco de dados configurado
- [ ] Cache configurado (Redis)
- [ ] Queue worker rodando
- [ ] Scheduler configurado (cron)
- [ ] Logs configurados
- [ ] Backup automático

### Cron Job (Scheduler)
```bash
* * * * * cd /caminho-projeto && php artisan schedule:run >> /dev/null 2>&1
```

---

**Documento atualizado:** 16 de novembro de 2025
