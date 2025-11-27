<?php
/**
 * Script de Instalação de Push Notifications
 * Execute este script no servidor para configurar tudo
 * 
 * URL: https://valedabencao.com.br/install-push.php
 * 
 * REMOVA ESTE ARQUIVO APÓS USO!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$basePath = __DIR__;

// Verifica se é o caminho correto
if (strpos($basePath, 'public_html') !== false && strpos($basePath, 'public') !== false) {
    // Estamos em public_html/public, subir um nível
    $basePath = dirname($basePath);
}

echo "<h1>🔔 Instalação Push Notifications</h1>";
echo "<pre>";

// 1. Criar diretórios necessários
$dirs = [
    $basePath . '/app/Models',
    $basePath . '/app/Services',
    $basePath . '/app/Http/Controllers',
    $basePath . '/app/Http/Controllers/Admin',
    $basePath . '/public/js',
    $basePath . '/database/migrations'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "📁 Criado: $dir\n";
    }
}

// 2. PushSubscription Model
$pushSubscriptionModel = <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'p256dh_key',
        'auth_token',
        'user_agent',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function getActive()
    {
        return self::where('active', true)->get();
    }

    public static function deactivateByEndpoint(string $endpoint)
    {
        return self::where('endpoint', $endpoint)->update(['active' => false]);
    }
}
PHP;

file_put_contents($basePath . '/app/Models/PushSubscription.php', $pushSubscriptionModel);
echo "✅ Criado: app/Models/PushSubscription.php\n";

// 3. PushNotificationService
$pushService = <<<'PHP'
<?php

namespace App\Services;

use App\Models\PushSubscription;

class PushNotificationService
{
    private string $publicKey;
    private string $privateKey;
    private bool $initialized = false;

    public function __construct()
    {
        $this->publicKey = config('services.vapid.public_key', '');
        $this->privateKey = config('services.vapid.private_key', '');
        
        if (!empty($this->publicKey) && !empty($this->privateKey)) {
            $this->initialized = true;
        }
    }

    public function isAvailable(): bool
    {
        return $this->initialized;
    }

    public function sendToAll(string $title, string $body, array $data = []): array
    {
        $results = ['sent' => 0, 'failed' => 0, 'removed' => 0, 'total' => 0];
        
        if (!$this->isAvailable()) {
            \Log::warning('PushNotificationService não está disponível - chaves VAPID não configuradas');
            return $results;
        }

        $subscriptions = PushSubscription::getActive();
        $results['total'] = $subscriptions->count();

        if ($results['total'] === 0) {
            \Log::info('Nenhuma subscription ativa para enviar notificação');
            return $results;
        }

        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'icon' => '/assets/logo.png',
            'badge' => '/assets/logo.png',
            'data' => $data
        ]);

        try {
            $auth = [
                'VAPID' => [
                    'subject' => 'mailto:contato@valedabencao.com.br',
                    'publicKey' => $this->publicKey,
                    'privateKey' => $this->privateKey,
                ],
            ];

            $webPush = new \Minishlink\WebPush\WebPush($auth);

            foreach ($subscriptions as $sub) {
                $subscription = \Minishlink\WebPush\Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'keys' => [
                        'p256dh' => $sub->p256dh_key,
                        'auth' => $sub->auth_token,
                    ],
                ]);

                $webPush->queueNotification($subscription, $payload);
            }

            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();

                if ($report->isSuccess()) {
                    $results['sent']++;
                    \Log::info('Push enviado com sucesso para: ' . substr($endpoint, 0, 50) . '...');
                } else {
                    $results['failed']++;
                    $reason = $report->getReason();
                    \Log::warning('Falha ao enviar push: ' . $reason);
                    
                    if ($report->isSubscriptionExpired()) {
                        PushSubscription::deactivateByEndpoint($endpoint);
                        $results['removed']++;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar notificações push: ' . $e->getMessage());
            $results['failed'] = $results['total'];
        }

        return $results;
    }

    public function notifyNewDevocional(string $titulo, string $descricao): array
    {
        return $this->sendToAll(
            "📖 Novo Devocional: {$titulo}",
            $descricao,
            [
                'url' => '/#devocional',
                'type' => 'devocional'
            ]
        );
    }
}
PHP;

file_put_contents($basePath . '/app/Services/PushNotificationService.php', $pushService);
echo "✅ Criado: app/Services/PushNotificationService.php\n";

// 4. PushNotificationController
$pushController = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    public function getPublicKey()
    {
        return response()->json([
            'publicKey' => config('services.vapid.public_key')
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string|max:500',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        try {
            PushSubscription::updateOrCreate(
                ['endpoint' => $request->endpoint],
                [
                    'p256dh_key' => $request->input('keys.p256dh'),
                    'auth_token' => $request->input('keys.auth'),
                    'user_agent' => $request->header('User-Agent'),
                    'active' => true
                ]
            );

            return response()->json(['success' => true, 'message' => 'Inscrição salva com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao salvar inscrição'], 500);
        }
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string'
        ]);

        PushSubscription::where('endpoint', $request->endpoint)->delete();

        return response()->json(['success' => true, 'message' => 'Inscrição removida']);
    }

    public function testNotification()
    {
        try {
            $totalSubs = PushSubscription::where('active', true)->count();
            
            if ($totalSubs === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma subscription ativa. Primeiro ative as notificações no site.'
                ]);
            }

            $pushService = new PushNotificationService();
            $result = $pushService->sendToAll(
                '🙏 Vale da Bênção Church',
                'Esta é uma notificação de teste! Se você está vendo isso, as notificações estão funcionando.',
                ['url' => '/', 'type' => 'test']
            );

            return response()->json([
                'success' => true,
                'message' => 'Notificação de teste enviada!',
                'stats' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar: ' . $e->getMessage()
            ], 500);
        }
    }
}
PHP;

file_put_contents($basePath . '/app/Http/Controllers/PushNotificationController.php', $pushController);
echo "✅ Criado: app/Http/Controllers/PushNotificationController.php\n";

// 5. push-notifications.js
$pushJs = <<<'JS'
class PushManager {
    constructor() {
        this.swRegistration = null;
        this.isSubscribed = false;
        this.vapidPublicKey = null;
    }

    async init() {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            console.log('Push notifications não suportadas neste navegador');
            return false;
        }

        try {
            const response = await fetch('/api/push/public-key');
            const data = await response.json();
            this.vapidPublicKey = data.publicKey;

            if (!this.vapidPublicKey) {
                console.error('Chave VAPID não configurada');
                return false;
            }

            this.swRegistration = await navigator.serviceWorker.ready;
            const subscription = await this.swRegistration.pushManager.getSubscription();
            this.isSubscribed = subscription !== null;
            
            console.log('Push Manager inicializado. Inscrito:', this.isSubscribed);
            return true;
        } catch (error) {
            console.error('Erro ao inicializar Push Manager:', error);
            return false;
        }
    }

    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');
        
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    async subscribe() {
        try {
            const permission = await Notification.requestPermission();
            
            if (permission !== 'granted') {
                console.log('Permissão para notificações negada');
                return { success: false, message: 'Permissão negada' };
            }

            const applicationServerKey = this.urlBase64ToUint8Array(this.vapidPublicKey);
            const subscription = await this.swRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            });

            const response = await fetch('/api/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(subscription.toJSON())
            });

            const result = await response.json();
            
            if (result.success) {
                this.isSubscribed = true;
                return { success: true, message: 'Notificações ativadas!' };
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Erro ao inscrever:', error);
            return { success: false, message: 'Erro ao ativar notificações' };
        }
    }

    async unsubscribe() {
        try {
            const subscription = await this.swRegistration.pushManager.getSubscription();
            
            if (subscription) {
                await fetch('/api/push/unsubscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ endpoint: subscription.endpoint })
                });

                await subscription.unsubscribe();
                this.isSubscribed = false;
                
                return { success: true, message: 'Notificações desativadas' };
            }
        } catch (error) {
            console.error('Erro ao desinscrever:', error);
            return { success: false, message: 'Erro ao desativar notificações' };
        }
    }

    async toggle() {
        if (this.isSubscribed) {
            return await this.unsubscribe();
        } else {
            return await this.subscribe();
        }
    }

    static isSupported() {
        return 'serviceWorker' in navigator && 'PushManager' in window;
    }

    static getPermissionStatus() {
        if (!('Notification' in window)) {
            return 'unsupported';
        }
        return Notification.permission;
    }
}

window.pushManager = new PushManager();

document.addEventListener('DOMContentLoaded', async () => {
    if (PushManager.isSupported()) {
        await window.pushManager.init();
        updateNotificationUI();
    }
});

function updateNotificationUI() {
    const btn = document.getElementById('notificationToggle');
    if (!btn) return;
    
    const permission = PushManager.getPermissionStatus();
    
    if (permission === 'denied') {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-bell-slash"></i> Bloqueado';
        btn.style.background = '#666';
    } else if (window.pushManager.isSubscribed) {
        btn.innerHTML = '<i class="fas fa-bell"></i> Notificações Ativas';
        btn.style.background = 'linear-gradient(135deg, #28a745 0%, #1e7e34 100%)';
    } else {
        btn.innerHTML = '<i class="fas fa-bell"></i> Ativar Notificações';
        btn.style.background = 'linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)';
    }
}

async function toggleNotifications() {
    const btn = document.getElementById('notificationToggle');
    if (!btn) return;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
    
    const result = await window.pushManager.toggle();
    
    if (typeof showToast === 'function') {
        showToast(result.message, result.success ? 'success' : 'error');
    } else {
        alert(result.message);
    }
    
    btn.disabled = false;
    updateNotificationUI();
}
JS;

file_put_contents($basePath . '/public/js/push-notifications.js', $pushJs);
echo "✅ Criado: public/js/push-notifications.js\n";

// 6. service-worker.js
$serviceWorker = <<<'JS'
const CACHE_NAME = 'vale-da-bencao-v5';
const urlsToCache = [
  '/',
  '/css/style-projeto.css',
  '/js/script.js',
  '/assets/logo.png',
  '/assets/perfil.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Cache aberto');
        return cache.addAll(urlsToCache);
      })
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('Removendo cache antigo:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

self.addEventListener('push', event => {
  console.log('Push notification recebida:', event);
  
  let data = {
    title: 'Vale da Bênção',
    body: 'Você tem uma nova mensagem!',
    icon: '/assets/logo.png',
    badge: '/assets/logo.png',
    data: { url: '/' }
  };
  
  if (event.data) {
    try {
      data = { ...data, ...event.data.json() };
    } catch (e) {
      console.error('Erro ao parsear dados do push:', e);
    }
  }
  
  const options = {
    body: data.body,
    icon: data.icon || '/assets/logo.png',
    badge: data.badge || '/assets/logo.png',
    vibrate: [100, 50, 100],
    data: data.data || { url: '/' },
    actions: [
      { action: 'open', title: 'Abrir' },
      { action: 'close', title: 'Fechar' }
    ],
    requireInteraction: true
  };
  
  event.waitUntil(
    self.registration.showNotification(data.title, options)
  );
});

self.addEventListener('notificationclick', event => {
  console.log('Notificação clicada:', event);
  
  event.notification.close();
  
  if (event.action === 'close') {
    return;
  }
  
  const urlToOpen = event.notification.data?.url || '/';
  
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then(windowClients => {
        for (const client of windowClients) {
          if (client.url.includes(self.location.origin) && 'focus' in client) {
            client.navigate(urlToOpen);
            return client.focus();
          }
        }
        if (clients.openWindow) {
          return clients.openWindow(urlToOpen);
        }
      })
  );
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') {
    return;
  }
  
  if (event.request.url.includes('/admin/') || event.request.url.includes('/api/')) {
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then(response => {
        const responseToCache = response.clone();
        
        caches.open(CACHE_NAME)
          .then(cache => {
            cache.put(event.request, responseToCache);
          });
        
        return response;
      })
      .catch(() => {
        return caches.match(event.request)
          .then(response => {
            if (response) {
              return response;
            }
            if (event.request.mode === 'navigate') {
              return caches.match('/');
            }
          });
      })
  );
});
JS;

file_put_contents($basePath . '/service-worker.js', $serviceWorker);
echo "✅ Criado: service-worker.js\n";

// 7. Adicionar VAPID ao .env
$envFile = $basePath . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    if (strpos($envContent, 'VAPID_PUBLIC_KEY') === false) {
        $vapidConfig = "\n\n# Push Notifications VAPID Keys\nVAPID_PUBLIC_KEY=BPX9zell8781nas1I4szcch4zuq9kd78Pk6D6TkCSndaYWakaiQbyngCT798xgcDlMN792THyAWuaw4uyC-rwQk\nVAPID_PRIVATE_KEY=4ZTv3gz4bNPlmS5MIWFdA1bWUS0kyH2XWekGy_rAhsk\n";
        file_put_contents($envFile, $envContent . $vapidConfig);
        echo "✅ Adicionado: VAPID keys ao .env\n";
    } else {
        echo "⏭️ VAPID keys já existem no .env\n";
    }
}

// 8. Atualizar config/services.php
$servicesFile = $basePath . '/config/services.php';
if (file_exists($servicesFile)) {
    $servicesContent = file_get_contents($servicesFile);
    
    if (strpos($servicesContent, "'vapid'") === false) {
        // Adicionar antes do último ];
        $vapidConfig = "\n    'vapid' => [\n        'public_key' => env('VAPID_PUBLIC_KEY'),\n        'private_key' => env('VAPID_PRIVATE_KEY'),\n    ],\n";
        $servicesContent = preg_replace('/\];[\s]*$/', $vapidConfig . "\n];", $servicesContent);
        file_put_contents($servicesFile, $servicesContent);
        echo "✅ Adicionado: vapid config em config/services.php\n";
    } else {
        echo "⏭️ VAPID config já existe em config/services.php\n";
    }
}

// 9. Criar tabela via SQL (mostra o SQL para executar)
echo "\n📋 SQL para criar tabela (execute no PHPMyAdmin):\n";
echo "--------------------------------------------\n";
$sql = <<<SQL
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
echo $sql . "\n";
echo "--------------------------------------------\n";

// 10. Tentar criar tabela automaticamente
try {
    // Carregar Laravel para usar DB
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    \Illuminate\Support\Facades\DB::statement($sql);
    echo "✅ Tabela push_subscriptions criada automaticamente!\n";
} catch (\Exception $e) {
    echo "⚠️ Não foi possível criar tabela automaticamente: " . $e->getMessage() . "\n";
    echo "   Execute o SQL acima manualmente no PHPMyAdmin.\n";
}

// 11. Limpar cache
try {
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    
    // Limpar cache Laravel
    $cacheFiles = glob($basePath . '/bootstrap/cache/*.php');
    foreach ($cacheFiles as $file) {
        if (basename($file) !== 'packages.php' && basename($file) !== 'services.php') {
            @unlink($file);
        }
    }
    
    echo "✅ Cache limpo!\n";
} catch (\Exception $e) {
    echo "⚠️ Erro ao limpar cache: " . $e->getMessage() . "\n";
}

echo "\n</pre>";
echo "<h2>✅ Instalação Concluída!</h2>";
echo "<p><strong>Próximos passos:</strong></p>";
echo "<ol>";
echo "<li>Verifique se a tabela foi criada no banco de dados</li>";
echo "<li>Acesse o site e clique no botão de notificações</li>";
echo "<li>Teste criando um novo devocional no admin</li>";
echo "<li><strong style='color:red'>REMOVA ESTE ARQUIVO: install-push.php</strong></li>";
echo "</ol>";
echo "<p><a href='/'>Ir para o site</a> | <a href='/admin/dashboard'>Ir para o admin</a></p>";
