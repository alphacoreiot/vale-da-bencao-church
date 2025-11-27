<!-- Rádio Gospel Flutuante -->
<div class="radio-player-float" id="radioPlayer">
    <div class="radio-header">
        <span>🎵 Rádio Vale da Benção Church</span>
        <button class="radio-close" id="radioClose">✕</button>
    </div>
    <div class="radio-body">
        <div id="radioYoutubePlayer" style="display:none;"></div>
        <p class="radio-song-title" id="radioSongTitle">Carregando...</p>
        <div class="radio-progress">
            <div class="radio-progress-bar" id="radioProgressBar"></div>
        </div>
        <div class="radio-controls">
            <button class="radio-control-btn" id="radioPrev" title="Anterior">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                </svg>
            </button>
            <button class="radio-control-btn play-btn" id="radioToggle" title="Play/Pause">
                <svg class="play-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8 5v14l11-7z"/>
                </svg>
                <svg class="pause-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                </svg>
            </button>
            <button class="radio-control-btn" id="radioNext" title="Próximo">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 6l8.5 6L6 18V6zm10 0h2v12h-2V6z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Botão Rádio -->
<button class="radio-button" id="radioButton">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"/>
        <path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"/>
        <circle cx="12" cy="12" r="2"/>
        <path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"/>
        <path d="M19.1 4.9C23 8.8 23 15.1 19.1 19"/>
    </svg>
</button>

<!-- Balão de Dica Alternante -->
<div class="tip-balloon" id="tipBalloon">
    <span class="tip-text" id="tipText">🎵 Ouça nossa Rádio!</span>
    <button class="tip-close" id="tipClose">×</button>
</div>

<style>
.tip-balloon {
    position: fixed;
    bottom: 110px;
    right: 100px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%);
    color: #000;
    padding: 12px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
    z-index: 1000;
    animation: tipPulse 2s ease-in-out infinite;
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 200px;
}

.tip-balloon::after {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 30px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #B8941F;
}

.tip-balloon.pointing-chat {
    right: 20px;
}

.tip-balloon.pointing-chat::after {
    right: 25px;
}

.tip-text {
    flex: 1;
}

.tip-close {
    background: rgba(0,0,0,0.2);
    border: none;
    color: #000;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.tip-close:hover {
    background: rgba(0,0,0,0.3);
}

@keyframes tipPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.tip-balloon.hidden {
    display: none !important;
}

@media (max-width: 768px) {
    .tip-balloon {
        bottom: 100px;
        right: 80px;
        font-size: 12px;
        padding: 10px 15px;
    }
    .tip-balloon.pointing-chat {
        right: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipBalloon = document.getElementById('tipBalloon');
    const tipText = document.getElementById('tipText');
    const tipClose = document.getElementById('tipClose');
    
    if (!tipBalloon) return;
    
    const tips = [
        { text: '🎵 Ouça nossa Rádio!', target: 'radio' },
        { text: '💬 Fale conosco no Chat!', target: 'chat' }
    ];
    
    let currentTip = 0;
    let tipInterval;
    let tipDismissed = sessionStorage.getItem('tipDismissed');
    
    if (tipDismissed) {
        tipBalloon.classList.add('hidden');
        return;
    }
    
    function showNextTip() {
        currentTip = (currentTip + 1) % tips.length;
        tipText.textContent = tips[currentTip].text;
        
        if (tips[currentTip].target === 'chat') {
            tipBalloon.classList.add('pointing-chat');
        } else {
            tipBalloon.classList.remove('pointing-chat');
        }
    }
    
    // Alternar a cada 5 segundos
    tipInterval = setInterval(showNextTip, 5000);
    
    // Fechar balão
    tipClose.addEventListener('click', function() {
        tipBalloon.classList.add('hidden');
        sessionStorage.setItem('tipDismissed', 'true');
        clearInterval(tipInterval);
    });
    
    // Esconder após 30 segundos automaticamente
    setTimeout(function() {
        tipBalloon.classList.add('hidden');
        clearInterval(tipInterval);
    }, 30000);
    
    // Esconder quando clicar na rádio ou chat
    document.getElementById('radioButton')?.addEventListener('click', function() {
        tipBalloon.classList.add('hidden');
        clearInterval(tipInterval);
    });
});
</script>
