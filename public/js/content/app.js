/**
 * Content Manager - Vale da Bênção
 * Single Page Application (SPA) - Vanilla JavaScript
 * 
 * @author Vale da Bênção Church
 * @version 1.0.0
 */

(function() {
    'use strict';

    // ==========================================
    // Configuration
    // ==========================================
    const Config = {
        baseUrl: '/content',
        apiUrl: '/api/content',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || ''
    };

    // ==========================================
    // State Management
    // ==========================================
    const State = {
        user: null,
        isAuthenticated: false,
        currentRoute: '',
        isLoading: false
    };

    // ==========================================
    // API Service
    // ==========================================
    const Api = {
        /**
         * Make an HTTP request
         */
        async request(method, endpoint, data = null) {
            const options = {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': Config.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            };

            if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
                options.body = JSON.stringify(data);
            }

            try {
                const response = await fetch(endpoint, options);
                const json = await response.json();

                if (!response.ok) {
                    throw { status: response.status, ...json };
                }

                return json;
            } catch (error) {
                if (error.status === 401) {
                    State.isAuthenticated = false;
                    State.user = null;
                    Router.navigate('/content');
                }
                throw error;
            }
        },

        get(endpoint) {
            return this.request('GET', endpoint);
        },

        post(endpoint, data) {
            return this.request('POST', endpoint, data);
        },

        put(endpoint, data) {
            return this.request('PUT', endpoint, data);
        },

        delete(endpoint) {
            return this.request('DELETE', endpoint);
        }
    };

    // ==========================================
    // Router
    // ==========================================
    const Router = {
        routes: {},

        /**
         * Register a route
         */
        register(path, handler, requiresAuth = true) {
            this.routes[path] = { handler, requiresAuth };
        },

        /**
         * Navigate to a path
         */
        navigate(path, replace = false) {
            if (replace) {
                history.replaceState(null, '', path);
            } else {
                history.pushState(null, '', path);
            }
            this.handleRoute();
        },

        /**
         * Handle the current route
         */
        async handleRoute() {
            const path = window.location.pathname;
            State.currentRoute = path;

            // Find matching route
            let route = this.routes[path];
            
            // Check for parameterized routes
            if (!route) {
                for (const [routePath, routeConfig] of Object.entries(this.routes)) {
                    if (routePath.includes(':')) {
                        const regex = new RegExp('^' + routePath.replace(/:[^/]+/g, '([^/]+)') + '$');
                        const match = path.match(regex);
                        if (match) {
                            route = routeConfig;
                            break;
                        }
                    }
                }
            }

            // Default to login if no route found
            if (!route) {
                if (path.startsWith('/content')) {
                    route = this.routes['/content'];
                } else {
                    return;
                }
            }

            // Check authentication
            if (route.requiresAuth && !State.isAuthenticated) {
                this.navigate('/content', true);
                return;
            }

            // If authenticated and trying to access login, redirect to dashboard
            if (path === '/content' && State.isAuthenticated) {
                this.navigate('/content/dashboard', true);
                return;
            }

            // Execute route handler
            if (route && route.handler) {
                App.showLoading();
                await route.handler();
                App.hideLoading();
            }
        },

        /**
         * Initialize router
         */
        init() {
            // Handle browser back/forward
            window.addEventListener('popstate', () => this.handleRoute());

            // Handle link clicks
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[data-spa]');
                if (link) {
                    e.preventDefault();
                    this.navigate(link.getAttribute('href'));
                }
            });
        }
    };

    // ==========================================
    // Components
    // ==========================================
    const Components = {
        /**
         * Login Page
         */
        loginPage() {
            return `
                <div class="login-container">
                    <div class="login-box">
                        <div class="login-header">
                            <div class="logo">
                                <img src="/assets/logo.png" alt="Vale da Bênção">
                            </div>
                            <h1>Área Restrita</h1>
                            <p>Gestão de Conteúdo da Igreja</p>
                        </div>

                        <div id="alert-container"></div>

                        <form id="login-form">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <div class="input-wrapper">
                                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                                    <i class="fas fa-envelope input-icon"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Senha</label>
                                <div class="input-wrapper">
                                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                                    <i class="fas fa-lock input-icon"></i>
                                </div>
                            </div>

                            <div class="form-group checkbox">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Manter conectado</label>
                            </div>

                            <button type="submit" class="btn btn-primary" id="login-btn">
                                <span>Entrar</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            `;
        },

        /**
         * Dashboard Layout
         */
        dashboardLayout(content, pageTitle = 'Dashboard') {
            const userInitial = State.user?.name?.charAt(0).toUpperCase() || 'U';
            const userName = State.user?.name || 'Usuário';

            return `
                <div class="dashboard-container">
                    <div class="sidebar-overlay" id="sidebar-overlay"></div>
                    
                    <aside class="sidebar" id="sidebar">
                        <div class="sidebar-header">
                            <img src="/assets/logo.png" alt="Vale da Bênção" class="sidebar-logo">
                        </div>
                        
                        <nav class="sidebar-nav">
                            <div class="nav-section">
                                <div class="nav-section-title">Principal</div>
                                <a href="/content/dashboard" data-spa class="nav-item ${State.currentRoute === '/content/dashboard' ? 'active' : ''}">
                                    <i class="fas fa-home"></i>
                                    <span>Dashboard</span>
                                </a>
                            </div>

                            <div class="nav-section">
                                <div class="nav-section-title">Conteúdo</div>
                                <a href="/content/sections" data-spa class="nav-item ${State.currentRoute === '/content/sections' ? 'active' : ''}">
                                    <i class="fas fa-layer-group"></i>
                                    <span>Seções</span>
                                </a>
                                <a href="/content/estudo-celula" data-spa class="nav-item ${State.currentRoute === '/content/estudo-celula' ? 'active' : ''}">
                                    <i class="fas fa-book-open"></i>
                                    <span>Estudo da Célula</span>
                                </a>
                                <a href="/content/media" data-spa class="nav-item ${State.currentRoute === '/content/media' ? 'active' : ''}">
                                    <i class="fas fa-images"></i>
                                    <span>Mídia</span>
                                </a>
                            </div>

                            <div class="nav-section">
                                <div class="nav-section-title">Sistema</div>
                                <a href="/content/users" data-spa class="nav-item ${State.currentRoute === '/content/users' ? 'active' : ''}">
                                    <i class="fas fa-users"></i>
                                    <span>Usuários</span>
                                </a>
                                <a href="/content/settings" data-spa class="nav-item ${State.currentRoute === '/content/settings' ? 'active' : ''}">
                                    <i class="fas fa-cog"></i>
                                    <span>Configurações</span>
                                </a>
                            </div>
                        </nav>

                        <div class="sidebar-footer">
                            <div class="user-info">
                                <div class="user-avatar">${userInitial}</div>
                                <div class="user-details">
                                    <div class="name">${userName}</div>
                                    <div class="role">Administrador</div>
                                </div>
                            </div>
                            <button class="logout-btn" id="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Sair</span>
                            </button>
                        </div>
                    </aside>

                    <main class="main-content">
                        <header class="top-bar">
                            <div class="top-bar-left">
                                <button class="menu-toggle" id="menu-toggle">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <h1 class="page-title">${pageTitle}</h1>
                            </div>
                            <div class="top-bar-right">
                                <a href="/" target="_blank" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Ver Site</span>
                                </a>
                            </div>
                        </header>

                        <div class="content-area">
                            ${content}
                        </div>
                    </main>
                </div>
            `;
        },

        /**
         * Dashboard Home Content
         */
        dashboardHome() {
            const userName = State.user?.name || 'Usuário';

            return `
                <div class="welcome-card">
                    <h2>Bem-vindo(a), ${userName}!</h2>
                    <p>Gerencie o conteúdo do site da Igreja Vale da Bênção</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="stat-content">
                            <h4>--</h4>
                            <p>Seções</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <div class="stat-content">
                            <h4>--</h4>
                            <p>Imagens</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h4>--</h4>
                            <p>Usuários</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-content">
                            <h4>--</h4>
                            <p>Visitas Hoje</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Atividade Recente</h3>
                    </div>
                    <div class="card-body">
                        <p style="color: var(--gray);">Nenhuma atividade recente.</p>
                    </div>
                </div>
            `;
        },

        /**
         * Alert Component
         */
        alert(type, message) {
            const icons = {
                error: 'fa-exclamation-circle',
                success: 'fa-check-circle',
                warning: 'fa-exclamation-triangle'
            };

            return `
                <div class="alert alert-${type}">
                    <i class="fas ${icons[type]}"></i>
                    <span>${message}</span>
                </div>
            `;
        },

        /**
         * Coming Soon Page
         */
        comingSoon(title) {
            return `
                <div class="card">
                    <div class="card-body text-center" style="padding: 60px;">
                        <i class="fas fa-tools" style="font-size: 48px; color: var(--gold); margin-bottom: 20px;"></i>
                        <h3 style="margin-bottom: 10px;">Em Construção</h3>
                        <p style="color: var(--gray);">A página "${title}" está sendo desenvolvida.</p>
                    </div>
                </div>
            `;
        },

        /**
         * Users Page
         */
        usersPage(users = []) {
            const usersList = users.length > 0 ? users.map(user => `
                <tr>
                    <td data-label="Nome">${user.name}</td>
                    <td data-label="E-mail">${user.email}</td>
                    <td data-label="Cadastrado em">${user.created_at}</td>
                    <td data-label="Ações" class="table-actions">
                        <button class="btn-icon btn-edit" data-id="${user.id}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon btn-delete" data-id="${user.id}" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('') : '<tr><td colspan="4" class="empty-message">Nenhum usuário cadastrado.</td></tr>';

            return `
                <div class="page-header">
                    <h2>Usuários</h2>
                    <button class="btn btn-primary" id="btn-novo-usuario">
                        <i class="fas fa-plus"></i>
                        <span>Novo Usuário</span>
                    </button>
                </div>

                <div id="alert-container"></div>

                <div class="card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Cadastrado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${usersList}
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div class="modal-overlay" id="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <h3 id="modal-title">Novo Usuário</h3>
                            <button class="modal-close" id="modal-close">&times;</button>
                        </div>
                        <form id="user-form">
                            <input type="hidden" id="user-id" name="id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Nome *</label>
                                    <input type="text" id="name" name="name" placeholder="Nome completo" required>
                                </div>
                                <div class="form-group">
                                    <label for="user-email">E-mail *</label>
                                    <input type="email" id="user-email" name="email" placeholder="email@exemplo.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="user-password">Senha <span id="password-required">*</span></label>
                                    <input type="password" id="user-password" name="password" placeholder="Mínimo 6 caracteres">
                                    <small class="form-hint" id="password-hint">Mínimo 6 caracteres</small>
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">Confirmar Senha <span id="password-confirm-required">*</span></label>
                                    <input type="password" id="password-confirm" name="password_confirmation" placeholder="Digite a senha novamente">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="btn-cancelar">Cancelar</button>
                                <button type="submit" class="btn btn-primary" id="btn-salvar">
                                    <i class="fas fa-save"></i> Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
        },

        /**
         * Estudo da Célula Page
         */
        estudoCelulaPage(estudos = []) {
            const estudosList = estudos.length > 0 ? estudos.map(estudo => `
                <div class="estudo-card" data-id="${estudo.id}">
                    <div class="estudo-info">
                        <div class="estudo-date">${estudo.data}</div>
                        <h3 class="estudo-tema">${estudo.tema}</h3>
                        <div class="estudo-links">
                            ${estudo.pdf_url ? `<a href="${estudo.pdf_url}" target="_blank" class="estudo-link"><i class="fas fa-file-pdf"></i> Ver PDF</a>` : ''}
                            ${estudo.youtube_url ? `<a href="${estudo.youtube_url}" target="_blank" class="estudo-link"><i class="fab fa-youtube"></i> Louvor</a>` : ''}
                        </div>
                    </div>
                    <div class="estudo-actions">
                        <button class="btn-icon btn-edit" data-id="${estudo.id}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon btn-delete" data-id="${estudo.id}" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('') : '<p class="empty-message">Nenhum estudo cadastrado.</p>';

            return `
                <div class="page-header">
                    <h2>Estudos da Célula</h2>
                    <button class="btn btn-primary" id="btn-novo-estudo">
                        <i class="fas fa-plus"></i>
                        <span>Novo Estudo</span>
                    </button>
                </div>

                <div id="alert-container"></div>

                <div class="estudos-list">
                    ${estudosList}
                </div>

                <!-- Modal -->
                <div class="modal-overlay" id="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <h3 id="modal-title">Novo Estudo</h3>
                            <button class="modal-close" id="modal-close">&times;</button>
                        </div>
                        <form id="estudo-form" enctype="multipart/form-data">
                            <input type="hidden" id="estudo-id" name="id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="tema">Tema do Estudo *</label>
                                    <input type="text" id="tema" name="tema" placeholder="Ex: A importância da oração" required>
                                </div>
                                <div class="form-group">
                                    <label for="data">Data *</label>
                                    <input type="date" id="data" name="data" required>
                                </div>
                                <div class="form-group">
                                    <label for="pdf">PDF do Estudo</label>
                                    <input type="file" id="pdf" name="pdf" accept=".pdf">
                                    <small class="form-hint">Máximo 10MB</small>
                                    <div id="pdf-atual" class="pdf-atual"></div>
                                </div>
                                <div class="form-group">
                                    <label for="youtube_url">Link do YouTube (Louvor)</label>
                                    <input type="url" id="youtube_url" name="youtube_url" placeholder="https://youtube.com/watch?v=...">
                                    <small class="form-hint">Opcional - Link do vídeo de louvor</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="btn-cancelar">Cancelar</button>
                                <button type="submit" class="btn btn-primary" id="btn-salvar">
                                    <i class="fas fa-save"></i> Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
        },

        /**
         * Sections Page Content
         */
        sectionsPage() {
            return `
                <div class="sections-grid">
                    <a href="/content/sections/eventos" data-spa class="section-card">
                        <div class="section-card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="section-card-content">
                            <h3>Eventos</h3>
                            <p>Gerencie os eventos e programações da igreja</p>
                        </div>
                        <div class="section-card-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            `;
        }
    };

    // ==========================================
    // Pages
    // ==========================================
    const Pages = {
        /**
         * Login Page
         */
        login() {
            App.render(Components.loginPage());
            
            const form = document.getElementById('login-form');
            const alertContainer = document.getElementById('alert-container');
            const loginBtn = document.getElementById('login-btn');

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const remember = document.getElementById('remember').checked;

                loginBtn.disabled = true;
                loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Entrando...</span>';
                alertContainer.innerHTML = '';

                try {
                    const response = await Api.post(`${Config.apiUrl}/login`, {
                        email,
                        password,
                        remember
                    });

                    if (response.success) {
                        State.isAuthenticated = true;
                        State.user = response.user;
                        
                        alertContainer.innerHTML = Components.alert('success', response.message);
                        
                        setTimeout(() => {
                            Router.navigate(response.redirect || '/content/dashboard');
                        }, 500);
                    }
                } catch (error) {
                    alertContainer.innerHTML = Components.alert('error', error.message || 'Erro ao fazer login');
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = '<span>Entrar</span> <i class="fas fa-arrow-right"></i>';
                }
            });
        },

        /**
         * Dashboard Page
         */
        dashboard() {
            const content = Components.dashboardHome();
            App.render(Components.dashboardLayout(content, 'Dashboard'));
            Pages.initDashboardEvents();
        },

        /**
         * Sections Page
         */
        sections() {
            const content = Components.sectionsPage();
            App.render(Components.dashboardLayout(content, 'Seções'));
            Pages.initDashboardEvents();
        },

        /**
         * Estudo da Célula Page
         */
        async estudoCelula() {
            App.render(Components.dashboardLayout('<div class="loading-inline"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>', 'Estudo da Célula'));
            Pages.initDashboardEvents();

            try {
                const response = await Api.get(`${Config.apiUrl}/estudos-celula`);
                const content = Components.estudoCelulaPage(response.data || []);
                App.render(Components.dashboardLayout(content, 'Estudo da Célula'));
                Pages.initDashboardEvents();
                Pages.initEstudoCelulaEvents();
            } catch (error) {
                const content = Components.estudoCelulaPage([]);
                App.render(Components.dashboardLayout(content, 'Estudo da Célula'));
                Pages.initDashboardEvents();
                Pages.initEstudoCelulaEvents();
            }
        },

        /**
         * Initialize Estudo Celula events
         */
        initEstudoCelulaEvents() {
            const btnNovo = document.getElementById('btn-novo-estudo');
            const modalOverlay = document.getElementById('modal-overlay');
            const modalClose = document.getElementById('modal-close');
            const btnCancelar = document.getElementById('btn-cancelar');
            const form = document.getElementById('estudo-form');
            const alertContainer = document.getElementById('alert-container');

            // Abrir modal para novo
            btnNovo?.addEventListener('click', () => {
                document.getElementById('modal-title').textContent = 'Novo Estudo';
                document.getElementById('estudo-id').value = '';
                form.reset();
                document.getElementById('pdf-atual').innerHTML = '';
                modalOverlay.classList.add('active');
            });

            // Fechar modal
            const closeModal = () => {
                modalOverlay.classList.remove('active');
            };
            modalClose?.addEventListener('click', closeModal);
            btnCancelar?.addEventListener('click', closeModal);
            modalOverlay?.addEventListener('click', (e) => {
                if (e.target === modalOverlay) closeModal();
            });

            // Editar
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;
                    const card = btn.closest('.estudo-card');
                    const tema = card.querySelector('.estudo-tema').textContent;
                    
                    try {
                        const response = await Api.get(`${Config.apiUrl}/estudos-celula`);
                        const estudo = response.data.find(e => e.id == id);
                        
                        if (estudo) {
                            document.getElementById('modal-title').textContent = 'Editar Estudo';
                            document.getElementById('estudo-id').value = estudo.id;
                            document.getElementById('tema').value = estudo.tema;
                            document.getElementById('data').value = estudo.data_raw;
                            document.getElementById('youtube_url').value = estudo.youtube_url || '';
                            
                            const pdfAtual = document.getElementById('pdf-atual');
                            if (estudo.pdf_path) {
                                pdfAtual.innerHTML = `<a href="${estudo.pdf_url}" target="_blank"><i class="fas fa-file-pdf"></i> ${estudo.pdf_path}</a>`;
                            } else {
                                pdfAtual.innerHTML = '';
                            }
                            
                            modalOverlay.classList.add('active');
                        }
                    } catch (error) {
                        alertContainer.innerHTML = Components.alert('error', 'Erro ao carregar estudo');
                    }
                });
            });

            // Excluir
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', async () => {
                    if (!confirm('Tem certeza que deseja excluir este estudo?')) return;
                    
                    const id = btn.dataset.id;
                    try {
                        await Api.delete(`${Config.apiUrl}/estudos-celula/${id}`);
                        alertContainer.innerHTML = Components.alert('success', 'Estudo excluído com sucesso!');
                        setTimeout(() => Pages.estudoCelula(), 1000);
                    } catch (error) {
                        alertContainer.innerHTML = Components.alert('error', error.message || 'Erro ao excluir');
                    }
                });
            });

            // Submit form
            form?.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const btnSalvar = document.getElementById('btn-salvar');
                const originalHtml = btnSalvar.innerHTML;
                btnSalvar.disabled = true;
                btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';

                const formData = new FormData(form);
                const id = document.getElementById('estudo-id').value;
                const url = id ? `${Config.apiUrl}/estudos-celula/${id}` : `${Config.apiUrl}/estudos-celula`;

                // Adiciona o CSRF token ao FormData também
                formData.append('_token', Config.csrfToken);

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': Config.csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: formData
                    });

                    const json = await response.json();

                    if (!response.ok) {
                        throw json;
                    }

                    closeModal();
                    alertContainer.innerHTML = Components.alert('success', json.message);
                    setTimeout(() => Pages.estudoCelula(), 1000);
                } catch (error) {
                    alertContainer.innerHTML = Components.alert('error', error.message || 'Erro ao salvar');
                    btnSalvar.disabled = false;
                    btnSalvar.innerHTML = originalHtml;
                }
            });
        },

        /**
         * Media Page
         */
        media() {
            const content = Components.comingSoon('Mídia');
            App.render(Components.dashboardLayout(content, 'Mídia'));
            Pages.initDashboardEvents();
        },

        /**
         * Users Page
         */
        async users() {
            App.render(Components.dashboardLayout('<div class="loading-inline"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>', 'Usuários'));
            Pages.initDashboardEvents();

            try {
                const response = await Api.get(`${Config.apiUrl}/users`);
                const content = Components.usersPage(response.data || []);
                App.render(Components.dashboardLayout(content, 'Usuários'));
                Pages.initDashboardEvents();
                Pages.initUsersEvents();
            } catch (error) {
                const content = Components.usersPage([]);
                App.render(Components.dashboardLayout(content, 'Usuários'));
                Pages.initDashboardEvents();
                Pages.initUsersEvents();
            }
        },

        /**
         * Initialize Users events
         */
        initUsersEvents() {
            const btnNovo = document.getElementById('btn-novo-usuario');
            const modalOverlay = document.getElementById('modal-overlay');
            const modalClose = document.getElementById('modal-close');
            const btnCancelar = document.getElementById('btn-cancelar');
            const form = document.getElementById('user-form');
            const alertContainer = document.getElementById('alert-container');

            // Abrir modal para novo
            btnNovo?.addEventListener('click', () => {
                document.getElementById('modal-title').textContent = 'Novo Usuário';
                document.getElementById('user-id').value = '';
                form.reset();
                
                // Senha obrigatória para novo usuário
                const passwordInput = document.getElementById('user-password');
                const passwordConfirm = document.getElementById('password-confirm');
                passwordInput.required = true;
                passwordConfirm.required = true;
                document.getElementById('password-required').style.display = 'inline';
                document.getElementById('password-confirm-required').style.display = 'inline';
                document.getElementById('password-hint').textContent = 'Mínimo 6 caracteres';
                
                modalOverlay.classList.add('active');
            });

            // Fechar modal
            const closeModal = () => {
                modalOverlay.classList.remove('active');
            };
            modalClose?.addEventListener('click', closeModal);
            btnCancelar?.addEventListener('click', closeModal);
            modalOverlay?.addEventListener('click', (e) => {
                if (e.target === modalOverlay) closeModal();
            });

            // Editar
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;
                    
                    try {
                        const response = await Api.get(`${Config.apiUrl}/users`);
                        const user = response.data.find(u => u.id == id);
                        
                        if (user) {
                            document.getElementById('modal-title').textContent = 'Editar Usuário';
                            document.getElementById('user-id').value = user.id;
                            document.getElementById('name').value = user.name;
                            document.getElementById('user-email').value = user.email;
                            document.getElementById('user-password').value = '';
                            document.getElementById('password-confirm').value = '';
                            
                            // Senha opcional para edição
                            const passwordInput = document.getElementById('user-password');
                            const passwordConfirm = document.getElementById('password-confirm');
                            passwordInput.required = false;
                            passwordConfirm.required = false;
                            document.getElementById('password-required').style.display = 'none';
                            document.getElementById('password-confirm-required').style.display = 'none';
                            document.getElementById('password-hint').textContent = 'Deixe em branco para manter a senha atual';
                            
                            modalOverlay.classList.add('active');
                        }
                    } catch (error) {
                        alertContainer.innerHTML = Components.alert('error', 'Erro ao carregar usuário');
                    }
                });
            });

            // Excluir
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', async () => {
                    if (!confirm('Tem certeza que deseja excluir este usuário?')) return;
                    
                    const id = btn.dataset.id;
                    try {
                        await Api.delete(`${Config.apiUrl}/users/${id}`);
                        alertContainer.innerHTML = Components.alert('success', 'Usuário excluído com sucesso!');
                        setTimeout(() => Pages.users(), 1000);
                    } catch (error) {
                        alertContainer.innerHTML = Components.alert('error', error.message || 'Erro ao excluir');
                    }
                });
            });

            // Submit form
            form?.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const btnSalvar = document.getElementById('btn-salvar');
                const userId = document.getElementById('user-id').value;
                
                // Validar senhas
                const password = document.getElementById('user-password').value;
                const passwordConfirm = document.getElementById('password-confirm').value;
                
                if (password || passwordConfirm) {
                    if (password !== passwordConfirm) {
                        alertContainer.innerHTML = Components.alert('error', 'As senhas não coincidem');
                        return;
                    }
                    if (password.length < 6) {
                        alertContainer.innerHTML = Components.alert('error', 'A senha deve ter no mínimo 6 caracteres');
                        return;
                    }
                }
                
                // Desabilitar botão
                btnSalvar.disabled = true;
                btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
                
                try {
                    const formData = {
                        name: document.getElementById('name').value,
                        email: document.getElementById('user-email').value,
                    };
                    
                    if (password) {
                        formData.password = password;
                        formData.password_confirmation = passwordConfirm;
                    }
                    
                    if (userId) {
                        // Atualizar
                        await Api.post(`${Config.apiUrl}/users/${userId}`, formData);
                        alertContainer.innerHTML = Components.alert('success', 'Usuário atualizado com sucesso!');
                    } else {
                        // Criar novo
                        await Api.post(`${Config.apiUrl}/users`, formData);
                        alertContainer.innerHTML = Components.alert('success', 'Usuário criado com sucesso!');
                    }
                    
                    closeModal();
                    setTimeout(() => Pages.users(), 1000);
                    
                } catch (error) {
                    btnSalvar.disabled = false;
                    btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar';
                    
                    if (error.errors) {
                        const errorMessages = Object.values(error.errors).flat().join('<br>');
                        alertContainer.innerHTML = Components.alert('error', errorMessages);
                    } else {
                        alertContainer.innerHTML = Components.alert('error', error.message || 'Erro ao salvar');
                    }
                }
            });
        },

        /**
         * Settings Page
         */
        settings() {
            const content = Components.comingSoon('Configurações');
            App.render(Components.dashboardLayout(content, 'Configurações'));
            Pages.initDashboardEvents();
        },

        /**
         * Initialize dashboard events
         */
        initDashboardEvents() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (menuToggle && sidebar) {
                menuToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                    overlay?.classList.toggle('active');
                });

                overlay?.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                });
            }

            // Logout button
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', async () => {
                    try {
                        await Api.post(`${Config.apiUrl}/logout`);
                    } catch (e) {
                        // Continue with logout even if API fails
                        console.error('Logout error:', e);
                    }
                    
                    // Clear all state
                    State.isAuthenticated = false;
                    State.user = null;
                    State.currentRoute = '';
                    
                    // Clear any cached data
                    sessionStorage.clear();
                    
                    // Force reload to clear all JS state
                    window.location.href = '/content';
                });
            }
        }
    };

    // ==========================================
    // Main Application
    // ==========================================
    const App = {
        /**
         * Render content to #app
         */
        render(html) {
            const app = document.getElementById('app');
            if (app) {
                app.innerHTML = html;
            }
        },

        /**
         * Show loading state
         */
        showLoading() {
            State.isLoading = true;
        },

        /**
         * Hide loading state
         */
        hideLoading() {
            State.isLoading = false;
        },

        /**
         * Check authentication status
         */
        async checkAuth() {
            try {
                const response = await Api.get(`${Config.apiUrl}/check`);
                State.isAuthenticated = response.authenticated;
                State.user = response.user;
            } catch (error) {
                State.isAuthenticated = false;
                State.user = null;
            }
        },

        /**
         * Initialize the application
         */
        async init() {
            // Register routes
            Router.register('/content', Pages.login, false);
            Router.register('/content/dashboard', Pages.dashboard, true);
            Router.register('/content/sections', Pages.sections, true);
            Router.register('/content/estudo-celula', Pages.estudoCelula, true);
            Router.register('/content/media', Pages.media, true);
            Router.register('/content/users', Pages.users, true);
            Router.register('/content/settings', Pages.settings, true);

            // Initialize router
            Router.init();

            // Check authentication
            await this.checkAuth();

            // Handle initial route
            Router.handleRoute();
        }
    };

    // ==========================================
    // Initialize when DOM is ready
    // ==========================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => App.init());
    } else {
        App.init();
    }

})();
