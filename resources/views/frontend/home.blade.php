@extends('layouts.app')

@section('title', 'Vale da Benção Church - Site Oficial')

@section('content')
<!-- Hero Section com Background -->
<section class="hero" id="home">
    <!-- YouTube Video Background -->
    <div class="video-background">
        <iframe 
            src="https://www.youtube.com/embed/Pr98Ozup7oU?autoplay=1&mute=1&loop=1&playlist=Pr98Ozup7oU&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&vq=hd1080" 
            id="heroVideo" 
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
                @forelse($eventosMedia as $media)
                    <div class="banner-slide">
                        @if($media->type === 'image')
                            <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->alt_text ?? 'Vale News' }}" class="banner-image">
                        @elseif($media->type === 'video')
                            <video class="banner-image" controls>
                                <source src="{{ asset('storage/' . $media->path) }}" type="{{ $media->mime_type }}">
                                Seu navegador não suporta o elemento de vídeo.
                            </video>
                        @endif
                    </div>
                @empty
                    <!-- Fallback: Imagens padrão caso não haja mídias cadastradas -->
                    <div class="banner-slide">
                        <img src="{{ asset('assets/imagem 0.jpeg') }}" alt="Vale News 1" class="banner-image">
                    </div>
                    <div class="banner-slide">
                        <img src="{{ asset('assets/imagem 1.jpg') }}" alt="Vale News 2" class="banner-image">
                    </div>
                    <div class="banner-slide">
                        <img src="{{ asset('assets/imagem 2.jpg') }}" alt="Vale News 3" class="banner-image">
                    </div>
                    <div class="banner-slide">
                        <img src="{{ asset('assets/imagem 3.jpg') }}" alt="Vale News 4" class="banner-image">
                    </div>
                    <div class="banner-slide">
                        <img src="{{ asset('assets/imagem 4.jpg') }}" alt="Vale News 5" class="banner-image">
                    </div>
                @endforelse
            </div>
        </div>
        <div class="carousel-dots" id="carouselDots"></div>
        <button class="carousel-control prev" id="bannerPrev">‹</button>
        <button class="carousel-control next" id="bannerNext">›</button>
    </div>
</section>

<!-- Seção Devocional -->
<section class="devocional-section">
    <div class="devocional-container">
        <div class="devocional-content">
            @if($devocional)
                <div class="section-header">
                    <span class="section-label">Devocional Diário - {{ $devocional->data->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY') }}</span>
                    <h2 class="section-main-title">{!! $devocional->titulo_html ?? e($devocional->titulo) !!}</h2>
                    <p class="section-description">{!! $devocional->descricao_html ?? e($devocional->descricao) !!}</p>
                </div>
                <div class="devocional-text">
                    {!! $devocional->texto_html ?? nl2br(e($devocional->texto)) !!}
                </div>
            @else
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
            @endif
        </div>
        <div class="devocional-image" @if($devocional && $devocional->imagem) style="background-image: url('{{ asset('storage/' . $devocional->imagem) }}');" @endif>
            <div class="image-overlay"></div>
        </div>
    </div>
</section>

<!-- Seção Culto Online -->
<section class="culto-online-section">
    <div class="culto-online-container">
        <div class="section-header">
            <span class="section-label">Culto Online</span>
            <h2 class="section-main-title">Participe do Culto de Onde Você Estiver</h2>
            <p class="section-description">Assista nossas transmissões ao vivo e experimente a presença de Deus</p>
        </div>
        <div class="culto-video-preview">
            <div class="video-frame">
                <iframe 
                    id="cultoVideo"
                    width="100%" 
                    height="100%" 
                    src="https://www.youtube.com/embed/hM9YbvTNOOg?enablejsapi=1" 
                    title="Culto ao Vivo - Igreja Vale da Benção" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
        <div class="culto-online-content">
            <div class="culto-buttons">
                <a href="https://www.youtube.com/@valedabencaochurch" target="_blank" class="culto-btn primary">
                    <span class="btn-icon">▶</span>
                    Assistir Ao Vivo
                </a>
                <a href="https://www.youtube.com/@valedabencaochurch" target="_blank" class="culto-btn secondary">
                    <span class="btn-icon">📺</span>
                    Ver Cultos Anteriores
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Seção Localização -->
<section class="localizacao-section">
    <div class="localizacao-container">
        <div class="section-header">
            <span class="section-label">Localização</span>
            <h2 class="section-main-title">Venha nos Visitar</h2>
            <p class="section-description">Estamos de portas abertas para receber você e sua família</p>
        </div>
        
        <div class="localizacao-content">
            <div class="localizacao-info">
                <div class="info-card">
                    <div class="info-icon">📍</div>
                    <div class="info-text">
                        <h4>Endereço</h4>
                        <p>Rua Dos Buritis, 07<br>Camaçari/BA</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">🕒</div>
                    <div class="info-text">
                        <h4>Horários dos Cultos</h4>
                        <p>Domingos: 18:30 - 20:30<br>
                           Quartas: 19:00 - 21:00<br>
                           Quintas (Célula): 19:00 - 21:00</p>
                    </div>
                </div>
                
                <a href="https://www.google.com/maps/dir/?api=1&destination=-12.6957261,-38.2934209" 
                   target="_blank" 
                   class="route-btn">
                    <span class="btn-icon">🧭</span>
                    Traçar Rota no Google Maps
                </a>
            </div>
            
            <div class="localizacao-mapa">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3890.0442756842845!2d-38.29599582516824!3d-12.695726119159485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x71642f50e03969f%3A0x91f3d25ced8e8301!2sIgreja%20Vale%20Da%20Ben%C3%A7%C3%A3o!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

<!-- Rádio somente na home -->
@include('components.radio')

@endsection

