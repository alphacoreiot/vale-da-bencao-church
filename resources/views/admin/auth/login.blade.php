<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Vale da Bênção</title>
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Encode Sans Condensed', sans-serif;
            background: radial-gradient(circle at top right, #1a1a1a 0%, #000000 50%, #000a0a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(192, 192, 192, 0.15) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(208, 251, 249, 0.08) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .login-container {
            position: relative;
            z-index: 10;
            perspective: 1000px;
        }

        .login-card {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(13, 13, 13, 0.98) 100%);
            backdrop-filter: blur(10px);
            border: 2px solid var(--cor-destaque);
            border-radius: 20px;
            box-shadow: 
                0 20px 60px rgba(192, 192, 192, 0.3),
                0 0 100px rgba(192, 192, 192, 0.15),
                inset 0 0 60px rgba(192, 192, 192, 0.05);
            padding: 35px 50px;
            max-width: 618px;
            width: 100%;
        }

        @keyframes cardFloat {
            0%, 100% { transform: translateY(0) rotateX(0); }
            50% { transform: translateY(-10px) rotateX(2deg); }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        @keyframes logoGlow {
            0%, 100% { filter: drop-shadow(0 0 20px rgba(192, 192, 192, 0.6)); }
            50% { filter: drop-shadow(0 0 40px rgba(192, 192, 192, 0.9)); }
        }

        .login-logo img {
            max-width: 160px;
            height: auto;
            margin-bottom: 15px;
        }

        .login-logo h2 {
            color: var(--cor-secundaria);
            font-weight: 700;
            font-size: 24px;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 0 20px rgba(208, 251, 249, 0.5);
            margin-bottom: 3px;
        }

        .login-logo p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            font-weight: 400;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(168, 168, 168, 0.2) 0%, rgba(192, 192, 192, 0.2) 100%);
            color: var(--cor-acento);
            border: 1px solid var(--cor-destaque);
            border-radius: 10px;
            font-weight: 500;
            border-left: 4px solid var(--cor-acento);
        }

        .form-label {
            color: var(--cor-secundaria);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(192, 192, 192, 0.3);
            border-radius: 10px;
            color: var(--cor-principal);
            padding: 10px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--cor-destaque);
            box-shadow: 0 0 20px rgba(192, 192, 192, 0.4);
            color: var(--cor-principal);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .form-check-input {
            background-color: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(192, 192, 192, 0.5);
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--cor-destaque);
            border-color: var(--cor-destaque);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
            cursor: pointer;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--cor-destaque) 0%, var(--cor-acento) 100%);
            border: none;
            padding: 12px;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(192, 192, 192, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(192, 192, 192, 0.6);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            pointer-events: none;
        }

        /* Particles Background */
        .particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .particle:nth-child(1) { width: 3px; height: 3px; background: rgba(192, 192, 192, 0.3); top: 10%; left: 15%; animation: float 12s linear infinite; }
        .particle:nth-child(2) { width: 4px; height: 4px; background: rgba(192, 192, 192, 0.4); top: 20%; left: 85%; animation: float 15s linear infinite; animation-delay: 2s; }
        .particle:nth-child(3) { width: 2px; height: 2px; background: rgba(192, 192, 192, 0.2); top: 40%; left: 25%; animation: float 18s linear infinite; animation-delay: 4s; }
        .particle:nth-child(4) { width: 5px; height: 5px; background: rgba(192, 192, 192, 0.5); top: 60%; left: 75%; animation: float 14s linear infinite; animation-delay: 1s; }
        .particle:nth-child(5) { width: 3px; height: 3px; background: rgba(192, 192, 192, 0.3); top: 80%; left: 45%; animation: float 16s linear infinite; animation-delay: 3s; }
        .particle:nth-child(6) { width: 4px; height: 4px; background: rgba(192, 192, 192, 0.4); top: 15%; left: 65%; animation: float 13s linear infinite; animation-delay: 5s; }
        .particle:nth-child(7) { width: 2px; height: 2px; background: rgba(192, 192, 192, 0.2); top: 35%; left: 55%; animation: float 17s linear infinite; }
        .particle:nth-child(8) { width: 5px; height: 5px; background: rgba(192, 192, 192, 0.5); top: 55%; left: 35%; animation: float 11s linear infinite; animation-delay: 2s; }
        .particle:nth-child(9) { width: 3px; height: 3px; background: rgba(192, 192, 192, 0.3); top: 75%; left: 85%; animation: float 19s linear infinite; animation-delay: 4s; }
        .particle:nth-child(10) { width: 4px; height: 4px; background: rgba(192, 192, 192, 0.4); top: 25%; left: 5%; animation: float 14s linear infinite; animation-delay: 1s; }

        @keyframes float {
            0% { transform: translateY(0) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) translateX(20px); opacity: 0; }
        }
    </style>
</head>
<body>
    <!-- Particles -->
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('assets/logo.png') }}" alt="Vale da Bênção">
                <h2>Admin Panel</h2>
                <p>Vale da Bênção Church</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Email
                    </label>
                    <div class="input-icon">
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com" required autofocus>
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Senha
                    </label>
                    <div class="input-icon">
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                        <i class="fas fa-key"></i>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Lembrar-me neste dispositivo
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>Entrar
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
