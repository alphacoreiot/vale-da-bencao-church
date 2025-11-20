<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Vale da Bênção</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Condensed:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --cor-principal: #FFFFFF;
            --cor-fundo: #000000;
            --cor-destaque: #C0C0C0;
            --cor-secundaria: #D0FBF9;
            --cor-acento: #A8A8A8;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Encode Sans Condensed', sans-serif;
            background-color: #0a0a0a;
            color: var(--cor-principal);
            overflow-x: hidden;
        }

        /* TOAST NOTIFICATIONS */
        #adminToast {
            min-width: 300px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border-left: 4px solid var(--cor-destaque) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        #adminToast.toast-success {
            border-left-color: #00ff88 !important;
        }

        #adminToast.toast-success .toast-body i {
            color: #00ff88;
        }

        #adminToast.toast-error {
            border-left-color: #ff4444 !important;
        }

        #adminToast.toast-error .toast-body i {
            color: #ff4444;
        }

        #adminToast .toast-body {
            color: var(--cor-principal);
            font-size: 0.95rem;
        }

        #adminToast .btn-close {
            filter: brightness(0) invert(1);
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #000000 0%, #1a1a1a 100%);
            border-right: 2px solid var(--cor-destaque);
            padding-top: 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 5px 0 20px rgba(192, 192, 192, 0.2);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--cor-destaque);
            border-radius: 3px;
        }

        .logo-admin {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(192, 192, 192, 0.3);
            background: linear-gradient(135deg, rgba(192, 192, 192, 0.1) 0%, transparent 100%);
        }

        .logo-admin img {
            max-width: 180px;
            height: auto;
            filter: drop-shadow(0 0 10px rgba(192, 192, 192, 0.5));
            transition: all 0.3s ease;
        }

        .logo-admin img:hover {
            filter: drop-shadow(0 0 20px rgba(192, 192, 192, 0.8));
            transform: scale(1.05);
        }

        .logo-admin h4 {
            color: var(--cor-secundaria);
            font-weight: 600;
            margin-top: 15px;
            font-size: 18px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar .nav {
            padding: 20px 0;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 15px 25px;
            transition: all 0.3s ease;
            font-size: 16px;
            font-weight: 500;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 5px 0;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .sidebar .nav-link:hover {
            color: var(--cor-principal);
            background: linear-gradient(90deg, rgba(192, 192, 192, 0.2) 0%, transparent 100%);
            border-left-color: var(--cor-destaque);
            padding-left: 30px;
        }

        .sidebar .nav-link.active {
            color: var(--cor-secundaria);
            background: linear-gradient(90deg, rgba(192, 192, 192, 0.3) 0%, transparent 100%);
            border-left-color: var(--cor-secundaria);
            font-weight: 600;
        }

        .sidebar hr {
            border-color: rgba(192, 192, 192, 0.3);
            margin: 15px 25px;
        }

        /* TOP NAVBAR */
        .navbar-admin {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
            border-bottom: 2px solid var(--cor-destaque);
            padding: 20px 40px;
            z-index: 999;
            box-shadow: 0 4px 20px rgba(192, 192, 192, 0.2);
        }

        .navbar-admin h5 {
            color: var(--cor-secundaria);
            font-weight: 600;
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
        }

        .user-info .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--cor-destaque) 0%, var(--cor-acento) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid var(--cor-secundaria);
            box-shadow: 0 0 15px rgba(192, 192, 192, 0.5);
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 80px;
            padding: 30px 40px;
            min-height: calc(100vh - 80px);
        }

        .main-content h1, .main-content h2, .main-content h3, .main-content h4, .main-content h5 {
            color: #FFFFFF;
        }

        .main-content p, .main-content div, .main-content span {
            color: rgba(255, 255, 255, 0.95);
        }

        .list-group-item {
            background-color: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
        }

        .list-group-item h6 {
            color: #FFFFFF;
            font-weight: 600;
        }

        .list-group-item small {
            color: rgba(255, 255, 255, 0.7);
        }

        /* CARDS */
        .card {
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
            border: 1px solid rgba(192, 192, 192, 0.3);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .card:hover {
            border-color: var(--cor-destaque);
            box-shadow: 0 8px 25px rgba(192, 192, 192, 0.3);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(192, 192, 192, 0.3) 0%, rgba(100, 100, 100, 0.2) 100%);
            border-bottom: 2px solid rgba(192, 192, 192, 0.5);
            color: #000000;
            font-weight: 700;
            padding: 15px 20px;
            font-size: 18px;
        }

        .card-header h5 {
            color: #000000;
            margin: 0;
            font-weight: 700;
        }

        .card-body {
            color: #FFFFFF;
            padding: 20px;
        }

        .card-body h2, .card-body h3, .card-body h4, .card-body h5, .card-body h6 {
            color: #FFFFFF;
        }

        .card-body p, .card-body div, .card-body span {
            color: rgba(255, 255, 255, 0.95);
        }

        .card-body .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .card-body h6 {
            color: rgba(255, 255, 255, 0.8);
        }

        /* ALERTS */
        .alert {
            border-radius: 8px;
            border: none;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(0, 255, 0, 0.1) 0%, rgba(0, 200, 0, 0.05) 100%);
            color: #00ff00;
            border-left: 4px solid #00ff00;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(168, 168, 168, 0.1) 0%, rgba(192, 192, 192, 0.05) 100%);
            color: var(--cor-acento);
            border-left: 4px solid var(--cor-acento);
        }

        /* BUTTONS */
        .btn-primary {
            background: linear-gradient(135deg, var(--cor-destaque) 0%, var(--cor-acento) 100%);
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(192, 192, 192, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(192, 192, 192, 0.5);
        }

        /* TABLES */
        .table {
            color: #FFFFFF;
        }

        .table thead th {
            border-bottom: 2px solid var(--cor-destaque);
            color: #FFFFFF;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
            background-color: rgba(192, 192, 192, 0.1);
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(192, 192, 192, 0.1);
        }

        .table tbody td {
            color: #000000;
            font-weight: 500;
        }
        
        .table tbody td strong {
            color: #000000;
        }

        /* BADGES */
        .badge {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #00ff00 0%, #00cc00 100%) !important;
            color: #000;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, #666 0%, #444 100%) !important;
        }

        /* SCROLLBAR GLOBAL */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--cor-destaque);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--cor-acento);
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .navbar-admin {
                left: 0;
                padding: 15px 20px;
            }

            .navbar-admin h5 {
                font-size: 18px;
            }

            .user-info span {
                display: none;
            }

            .user-info .avatar {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }

            .main-content {
                margin-left: 0 !important;
                margin-top: 70px;
                padding: 20px 15px;
            }

            .main-content h1 {
                font-size: 24px;
            }

            .main-content h2 {
                font-size: 20px;
            }

            .main-content h3 {
                font-size: 18px;
            }

            .card {
                margin-bottom: 15px;
            }

            .card-header {
                padding: 12px 15px;
                font-size: 16px;
            }

            .card-body {
                padding: 15px;
            }

            .table-responsive {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }

            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.7);
                z-index: 999;
            }

            .mobile-overlay.show {
                display: block;
            }

            .menu-toggle {
                display: flex !important;
            }
        }

        @media (max-width: 576px) {
            .navbar-admin {
                padding: 12px 15px;
            }

            .navbar-admin h5 {
                font-size: 16px;
            }

            .main-content {
                padding: 15px 10px;
            }

            .main-content h2 {
                font-size: 18px;
            }

            .table {
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 8px 5px;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 12px;
            }

            .badge {
                font-size: 11px;
                padding: 4px 8px;
            }

            .form-label {
                font-size: 14px;
            }

            .form-control,
            .form-select {
                font-size: 14px;
            }
        }

        .menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(192, 192, 192, 0.2);
            border: 1px solid var(--cor-destaque);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: rgba(192, 192, 192, 0.3);
        }

        .menu-toggle i {
            color: var(--cor-destaque);
            font-size: 20px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-admin">
            <img src="{{ asset('assets/logo.png') }}" alt="Vale da Bênção">
            <h4>Admin Panel</h4>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.user-groups.*') ? 'active' : '' }}" href="{{ route('admin.user-groups.index') }}">
                <i class="fas fa-users-cog"></i>
                <span>Grupos de Usuários</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i>
                <span>Usuários</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" href="{{ route('admin.logs.index') }}">
                <i class="fas fa-history"></i>
                <span>Logs do Sistema</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.content.*') ? 'active' : '' }}" href="{{ route('admin.content.index') }}">
                <i class="fas fa-edit"></i>
                <span>Gerenciador de Conteúdo</span>
            </a>
            
            <hr>
            
            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                <span>Ver Site</span>
            </a>
            <a class="nav-link" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </a>
        </nav>
        
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Top Navbar -->
    <nav class="navbar-admin">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Admin')</h5>
            </div>
            <div class="user-info">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>
    </nav>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="adminToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="toastMessage"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('adminToast');
            const toastMessage = document.getElementById('toastMessage');
            const toastBody = toastEl.querySelector('.toast-body');
            const icon = toastBody.querySelector('i');
            
            // Set message
            toastMessage.textContent = message;
            
            // Set type
            toastEl.classList.remove('toast-success', 'toast-error');
            toastEl.classList.add('toast-' + type);
            
            // Set icon
            icon.className = type === 'success' ? 'fas fa-check-circle me-2' : 'fas fa-exclamation-circle me-2';
            
            // Show toast
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 3000
            });
            toast.show();
        }
    </script>
    
    <script>
        // SPA Navigation for Admin Panel
        document.addEventListener('DOMContentLoaded', function() {
            const contentArea = document.querySelector('.main-content');
            
            // Intercept all navigation links
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                
                // Skip if not a link or is external/logout
                if (!link || 
                    link.target === '_blank' || 
                    link.getAttribute('href') === '#' ||
                    link.closest('#logout-form') ||
                    !link.getAttribute('href') ||
                    link.getAttribute('href').startsWith('javascript:')) {
                    return;
                }
                
                const href = link.getAttribute('href');
                
                // Only intercept admin routes
                if (href.includes('/admin/') && !href.includes('/admin/logout')) {
                    e.preventDefault();
                    loadPage(href);
                }
            });
            
            // Handle browser back/forward
            window.addEventListener('popstate', function(e) {
                if (e.state && e.state.url) {
                    loadPage(e.state.url, false);
                }
            });
            
            // Load page via AJAX
            function loadPage(url, pushState = true) {
                // Show loading state
                contentArea.style.opacity = '0.5';
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract main content
                    const newContent = doc.querySelector('.main-content');
                    if (newContent) {
                        contentArea.innerHTML = newContent.innerHTML;
                    }
                    
                    // Update page title
                    const newTitle = doc.querySelector('.navbar-admin h5');
                    const currentTitle = document.querySelector('.navbar-admin h5');
                    if (newTitle && currentTitle) {
                        currentTitle.textContent = newTitle.textContent;
                    }
                    
                    // Update active menu
                    updateActiveMenu(url);
                    
                    // Update browser history
                    if (pushState) {
                        history.pushState({url: url}, '', url);
                    }
                    
                    // Restore opacity
                    contentArea.style.opacity = '1';
                    
                    // Scroll to top
                    window.scrollTo(0, 0);
                })
                .catch(error => {
                    console.error('Error loading page:', error);
                    contentArea.style.opacity = '1';
                });
            }
            
            // Update active menu item
            function updateActiveMenu(url) {
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    link.classList.remove('active');
                    const linkHref = link.getAttribute('href');
                    if (linkHref === url || 
                        (url.includes('/user-groups') && linkHref.includes('/user-groups')) ||
                        (url.includes('/users') && linkHref.includes('/users') && !linkHref.includes('/user-groups')) ||
                        (url.includes('/sections') && linkHref.includes('/sections')) ||
                        (url.includes('/rotation') && linkHref.includes('/rotation'))) {
                        link.classList.add('active');
                    }
                });
            }
            
            // Add transition effect
            contentArea.style.transition = 'opacity 0.3s ease';

            // Mobile menu toggle
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.querySelector('.sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    mobileOverlay.classList.toggle('show');
                });

                mobileOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    mobileOverlay.classList.remove('show');
                });

                // Close menu when clicking on links (mobile)
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 992) {
                            sidebar.classList.remove('show');
                            mobileOverlay.classList.remove('show');
                        }
                    });
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
