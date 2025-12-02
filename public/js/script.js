// ========================================
// SISTEMA SPA - Single Page Application
// PRIORIDADE MÁXIMA - Executar ANTES de tudo
// ========================================
(function() {
    'use strict';
    
    const appContent = document.getElementById('app-content');
    
    if (!appContent) return;
    
    // Interceptar TODOS os cliques no documento
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        
        if (!link) return;
        
        const href = link.getAttribute('href');
        
        // Ignorar links externos, âncoras, mailto, tel e javascript
        if (!href || 
            href.startsWith('http') || 
            href.startsWith('#') || 
            href.startsWith('mailto:') || 
            href.startsWith('tel:') || 
            href === 'javascript:void(0)' ||
            link.target === '_blank') {
            return;
        }
        
        // Prevenir navegação padrão
        e.preventDefault();
        e.stopPropagation();
        
        // Navegar via AJAX
        navigateTo(href);
    }, true); // true = capture phase para garantir execução antes de outros handlers
    
    // Função de navegação SPA
    function navigateTo(url) {
        // Transição de saída
        appContent.style.opacity = '0.5';
        appContent.style.transform = 'translateY(20px)';
        
        // Requisição AJAX
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Erro na requisição');
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('#app-content');
            
            if (newContent) {
                // Atualizar conteúdo
                appContent.innerHTML = newContent.innerHTML;
                
                // Transição de entrada
                setTimeout(() => {
                    appContent.style.opacity = '1';
                    appContent.style.transform = 'translateY(0)';
                }, 50);
                
                // Atualizar URL
                window.history.pushState({ url: url }, '', url);
                
                // Scroll suave para o topo
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Re-inicializar scripts da página
                reinitializePageScripts();
            }
        })
        .catch(error => {
            console.error('Erro SPA:', error);
            window.location.href = url;
        });
    }
    
    // Re-inicializar scripts específicos da página
    function reinitializePageScripts() {
        // Carrossel
        if (typeof initBannerCarousel === 'function') {
            initBannerCarousel();
        }
        
        // Zoom de imagens
        document.querySelectorAll('.banner-image').forEach(img => {
            img.onclick = function() {
                if (typeof openImageZoom === 'function') {
                    openImageZoom(this.src, this.alt);
                }
            };
        });
    }
    
    // Navegação browser (back/forward)
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.url) {
            navigateTo(e.state.url);
        } else {
            navigateTo(window.location.pathname);
        }
    });
    
    // Adicionar transição CSS
    appContent.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    
    // Estado inicial
    window.history.replaceState({ url: window.location.pathname }, '', window.location.pathname);
})();

// ========================================
// Animação de texto do hero
// ========================================
const words = ['Humildade', 'Justiça', 'Misericórdia', 'Você'];
let currentWordIndex = 0;
const animatedTextElement = document.getElementById('animatedText');

function animateWords() {
    if (!animatedTextElement) return; // Verificar se o elemento existe
    
    if (currentWordIndex < words.length - 1) {
        // Fade out
        animatedTextElement.style.animation = 'none';
        setTimeout(() => {
            animatedTextElement.style.animation = 'textFadeOut 0.6s ease forwards';
        }, 10);
        
        setTimeout(() => {
            // Trocar texto
            currentWordIndex++;
            animatedTextElement.textContent = words[currentWordIndex];
            
            // Fade in
            animatedTextElement.style.animation = 'none';
            setTimeout(() => {
                animatedTextElement.style.animation = 'textFadeIn 0.6s ease forwards';
            }, 10);
            
            // Continuar animação se não for a última palavra
            if (currentWordIndex < words.length - 1) {
                setTimeout(animateWords, 2000);
            }
        }, 600);
    }
}

// Iniciar animação após 1 segundo
setTimeout(() => {
    if (animatedTextElement) animateWords();
}, 1500);

// Menu Toggle
const menuToggle = document.getElementById('menuToggle');
const mainNav = document.getElementById('mainNav');

