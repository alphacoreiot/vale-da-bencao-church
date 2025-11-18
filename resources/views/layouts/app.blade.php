<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vale da Benção Church - Site Oficial')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/perfil.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&family=Exo:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-projeto.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Barra de Progresso de Scroll -->
    <div class="scroll-progress" id="scrollProgress"></div>
    
    <!-- Header com Logo -->
    <header class="header">
        <button class="menu-toggle" id="menuToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="nav" id="mainNav">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('section.show', 'eventos') }}" class="nav-link">Eventos</a>
            <a href="{{ route('section.show', 'ministerios') }}" class="nav-link">Ministérios</a>
            <a href="{{ route('section.show', 'galeria') }}" class="nav-link">Galeria</a>
            <a href="{{ route('section.show', 'contato') }}" class="nav-link">Contato</a>
        </nav>
        <img src="{{ asset('assets/logo.png') }}" alt="Vale da Benção Church" class="logo">
    </header>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Contato</h4>
                    <p>📧 contato@igreja.com.br</p>
                    <p>📱 (11) 98765-4321</p>
                </div>
                <div class="footer-section">
                    <h4>Horários</h4>
                    <p>Domingo: 9h00 e 19h00</p>
                    <p>Quarta: 20h00</p>
                </div>
                <div class="footer-section">
                    <h4>Redes Sociais</h4>
                    <p>Facebook | Instagram | YouTube</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Vale da Benção Church. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
