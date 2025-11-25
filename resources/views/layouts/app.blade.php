<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vale da Benção Church - Site Oficial')</title>
    <meta name="description" content="Igreja Vale da Bênção - Um lugar de fé, amor e esperança.">
    <meta name="theme-color" content="#D4AF37">
    <link rel="icon" type="image/png" href="{{ asset('assets/perfil.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/perfil.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&family=Exo:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style-projeto.css') }}">
    <style>
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        #app-content {
            overflow-x: hidden;
            max-width: 100%;
        }
        #notificationToggle {
            position: fixed;
            bottom: 100px;
            right: 20px;
            background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%);
            color: #000;
            border: none;
            padding: 12px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            z-index: 9998;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
            transition: all 0.3s ease;
            display: none;
        }
        #notificationToggle:hover {
            transform: scale(1.05);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Barra de Progresso de Scroll -->
    <div class="scroll-progress" id="scrollProgress"></div>
    
    <!-- Header Fixo -->
    @include('components.header')

    <!-- Conteúdo Dinâmico (SPA) -->
    <div id="app-content">
        @yield('content')
    </div>

    <!-- Componentes Flutuantes Fixos -->
    @include('components.chat')

    <!-- Footer Fixo -->
    @include('components.footer')

    <!-- Botão de Notificações -->
    <button id="notificationToggle" onclick="toggleNotifications()">
        <i class="fas fa-bell"></i> Ativar Notificações
    </button>

    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/push-notifications.js') }}"></script>
    
    <!-- Service Worker & Push Notifications -->
    <script>
        // Registrar Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('✅ Service Worker registrado'))
                    .catch(err => console.log('❌ Erro SW:', err));
            });
        }
        
        // Mostrar botão de notificações após 3 segundos
        setTimeout(() => {
            if ('serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window) {
                if (Notification.permission !== 'denied') {
                    document.getElementById('notificationToggle').style.display = 'block';
                }
            }
        }, 3000);
    </script>
    
    @stack('scripts')
</body>
</html>