menuToggle.addEventListener('click', () => {
    menuToggle.classList.toggle('active');
    mainNav.classList.toggle('active');
});

// Fechar menu ao clicar em um link
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        menuToggle.classList.remove('active');
        mainNav.classList.remove('active');
    });
});

// Fechar menu ao clicar fora
document.addEventListener('click', (e) => {
    if (!menuToggle.contains(e.target) && !mainNav.contains(e.target)) {
        menuToggle.classList.remove('active');
        mainNav.classList.remove('active');
    }
});

// Configuração das seções para rotação
const sections = [
    {
        id: 'eventos',
        title: 'Eventos da Semana',
        icon: '📅',
        duration: 10000, // 10 segundos (para demo)
        content: [
            { icon: '📅', title: 'Culto de Celebração', desc: 'Domingo, 19h00 - Venha adorar conosco' },
            { icon: '🎵', title: 'Ensaio do Coral', desc: 'Quarta-feira, 20h00 - Participe!' },
            { icon: '📖', title: 'Estudo Bíblico', desc: 'Sexta-feira, 19h30 - Aprofunde sua fé' }
        ]
    },
    {
        id: 'ministerios',
        title: 'Nossos Ministérios',
        icon: '🙏',
        duration: 10000,
        content: [
            { icon: '👶', title: 'Ministério Infantil', desc: 'Educação cristã para crianças' },
            { icon: '🎤', title: 'Ministério de Louvor', desc: 'Adoração e música' },
            { icon: '🤝', title: 'Ministério de Ação Social', desc: 'Ajudando a comunidade' }
        ]
    },
    {
        id: 'estudos',
        title: 'Estudos Bíblicos',
        icon: '📖',
        duration: 10000,
        content: [
            { icon: '📚', title: 'Escola Bíblica Dominical', desc: 'Domingos, 9h00 - Todas as idades' },
            { icon: '💡', title: 'Célula de Estudo', desc: 'Terças, 20h00 - Na sua casa' },
            { icon: '🎓', title: 'Curso de Teologia', desc: 'Aprofunde seu conhecimento' }
        ]
    },
    {
        id: 'galeria',
        title: 'Galeria de Momentos',
        icon: '📸',
        duration: 10000,
        content: [
            { icon: '🎉', title: 'Eventos Especiais', desc: 'Conferências e celebrações' },
            { icon: '👨‍👩‍👧‍👦', title: 'Vida em Comunidade', desc: 'Momentos de comunhão' },
            { icon: '🌟', title: 'Testemunhos', desc: 'Vidas transformadas' }
        ]
    }
];

let currentSectionIndex = 0;
let rotationInterval;

// Função para atualizar a seção em destaque
function updateHighlightSection() {
    const section = sections[currentSectionIndex];
    const highlightTitle = document.getElementById('highlight-title');
    const highlightContent = document.querySelector('.highlight-content');

    // Fade out
    highlightContent.style.opacity = '0';
    highlightContent.style.transform = 'translateY(20px)';

    setTimeout(() => {
        highlightTitle.textContent = section.title;
        
        // Limpar conteúdo anterior
        highlightContent.innerHTML = '';

        // Adicionar novos cards
        section.content.forEach(item => {
            const card = document.createElement('div');
            card.className = 'highlight-card';
            card.innerHTML = `
                <div class="card-icon">${item.icon}</div>
                <h3>${item.title}</h3>
                <p>${item.desc}</p>
                <button class="btn btn-small">Saiba Mais</button>
            `;
            highlightContent.appendChild(card);
        });

        // Fade in
        setTimeout(() => {
            highlightContent.style.opacity = '1';
            highlightContent.style.transform = 'translateY(0)';
        }, 50);
    }, 300);

    // Próxima seção
    currentSectionIndex = (currentSectionIndex + 1) % sections.length;
}

