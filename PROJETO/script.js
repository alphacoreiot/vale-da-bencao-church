// Animação de texto do hero
const words = ['Humildade', 'Justiça', 'Misericórdia', 'Você'];
let currentWordIndex = 0;
const animatedTextElement = document.getElementById('animatedText');

function animateWords() {
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
    animateWords();
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
const aiChat = document.getElementById('aiChat');
const chatClose = document.getElementById('chatClose');
const chatSend = document.getElementById('chatSend');
const chatInput = document.getElementById('chatInput');
const chatBody = document.getElementById('chatBody');

// Respostas do bot (simulação)
const botResponses = {
    'horario': 'Nossos cultos são aos domingos às 9h00 e 19h00, e às quartas-feiras às 20h00.',
    'endereço': 'Estamos localizados na Rua da Fé, 123 - Centro.',
    'evento': 'Confira nossa seção de eventos para ver a programação completa!',
    'contato': 'Você pode nos contatar pelo telefone (11) 98765-4321 ou pelo e-mail contato@igreja.com.br',
    'dizimo': 'Para contribuir com dízimos e ofertas, acesse nossa seção de doações.',
    'default': 'Obrigado pela sua mensagem! Um membro de nossa equipe entrará em contato em breve. Como posso ajudar com informações sobre horários, eventos ou localização?'
};

// Abrir/Fechar chat
chatButton.addEventListener('click', () => {
    aiChat.classList.add('active');
    chatButton.style.display = 'none';
});

chatClose.addEventListener('click', () => {
    aiChat.classList.remove('active');
    chatButton.style.display = 'block';
});

// Enviar mensagem
function sendMessage() {
    const message = chatInput.value.trim();
    if (message === '') return;

    // Adicionar mensagem do usuário
    const userMsg = document.createElement('div');
    userMsg.className = 'user-message';
    userMsg.innerHTML = `<p>${message}</p>`;
    chatBody.appendChild(userMsg);

    chatInput.value = '';

    // Simular resposta do bot
    setTimeout(() => {
        const botMsg = document.createElement('div');
        botMsg.className = 'ai-message';
        
        // Buscar resposta baseada em palavras-chave
        let response = botResponses.default;
        const lowerMessage = message.toLowerCase();
        
        for (let key in botResponses) {
            if (lowerMessage.includes(key)) {
                response = botResponses[key];
                break;
            }
        }
        
        botMsg.innerHTML = `<p>${response}</p>`;
        chatBody.appendChild(botMsg);
        
        // Scroll para baixo
        chatBody.scrollTop = chatBody.scrollHeight;
    }, 1000);

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

// Inicializar ao carregar a página
window.addEventListener('load', () => {
    startRotation();
    console.log('🚀 Site da Igreja carregado com sucesso!');
    console.log('✨ Rotação automática de seções ativada');
    console.log('🤖 Assistente virtual pronto para atender');
});

// Adicionar efeito de transição no conteúdo
const style = document.createElement('style');
style.textContent = `
    .highlight-content {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
`;
document.head.appendChild(style);
