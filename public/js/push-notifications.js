/**
 * Push Notifications Manager
 * Gerencia a inscrição e desinscrição de notificações push
 */

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
