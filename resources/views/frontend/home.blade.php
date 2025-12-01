@extends('layouts.app')

@section('title', 'Vale da Benção Church - Site Oficial')

@section('content')
<!-- Hero Section com Background -->
<section class="hero" id="home">
    <style>
        @media (max-width: 768px) {
            .hero-logo {
                width: 180px !important;
            }
        }
    </style>
    <!-- YouTube Video Background -->
    <div class="video-background">
        <iframe 
            src="https://www.youtube.com/embed/N7__lfkWDXA?autoplay=1&mute=1&loop=1&playlist=N7__lfkWDXA&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&vq=hd1080" 
            id="heroVideo" 
            frameborder="0" 
            allow="autoplay; encrypted-media" 
            allowfullscreen>
        </iframe>
    </div>
    
    <div class="hero-overlay"></div>
    <div class="hero-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <img src="{{ asset('assets/OffWhite-Simbol-8.png') }}" alt="Vale da Bênção" class="hero-logo" style="width: 300px; height: auto; margin-bottom: 0; filter: drop-shadow(0 4px 20px rgba(0,0,0,0.5)); display: block; margin-left: auto; margin-right: auto;">
        <h1 class="hero-title-exo" style="text-align: center; width: 100%;">Amamos o que Jesus ama:</h1>
        <h2 class="hero-animated-text" id="animatedText" style="text-align: center; width: 100%;">Humildade</h2>
    </div>
    <div class="scroll-indicator">
        <span>Role para baixo</span>
        <div class="scroll-arrow">↓</div>
    </div>
</section>

