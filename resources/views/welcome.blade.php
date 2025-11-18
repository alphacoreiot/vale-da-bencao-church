<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vale da Benção Church - Site Oficial</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&family=Exo:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="header">
        <button class="menu-toggle" id="menuToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="nav" id="mainNav">
            <a href="#home" class="nav-link">Home</a>
            <a href="#eventos" class="nav-link">Eventos</a>
            <a href="#ministerios" class="nav-link">Ministérios</a>
            <a href="#galeria" class="nav-link">Galeria</a>
            <a href="#contato" class="nav-link">Contato</a>
        </nav>
        <img src="{{ asset('assets/logo.png') }}" alt="Vale da Benção Church" class="logo">
    </header>

    <section class="hero" id="home">
        <div class="video-background">
            <iframe 
                src="https://www.youtube.com/embed/Pr98Ozup7oU?autoplay=1&mute=1&loop=1&playlist=Pr98Ozup7oU&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&vq=hd1080" 
                frameborder="0"
                allow="autoplay; encrypted-media" 
                allowfullscreen>
            </iframe>
        </div>
        
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title-exo">Amamos o que Jesus ama:</h1>
            <h2 class="hero-animated-text" id="animatedText">Humildade</h2>
        </div>
    </section>

    <div class="ai-chat" id="aiChat">
        <div class="ai-chat-header">
            <span> Assistente Virtual</span>
            <button class="ai-chat-close" id="chatClose"></button>
        </div>
        <div class="ai-chat-body" id="chatBody">
            <div class="ai-message">
                <p>Olá! Sou o assistente virtual da igreja. Como posso ajudá-lo(a) hoje?</p>
            </div>
        </div>
        <div class="ai-chat-footer">
            <input type="text" id="chatInput" placeholder="Digite sua mensagem...">
            <button id="chatSend"></button>
        </div>
    </div>

    <button class="ai-chat-button" id="chatButton">
        
    </button>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Contato</h4>
                    <p> contato@igreja.com.br</p>
                    <p> (11) 98765-4321</p>
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
                <p>&copy; 2025 Vale da Benção Church. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>