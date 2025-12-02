<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vale da Benção Church - Site Oficial')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Igreja Vale da Bênção Church em Camaçari-BA. Cultos aos domingos e quartas, células às quintas. Venha fazer parte da nossa família!">
    <meta name="author" content="Igreja Vale da Bênção Church">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="igreja, evangélica, camaçari, bahia, culto, célula, vale da bênção, church">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Igreja Vale da Bênção Church - Camaçari BA">
    <meta property="og:description" content="Igreja evangélica em Camaçari-BA. Cultos aos domingos 18:30 e quartas 19:00. Células às quintas. Venha fazer parte da nossa família!">
    <meta property="og:image" content="{{ asset('assets/logo.png') }}">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Igreja Vale da Bênção Church">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Igreja Vale da Bênção Church - Camaçari BA">
    <meta name="twitter:description" content="Igreja evangélica em Camaçari-BA. Cultos aos domingos 18:30 e quartas 19:00. Venha fazer parte da nossa família!">
    <meta name="twitter:image" content="{{ asset('assets/logo.png') }}">
    
    <!-- Geo Tags (SEO Local) -->
    <meta name="geo.region" content="BR-BA">
    <meta name="geo.placename" content="Camaçari">
    <meta name="geo.position" content="-12.6957261;-38.2934209">
    <meta name="ICBM" content="-12.6957261, -38.2934209">
    
    <meta name="theme-color" content="#D4AF37">
    <link rel="icon" type="image/png" href="{{ asset('assets/perfil.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/perfil.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&family=Exo:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
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
        /* PWA Install Prompt */
        .pwa-install-prompt {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            padding: 16px 24px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            z-index: 10000;
            display: none;
            align-items: center;
            gap: 16px;
            max-width: 90%;
            width: 380px;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }
        .pwa-install-prompt.show {
            display: flex;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { transform: translateX(-50%) translateY(100px); opacity: 0; }
            to { transform: translateX(-50%) translateY(0); opacity: 1; }
        }
        .pwa-install-prompt img {
            width: 50px;
            height: 50px;
            border-radius: 12px;
        }
        .pwa-install-prompt .pwa-text {
            flex: 1;
        }
        .pwa-install-prompt .pwa-text h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            color: #D4AF37;
        }
        .pwa-install-prompt .pwa-text p {
            margin: 0;
            font-size: 13px;
            opacity: 0.8;
        }
        .pwa-install-prompt .pwa-buttons {
            display: flex;
            gap: 8px;
        }
        .pwa-install-prompt button {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .pwa-install-prompt .pwa-install-btn {
            background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%);
            color: #000;
        }
        .pwa-install-prompt .pwa-install-btn:hover {
            transform: scale(1.05);
        }
        .pwa-install-prompt .pwa-close-btn {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .pwa-install-prompt .pwa-close-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        /* iOS Install Instructions */
        .ios-install-instructions {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            z-index: 10000;
            display: none;
            flex-direction: column;
            gap: 12px;
            max-width: 90%;
            width: 340px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            text-align: center;
        }
        .ios-install-instructions.show {
            display: flex;
            animation: slideUp 0.5s ease;
        }
        .ios-install-instructions h4 {
            margin: 0;
            color: #D4AF37;
            font-size: 18px;
        }
        .ios-install-instructions p {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
        }
        .ios-install-instructions .ios-icon {
            font-size: 24px;
        }
        .ios-install-instructions .pwa-close-btn {
            background: rgba(255,255,255,0.1);
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 8px;
        }
    </style>
    
    <!-- Structured Data JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Church",
        "name": "Igreja Vale da Bênção Church",
        "alternateName": "Vale da Bênção Church",
        "description": "Igreja evangélica em Camaçari-BA. Cultos, células, eventos e comunidade de fé.",
        "url": "https://valedabencao.com.br",
        "logo": "https://valedabencao.com.br/assets/logo.png",
        "image": "https://valedabencao.com.br/assets/logo.png",
        "telephone": "+55-71-99999-9999",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Rua do Cajueiro - Parque das Palmeiras",
            "addressLocality": "Camaçari",
            "addressRegion": "BA",
            "postalCode": "42800-000",
            "addressCountry": "BR"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "-12.6957261",
            "longitude": "-38.2934209"
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Sunday",
                "opens": "18:30",
                "closes": "20:30"
            },
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Wednesday",
                "opens": "19:00",
                "closes": "21:00"
            },
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Thursday",
                "opens": "19:00",
                "closes": "21:00"
            }
        ],
        "sameAs": [
            "https://www.instagram.com/valedabencaochurch",
            "https://www.youtube.com/@valedabencaochurch"
        ]
    }
    </script>
    
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

    <!-- PWA Install Prompt -->
    <div class="pwa-install-prompt" id="pwaInstallPrompt">
        <img src="{{ asset('assets/perfil.png') }}" alt="Vale da Bênção">
        <div class="pwa-text">
            <h4>📱 Instalar App</h4>
            <p>Adicione à tela inicial para acesso rápido</p>
        </div>
        <div class="pwa-buttons">
            <button class="pwa-install-btn" id="pwaInstallBtn">Instalar</button>
            <button class="pwa-close-btn" id="pwaCloseBtn">✕</button>
        </div>
    </div>
    
    <!-- iOS Install Instructions -->
    <div class="ios-install-instructions" id="iosInstallPrompt">
        <h4>📱 Instalar App</h4>
        <p>
            <span class="ios-icon">⬆️</span><br>
            Toque em <strong>Compartilhar</strong> e depois em<br>
            <strong>"Adicionar à Tela de Início"</strong>
        </p>
        <button class="pwa-close-btn" id="iosCloseBtn">Entendi</button>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/push-notifications.js') }}"></script>
    
    <!-- Service Worker & Push Notifications & PWA Install -->
    <script>
        // Registrar Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('✅ Service Worker registrado'))
                    .catch(err => console.log('❌ Erro SW:', err));
            });
        }
        
        // ========================================
        // PWA Install Prompt
        // ========================================
        let deferredPrompt;
        const pwaInstallPrompt = document.getElementById('pwaInstallPrompt');
        const pwaInstallBtn = document.getElementById('pwaInstallBtn');
        const pwaCloseBtn = document.getElementById('pwaCloseBtn');
        const iosInstallPrompt = document.getElementById('iosInstallPrompt');
        const iosCloseBtn = document.getElementById('iosCloseBtn');
        
        // Verificar se já instalou ou já fechou
        const pwaInstalled = localStorage.getItem('pwaInstalled');
        const pwaDismissed = localStorage.getItem('pwaDismissed');
        const pwaDismissedTime = localStorage.getItem('pwaDismissedTime');
        
        // Se fechou há menos de 7 dias, não mostrar
        const shouldShowPrompt = () => {
            if (pwaInstalled) return false;
            if (pwaDismissed && pwaDismissedTime) {
                const daysSinceDismiss = (Date.now() - parseInt(pwaDismissedTime)) / (1000 * 60 * 60 * 24);
                if (daysSinceDismiss < 7) return false;
            }
            return true;
        };
        
        // Android/Desktop - beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            console.log('📱 PWA install prompt disponível');
            
            if (shouldShowPrompt()) {
                setTimeout(() => {
                    pwaInstallPrompt.classList.add('show');
                }, 3000);
            }
        });
        
        // Botão instalar
        pwaInstallBtn?.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log('PWA install outcome:', outcome);
                
                if (outcome === 'accepted') {
                    localStorage.setItem('pwaInstalled', 'true');
                }
                
                deferredPrompt = null;
                pwaInstallPrompt.classList.remove('show');
            }
        });
        
        // Botão fechar
        pwaCloseBtn?.addEventListener('click', () => {
            pwaInstallPrompt.classList.remove('show');
            localStorage.setItem('pwaDismissed', 'true');
            localStorage.setItem('pwaDismissedTime', Date.now().toString());
        });
        
        // iOS Detection
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        const isInStandaloneMode = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
        
        if (isIOS && !isInStandaloneMode && shouldShowPrompt()) {
            setTimeout(() => {
                iosInstallPrompt.classList.add('show');
            }, 5000);
        }
        
        iosCloseBtn?.addEventListener('click', () => {
            iosInstallPrompt.classList.remove('show');
            localStorage.setItem('pwaDismissed', 'true');
            localStorage.setItem('pwaDismissedTime', Date.now().toString());
        });
        
        // Detectar quando PWA é instalado
        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA instalado com sucesso!');
            localStorage.setItem('pwaInstalled', 'true');
            pwaInstallPrompt.classList.remove('show');
        });
        

    </script>
    
    @stack('scripts')
</body>
</html>