<!-- Seção de Carrossel Vale News - 3D -->
<section class="carousel-section vale-news-3d-section">
    <style>
        .vale-news-3d-section {
            padding: 80px 0;
            background: linear-gradient(180deg, #000 0%, #0a0a0a 100%);
            overflow: hidden;
        }
        
        .vale-news-3d-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .vale-news-3d-wrapper {
            position: relative;
            height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 1000px;
        }
        
        .vale-news-3d-slide {
            position: absolute;
            width: 54%;
            max-width: 480px;
            height: 90%;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.5s ease-out, opacity 0.5s ease-out, filter 0.5s ease-out;
            will-change: transform, opacity;
            backface-visibility: hidden;
        }
        
        .vale-news-3d-slide img,
        .vale-news-3d-slide video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #000;
        }
        
        .vale-news-3d-slide.active {
            z-index: 100 !important;
            transform: translateX(0) scale(1);
            opacity: 1;
            filter: brightness(1);
        }
        
        .vale-news-3d-slide.active:hover {
            transform: translateX(0) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        }
        
        .vale-news-3d-slide.prev {
            z-index: 50 !important;
            transform: translateX(-70%) scale(0.75);
            opacity: 0.7;
            filter: brightness(0.5);
        }
        
        .vale-news-3d-slide.next {
            z-index: 50 !important;
            transform: translateX(70%) scale(0.75);
            opacity: 0.7;
            filter: brightness(0.5);
        }
        
        .vale-news-3d-slide.far-prev {
            z-index: 25 !important;
            transform: translateX(-120%) scale(0.55);
            opacity: 0.4;
            filter: brightness(0.3);
        }
        
        .vale-news-3d-slide.far-next {
            z-index: 25 !important;
            transform: translateX(120%) scale(0.55);
            opacity: 0.4;
            filter: brightness(0.3);
        }
        
        .vale-news-3d-slide.hidden {
            z-index: 1 !important;
            opacity: 0;
            pointer-events: none;
            transform: translateX(0) scale(0.4);
        }
        
        .vale-news-3d-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            cursor: pointer;
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }
        
        .vale-news-3d-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        
        .vale-news-3d-btn.prev {
            left: 20px;
        }
        
        .vale-news-3d-btn.next {
            right: 20px;
        }
        
        .vale-news-3d-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .vale-news-3d-dots .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        
        .vale-news-3d-dots .dot:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        .vale-news-3d-dots .dot.active {
            background: #D4AF37;
            transform: scale(1.2);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
        }
        
        /* Modal Fullscreen Vale News */
        .vale-news-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .vale-news-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }
        
        .vale-news-modal-content {
            max-width: 95vw;
            max-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .vale-news-modal-content img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            animation: valeNewsZoomIn 0.3s ease;
        }
        
        @keyframes valeNewsZoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        .vale-news-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }
        
        .vale-news-modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1) rotate(90deg);
        }
        
        .vale-news-modal-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 1.5rem;
        }
        
        .vale-news-modal-nav:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%) scale(1.1);
        }
        
        .vale-news-modal-nav.prev { left: 20px; }
        .vale-news-modal-nav.next { right: 20px; }
        
        .vale-news-modal-counter {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            background: rgba(0, 0, 0, 0.5);
            padding: 8px 20px;
            border-radius: 20px;
        }
        
        /* Responsivo */
        @media (max-width: 768px) {
            .vale-news-3d-section {
                padding: 40px 0 20px 0;
            }
            
            .vale-news-3d-section .section-header {
                margin-bottom: 15px;
            }
            
            .vale-news-3d-wrapper {
                height: 55vh;
                min-height: 350px;
            }
            
            .vale-news-3d-slide {
                width: 85%;
                max-width: none;
            }
            
            .vale-news-3d-slide.prev,
            .vale-news-3d-slide.next {
                transform: translateX(-60%) scale(0.65);
                opacity: 0.5;
            }
            
            .vale-news-3d-slide.next {
                transform: translateX(60%) scale(0.65);
            }
            
            .vale-news-3d-slide.far-prev,
            .vale-news-3d-slide.far-next {
                display: none;
            }
            
            .vale-news-3d-btn {
                width: 40px;
                height: 40px;
            }
            
            .vale-news-3d-btn.prev { left: 5px; }
            .vale-news-3d-btn.next { right: 5px; }
            
            .vale-news-3d-dots {
                margin-top: 15px;
            }
            
            .vale-news-modal-nav {
                width: 45px;
                height: 45px;
            }
        }
        
        @media (max-width: 480px) {
            .vale-news-3d-section {
                padding: 30px 0 15px 0;
            }
            
            .vale-news-3d-wrapper {
                height: 50vh;
                min-height: 300px;
            }
            
            .vale-news-3d-slide {
                width: 90%;
            }
            
            .vale-news-3d-slide.prev,
            .vale-news-3d-slide.next {
                display: none;
            }
            
            .vale-news-3d-btn {
                width: 35px;
                height: 35px;
                font-size: 1.2rem;
            }
            
            .vale-news-3d-dots .dot {
                width: 10px;
                height: 10px;
            }
        }
    </style>
    
    <div class="vale-news-3d-container">
        <div class="section-header" style="text-align: center; margin-bottom: 40px;">
            <span class="section-label">Vale News</span>
            <h2 class="section-main-title">Fique por dentro das últimas novidades</h2>
            <p class="section-description">Acompanhe os eventos, ministérios e tudo que acontece na igreja</p>
        </div>
        
        <div class="vale-news-3d-wrapper" id="valeNews3DWrapper">
            @forelse($eventosMedia as $index => $media)
                <div class="vale-news-3d-slide" data-index="{{ $index }}">
                    @if($media->type === 'image')
                        <img src="{{ asset('uploads/' . $media->path) }}" alt="{{ $media->alt_text ?? 'Vale News' }}">
                    @elseif($media->type === 'video')
                        <video controls>
                            <source src="{{ asset('uploads/' . $media->path) }}" type="{{ $media->mime_type }}">
                        </video>
                    @endif
                </div>
            @empty
                <div class="vale-news-3d-slide" data-index="0">
                    <img src="{{ asset('assets/001.jpeg') }}" alt="Vale News 1">
                </div>
                <div class="vale-news-3d-slide" data-index="1">
                    <img src="{{ asset('assets/002.jpeg') }}" alt="Vale News 2">
                </div>
                <div class="vale-news-3d-slide" data-index="2">
                    <img src="{{ asset('assets/003.jpeg') }}" alt="Vale News 3">
                </div>
                <div class="vale-news-3d-slide" data-index="3">
                    <img src="{{ asset('assets/004.jpeg') }}" alt="Vale News 4">
                </div>
                <div class="vale-news-3d-slide" data-index="4">
                    <img src="{{ asset('assets/005.jpeg') }}" alt="Vale News 5">
                </div>
                <div class="vale-news-3d-slide" data-index="5">
                    <img src="{{ asset('assets/006.jpeg') }}" alt="Vale News 6">
                </div>
            @endforelse
            
            <button class="vale-news-3d-btn prev" id="valeNewsPrev">‹</button>
            <button class="vale-news-3d-btn next" id="valeNewsNext">›</button>
        </div>
        
        <div class="vale-news-3d-dots" id="valeNewsDots"></div>
    </div>
    
    <!-- Modal Fullscreen -->
    <div class="vale-news-modal" id="valeNewsModal">
        <button class="vale-news-modal-close" id="valeNewsModalClose">✕</button>
        <button class="vale-news-modal-nav prev" id="valeNewsModalPrev">‹</button>
        <div class="vale-news-modal-content">
            <img id="valeNewsModalImage" src="" alt="Fullscreen">
        </div>
        <button class="vale-news-modal-nav next" id="valeNewsModalNext">›</button>
        <div class="vale-news-modal-counter" id="valeNewsModalCounter"></div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('valeNews3DWrapper');
        const slides = wrapper.querySelectorAll('.vale-news-3d-slide');
        const slidesArray = Array.from(slides);
        const dotsContainer = document.getElementById('valeNewsDots');
        const prevBtn = document.getElementById('valeNewsPrev');
        const nextBtn = document.getElementById('valeNewsNext');
        const totalSlides = slides.length;
        let currentIndex = 0;
        let autoPlayInterval;
        let isAnimating = false;

        // Modal elements
        const modal = document.getElementById('valeNewsModal');
        const modalImage = document.getElementById('valeNewsModalImage');
        const modalClose = document.getElementById('valeNewsModalClose');
        const modalPrev = document.getElementById('valeNewsModalPrev');
        const modalNext = document.getElementById('valeNewsModalNext');
        const modalCounter = document.getElementById('valeNewsModalCounter');

        // Criar dots
        slides.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        const dots = dotsContainer.querySelectorAll('.dot');

        function updateSlides() {
            slidesArray.forEach((slide, index) => {
                slide.classList.remove('active', 'prev', 'next', 'far-prev', 'far-next', 'hidden');
                
                const diff = index - currentIndex;
                const normalizedDiff = ((diff % totalSlides) + totalSlides) % totalSlides;
                
                if (normalizedDiff === 0) {
                    slide.classList.add('active');
                    slide.style.zIndex = '100';
                } else if (normalizedDiff === 1 || (normalizedDiff === totalSlides - 1 && totalSlides > 2)) {
                    if (normalizedDiff === 1) {
                        slide.classList.add('next');
                    } else {
                        slide.classList.add('prev');
                    }
                    slide.style.zIndex = '50';
                } else if (normalizedDiff === totalSlides - 1) {
                    slide.classList.add('prev');
                    slide.style.zIndex = '50';
                } else if (normalizedDiff === 2) {
                    slide.classList.add('far-next');
                    slide.style.zIndex = '25';
                } else if (normalizedDiff === totalSlides - 2) {
                    slide.classList.add('far-prev');
                    slide.style.zIndex = '25';
                } else {
                    slide.classList.add('hidden');
                    slide.style.zIndex = '1';
                }
            });
            
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }

        function goToSlide(index) {
            if (isAnimating || index === currentIndex) return;
            isAnimating = true;
            currentIndex = index;
            updateSlides();
            resetAutoPlay();
            setTimeout(() => { isAnimating = false; }, 500);
        }

        function nextSlide() {
            if (isAnimating) return;
            isAnimating = true;
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSlides();
            setTimeout(() => { isAnimating = false; }, 500);
        }

        function prevSlide() {
            if (isAnimating) return;
            isAnimating = true;
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateSlides();
            setTimeout(() => { isAnimating = false; }, 500);
        }

        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        }

        // Event listeners
        prevBtn.addEventListener('click', () => { prevSlide(); resetAutoPlay(); });
        nextBtn.addEventListener('click', () => { nextSlide(); resetAutoPlay(); });

        // Modal functions
        function openModal(index) {
            const slide = slidesArray[index];
            const img = slide.querySelector('img');
            if (img) {
                modalImage.src = img.src;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                updateModalCounter();
            }
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function modalNextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSlides();
            const img = slidesArray[currentIndex].querySelector('img');
            if (img) {
                modalImage.src = img.src;
                updateModalCounter();
            }
        }

        function modalPrevSlide() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateSlides();
            const img = slidesArray[currentIndex].querySelector('img');
            if (img) {
                modalImage.src = img.src;
                updateModalCounter();
            }
        }

        function updateModalCounter() {
            modalCounter.textContent = `${currentIndex + 1} / ${totalSlides}`;
        }

        // Click on slide to open modal
        slidesArray.forEach((slide, index) => {
            slide.addEventListener('click', () => {
                if (slide.classList.contains('active')) {
                    openModal(index);
                } else {
                    goToSlide(index);
                }
            });
        });

        modalClose.addEventListener('click', closeModal);
        modalPrev.addEventListener('click', modalPrevSlide);
        modalNext.addEventListener('click', modalNextSlide);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', (e) => {
            if (modal.classList.contains('active')) {
                if (e.key === 'Escape') closeModal();
                if (e.key === 'ArrowRight') modalNextSlide();
                if (e.key === 'ArrowLeft') modalPrevSlide();
            }
        });

        // Touch/Swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        wrapper.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        wrapper.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) { nextSlide(); resetAutoPlay(); }
                else { prevSlide(); resetAutoPlay(); }
            }
        }, { passive: true });

        // Initialize
        updateSlides();
        startAutoPlay();
    });
    </script>
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
        <div class="devocional-image" @if($devocional && $devocional->imagem) style="background-image: url('{{ asset('uploads/' . $devocional->imagem) }}');" @endif>
            <div class="image-overlay"></div>
        </div>
    </div>