// Iniciar rotação automática
function startRotation() {
    updateHighlightSection();
    rotationInterval = setInterval(updateHighlightSection, sections[currentSectionIndex].duration);
}

// Chat IA
const chatButton = document.getElementById('chatButton');
const chatBubble = document.getElementById('chatBubble');
const aiChat = document.getElementById('aiChat');
const chatClose = document.getElementById('chatClose');
const chatSend = document.getElementById('chatSend');
const chatInput = document.getElementById('chatInput');
const chatBody = document.getElementById('chatBody');

// Mensagens de convite baseadas em visitas
const welcomeMessages = {
    firstVisit: [
        "Olá! 😊 Primeira vez aqui?",
        "Seja bem-vindo(a)! 🙏",
        "Jesus te ama! ❤️",
        "Posso ajudar você?",
        "Vamos conversar?"
    ],
    returning: [
        "Que bom ter você de volta! 😊",
        "Bem-vindo(a) novamente! 🙏",
        "Como posso ajudar hoje?",
        "Paz do Senhor! ❤️",
        "Estamos aqui por você! 😊"
    ],
    frequent: [
        "Você já é da família! ❤️",
        "Sempre um prazer! 😊",
        "Como vai você? 🙏",
        "Podemos conversar? 💬",
        "Estou aqui para servir! 😊"
    ]
};

// Controle de visitas usando localStorage
let visitCount = parseInt(localStorage.getItem('churchVisitCount') || '0');
visitCount++;
localStorage.setItem('churchVisitCount', visitCount.toString());

let currentMessageIndex = 0;

// Escolher conjunto de mensagens baseado no número de visitas
function getMessageSet() {
    if (visitCount === 1) {
        return welcomeMessages.firstVisit;
    } else if (visitCount <= 5) {
        return welcomeMessages.returning;
    } else {
        return welcomeMessages.frequent;
    }
}

// Mostrar balão de convite com rotação de mensagens
function showChatInvite() {
    const messages = getMessageSet();
    const message = messages[currentMessageIndex % messages.length];
    
    chatBubble.textContent = message;
    chatButton.classList.add('show-bubble');
    
    setTimeout(() => {
        chatButton.classList.remove('show-bubble');
    }, 8000); // Mensagem fica visível por 8 segundos
    
    currentMessageIndex++;
}

// Mostrar primeira mensagem após 3 segundos
setTimeout(showChatInvite, 3000);

// Repetir convite a cada 12 segundos com mensagens diferentes
let inviteInterval = setInterval(() => {
    if (!aiChat.classList.contains('active')) {
        showChatInvite();
    }
}, 12000); // A cada 12 segundos

// Respostas do bot com informações da igreja
const churchInfo = {
    cultos: {
        domingo: 'Domingos das 18:30 às 20:30',
        quarta: 'Quartas-feiras das 19:00 às 21:00',
        celula: 'Célula às quintas-feiras das 19:00 às 21:00'
    },
    lideranca: 'Apóstolo Ary Dallas e Naele Santana',
    endereco: 'Rua Dos Buritis, 07 - Parque Das Palmeiras, Camaçari/BA',
    mensagem: 'Seja cordial ao convite. Focamos no que Jesus ama: Você!'
};

