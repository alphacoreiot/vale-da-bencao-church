@extends('layouts.app')

@section('title', 'Vale da Benção Church - Site Oficial')

@section('content')
<!-- Hero Section com Background -->
<section class="hero" id="home">
    <!-- YouTube Video Background -->
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
    <div class="scroll-indicator">
        <span>Role para baixo</span>
        <div class="scroll-arrow">↓</div>
    </div>
</section>

<!-- Seção de Carrossel de Banners -->
<section class="carousel-section">
    <div class="carousel-content-wrapper">
        <div class="section-header">
            <span class="section-label">Vale News</span>
            <h2 class="section-main-title">Fique por dentro das últimas novidades</h2>
            <p class="section-description">Acompanhe os eventos, ministérios e tudo que acontece na igreja</p>
        </div>
        <div class="carousel-wrapper-full">
            <div class="carousel-banners" id="carouselBanners">
            @foreach($sections as $section)
            <div class="banner-slide">
                <div class="banner-overlay"></div>
                <div class="banner-content">
                    <div class="banner-icon">
                        @switch($section->slug)
                            @case('eventos') 📅 @break
                            @case('ministerios') 🙏 @break
                            @case('estudos') 📖 @break
                            @case('galeria') 📸 @break
                            @case('testemunhos') ⭐ @break
                            @case('contato') 📞 @break
                            @case('boas-vindas') 👋 @break
                            @default 📄
                        @endswitch
                    </div>
                    <h2>{{ $section->name }}</h2>
                    <p>{{ $section->description }}</p>
                    <a href="{{ route('section.show', $section->slug) }}" class="banner-btn">Explorar</a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="carousel-dots" id="carouselDots"></div>
        <button class="carousel-control prev" id="bannerPrev">‹</button>
        <button class="carousel-control next" id="bannerNext">›</button>
    </div>
    </div>
</section>

<!-- Seção Devocional -->
<section class="devocional-section">
    <div class="devocional-container">
        <div class="devocional-content">
            <div class="section-header">
                <span class="section-label">Devocional Diário - {{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY') }}</span>
                <h2 class="section-main-title">Nada nos Separará do Amor de Deus</h2>
                <p class="section-description">Medite na Palavra e fortaleça sua fé hoje</p>
            </div>
            <div class="devocional-verse">
                <p class="verse-text">
                    "Porque estou certo de que nem a morte, nem a vida, nem os anjos, nem os principados, 
                    nem as coisas presentes, nem as futuras, nem os poderes, nem a altura, nem a profundidade, 
                    nem qualquer outra criatura poderá separar-nos do amor de Deus, que está em Cristo Jesus, nosso Senhor."
                </p>
                <p class="verse-reference">— Romanos 8:38-39</p>
            </div>
            <div class="devocional-reflection">
                <h3>Reflexão</h3>
                <p>
                    O amor de Deus é inabalável e eterno. Não importa quais desafios você enfrente hoje, 
                    saiba que nada pode separar você do amor incondicional de Cristo. Ele está com você 
                    em cada momento, em cada circunstância. Permita que essa verdade traga paz ao seu coração.
                </p>
            </div>
            <div class="devocional-prayer">
                <h3>Oração</h3>
                <p>
                    Senhor, obrigado pelo Seu amor que nunca falha. Ajuda-me a lembrar que nada pode me separar de Ti. 
                    Fortaleça minha fé e encha meu coração com a certeza do Teu amor. Em nome de Jesus, amém.
                </p>
            </div>
        </div>
        <div class="devocional-image">
            <div class="image-overlay"></div>
            <div class="bible-icon">📖</div>
        </div>
    </div>
</section>

<!-- Seção Culto Online -->
<section class="culto-online-section">
    <div class="culto-online-container">
        <div class="culto-online-content">
            <div class="section-header">
                <span class="section-label">Culto Online</span>
                <h2 class="section-main-title">Participe do Culto de Onde Você Estiver</h2>
                <p class="section-description">Assista nossas transmissões ao vivo e experimente a presença de Deus</p>
            </div>
            <div class="culto-schedule">
                <div class="schedule-item">
                    <div class="schedule-icon">📅</div>
                    <div class="schedule-info">
                        <h4>Domingos</h4>
                        <p>18:30 - 20:30</p>
                    </div>
                </div>
                <div class="schedule-item">
                    <div class="schedule-icon">🕒</div>
                    <div class="schedule-info">
                        <h4>Quartas-feiras</h4>
                        <p>19:00 - 21:00</p>
                    </div>
                </div>
                <div class="schedule-item">
                    <div class="schedule-icon">⭐</div>
                    <div class="schedule-info">
                        <h4>Quintas-feiras</h4>
                        <p>19:00 - 21:00 (Célula)</p>
                    </div>
                </div>
            </div>
            <div class="culto-buttons">
                <a href="https://youtube.com/@igrejavaledasbencaos" target="_blank" class="culto-btn primary">
                    <span class="btn-icon">▶</span>
                    Assistir Ao Vivo
                </a>
                <a href="https://youtube.com/@igrejavaledasbencaos" target="_blank" class="culto-btn secondary">
                    <span class="btn-icon">📺</span>
                    Ver Cultos Anteriores
                </a>
            </div>
        </div>
        <div class="culto-video-preview">
            <div class="video-frame">
                <iframe 
                    width="100%" 
                    height="100%" 
                    src="https://www.youtube.com/embed/hM9YbvTNOOg" 
                    title="Culto ao Vivo - Igreja Vale da Benção" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</section>

<!-- Chat IA Flutuante -->
<div class="ai-chat" id="aiChat">
    <div class="ai-chat-header">
        <div class="header-left">
            <img src="{{ asset('assets/OffWhite-Simbol-8.png') }}" alt="IA">
            <span>Assistente Virtual</span>
        </div>
        <div class="header-right">
            <button class="ai-chat-maximize" id="chatMaximize" title="Maximizar">⛶</button>
            <button class="ai-chat-close" id="chatClose">✕</button>
        </div>
    </div>
    <div class="ai-chat-body" id="chatBody">
        <div class="ai-message">
            <p>Olá! Sou o assistente virtual da igreja. Como posso ajudá-lo(a) hoje?</p>
        </div>
    </div>
    <div class="ai-chat-footer">
        <input type="text" id="chatInput" placeholder="Digite sua mensagem...">
        <button id="chatSend">➤</button>
    </div>
</div>

<!-- Botão para abrir Chat -->
<button class="ai-chat-button" id="chatButton">
    <img src="{{ asset('assets/perfil.png') }}" alt="Chat IA">
    <div class="chat-bubble" id="chatBubble"></div>
</button>
@endsection

