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

    /**
     * Verifica se o serviço está disponível
     */
    public function isAvailable(): bool
    {
        return $this->initialized;
    }

    /**
     * Envia notificação para todos os inscritos
     */
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

    /**
     * Envia notificação de novo devocional
     */
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
