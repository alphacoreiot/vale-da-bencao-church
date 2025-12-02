@extends('layouts.app')

@section('title', $section->name . ' - Igreja Vale da Bênção')

@section('content')
<!-- Conteúdo Principal -->
<section class="section-content-area {{ $section->slug === 'eventos' ? 'section-eventos-page' : '' }}" style="padding: 100px 0 60px 0; background: #000;">
    <div class="container">
        <!-- Título da Seção -->
        <div class="section-header" style="text-align: center; margin-bottom: 40px; padding: 0 20px; position: relative; z-index: 200;">
            <div style="margin-bottom: 15px;">
                @switch($section->slug)
                    @case('eventos') <lord-icon src="https://cdn.lordicon.com/abfverha.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:80px;height:80px"></lord-icon> @break
                    @case('ministerios') <lord-icon src="https://cdn.lordicon.com/jjoolpwc.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:80px;height:80px"></lord-icon> @break
                    @case('estudos') 📖 @break
                    @case('galeria') <lord-icon src="https://cdn.lordicon.com/vixtkkbk.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:80px;height:80px"></lord-icon> @break
                    @case('testemunhos') ⭐ @break
                    @case('contato') <lord-icon src="https://cdn.lordicon.com/srsgifqc.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:80px;height:80px"></lord-icon> @break
                    @case('boas-vindas') 👋 @break
                    @default 📄
                @endswitch
            </div>
            <h1 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">{{ $section->slug === 'contato' ? 'Contatos' : $section->name }}</h1>
            @if($section->description)
                <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">{{ $section->description }}</p>
            @endif
        </div>

        @if($section->slug === 'eventos')
            <!-- Carrossel 3D Parallax de Eventos -->
            <div class="carousel-3d-container" style="max-width: 1200px; margin: -20px auto 0; padding: 0 20px; perspective: 1200px;">
                <div class="carousel-3d-wrapper" id="carousel3DWrapper" style="
                    position: relative;
                    width: 100%;
                    height: 600px;
                    transform-style: preserve-3d;
                ">
                    <div class="carousel-3d-slide" data-index="0">
                        <img src="{{ asset('assets/000.jpeg') }}" alt="Evento Destaque">
                    </div>
                    <div class="carousel-3d-slide" data-index="1">
                        <img src="{{ asset('assets/001.jpeg') }}" alt="Evento 1">
                    </div>
                    <div class="carousel-3d-slide" data-index="2">
                        <img src="{{ asset('assets/002.jpeg') }}" alt="Evento 2">
                    </div>
                    <div class="carousel-3d-slide" data-index="3">
                        <img src="{{ asset('assets/003.jpeg') }}" alt="Evento 3">
                    </div>
                    <div class="carousel-3d-slide" data-index="4">
                        <img src="{{ asset('assets/004.jpeg') }}" alt="Evento 4">
                    </div>
                    <div class="carousel-3d-slide" data-index="5">
                        <img src="{{ asset('assets/005.jpeg') }}" alt="Evento 5">
                    </div>
                    <div class="carousel-3d-slide" data-index="6">
                        <img src="{{ asset('assets/006.jpeg') }}" alt="Evento 6">
                    </div>
                </div>
                
                <!-- Controles -->
                <button class="carousel-3d-btn prev" id="prev3D">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="carousel-3d-btn next" id="next3D">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
                
                <!-- Dots -->
                <div class="carousel-3d-dots" id="dots3D"></div>
                
                <!-- Dica de clique (esconde em telas pequenas) -->
                <p class="carousel-hint" style="text-align: center; color: rgba(255,255,255,0.5); font-size: 0.85rem; margin-top: 15px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 5px;">
                        <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/>
                    </svg>
                    Clique na imagem para ver em tela cheia
                </p>
            </div>

            <!-- Modal Fullscreen -->
            <div id="fullscreenModal" class="fullscreen-modal">
                <button class="modal-close" id="modalClose">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <button class="modal-nav prev" id="modalPrev">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="modal-nav next" id="modalNext">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
                <div class="modal-content">
                    <img id="modalImage" src="" alt="Evento em tela cheia">
                </div>
                <div class="modal-counter" id="modalCounter">1 / 6</div>
            </div>

            <style>
            .carousel-3d-container {
                position: relative;
                overflow: hidden;
            }
            
            .carousel-3d-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .carousel-hint {
                display: block;
            }
            
            @media (max-width: 768px) {
                .carousel-hint {
                    display: none;
                }
            }
            
            .carousel-3d-slide {
                position: absolute;
                width: 50%;
                max-width: 450px;
                height: 100%;
                border-radius: 16px;
                overflow: hidden;
                cursor: pointer;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
                background: rgba(0, 0, 0, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                /* Transições separadas para evitar conflitos */
                transition: transform 0.5s ease-out, opacity 0.5s ease-out, filter 0.5s ease-out;
                will-change: transform, opacity;
                backface-visibility: hidden;
                -webkit-backface-visibility: hidden;
            }
            
            .carousel-3d-slide img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }
            
            .carousel-3d-slide.active {
                z-index: 100 !important;
                transform: translateX(0) scale(1);
                opacity: 1;
                filter: brightness(1);
            }
            
            .carousel-3d-slide.active:hover {
                transform: translateX(0) scale(1.02);
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
            }
            
            .carousel-3d-slide.prev {
                z-index: 50 !important;
                transform: translateX(-70%) scale(0.75);
                opacity: 0.7;
                filter: brightness(0.5);
            }
            
            .carousel-3d-slide.next {
                z-index: 50 !important;
                transform: translateX(70%) scale(0.75);
                opacity: 0.7;
                filter: brightness(0.5);
            }
            
            .carousel-3d-slide.far-prev {
                z-index: 25 !important;
                transform: translateX(-120%) scale(0.55);
                opacity: 0.4;
                filter: brightness(0.3);
            }
            
            .carousel-3d-slide.far-next {
                z-index: 25 !important;
                transform: translateX(120%) scale(0.55);
                opacity: 0.4;
                filter: brightness(0.3);
            }
            
            .carousel-3d-slide.hidden {
                z-index: 1 !important;
                opacity: 0;
                pointer-events: none;
                transform: translateX(0) scale(0.4);
            }
            
            .carousel-3d-btn {
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
            }
            
            .carousel-3d-btn:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: translateY(-50%) scale(1.1);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            }
            
            .carousel-3d-btn.prev {
                left: 20px;
            }
            
            .carousel-3d-btn.next {
                right: 20px;
            }
            
            .carousel-3d-dots {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 30px;
            }
            
            .carousel-3d-dots .dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .carousel-3d-dots .dot:hover {
                background: rgba(255, 255, 255, 0.5);
            }
            
            .carousel-3d-dots .dot.active {
                background: #fff;
                transform: scale(1.2);
                box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            }
            
            /* Modal Fullscreen */
            .fullscreen-modal {
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
            
            .fullscreen-modal.active {
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 1;
            }
            
            .modal-content {
                max-width: 95vw;
                max-height: 90vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .modal-content img {
                max-width: 100%;
                max-height: 90vh;
                object-fit: contain;
                border-radius: 8px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
                animation: zoomIn 0.3s ease;
            }
            
            @keyframes zoomIn {
                from {
                    transform: scale(0.8);
                    opacity: 0;
                }
                to {
                    transform: scale(1);
                    opacity: 1;
                }
            }
            
            .modal-close {
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
                z-index: 10001;
            }
            
            .modal-close:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: scale(1.1) rotate(90deg);
            }
            
            .modal-nav {
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
                z-index: 10001;
            }
            
            .modal-nav:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateY(-50%) scale(1.1);
            }
            
            .modal-nav.prev {
                left: 20px;
            }
            
            .modal-nav.next {
                right: 20px;
            }
            
            .modal-counter {
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
            
            @media (max-width: 768px) {
                .carousel-3d-container {
                    padding: 0 5px !important;
                    margin-top: -125px !important;
                }
                
                .carousel-3d-wrapper {
                    height: 55vh;
                    min-height: 350px;
                }
                
                .carousel-3d-slide {
                    width: 90%;
                    max-width: none;
                }
                
                .carousel-3d-slide.prev,
                .carousel-3d-slide.next {
                    transform: translateX(-60%) scale(0.65);
                    opacity: 0.5;
                }
                
                .carousel-3d-slide.next {
                    transform: translateX(60%) scale(0.65);
                }
                
                .carousel-3d-slide.far-prev,
                .carousel-3d-slide.far-next {
                    display: none;
                }
                
                .carousel-3d-btn {
                    width: 36px;
                    height: 36px;
                }
                
                .carousel-3d-btn.prev {
                    left: 2px;
                }
                
                .carousel-3d-btn.next {
                    right: 2px;
                }
                
                .carousel-3d-dots {
                    margin-top: 15px;
                    gap: 8px;
                }
                
                .carousel-3d-dots .dot {
                    width: 10px;
                    height: 10px;
                }
                
                .modal-nav {
                    width: 40px;
                    height: 40px;
                }
                
                .modal-nav.prev {
                    left: 5px;
                }
                
                .modal-nav.next {
                    right: 5px;
                }
                
                .carousel-hint {
                    display: none !important;
                }
            }
            
            @media (max-width: 480px) {
                .carousel-3d-wrapper {
                    height: 60vh;
                    min-height: 400px;
                }
                
                .carousel-3d-slide {
                    width: 95%;
                }
                
                .carousel-3d-slide.prev,
                .carousel-3d-slide.next {
                    opacity: 0.3;
                    display: none;
                }
                
                .carousel-3d-btn {
                    width: 32px;
                    height: 32px;
                }
                
                .carousel-3d-dots {
                    margin-top: 8px;
                    gap: 6px;
                }
                
                .carousel-3d-dots .dot {
                    width: 8px;
                    height: 8px;
                }
            }
            </style>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.getElementById('carousel3DWrapper');
                const slides = wrapper.querySelectorAll('.carousel-3d-slide');
                const slidesArray = Array.from(slides);
                const dotsContainer = document.getElementById('dots3D');
                const prevBtn = document.getElementById('prev3D');
                const nextBtn = document.getElementById('next3D');
                const totalSlides = slides.length;
                let currentIndex = 0;
                let autoPlayInterval;
                let isAnimating = false;

                // Modal elements
                const modal = document.getElementById('fullscreenModal');
                const modalImage = document.getElementById('modalImage');
                const modalClose = document.getElementById('modalClose');
                const modalPrev = document.getElementById('modalPrev');
                const modalNext = document.getElementById('modalNext');
                const modalCounter = document.getElementById('modalCounter');

                // Criar dots
                slides.forEach((_, index) => {
                    const dot = document.createElement('span');
                    dot.classList.add('dot');
                    if (index === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => goToSlide(index));
                    dotsContainer.appendChild(dot);
                });

                const dots = dotsContainer.querySelectorAll('.dot');

                function getRelativeIndex(index) {
                    return ((index % totalSlides) + totalSlides) % totalSlides;
                }

                function updateCarousel() {
                    // Primeiro, remover todas as classes e resetar
                    slidesArray.forEach((slide) => {
                        slide.classList.remove('active', 'prev', 'next', 'far-prev', 'far-next', 'hidden');
                        slide.style.zIndex = '1';
                    });
                    
                    // Depois aplicar as novas classes com z-index explícito
                    slidesArray.forEach((slide, index) => {
                        const diff = index - currentIndex;
                        const normalizedDiff = ((diff + totalSlides + Math.floor(totalSlides/2)) % totalSlides) - Math.floor(totalSlides/2);
                        
                        if (normalizedDiff === 0) {
                            slide.classList.add('active');
                            slide.style.zIndex = '100';
                        } else if (normalizedDiff === -1 || (normalizedDiff === totalSlides - 1)) {
                            slide.classList.add('prev');
                            slide.style.zIndex = '50';
                        } else if (normalizedDiff === 1 || (normalizedDiff === -(totalSlides - 1))) {
                            slide.classList.add('next');
                            slide.style.zIndex = '50';
                        } else if (normalizedDiff === -2 || normalizedDiff === totalSlides - 2) {
                            slide.classList.add('far-prev');
                            slide.style.zIndex = '25';
                        } else if (normalizedDiff === 2 || normalizedDiff === -(totalSlides - 2)) {
                            slide.classList.add('far-next');
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
                    if (isAnimating) return;
                    isAnimating = true;
                    currentIndex = getRelativeIndex(index);
                    updateCarousel();
                    resetAutoPlay();
                    setTimeout(() => isAnimating = false, 550);
                }

                function nextSlide() {
                    goToSlide(currentIndex + 1);
                }

                function prevSlide() {
                    goToSlide(currentIndex - 1);
                }

                function resetAutoPlay() {
                    clearInterval(autoPlayInterval);
                    autoPlayInterval = setInterval(nextSlide, 5000);
                }

                // Modal functions
                function openModal(index) {
                    currentIndex = index;
                    const img = slides[index].querySelector('img');
                    modalImage.src = img.src;
                    modalCounter.textContent = `${index + 1} / ${totalSlides}`;
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    clearInterval(autoPlayInterval);
                }

                function closeModal() {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                    resetAutoPlay();
                }

                function modalNextSlide() {
                    currentIndex = getRelativeIndex(currentIndex + 1);
                    const img = slides[currentIndex].querySelector('img');
                    modalImage.style.opacity = '0';
                    setTimeout(() => {
                        modalImage.src = img.src;
                        modalImage.style.opacity = '1';
                        modalCounter.textContent = `${currentIndex + 1} / ${totalSlides}`;
                        updateCarousel();
                    }, 150);
                }

                function modalPrevSlide() {
                    currentIndex = getRelativeIndex(currentIndex - 1);
                    const img = slides[currentIndex].querySelector('img');
                    modalImage.style.opacity = '0';
                    setTimeout(() => {
                        modalImage.src = img.src;
                        modalImage.style.opacity = '1';
                        modalCounter.textContent = `${currentIndex + 1} / ${totalSlides}`;
                        updateCarousel();
                    }, 150);
                }

                prevBtn.addEventListener('click', prevSlide);
                nextBtn.addEventListener('click', nextSlide);
                modalClose.addEventListener('click', closeModal);
                modalPrev.addEventListener('click', modalPrevSlide);
                modalNext.addEventListener('click', modalNextSlide);

                // Click no slide ativo abre modal
                slides.forEach((slide, index) => {
                    slide.addEventListener('click', () => {
                        if (slide.classList.contains('active')) {
                            openModal(index);
                        } else {
                            goToSlide(index);
                        }
                    });
                });

                // Fechar modal com ESC ou clique fora
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModal();
                });

                document.addEventListener('keydown', (e) => {
                    if (modal.classList.contains('active')) {
                        if (e.key === 'Escape') closeModal();
                        if (e.key === 'ArrowLeft') modalPrevSlide();
                        if (e.key === 'ArrowRight') modalNextSlide();
                    } else {
                        if (e.key === 'ArrowLeft') prevSlide();
                        if (e.key === 'ArrowRight') nextSlide();
                    }
                });

                // Touch/Swipe no carrossel
                let touchStartX = 0;
                wrapper.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                wrapper.addEventListener('touchend', (e) => {
                    const touchEndX = e.changedTouches[0].screenX;
                    if (touchStartX - touchEndX > 50) nextSlide();
                    else if (touchEndX - touchStartX > 50) prevSlide();
                }, { passive: true });

                // Touch/Swipe no modal
                modal.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                modal.addEventListener('touchend', (e) => {
                    const touchEndX = e.changedTouches[0].screenX;
                    if (touchStartX - touchEndX > 50) modalNextSlide();
                    else if (touchEndX - touchStartX > 50) modalPrevSlide();
                }, { passive: true });

                // Inicializar
                updateCarousel();
                autoPlayInterval = setInterval(nextSlide, 5000);
            });
            </script>
        @elseif($section->slug === 'ministerios')
            <!-- Grid de Ministérios -->
            <div class="ministerios-grid" style="max-width: 1200px; margin: 0 auto 60px auto; padding: 0 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                    <!-- Professores -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Professores</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Ensinando a Palavra</p>
                    </div>

                    <!-- Intercessão -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Intercessão</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Orando pela Igreja</p>
                    </div>

                    <!-- Obreiros -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M6 7h12" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Obreiros</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Servindo com Dedicação</p>
                    </div>

                    <!-- Consolidação -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Consolidação</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Cuidando das Almas</p>
                    </div>

                    <!-- Sonorização -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Sonorização</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Excelência no Som</p>
                    </div>

                    <!-- Staff Apóstolo -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Staff Apóstolo</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Apoio à Liderança</p>
                    </div>

                    <!-- Produção -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Produção</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Teatro, Música e Eventos</p>
                    </div>

                    <!-- Introdução -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Introdução</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Recepcionando com Amor</p>
                    </div>

                    <!-- Mídia -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Mídia</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Comunicação Digital</p>
                    </div>

                    <!-- Multimídia -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Multimídia</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Transmissão ao Vivo</p>
                    </div>

                    <!-- Libras -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0 .075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0-3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712-1.538l1.732-1.732a5.25 5.25 0 0 0 1.538-3.712l.003-2.024a.668.668 0 0 1 .198-.471 1.575 1.575 0 1 0-2.228-2.228 3.818 3.818 0 0 0-1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0 1 16.35 15m.002 0h-.002" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Libras</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Inclusão e Acessibilidade</p>
                    </div>

                    <!-- Músicos -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19c-2.5 0-4-1.5-4-3s1.5-2 4-2v-11l10-2v11c0 1.5-1.5 3-4 3s-4-1.5-4-3 1.5-2 4-2V5l-6 1.2V16c0 1.5-1.5 3-4 3z"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Músicos</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Adoração e Louvor</p>
                    </div>

                    <!-- Hadash -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;"><circle cx="16" cy="4" r="2"/><path d="M3 9l4-1 5 1"/><path d="M12 9l4-3"/><path d="M14 13l-3 8"/><path d="M16 6l-2 7 5 8"/><path d="M7 8l-2 4"/><path d="M5 12l6 1"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Hadash</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Ministério de Dança</p>
                    </div>

                    <!-- Limpeza -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M18 9.75 17.625 12m0 0L15.75 14.25M17.625 12l1.875 2.25M17.625 12 19.5 9.75" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Limpeza</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Mantendo a Casa de Deus</p>
                    </div>

                    <!-- Casais -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Casais</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Fortalecendo Matrimônios</p>
                    </div>

                    <!-- Batismo -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Batismo</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Celebrando a Nova Vida</p>
                    </div>

                    <!-- Mulheres -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;"><circle cx="12" cy="9" r="5"/><path d="M12 14v7"/><path d="M9 18h6"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Mulheres</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Empoderadas em Cristo</p>
                    </div>

                    <!-- Homens -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;"><circle cx="10" cy="14" r="5"/><path d="M14 10l6-6"/><path d="M16 4h4v4"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Homens</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Guerreiros de Fé</p>
                    </div>

                    <!-- Teatro -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;"><path d="M2 4c0 0 2-2 5-2s5 2 5 2v7c0 2.5-2 5-5 5s-5-2.5-5-5V4z"/><circle cx="5" cy="8" r="0.5" fill="#D4AF37"/><circle cx="9" cy="8" r="0.5" fill="#D4AF37"/><path d="M5 11c0 0 1 1.5 2 1.5s2-1.5 2-1.5"/><path d="M12 6c0 0 2-2 5-2s5 2 5 2v7c0 2.5-2 5-5 5s-5-2.5-5-5V6z"/><circle cx="15" cy="10" r="0.5" fill="#D4AF37"/><circle cx="19" cy="10" r="0.5" fill="#D4AF37"/><path d="M15 14c0 0 1-1.5 2-1.5s2 1.5 2 1.5"/></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Teatro</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Arte que Transforma</p>
                    </div>

                    <!-- Jump -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="margin-bottom: 15px; display: flex; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D4AF37" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg></div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Jump</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Ministério de Adolescentes</p>
                    </div>
                </div>
            </div>

            <!-- Vídeo dos Ministérios -->
            <div class="ministerios-section" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <div class="section-header" style="text-align: center; margin-bottom: 30px;">
                    <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 8px 20px; border-radius: 20px; font-size: clamp(12px, 2vw, 14px); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Vídeo Institucional</span>
                    <h2 style="font-size: clamp(1.5rem, 3vw, 2rem); color: #fff; font-weight: 700; margin-bottom: 10px;">Conheça Nossos Ministérios</h2>
                </div>
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 15px; box-shadow: 0 8px 30px rgba(212, 175, 55, 0.3);">
                    <iframe 
                        src="https://www.youtube.com/embed/fhB35BCk--M?autoplay=1&mute=1" 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        @elseif($section->slug === 'galeria')
            <!-- Feed do Instagram para Galeria -->
            <div class="instagram-feed-section">
                <div class="section-header" style="text-align: center; margin-bottom: 60px; padding: 0 20px;">
                    <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 8px 20px; border-radius: 20px; font-size: clamp(12px, 2vw, 14px); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Instagram</span>
                    <h2 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Acompanhe Nossos Momentos</h2>
                    <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">Veja as fotos e vídeos dos nossos cultos e eventos</p>
                </div>
                
                <!-- Embed do Instagram -->
                <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; overflow-x: hidden;">
                    <!-- Elfsight Instagram Feed | Untitled Instagram Feed -->
                    <script src="https://elfsightcdn.com/platform.js" async></script>
                    <div class="elfsight-app-f416f43e-684e-444e-9add-9ebdc7e53f18" data-elfsight-app-lazy style="max-width: 100%; width: 100%;"></div>
                </div>
            </div>
        @elseif($section->slug === 'contato')
            <!-- Página de Contato -->
            <div class="contato-section" style="max-width: 1200px; margin: 0 auto; min-height: 60vh; display: flex; align-items: center; justify-content: center;">
                <div style="width: 100%;">
                
                <!-- Versículo sobre Localização Celestial -->
                <div style="text-align: center; margin-bottom: 60px; padding: 0 20px;">
                    <div style="max-width: 800px; margin: 0 auto; padding: 40px 30px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border-left: 4px solid #D4AF37; border-radius: 10px;">
                        <p style="font-size: clamp(1.1rem, 2.5vw, 1.3rem); color: #fff; font-weight: 300; line-height: 1.8; font-style: italic; margin-bottom: 15px;">
                            "Mas a nossa cidade está nos céus, de onde também esperamos o Salvador, o Senhor Jesus Cristo."
                        </p>
                        <p style="font-size: clamp(0.9rem, 2vw, 1rem); color: #D4AF37; font-weight: 600; letter-spacing: 1px;">
                            Filipenses 3:20
                        </p>
                    </div>
                </div>
                
                <!-- Redes Sociais -->
                <div class="section-header" style="text-align: center; margin-bottom: 40px; padding: 0 20px;">
                    <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 8px 20px; border-radius: 20px; font-size: clamp(12px, 2vw, 14px); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Redes Sociais</span>
                    <h3 style="font-size: clamp(1.5rem, 3vw, 2rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Conecte-se Conosco</h3>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; max-width: 1200px; margin: 0 auto 60px; padding: 0 20px;">
                    <!-- Instagram Button -->
                    <a href="https://www.instagram.com/igvaledabencao/" target="_blank" class="social-button" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease; cursor: pointer; backdrop-filter: blur(10px); min-height: 80px;" onmouseover="this.style.background='linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)'; this.style.borderColor='#D4AF37'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;" viewBox="0 0 24 24" fill="#fff">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 4px; flex: 1;">
                            <span style="color: #fff; font-weight: 700; font-size: 1rem; line-height: 1.2;">Instagram</span>
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; font-weight: 400;">@igvaledabencao</span>
                        </div>
                    </a>
                    
                    <!-- Facebook Button -->
                    <a href="https://www.facebook.com/igvaledabencao" target="_blank" class="social-button" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease; cursor: pointer; backdrop-filter: blur(10px); min-height: 80px;" onmouseover="this.style.background='linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)'; this.style.borderColor='#D4AF37'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;" viewBox="0 0 24 24" fill="#fff">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 4px; flex: 1;">
                            <span style="color: #fff; font-weight: 700; font-size: 1rem; line-height: 1.2;">Facebook</span>
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; font-weight: 400;">Igreja Vale da Bênção</span>
                        </div>
                    </a>
                    
                    <!-- YouTube Button -->
                    <a href="https://www.youtube.com/@valedabencaochurch" target="_blank" class="social-button" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease; cursor: pointer; backdrop-filter: blur(10px); min-height: 80px;" onmouseover="this.style.background='linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)'; this.style.borderColor='#D4AF37'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;" viewBox="0 0 24 24" fill="#fff">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 4px; flex: 1;">
                            <span style="color: #fff; font-weight: 700; font-size: 1rem; line-height: 1.2;">YouTube</span>
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; font-weight: 400;">@valedabencaochurch</span>
                        </div>
                    </a>
                    
                    <!-- Localização Button -->
                    <a href="https://www.google.com/maps/search/?api=1&query=Rua+Dos+Buritis+07+Parque+Das+Palmeiras+Camaçari+BA" target="_blank" class="social-button" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease; cursor: pointer; backdrop-filter: blur(10px); min-height: 80px;" onmouseover="this.style.background='linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)'; this.style.borderColor='#D4AF37'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;" viewBox="0 0 24 24" fill="#fff">
                                <path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 4px; flex: 1;">
                            <span style="color: #fff; font-weight: 700; font-size: 1rem; line-height: 1.2;">Localização</span>
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; font-weight: 400;">Camaçari, BA</span>
                        </div>
                    </a>
                </div>
            </div>
        @elseif($section->publishedContents->isEmpty())
            <div class="empty-content" style="text-align: center; padding: 80px 20px; background: #1a1a1a; border-radius: 15px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 20px rgba(255,255,255,0.05);">
                <div class="empty-icon" style="font-size: 5rem; margin-bottom: 20px;">📝</div>
                <h3 style="font-size: 2rem; color: #fff; font-weight: 700; margin-bottom: 15px;">Conteúdo em breve</h3>
                <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem; margin-bottom: 30px;">Estamos preparando conteúdos especiais para esta seção.</p>
                <a href="{{ route('home') }}" class="btn-primary" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">← Voltar ao Início</a>
            </div>
        @else
            <div class="content-grid-full" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
                @foreach($section->publishedContents as $content)
                    <article class="content-card" style="background: #1a1a1a; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(255,255,255,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255,255,255,0.05)';">
                        <h3 class="content-card-title" style="font-size: 1.5rem; color: #fff; font-weight: 700; margin-bottom: 15px;">{{ $content->title }}</h3>
                        
                        <div class="content-meta" style="display: flex; gap: 20px; margin-bottom: 15px; color: rgba(255,255,255,0.6); font-size: 14px;">
                            <span>📅 {{ $content->published_at->format('d/m/Y') }}</span>
                            @if($content->author)
                                <span>👤 {{ $content->author }}</span>
                            @endif
                        </div>
                        
                        <div class="content-excerpt" style="color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 20px;">
                            {!! Str::limit(strip_tags($content->content), 300) !!}
                        </div>
                        
                        @if($content->media->isNotEmpty())
                            <div class="content-gallery">
                                @foreach($content->media->take(3) as $media)
                                    @if($media->isImage())
                                        <img src="{{ $media->getUrl() }}" 
                                             alt="{{ $media->alt_text }}" 
                                             class="gallery-thumb">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        
                        <a href="{{ route('section.content', [$section->slug, $content->id]) }}" 
                           class="btn-read-more" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 15px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            Ler Mais →
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