const botResponses = {
    'horario': `🙏 Nossos cultos são:\n\n📅 ${churchInfo.cultos.domingo}\n📅 ${churchInfo.cultos.quarta}\n📅 ${churchInfo.cultos.celula}\n\nVenha fazer parte da nossa família! ${churchInfo.mensagem}`,
    'culto': `🙏 Nossos cultos são:\n\n📅 ${churchInfo.cultos.domingo}\n📅 ${churchInfo.cultos.quarta}\n📅 ${churchInfo.cultos.celula}\n\nVenha fazer parte da nossa família! ${churchInfo.mensagem}`,
    'domingo': `📅 Culto aos ${churchInfo.cultos.domingo}. ${churchInfo.mensagem}`,
    'quarta': `📅 Culto às ${churchInfo.cultos.quarta}. ${churchInfo.mensagem}`,
    'quinta': `📅 ${churchInfo.cultos.celula}. ${churchInfo.mensagem}`,
    'celula': `📅 ${churchInfo.cultos.celula}. ${churchInfo.mensagem}`,
    'endereço': `📍 Estamos localizados na ${churchInfo.endereco}. Será um prazer recebê-lo(a)! ${churchInfo.mensagem}`,
    'endereco': `📍 Estamos localizados na ${churchInfo.endereco}. Será um prazer recebê-lo(a)! ${churchInfo.mensagem}`,
    'localização': `📍 ${churchInfo.endereco}. ${churchInfo.mensagem}`,
    'localizacao': `📍 ${churchInfo.endereco}. ${churchInfo.mensagem}`,
    'onde': `📍 ${churchInfo.endereco}. ${churchInfo.mensagem}`,
    'pastor': `✝️ Nossa liderança é formada pelo ${churchInfo.lideranca}. Será uma alegria tê-lo(a) conosco! ${churchInfo.mensagem}`,
    'apostolo': `✝️ ${churchInfo.lideranca} estão à frente da nossa igreja. ${churchInfo.mensagem}`,
    'liderança': `✝️ ${churchInfo.lideranca}. ${churchInfo.mensagem}`,
    'lideranca': `✝️ ${churchInfo.lideranca}. ${churchInfo.mensagem}`,
    'default': `Olá! 😊 Sou o assistente da Igreja Vale da Bênção. Posso ajudá-lo(a) com informações sobre nossos cultos, localização e liderança. ${churchInfo.mensagem}\n\nPergunte-me sobre: horários dos cultos, endereço ou nossa liderança.`
};

// Função para detectar em qual seção o usuário está
function getCurrentSection() {
    const sections = [
        { id: 'hero', name: 'Início', description: 'Seção de boas-vindas com vídeo de fundo' },
        { id: 'vale-news', name: 'Vale News', description: 'Carrossel com notícias e eventos da igreja' },
        { id: 'devocional', name: 'Devocional', description: 'Devocional diário com versículo bíblico' },
        { id: 'culto-online', name: 'Culto Online', description: 'Transmissão ao vivo e cultos anteriores' },
        { id: 'localizacao', name: 'Localização', description: 'Endereço e mapa da igreja' }
    ];
    
    const scrollPosition = window.scrollY + window.innerHeight / 2;
    
    for (const section of sections) {
        const element = document.getElementById(section.id);
        if (element) {
            const rect = element.getBoundingClientRect();
            const elementTop = rect.top + window.scrollY;
            const elementBottom = elementTop + rect.height;
            
            if (scrollPosition >= elementTop && scrollPosition <= elementBottom) {
                return section;
            }
        }
    }
    
    return sections[0]; // Default para Início
}

// Variáveis para controle de sessão do chat
let chatSessionId = localStorage.getItem('chatSessionId') || null;
let messageCount = 0;

// Função para chamar a API através do Laravel
async function getAIResponse(userMessage) {
    try {
        messageCount++;
        const isFirstMessage = messageCount === 1;
        
        console.log('🤖 Enviando mensagem para API:', {
            message: userMessage,
            session_id: chatSessionId,
            is_first_message: isFirstMessage,
            url: '/api/ai/chat/chatbot-geral'
        });
        
        const response = await fetch('/api/ai/chat/chatbot-geral', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: userMessage,
                session_id: chatSessionId,
                is_first_message: isFirstMessage
            })
        });

        console.log('📡 Response status:', response.status);
        const data = await response.json();
        console.log('📦 Response data:', data);
        
        if (data.success) {
            // Salvar session_id para próximas mensagens
            if (!chatSessionId) {
                chatSessionId = data.session_id;
                localStorage.setItem('chatSessionId', chatSessionId);
                console.log('✅ Session ID salvo:', chatSessionId);
            }
            
            return data.response;
        } else {
            throw new Error('Resposta inválida da API');
        }
    } catch (error) {
        console.error('❌ Erro ao chamar API:', error);
        console.log('🔄 Usando fallback local');
        return getBotResponse(userMessage); // Fallback para respostas locais
    }
}

