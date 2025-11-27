<!-- Header com Logo -->
<header class="header">
    <button class="menu-toggle" id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <nav class="nav" id="mainNav">
        <a href="{{ route('home') }}" class="nav-link" data-route="home">Home</a>
        <a href="{{ route('section.show', 'eventos') }}" class="nav-link" data-route="eventos">Eventos</a>
        <a href="{{ route('section.show', 'ministerios') }}" class="nav-link" data-route="ministerios">Ministérios</a>
        <a href="{{ route('section.show', 'galeria') }}" class="nav-link" data-route="galeria">Galeria</a>
        <a href="{{ route('celulas') }}" class="nav-link" data-route="celulas">Células</a>
        <a href="{{ url('/doacoes') }}" class="nav-link" data-route="doacoes">Doações</a>
        <a href="{{ route('section.show', 'contato') }}" class="nav-link" data-route="contato">Contato</a>
    </nav>
    <img src="{{ asset('assets/logo.png') }}" alt="Vale da Benção Church" class="logo">
</header>
