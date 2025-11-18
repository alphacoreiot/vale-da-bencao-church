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