// Função para obter resposta local (fallback)
function getBotResponse(message) {
    const lowerMessage = message.toLowerCase();
    
    for (let key in botResponses) {
        if (lowerMessage.includes(key)) {
            return botResponses[key];
        }
    }
    
    return botResponses.default;
}

// Abrir/Fechar chat
chatButton.addEventListener('click', () => {
    aiChat.classList.add('active');
    chatButton.classList.remove('show-bubble');
    clearInterval(inviteInterval);
    
    // Mensagem inicial personalizada - verificar se já existe mensagem inicial
    const existingMessages = chatBody.querySelectorAll('.ai-message');
    if (existingMessages.length <= 1) {
        const welcomeMsg = document.createElement('div');
        welcomeMsg.className = 'ai-message';
        let greeting = '';
        
        if (visitCount === 1) {
            greeting = 'Olá! 😊 Seja muito bem-vindo(a) à Igreja Vale da Bênção! É um prazer imenso ter você aqui. Sou o assistente virtual e estou aqui para ajudá-lo(a). Como posso servir? Gostaria de saber sobre nossos cultos, localização ou nossa liderança? 🙏';
        } else if (visitCount <= 3) {
            greeting = 'Que alegria ter você de volta! 🙏 Como vai? Posso ajudá-lo(a) com informações sobre nossos cultos, endereço ou nossa família da igreja? ❤️';
        } else {
            greeting = 'Você já é da família! 😊 Que bênção ter você aqui novamente. Como posso ajudar hoje? 🙏';
        }
        
        welcomeMsg.innerHTML = `<p>${greeting}</p>`;
        chatBody.appendChild(welcomeMsg);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
});

chatClose.addEventListener('click', () => {
    aiChat.classList.remove('active');
    aiChat.classList.remove('maximized');
    chatMaximize.textContent = '⛶';
});

// Maximizar/Restaurar chat
const chatMaximize = document.getElementById('chatMaximize');
chatMaximize.addEventListener('click', () => {
    aiChat.classList.toggle('maximized');
    if (aiChat.classList.contains('maximized')) {
        chatMaximize.textContent = '🗗';
        chatMaximize.title = 'Restaurar';
    } else {
        chatMaximize.textContent = '⛶';
        chatMaximize.title = 'Maximizar';
    }
});

// Enviar mensagem
async function sendMessage() {
    const message = chatInput.value.trim();
    if (message === '') return;

    // Adicionar mensagem do usuário
    const userMsg = document.createElement('div');
    userMsg.className = 'user-message';
    userMsg.innerHTML = `<p>${message}</p>`;
    chatBody.appendChild(userMsg);

    chatInput.value = '';
    chatBody.scrollTop = chatBody.scrollHeight;

    // Mostrar indicador de digitação
    const typingIndicator = document.createElement('div');
    typingIndicator.className = 'ai-message';
    typingIndicator.innerHTML = '<p>Digitando...</p>';
    chatBody.appendChild(typingIndicator);
    chatBody.scrollTop = chatBody.scrollHeight;

    // Obter resposta da IA
    const response = await getAIResponse(message);

    // Remover indicador de digitação
    chatBody.removeChild(typingIndicator);

    // Adicionar resposta do bot
    const botMsg = document.createElement('div');
    botMsg.className = 'ai-message';
    botMsg.innerHTML = `<p>${response.replace(/\n/g, '<br>')}</p>`;
    chatBody.appendChild(botMsg);
    
    // Scroll para baixo
    chatBody.scrollTop = chatBody.scrollHeight;
}

chatSend.addEventListener('click', sendMessage);

chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Navegação suave
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Atualizar barra de progresso de scroll
const scrollProgress = document.getElementById('scrollProgress');
if (scrollProgress) {
    window.addEventListener('scroll', () => {
        const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (window.scrollY / windowHeight) * 100;
        scrollProgress.style.width = scrolled + '%';
    });
}

// Inicializar ao carregar a página
window.addEventListener('load', () => {
    console.log('🚀 Site da Igreja carregado com sucesso!');
    console.log('🤖 Assistente virtual pronto para atender');
    
    // Carrossel de Banners
    initBannerCarousel();
    
    // Scroll suave ao clicar no indicador
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', () => {
            const carouselSection = document.querySelector('.carousel-section');
            if (carouselSection) {
                carouselSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});

// Função para inicializar o carrossel de banners
function initBannerCarousel() {
    const bannersContainer = document.getElementById('carouselBanners');
    const dotsContainer = document.getElementById('carouselDots');
    const prevBtn = document.getElementById('bannerPrev');
    const nextBtn = document.getElementById('bannerNext');
    
    if (!bannersContainer || !dotsContainer || !prevBtn || !nextBtn) return;
    
    const banners = bannersContainer.querySelectorAll('.banner-slide');
    const totalBanners = banners.length;
    let currentIndex = 0;
    
    // Criar dots
    banners.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.className = 'carousel-dot';
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });
    
    const dots = dotsContainer.querySelectorAll('.carousel-dot');
    
    function updateCarousel() {
        bannersContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }
    
    function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
        resetAutoplay();
    }
    
    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalBanners;
        updateCarousel();
    }
    
    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalBanners) % totalBanners;
        updateCarousel();
    }
    
    // Auto-play
    let autoplayInterval = setInterval(nextSlide, 6000);
    
    function resetAutoplay() {
        clearInterval(autoplayInterval);
        autoplayInterval = setInterval(nextSlide, 6000);
    }
    
    // Event listeners
    nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoplay();
    });
    
    prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoplay();
    });
    
    // Pausar ao passar o mouse
    bannersContainer.addEventListener('mouseenter', () => {
        clearInterval(autoplayInterval);
    });
    
    bannersContainer.addEventListener('mouseleave', () => {
        autoplayInterval = setInterval(nextSlide, 6000);
    });
    
    // Navegação por teclado
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            resetAutoplay();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            resetAutoplay();
        }
    });
    
    // Zoom ao clicar na imagem
    banners.forEach(banner => {
        const img = banner.querySelector('.banner-image');
        if (img) {
            img.style.cursor = 'pointer';
            img.addEventListener('click', () => {
                openImageZoom(img.src, img.alt);
            });
        }
    });
}

