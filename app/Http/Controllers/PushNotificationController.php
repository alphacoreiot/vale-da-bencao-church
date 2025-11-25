<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    /**
     * Retorna a chave pública VAPID
     */
    public function getPublicKey()
    {
        return response()->json([
            'publicKey' => config('services.vapid.public_key')
        ]);
    }

    /**
     * Registra uma nova subscription
     */
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

    /**
     * Remove uma subscription
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string'
        ]);

        PushSubscription::where('endpoint', $request->endpoint)->delete();

        return response()->json(['success' => true, 'message' => 'Inscrição removida']);
    }

    /**
     * Teste de notificação push
     */
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