</section>

<!-- Seção Culto Online -->
<section class="culto-online-section">
    <div class="culto-online-container">
        <div class="section-header">
            <span class="section-label">Culto Online @if($latestVideo['is_live'])<span class="live-badge">● AO VIVO</span>@endif</span>
            <h2 class="section-main-title">{{ $latestVideo['is_live'] ? 'Assista Agora ao Vivo' : 'Participe do Culto de Onde Você Estiver' }}</h2>
            <p class="section-description">{{ $latestVideo['is_live'] ? 'Estamos transmitindo ao vivo agora!' : 'Assista nossas transmissões ao vivo e experimente a presença de Deus' }}</p>
        </div>
        <div class="culto-video-preview">
            <div class="video-frame">
                <iframe 
                    id="cultoVideo"
                    width="100%" 
                    height="100%" 
                    src="https://www.youtube.com/embed/{{ $latestVideo['id'] }}?enablejsapi=1{{ $latestVideo['is_live'] ? '&autoplay=1&mute=0' : '' }}" 
                    title="{{ $latestVideo['title'] }}" 
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
                    {{ $latestVideo['is_live'] ? 'Ver no YouTube' : 'Assistir Ao Vivo' }}
                </a>
                <a href="https://www.youtube.com/@valedabencaochurch" target="_blank" class="culto-btn secondary">
                    <span class="btn-icon">📺</span>
                    Ver Cultos Anteriores
                </a>
            </div>
        </div>
    </div>