// Função para abrir zoom da imagem
function openImageZoom(src, alt) {
    const modal = document.createElement('div');
    modal.className = 'image-zoom-modal';
    modal.innerHTML = `
        <div class="image-zoom-overlay"></div>
        <div class="image-zoom-content">
            <button class="image-zoom-close">&times;</button>
            <img src="${src}" alt="${alt}" class="image-zoom-img">
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => modal.classList.add('active'), 10);
    
    const closeBtn = modal.querySelector('.image-zoom-close');
    const overlay = modal.querySelector('.image-zoom-overlay');
    
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => modal.remove(), 300);
    }
    
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    
    document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
            closeModal();
            document.removeEventListener('keydown', escHandler);
        }
    });
}

// YouTube Radio Player
let radioPlayer;
let radioPlayerReady = false;
const PLAYLIST_ID = 'PLa0zsoncpY1iBxnfvG_7hXB_OFazURIyV';

// Carregar API do YouTube
function loadYouTubeAPI() {
    // Evitar carregar a API múltiplas vezes
    if (window.YT || document.querySelector('script[src*="youtube.com/iframe_api"]')) {
        console.log('YouTube API já carregada');
        return;
    }
    
    console.log('Carregando YouTube API...');
    const tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

// Callback quando API estiver pronta
window.onYouTubeIframeAPIReady = function() {
    radioPlayer = new YT.Player('radioYoutubePlayer', {
        height: '0',
        width: '0',
        playerVars: {
            listType: 'playlist',
            list: PLAYLIST_ID,
            autoplay: 0,  // MUDADO para 0 - NÃO AUTOPLAY
            controls: 0,
            modestbranding: 1,
            rel: 0,
            showinfo: 0,
            loop: 1
            // shuffle removido daqui - será configurado manualmente
        },
        events: {
            'onReady': onRadioPlayerReady,
            'onStateChange': onRadioPlayerStateChange
        }
    });
};

function onRadioPlayerReady(event) {
    radioPlayerReady = true;
    console.log('Radio Player pronto!');
    
    // PARAR imediatamente qualquer reprodução que possa ter iniciado
    event.target.stopVideo();
    
    // NÃO FAZER NADA - deixar o player completamente inativo até o usuário clicar em play
    console.log('Player pronto e aguardando interação do usuário...');
    
    // Garantir que os ícones estão corretos (mostrar play)
    const playIcon = document.querySelector('#radioToggle .play-icon');
    const pauseIcon = document.querySelector('#radioToggle .pause-icon');
    if (playIcon && pauseIcon) {
        playIcon.style.display = 'block';
        pauseIcon.style.display = 'none';
    }
    
    setupAudioPlayerControls();
}

function onRadioPlayerStateChange(event) {
    const playIcon = document.querySelector('#radioToggle .play-icon');
    const pauseIcon = document.querySelector('#radioToggle .pause-icon');
    
    if (event.data === YT.PlayerState.PLAYING) {
        if (playIcon && pauseIcon) {
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
        }
        updateAudioCurrentSong();
        startAudioProgressBar();
    } else if (event.data === YT.PlayerState.PAUSED) {
        if (playIcon && pauseIcon) {
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        }
        stopAudioProgressBar();
    } else if (event.data === YT.PlayerState.ENDED) {
        if (playIcon && pauseIcon) {
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        }
    }
}

function setupAudioPlayerControls() {
    const toggleBtn = document.getElementById('radioToggle');
    const prevBtn = document.getElementById('radioPrev');
    const nextBtn = document.getElementById('radioNext');
    let firstPlay = true; // Flag para primeira reprodução
    
    // Controles de play/pause
    toggleBtn?.addEventListener('click', () => {
        if (!radioPlayerReady) {
            console.log('Player ainda não está pronto');
            return;
        }
        
        const state = radioPlayer.getPlayerState();
        console.log('Estado atual do player:', state);
        
        if (state === YT.PlayerState.PLAYING) {
            console.log('Pausando...');
            radioPlayer.pauseVideo();
        } else {
            console.log('Tocando...');
            // Na primeira vez que tocar, configurar shuffle
            if (firstPlay) {
                radioPlayer.setShuffle(true);
                firstPlay = false;
            }
            radioPlayer.playVideo();
        }
    });
    
    // Música anterior
    prevBtn?.addEventListener('click', () => {
        if (!radioPlayerReady) return;
        console.log('Música anterior');
        radioPlayer.previousVideo();
        setTimeout(updateAudioCurrentSong, 500);
    });
    
    // Próxima música
    nextBtn?.addEventListener('click', () => {
        if (!radioPlayerReady) return;
        console.log('Próxima música');
        radioPlayer.nextVideo();
        setTimeout(updateAudioCurrentSong, 500);
    });
}

function updateAudioCurrentSong() {
    if (!radioPlayerReady) return;
    
    try {
        const videoData = radioPlayer.getVideoData();
        const songTitle = videoData.title || 'Carregando...';
        const currentSongElement = document.getElementById('radioSongTitle');
        if (currentSongElement) {
            currentSongElement.textContent = songTitle;
        }
    } catch (error) {
        console.log('Aguardando informações da música...');
    }
}

let audioProgressInterval;

function startAudioProgressBar() {
    stopAudioProgressBar();
    audioProgressInterval = setInterval(() => {
        if (!radioPlayerReady) return;
        
        try {
            const duration = radioPlayer.getDuration();
            const currentTime = radioPlayer.getCurrentTime();
            const percentage = (currentTime / duration) * 100;
            
            const progressBar = document.getElementById('radioProgressBar');
            if (progressBar) {
                progressBar.style.width = percentage + '%';
            }
        } catch (error) {
            console.log('Erro ao atualizar progresso');
        }
    }, 1000);
}

function stopAudioProgressBar() {
    if (audioProgressInterval) {
        clearInterval(audioProgressInterval);
        audioProgressInterval = null;
    }
}

// === Inicialização quando DOM estiver pronto ===
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado - Inicializando Radio Player');
    
    // Carregar YouTube API automaticamente quando a página carregar
    loadYouTubeAPI();
    
    // Radio Player Toggle Functionality
    const radioButton = document.getElementById('radioButton');
    const radioPlayerEl = document.getElementById('radioPlayer');
    const radioClose = document.querySelector('.radio-close');
    const chatButton = document.getElementById('chatButton');
    const chatEl = document.getElementById('aiChat');

    console.log('Radio Button:', radioButton);
    console.log('Radio Player:', radioPlayerEl);
    console.log('Radio Close:', radioClose);

    if (radioButton && radioPlayerEl) {
        // Abrir radio player e trazer para frente
        radioButton.addEventListener('click', function() {
            console.log('Radio button clicado!');
            
            // Se estiver fechado, abrir (sem iniciar música automaticamente)
            if (!radioPlayerEl.classList.contains('active')) {
                radioPlayerEl.classList.add('active');
                
                // REMOVIDO: Não pular para próxima música automaticamente
                // O usuário precisa clicar em play manualmente
            }
            
            // Sempre trazer radio para frente quando clicado
            radioPlayerEl.classList.add('front');
            if (chatEl) chatEl.classList.remove('front');
        });
    }
    
    if (chatButton && chatEl) {
        // Quando chat for aberto, trazer para frente
        chatButton.addEventListener('click', function() {
            // Trazer chat para frente
            chatEl.classList.add('front');
            if (radioPlayerEl) radioPlayerEl.classList.remove('front');
        });
    }

    if (radioClose && radioPlayerEl) {
        // Ocultar radio player (não pausa a música)
        radioClose.addEventListener('click', function() {
            console.log('Radio close clicado!');
            radioPlayerEl.classList.remove('active');
            // Música continua tocando em background
        });
    }
    
    // Pausar rádio quando vídeos do site forem reproduzidos
    const cultoVideo = document.getElementById('cultoVideo');
    
    // Detectar quando vídeos do site (não a rádio) começarem a tocar
    window.addEventListener('message', function(event) {
        if (event.origin !== 'https://www.youtube.com') return;
        
        try {
            const data = JSON.parse(event.data);
            
            // Verificar se é um evento de play e NÃO é do player da rádio
            if (data.event === 'infoDelivery' && data.info && data.info.playerState === 1) {
                // Verificar se o iframe que está tocando é o cultoVideo
                const cultoIframe = document.getElementById('cultoVideo');
                if (cultoIframe && cultoIframe.contentWindow === event.source) {
                    console.log('Vídeo do culto começou - pausando rádio');
                    if (radioPlayerReady && radioPlayer) {
                        radioPlayer.pauseVideo();
                    }
                }
            }
        } catch (e) {
            // Ignorar erros de parsing
        }
    });
});

// Adicionar efeito de transição no conteúdo
const style = document.createElement('style');
style.textContent = `
    .highlight-content {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
`;
document.head.appendChild(style);

