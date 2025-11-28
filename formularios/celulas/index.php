<?php
/**
 * Formulário de Recadastramento de Células
 * Aplicação isolada - Não faz parte do Laravel
 */

// Configurações do banco de dados
$host = '127.0.0.1';
$dbname = 'u817008098_valedabencao';
$username = 'u817008098_valedabencao';
$password = 'QL95yuee3k?';

$message = '';
$messageType = '';

// Carregar gerações do banco
$geracoes = [];
$bairros = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar gerações
    $stmt = $pdo->query("SELECT id, nome FROM geracoes WHERE ativo = 1 ORDER BY nome");
    $geracoes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
} catch (PDOException $e) {
    $message = "Erro de conexão com o banco de dados.";
    $messageType = 'error';
}

// Carregar bairros do GeoJSON
$geojsonPath = __DIR__ . '/../../public/geojson/Camacari.geojson';
if (!file_exists($geojsonPath)) {
    $geojsonPath = __DIR__ . '/../../geojson/Camacari.geojson';
}

if (file_exists($geojsonPath)) {
    $geojson = json_decode(file_get_contents($geojsonPath));
    if ($geojson && isset($geojson->features)) {
        foreach ($geojson->features as $feature) {
            if (isset($feature->properties->nm_bairro)) {
                $bairros[] = $feature->properties->nm_bairro;
            }
        }
        $bairros = array_unique($bairros);
        sort($bairros);
    }
}
// Adicionar bairros extras
$bairros[] = 'DIAS DAVILA';
$bairros = array_unique($bairros);
sort($bairros);

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_celula = trim($_POST['nome_celula'] ?? '');
    $lider = trim($_POST['lider'] ?? '');
    $geracao_id = intval($_POST['geracao_id'] ?? 0);
    $bairro = trim($_POST['bairro'] ?? '');
    $rua = trim($_POST['rua'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $ponto_referencia = trim($_POST['ponto_referencia'] ?? '');
    $contato = trim($_POST['contato'] ?? '');
    $contato2_nome = trim($_POST['contato2_nome'] ?? '');
    $contato2_whatsapp = trim($_POST['contato2_whatsapp'] ?? '');
    $latitude = !empty($_POST['latitude']) ? floatval($_POST['latitude']) : null;
    $longitude = !empty($_POST['longitude']) ? floatval($_POST['longitude']) : null;
    
    // Validação
    $errors = [];
    if (empty($nome_celula)) $errors[] = "Nome da célula é obrigatório";
    if (empty($lider)) $errors[] = "Nome do líder é obrigatório";
    if ($geracao_id <= 0) $errors[] = "Selecione uma geração";
    if (empty($bairro)) $errors[] = "Selecione um bairro";
    if (empty($contato)) $errors[] = "Contato é obrigatório";
    if (empty($latitude) || empty($longitude)) $errors[] = "Localização no mapa é obrigatória. Faça o cadastro no local da célula.";
    
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO form_celulas_recadastramento 
                    (nome_celula, lider, geracao_id, bairro, rua, numero, complemento, ponto_referencia, contato, contato2_nome, contato2_whatsapp, latitude, longitude, status, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendente', NOW(), NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome_celula,
                $lider,
                $geracao_id,
                $bairro,
                $rua ?: null,
                $numero ?: null,
                $complemento ?: null,
                $ponto_referencia ?: null,
                $contato,
                $contato2_nome ?: null,
                $contato2_whatsapp ?: null,
                $latitude,
                $longitude
            ]);
            
            $message = "Recadastramento enviado com sucesso! Aguarde a aprovação.";
            $messageType = 'success';
            
            // Limpar campos após sucesso
            $_POST = [];
            
        } catch (PDOException $e) {
            $message = "Erro ao salvar os dados. Tente novamente.";
            $messageType = 'error';
        }
    } else {
        $message = implode("<br>", $errors);
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recadastramento de Células - Igreja Vale da Bênção</title>
    <link rel="icon" type="image/png" href="/assets/perfil.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --gold: #D4AF37;
            --gold-light: #F4D03F;
            --dark-bg: #1a1a2e;
            --dark-card: #16213e;
            --dark-input: #0f3460;
            --text-light: #ffffff;
            --text-muted: #a0a0a0;
            --success: #27ae60;
            --error: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Exo', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #0f0f23 100%);
            min-height: 100vh;
            color: var(--text-light);
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 30px 0;
        }

        .header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid var(--gold);
            background: #ffffff;
            padding: 5px;
        }

        .header h1 {
            font-size: 1.5rem;
            color: var(--gold);
            margin-bottom: 5px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .form-card {
            background: var(--dark-card);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .form-group label .required {
            color: var(--error);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            font-size: 1rem;
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 12px;
            background: var(--dark-input);
            color: var(--text-light);
            font-family: 'Exo', sans-serif;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23D4AF37' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 45px;
        }

        select.form-control option {
            background: var(--dark-card);
            color: var(--text-light);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-row.three-cols {
            grid-template-columns: 2fr 1fr 1fr;
        }

        @media (max-width: 500px) {
            .form-row, .form-row.three-cols {
                grid-template-columns: 1fr;
            }
        }

        .section-title {
            font-size: 1.1rem;
            color: var(--gold);
            margin: 25px 0 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            font-size: 1rem;
        }

        #map {
            height: 250px;
            border-radius: 12px;
            margin-top: 10px;
            border: 2px solid rgba(212, 175, 55, 0.3);
        }

        .map-instructions {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 8px;
        }

        .coords-display {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .coords-display span {
            background: var(--dark-input);
            padding: 8px 12px;
            border-radius: 8px;
            flex: 1;
            text-align: center;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-bg);
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 25px;
            font-family: 'Exo', sans-serif;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .message {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .message i {
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .message.success {
            background: rgba(39, 174, 96, 0.2);
            border: 1px solid var(--success);
            color: #7dcea0;
        }

        .message.error {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid var(--error);
            color: #f1948a;
        }

        .btn-location {
            background: var(--dark-input);
            color: var(--gold);
            border: 2px solid rgba(212, 175, 55, 0.3);
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Exo', sans-serif;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-location:hover {
            border-color: var(--gold);
            background: rgba(212, 175, 55, 0.1);
        }

        .location-alert {
            background: rgba(243, 156, 18, 0.15);
            border: 1px solid rgba(243, 156, 18, 0.5);
            border-radius: 12px;
            padding: 15px 18px;
            margin-bottom: 20px;
            color: #f5b041;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .location-alert i {
            margin-right: 8px;
            color: #f39c12;
        }

        .location-alert strong {
            color: #f39c12;
        }

        .footer {
            text-align: center;
            padding: 30px 0;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .footer a {
            color: var(--gold);
            text-decoration: none;
        }

        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--dark-input);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            border-color: var(--gold);
            background: rgba(212, 175, 55, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="/assets/perfil.png" alt="Igreja Vale da Bênção">
            <h1>Recadastramento de Células</h1>
            <p>Preencha os dados da sua célula para atualização cadastral</p>
        </div>

        <div class="form-card">
            <?php if ($message): ?>
                <div class="message <?= $messageType ?>">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <div><?= $message ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" id="formRecadastramento">
                <div class="section-title">
                    <i class="fas fa-users"></i>
                    Dados da Célula
                </div>

                <div class="form-group">
                    <label>Nome da Célula <span class="required">*</span></label>
                    <input type="text" name="nome_celula" class="form-control" 
                           placeholder="Ex: Célula Nova Vida"
                           value="<?= htmlspecialchars($_POST['nome_celula'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Nome do(s) Líder(es) da Célula <span class="required">*</span></label>
                    <input type="text" name="lider" class="form-control" 
                           placeholder="Nome completo do(s) líder(es)"
                           value="<?= htmlspecialchars($_POST['lider'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Geração <span class="required">*</span></label>
                    <select name="geracao_id" class="form-control" required>
                        <option value="">Selecione a geração</option>
                        <?php foreach ($geracoes as $id => $nome): ?>
                            <option value="<?= $id ?>" <?= (($_POST['geracao_id'] ?? '') == $id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Contato (WhatsApp) <span class="required">*</span></label>
                    <input type="tel" name="contato" class="form-control phone-mask" 
                           placeholder="(71) 99999-9999"
                           value="<?= htmlspecialchars($_POST['contato'] ?? '') ?>" required>
                </div>

                <div class="section-title">
                    <i class="fas fa-phone-alt"></i>
                    Contato Alternativo
                </div>

                <div class="form-group">
                    <label>Nome do Contato Alternativo</label>
                    <input type="text" name="contato2_nome" class="form-control" 
                           placeholder="Nome de quem será o contato alternativo"
                           value="<?= htmlspecialchars($_POST['contato2_nome'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>WhatsApp Alternativo</label>
                    <input type="tel" name="contato2_whatsapp" class="form-control phone-mask" 
                           placeholder="(71) 99999-9999"
                           value="<?= htmlspecialchars($_POST['contato2_whatsapp'] ?? '') ?>">
                </div>

                <div class="section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Endereço
                </div>

                <div class="form-group">
                    <label>Bairro <span class="required">*</span></label>
                    <select name="bairro" class="form-control" required>
                        <option value="">Selecione o bairro</option>
                        <?php foreach ($bairros as $bairro): ?>
                            <option value="<?= htmlspecialchars($bairro) ?>" <?= (($_POST['bairro'] ?? '') == $bairro) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($bairro) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row three-cols">
                    <div class="form-group">
                        <label>Rua</label>
                        <input type="text" name="rua" class="form-control" 
                               placeholder="Nome da rua"
                               value="<?= htmlspecialchars($_POST['rua'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" name="numero" class="form-control" 
                               placeholder="Nº"
                               value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Compl.</label>
                        <input type="text" name="complemento" class="form-control" 
                               placeholder="Apto, casa..."
                               value="<?= htmlspecialchars($_POST['complemento'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Ponto de Referência</label>
                    <input type="text" name="ponto_referencia" class="form-control" 
                           placeholder="Ex: Próximo ao mercado, em frente à praça..."
                           value="<?= htmlspecialchars($_POST['ponto_referencia'] ?? '') ?>">
                </div>

                <div class="section-title">
                    <i class="fas fa-map"></i>
                    Localização no Mapa <span class="required">*</span>
                </div>

                <div class="location-alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Importante:</strong> Este cadastro deve ser feito no local onde a célula se reúne. 
                    A localização é obrigatória para o mapeamento das células.
                </div>

                <button type="button" class="btn-location" onclick="getMyLocation()">
                    <i class="fas fa-crosshairs"></i>
                    Usar minha localização atual
                </button>

                <div id="map"></div>
                <p class="map-instructions">
                    <i class="fas fa-info-circle"></i>
                    Clique no mapa ou arraste o marcador para ajustar a localização exata da célula.
                </p>

                <div class="coords-display">
                    <span>Lat: <strong id="latDisplay">-</strong></span>
                    <span>Lng: <strong id="lngDisplay">-</strong></span>
                </div>

                <input type="hidden" name="latitude" id="latitude" value="<?= $_POST['latitude'] ?? '' ?>">
                <input type="hidden" name="longitude" id="longitude" value="<?= $_POST['longitude'] ?? '' ?>">

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    Enviar Recadastramento
                </button>
            </form>
        </div>

        <div class="footer">
            <p>Igreja Vale da Bênção - Camaçari/BA</p>
            <p><a href="/">← Voltar para o site</a></p>
            <p style="margin-top: 15px;"><a href="admin.php" class="btn-admin"><i class="fas fa-lock"></i> Área Administrativa</a></p>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inicializar mapa centrado em Camaçari
        const defaultLat = -12.6996;
        const defaultLng = -38.3263;
        
        const map = L.map('map').setView([defaultLat, defaultLng], 11);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Marcador arrastável
        let marker = null;

        function setMarker(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                
                marker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    updateCoords(pos.lat, pos.lng);
                });
            }
            map.setView([lat, lng], 16);
            updateCoords(lat, lng);
        }

        function updateCoords(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);
            document.getElementById('latDisplay').textContent = lat.toFixed(6);
            document.getElementById('lngDisplay').textContent = lng.toFixed(6);
        }

        // Clique no mapa
        map.on('click', function(e) {
            setMarker(e.latlng.lat, e.latlng.lng);
        });

        // Obter localização atual
        function getMyLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        setMarker(position.coords.latitude, position.coords.longitude);
                    },
                    function(error) {
                        alert('Não foi possível obter sua localização. Por favor, marque manualmente no mapa.');
                    },
                    { enableHighAccuracy: true }
                );
            } else {
                alert('Seu navegador não suporta geolocalização.');
            }
        }

        // Se já tem coordenadas preenchidas, mostrar marcador
        const savedLat = document.getElementById('latitude').value;
        const savedLng = document.getElementById('longitude').value;
        if (savedLat && savedLng) {
            setMarker(parseFloat(savedLat), parseFloat(savedLng));
        }

        // Máscara para telefone
        const phoneInput = document.querySelector('input[name="contato"]');
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 0) {
                value = '(' + value;
            }
            if (value.length > 3) {
                value = value.slice(0, 3) + ') ' + value.slice(3);
            }
            if (value.length > 10) {
                value = value.slice(0, 10) + '-' + value.slice(10);
            }
            
            e.target.value = value;
        });
    </script>
</body>
</html>
