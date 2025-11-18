<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vale da Benção Church - Site Oficial')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/perfil.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&family=Exo:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-projeto.css') }}">
    <style>
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        #app-content {
            overflow-x: hidden;
            max-width: 100%;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Barra de Progresso de Scroll -->
    <div class="scroll-progress" id="scrollProgress"></div>
    
    <!-- Header Fixo -->
    @include('components.header')

    <!-- Conteúdo Dinâmico (SPA) -->
    <div id="app-content">
        @yield('content')
    </div>

    <!-- Componentes Flutuantes Fixos -->
    @include('components.chat')

    <!-- Footer Fixo -->
    @include('components.footer')

    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