</section>

<section id="doacoes" class="doacoes-section" style="padding: 80px 0; background: linear-gradient(180deg, #000 0%, #0a0a0a 100%);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="section-header" style="text-align: center; margin-bottom: 60px;">
            <span class="section-label">Contribua</span>
            <h2 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Seja um Abençoador</h2>
            <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">Sua contribuição faz a diferença no Reino de Deus</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; max-width: 900px; margin: 0 auto;">
            <!-- Card Primícias -->
            <div class="doacao-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 20px; padding: 40px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 15px 40px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                <h3 style="color: #fff; font-size: clamp(1.3rem, 2.5vw, 1.6rem); font-weight: 700; margin-bottom: 15px;">Primícias</h3>
                <div style="background: #fff; padding: 15px; border-radius: 12px; margin-bottom: 15px; display: inline-block; cursor: pointer;" onclick="openQrModal('{{ asset('assets/primicias.jpeg') }}', 'QR Code Primícias')">
                    <img src="{{ asset('assets/primicias.jpeg') }}" alt="QR Code Primícias" style="width: 100%; max-width: 180px; height: auto; display: block; border-radius: 8px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <p style="color: rgba(255,255,255,0.7); font-size: clamp(0.85rem, 1.8vw, 0.95rem); line-height: 1.5; margin-bottom: 10px; font-style: italic;">
                    "Honra ao SENHOR com os teus bens e com as primícias de toda a tua renda"
                </p>
                <p style="color: #D4AF37; font-size: clamp(0.75rem, 1.5vw, 0.85rem); font-weight: 600; margin-bottom: 15px;">Provérbios 3:9</p>
                <div style="background: rgba(212, 175, 55, 0.15); padding: 12px; border-radius: 10px; border: 1px solid rgba(212, 175, 55, 0.3);">
                    <p style="color: rgba(255,255,255,0.6); font-size: clamp(0.7rem, 1.4vw, 0.8rem); margin-bottom: 5px;">Chave PIX:</p>
                    <p style="color: #fff; font-size: clamp(0.9rem, 1.8vw, 1rem); font-weight: 600;">(71) 99229-1423</p>
                </div>
            </div>

            <!-- Card Dízimos / Ofertas -->
            <div class="doacao-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 20px; padding: 40px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 15px 40px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                <h3 style="color: #fff; font-size: clamp(1.3rem, 2.5vw, 1.6rem); font-weight: 700; margin-bottom: 15px;">Dízimos / Ofertas</h3>
                <div style="background: #fff; padding: 15px; border-radius: 12px; margin-bottom: 15px; display: inline-block; cursor: pointer;" onclick="openQrModal('{{ asset('assets/dizimos.jpeg') }}', 'QR Code Dízimos e Ofertas')">
                    <img src="{{ asset('assets/dizimos.jpeg') }}" alt="QR Code Dízimos e Ofertas" style="width: 100%; max-width: 180px; height: auto; display: block; border-radius: 8px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <p style="color: rgba(255,255,255,0.7); font-size: clamp(0.85rem, 1.8vw, 0.95rem); line-height: 1.5; margin-bottom: 10px; font-style: italic;">
                    "Trazei todos os dízimos à casa do tesouro, para que haja mantimento na minha casa"
                </p>
                <p style="color: #D4AF37; font-size: clamp(0.75rem, 1.5vw, 0.85rem); font-weight: 600; margin-bottom: 15px;">Malaquias 3:10</p>
                <div style="background: rgba(212, 175, 55, 0.15); padding: 12px; border-radius: 10px; border: 1px solid rgba(212, 175, 55, 0.3);">
                    <p style="color: rgba(255,255,255,0.6); font-size: clamp(0.7rem, 1.4vw, 0.8rem); margin-bottom: 5px;">Chave PIX:</p>
                    <p style="color: #fff; font-size: clamp(0.9rem, 1.8vw, 1rem); font-weight: 600;">18.160.448/0001-38</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Zoom do QR Code -->
