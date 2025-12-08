<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Content Manager | Vale da Bênção</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Condensed:wght@300;400;500;600;700&family=Exo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/content.css') }}?v={{ time() }}">
</head>
<body>
    <div id="app">
        <!-- SPA Content will be rendered here -->
        <div class="loading-screen">
            <div class="spinner"></div>
            <p>Carregando...</p>
        </div>
    </div>

    <!-- SPA Scripts -->
    <script src="{{ asset('js/content/app.js') }}?v={{ time() }}"></script>
</body>
</html>
