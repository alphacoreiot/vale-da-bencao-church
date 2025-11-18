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
    🎵
</button>