<div id="qrModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
    <div style="position: relative; max-width: 90%; max-height: 90%; text-align: center;">
        <button onclick="closeQrModal()" style="position: absolute; top: -50px; right: 0; background: rgba(212, 175, 55, 0.2); color: #fff; border: 2px solid #D4AF37; width: 50px; height: 50px; border-radius: 50%; font-size: 24px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='#D4AF37'; this.style.color='#000';" onmouseout="this.style.background='rgba(212, 175, 55, 0.2)'; this.style.color='#fff';">×</button>
        <img id="qrModalImage" style="max-width: 90%; max-height: 90vh; border-radius: 15px; box-shadow: 0 20px 80px rgba(212, 175, 55, 0.3);">
        <p id="qrModalTitle" style="color: #fff; font-size: clamp(1.2rem, 2.5vw, 1.5rem); margin-top: 20px; font-weight: 600;"></p>
    </div>
</div>

<script>
    function openQrModal(imageSrc, title) {
        const modal = document.getElementById('qrModal');
        const img = document.getElementById('qrModalImage');
        const titleEl = document.getElementById('qrModalTitle');
        img.src = imageSrc;
        titleEl.textContent = title;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    document.getElementById('qrModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeQrModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQrModal();
        }
    });
</script>

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
                    <div class="info-icon">
                        <lord-icon
                            src="https://cdn.lordicon.com/surcxhka.json"
                            trigger="loop"
                            delay="2000"
                            colors="primary:#D4AF37,secondary:#ffffff"
                            style="width:50px;height:50px">
                        </lord-icon>
                    </div>
                    <div class="info-text">
                        <h4>Endereço</h4>
                        <p>Rua Dos Buritis, 07<br>Camaçari/BA</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <lord-icon
                            src="https://cdn.lordicon.com/kbtmbyzy.json"
                            trigger="loop"
                            delay="2000"
                            colors="primary:#D4AF37,secondary:#ffffff"
                            style="width:50px;height:50px">
                        </lord-icon>
                    </div>
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
                    <lord-icon
                        src="https://cdn.lordicon.com/ofwpzftr.json"
                        trigger="hover"
                        colors="primary:#000000,secondary:#D4AF37"
                        style="width:30px;height:30px">
                    </lord-icon>
                    Traçar Rota no Google Maps
                </a>
                

                <a href="{{ route('celulas') }}" class="route-btn">
                    <lord-icon
                        src="https://cdn.lordicon.com/osuxyevn.json"
                        trigger="hover"
                        colors="primary:#000000,secondary:#D4AF37"
                        style="width:30px;height:30px">
                    </lord-icon>
                    Encontrar Célula Próxima
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

