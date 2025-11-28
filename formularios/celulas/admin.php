<?php
/**
 * Administração de Recadastramento de Células
 * Aplicação isolada - Não faz parte do Laravel
 */

session_start();

// Configurações do banco de dados
$host = '127.0.0.1';
$dbname = 'u817008098_valedabencao';
$username = 'u817008098_valedabencao';
$password = 'QL95yuee3k?';

// Senha simples para acesso (altere para uma senha segura)
$adminPassword = 'celulas2024';

$message = '';
$messageType = '';

// Verificar login
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
        if ($_POST['admin_password'] === $adminPassword) {
            $_SESSION['admin_logged'] = true;
        } else {
            $message = 'Senha incorreta!';
            $messageType = 'error';
        }
    }
    
    if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
        showLoginForm($message, $messageType);
        exit;
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados.");
}

// Carregar gerações
$geracoes = [];
$stmt = $pdo->query("SELECT id, nome FROM geracoes WHERE ativo = 1 ORDER BY nome");
$geracoes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Carregar bairros do GeoJSON
$bairros = [];
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

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Excluir
    if ($action === 'delete' && isset($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM form_celulas_recadastramento WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $message = 'Registro excluído com sucesso!';
        $messageType = 'success';
    }
    
    // Mudar status rapidamente
    if ($action === 'change_status' && isset($_POST['id']) && isset($_POST['new_status'])) {
        $validStatuses = ['pendente', 'aprovado', 'rejeitado'];
        $newStatus = $_POST['new_status'];
        if (in_array($newStatus, $validStatuses)) {
            $stmt = $pdo->prepare("UPDATE form_celulas_recadastramento SET status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$newStatus, $_POST['id']]);
            $statusLabels = ['pendente' => 'Pendente', 'aprovado' => 'Aprovado', 'rejeitado' => 'Rejeitado'];
            $message = "Status alterado para {$statusLabels[$newStatus]}!";
            $messageType = 'success';
        }
    }
    
    // Atualizar
    if ($action === 'update' && isset($_POST['id'])) {
        $sql = "UPDATE form_celulas_recadastramento SET 
                nome_celula = ?, lider = ?, geracao_id = ?, bairro = ?, 
                rua = ?, numero = ?, complemento = ?, ponto_referencia = ?, contato = ?, 
                contato2_nome = ?, contato2_whatsapp = ?,
                latitude = ?, longitude = ?, status = ?, updated_at = NOW()
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nome_celula'],
            $_POST['lider'],
            $_POST['geracao_id'],
            $_POST['bairro'],
            $_POST['rua'] ?: null,
            $_POST['numero'] ?: null,
            $_POST['complemento'] ?: null,
            $_POST['ponto_referencia'] ?: null,
            $_POST['contato'],
            $_POST['contato2_nome'] ?: null,
            $_POST['contato2_whatsapp'] ?: null,
            $_POST['latitude'] ?: null,
            $_POST['longitude'] ?: null,
            $_POST['status'],
            $_POST['id']
        ]);
        $message = 'Registro atualizado com sucesso!';
        $messageType = 'success';
    }
}

// Buscar dados para edição
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM form_celulas_recadastramento WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Filtros
$filterStatus = $_GET['status'] ?? '';
$filterGeracao = $_GET['geracao'] ?? '';
$search = $_GET['search'] ?? '';

// Buscar registros
$sql = "SELECT r.*, g.nome as geracao_nome 
        FROM form_celulas_recadastramento r 
        LEFT JOIN geracoes g ON r.geracao_id = g.id 
        WHERE 1=1";
$params = [];

if ($filterStatus) {
    $sql .= " AND r.status = ?";
    $params[] = $filterStatus;
}
if ($filterGeracao) {
    $sql .= " AND r.geracao_id = ?";
    $params[] = $filterGeracao;
}
if ($search) {
    $sql .= " AND (r.nome_celula LIKE ? OR r.lider LIKE ? OR r.bairro LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contadores
$totalPendentes = $pdo->query("SELECT COUNT(*) FROM form_celulas_recadastramento WHERE status = 'pendente'")->fetchColumn();
$totalAprovados = $pdo->query("SELECT COUNT(*) FROM form_celulas_recadastramento WHERE status = 'aprovado'")->fetchColumn();
$totalRejeitados = $pdo->query("SELECT COUNT(*) FROM form_celulas_recadastramento WHERE status = 'rejeitado'")->fetchColumn();

// Exportar dados
if (isset($_GET['export'])) {
    $exportFormat = $_GET['export'];
    $exportData = $registros;
    
    if ($exportFormat === 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="celulas_recadastramento_' . date('Y-m-d_His') . '.csv"');
        
        $output = fopen('php://output', 'w');
        // BOM para UTF-8 no Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Cabeçalho
        fputcsv($output, ['ID', 'Célula', 'Líder', 'Geração', 'Contato Principal', 'Contato Alt. Nome', 'Contato Alt. WhatsApp', 'Bairro', 'Rua', 'Número', 'Complemento', 'Ponto de Referência', 'Latitude', 'Longitude', 'Status', 'Data Cadastro'], ';');
        
        foreach ($exportData as $row) {
            fputcsv($output, [
                $row['id'],
                $row['nome_celula'],
                $row['lider'],
                $row['geracao_nome'] ?? '',
                $row['contato'],
                $row['contato2_nome'] ?? '',
                $row['contato2_whatsapp'] ?? '',
                $row['bairro'],
                $row['rua'] ?? '',
                $row['numero'] ?? '',
                $row['complemento'] ?? '',
                $row['ponto_referencia'] ?? '',
                $row['latitude'] ?? '',
                $row['longitude'] ?? '',
                ucfirst($row['status']),
                date('d/m/Y H:i', strtotime($row['created_at']))
            ], ';');
        }
        
        fclose($output);
        exit;
    }
    
    if ($exportFormat === 'excel') {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="celulas_recadastramento_' . date('Y-m-d_His') . '.xls"');
        
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"><style>td, th { border: 1px solid #ccc; padding: 5px; } th { background: #f0f0f0; font-weight: bold; }</style></head>';
        echo '<body><table border="1">';
        echo '<tr><th>ID</th><th>Célula</th><th>Líder</th><th>Geração</th><th>Contato Principal</th><th>Contato Alt. Nome</th><th>Contato Alt. WhatsApp</th><th>Bairro</th><th>Rua</th><th>Número</th><th>Complemento</th><th>Ponto de Referência</th><th>Latitude</th><th>Longitude</th><th>Status</th><th>Data Cadastro</th></tr>';
        
        foreach ($exportData as $row) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . htmlspecialchars($row['nome_celula']) . '</td>';
            echo '<td>' . htmlspecialchars($row['lider']) . '</td>';
            echo '<td>' . htmlspecialchars($row['geracao_nome'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['contato']) . '</td>';
            echo '<td>' . htmlspecialchars($row['contato2_nome'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['contato2_whatsapp'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['bairro']) . '</td>';
            echo '<td>' . htmlspecialchars($row['rua'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['numero'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['complemento'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($row['ponto_referencia'] ?? '') . '</td>';
            echo '<td>' . ($row['latitude'] ?? '') . '</td>';
            echo '<td>' . ($row['longitude'] ?? '') . '</td>';
            echo '<td>' . ucfirst($row['status']) . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($row['created_at'])) . '</td>';
            echo '</tr>';
        }
        
        echo '</table></body></html>';
        exit;
    }
}

// Carregar GeoJSON para o mapa
$geojsonData = null;
$geojsonPath = __DIR__ . '/../../public/geojson/Camacari.geojson';
if (!file_exists($geojsonPath)) {
    $geojsonPath = __DIR__ . '/../../geojson/Camacari.geojson';
}
if (file_exists($geojsonPath)) {
    $geojsonData = file_get_contents($geojsonPath);
}

// Preparar dados das células para o mapa (com coordenadas)
$celulasComCoordenadas = array_filter($registros, function($r) {
    return !empty($r['latitude']) && !empty($r['longitude']);
});

// Mapeamento de cores por nome de geração
$coresPorNome = [
    'água viva' => '#00CED1',
    'azul celeste' => '#87CEEB',
    'b e d' => '#8B4513',
    'bege' => '#F5F5DC',
    'branca' => '#FFFFFF',
    'branca e azul' => '#B0E0E6',
    'cinza' => '#808080',
    'coral' => '#FF7F50',
    'dourada' => '#FFD700',
    'gaditas' => '#8B0000',
    'israel' => '#0038B8',
    'jeová makadech' => '#4B0082',
    'laranja' => '#FFA500',
    'mostarda' => '#FFDB58',
    'marrom' => '#8B4513',
    'neon' => '#39FF14',
    'ouro' => '#FFD700',
    'pink' => '#FF69B4',
    'porta do secreto' => '#4A0E4E',
    'prata' => '#C0C0C0',
    'preta' => '#1a1a1a',
    'preta e branca' => '#2F4F4F',
    'resgate' => '#DC143C',
    'rosinha' => '#FFB6C1',
    'roxa' => '#800080',
    'verde bandeira' => '#009739',
    'verde tifanes' => '#00A86B',
    'verde e vinho' => '#355E3B',
];

// Buscar cores das gerações
$coresGeracoes = [];
$stmtCores = $pdo->query("SELECT id, nome, cor FROM geracoes");
while ($row = $stmtCores->fetch(PDO::FETCH_ASSOC)) {
    // Tentar encontrar cor pelo nome
    $corEncontrada = '#D4AF37'; // cor padrão
    $nomeLower = mb_strtolower($row['nome']);
    foreach ($coresPorNome as $chave => $cor) {
        if (strpos($nomeLower, $chave) !== false) {
            $corEncontrada = $cor;
            break;
        }
    }
    
    $coresGeracoes[$row['id']] = [
        'nome' => $row['nome'],
        'cor' => $row['cor'] ?: $corEncontrada
    ];
}

function showLoginForm($message, $messageType) {
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Recadastramento de Células</title>
    <link rel="icon" type="image/png" href="/assets/perfil.png">
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Exo', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #0f0f23 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
        }
        .login-card {
            background: var(--dark-card);
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            margin: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(212, 175, 55, 0.2);
            text-align: center;
        }
        .login-card h1 {
            color: var(--gold);
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        .login-card p {
            color: var(--text-muted);
            margin-bottom: 30px;
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
            margin-bottom: 20px;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--gold);
        }
        .btn {
            width: 100%;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark-bg);
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-family: 'Exo', sans-serif;
        }
        .btn:hover { opacity: 0.9; }
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid var(--error);
            color: #f1948a;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1><i class="fas fa-lock"></i> Área Administrativa</h1>
        <p>Recadastramento de Células</p>
        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="password" name="admin_password" class="form-control" placeholder="Digite a senha" required autofocus>
            <button type="submit" class="btn">Entrar</button>
        </form>
    </div>
</body>
</html>
<?php
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Recadastramento de Células</title>
    <link rel="icon" type="image/png" href="/assets/perfil.png">
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
            --warning: #f39c12;
            --info: #3498db;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Exo', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #0f0f23 100%);
            min-height: 100vh;
            color: var(--text-light);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            color: var(--gold);
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Exo', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            color: var(--dark-bg);
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
        }

        .btn-secondary {
            color: var(--text-light);
            background: var(--dark-input);
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .btn-danger {
            color: #fff;
            background: var(--error);
        }

        .btn-success {
            color: #fff;
            background: var(--success);
        }

        .btn:hover { opacity: 0.85; transform: translateY(-1px); }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        /* Export Buttons */
        .export-buttons {
            display: flex;
            gap: 8px;
            margin-left: auto;
        }

        .btn-export-csv {
            background: #28a745;
            color: white;
        }

        .btn-export-csv:hover {
            background: #218838;
        }

        .btn-export-excel {
            background: #217346;
            color: white;
        }

        .btn-export-excel:hover {
            background: #1e6b40;
        }

        /* Stats Cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: var(--dark-card);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold);
        }

        .stat-card .label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .stat-card.pending .number { color: var(--warning); }
        .stat-card.approved .number { color: var(--success); }
        .stat-card.rejected .number { color: var(--error); }

        /* Filters */
        .filters {
            background: var(--dark-card);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .filters .form-control {
            padding: 10px 14px;
            font-size: 0.9rem;
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 10px;
            background: var(--dark-input);
            color: var(--text-light);
            font-family: 'Exo', sans-serif;
            min-width: 180px;
        }

        .filters .form-control:focus {
            outline: none;
            border-color: var(--gold);
        }

        .filters select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%23D4AF37' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 35px;
        }

        /* Table */
        .table-wrapper {
            background: var(--dark-card);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        th {
            background: rgba(212, 175, 55, 0.1);
            color: var(--gold);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background: rgba(212, 175, 55, 0.05);
        }

        td {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pending {
            background: rgba(243, 156, 18, 0.2);
            color: var(--warning);
        }

        .badge-approved {
            background: rgba(39, 174, 96, 0.2);
            color: var(--success);
        }

        .badge-rejected {
            background: rgba(231, 76, 60, 0.2);
            color: var(--error);
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-approve {
            background: var(--success);
            color: white;
        }

        .btn-approve:hover {
            background: #219a52;
        }

        .btn-reject {
            background: var(--warning);
            color: white;
        }

        .btn-reject:hover {
            background: #d68910;
        }

        .btn-pending {
            background: #6c757d;
            color: white;
        }

        .btn-pending:hover {
            background: #5a6268;
        }

        .status-form {
            display: inline;
        }

        /* Message */
        .message {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
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

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            background: var(--dark-card);
            border-radius: 20px;
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            color: var(--gold);
            font-size: 1.2rem;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
        }

        .modal-close:hover {
            color: var(--text-light);
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .form-group .form-control {
            width: 100%;
            padding: 12px 14px;
            font-size: 0.95rem;
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 10px;
            background: var(--dark-input);
            color: var(--text-light);
            font-family: 'Exo', sans-serif;
        }

        .form-group .form-control:focus {
            outline: none;
            border-color: var(--gold);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-row.three {
            grid-template-columns: 2fr 1fr 1fr;
        }

        @media (max-width: 600px) {
            .form-row, .form-row.three {
                grid-template-columns: 1fr;
            }
        }

        #editMap {
            height: 200px;
            border-radius: 10px;
            margin-top: 10px;
            border: 2px solid rgba(212, 175, 55, 0.3);
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .cell-name {
            font-weight: 600;
            color: var(--gold);
        }

        .text-muted {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Delete confirmation */
        .delete-form {
            display: inline;
        }

        /* Dashboard Map */
        .map-dashboard {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .map-dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .map-dashboard-header h2 {
            color: var(--gold);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .map-dashboard-header .map-stats {
            display: flex;
            gap: 15px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .map-dashboard-header .map-stats span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .map-dashboard-header .map-stats .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        #dashboardMap {
            height: 400px;
            border-radius: 12px;
            border: 2px solid rgba(212, 175, 55, 0.2);
        }

        .map-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(212, 175, 55, 0.1);
        }

        .map-legend-title {
            width: 100%;
            font-size: 0.85rem;
            color: var(--gold);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: var(--text-light);
            background: var(--dark-input);
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .legend-item .color-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.5);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        @media (max-width: 768px) {
            #dashboardMap {
                height: 300px;
            }
            .map-dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-users-cog"></i> Recadastramento de Células</h1>
            <div class="header-actions">
                <a href="index.php" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Ver Formulário
                </a>
                <a href="?logout=1" class="btn btn-secondary">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <div class="number"><?= count($registros) ?></div>
                <div class="label">Total</div>
            </div>
            <div class="stat-card pending">
                <div class="number"><?= $totalPendentes ?></div>
                <div class="label">Pendentes</div>
            </div>
            <div class="stat-card approved">
                <div class="number"><?= $totalAprovados ?></div>
                <div class="label">Aprovados</div>
            </div>
            <div class="stat-card rejected">
                <div class="number"><?= $totalRejeitados ?></div>
                <div class="label">Rejeitados</div>
            </div>
        </div>

        <form class="filters" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Buscar célula, líder ou bairro..." value="<?= htmlspecialchars($search) ?>">
            
            <select name="status" class="form-control">
                <option value="">Todos os status</option>
                <option value="pendente" <?= $filterStatus === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="aprovado" <?= $filterStatus === 'aprovado' ? 'selected' : '' ?>>Aprovado</option>
                <option value="rejeitado" <?= $filterStatus === 'rejeitado' ? 'selected' : '' ?>>Rejeitado</option>
            </select>
            
            <select name="geracao" class="form-control">
                <option value="">Todas as gerações</option>
                <?php foreach ($geracoes as $id => $nome): ?>
                    <option value="<?= $id ?>" <?= $filterGeracao == $id ? 'selected' : '' ?>><?= htmlspecialchars($nome) ?></option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filtrar
            </button>
            
            <?php if ($filterStatus || $filterGeracao || $search): ?>
                <a href="admin.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Limpar
                </a>
            <?php endif; ?>
            
            <div class="export-buttons">
                <a href="?export=csv<?= $filterStatus ? "&status=$filterStatus" : '' ?><?= $filterGeracao ? "&geracao=$filterGeracao" : '' ?><?= $search ? "&search=" . urlencode($search) : '' ?>" class="btn btn-export-csv" title="Exportar CSV">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
                <a href="?export=excel<?= $filterStatus ? "&status=$filterStatus" : '' ?><?= $filterGeracao ? "&geracao=$filterGeracao" : '' ?><?= $search ? "&search=" . urlencode($search) : '' ?>" class="btn btn-export-excel" title="Exportar Excel">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </form>

        <!-- Dashboard Map -->
        <div class="map-dashboard">
            <div class="map-dashboard-header">
                <h2><i class="fas fa-map-marked-alt"></i> Mapa das Células</h2>
                <div class="map-stats">
                    <span><i class="fas fa-map-marker-alt" style="color: var(--gold);"></i> <?= count($celulasComCoordenadas) ?> células no mapa</span>
                </div>
            </div>
            <div id="dashboardMap"></div>
            <div class="map-legend" id="mapLegend">
                <div class="map-legend-title"><i class="fas fa-palette"></i> Legenda das Gerações</div>
                <!-- Legendas serão inseridas via JS -->
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-responsive">
                <?php if (empty($registros)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Nenhum registro encontrado</p>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Célula</th>
                                <th>Líder</th>
                                <th>Geração</th>
                                <th>Bairro</th>
                                <th>Contato</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros as $row): ?>
                                <tr>
                                    <td><span class="cell-name"><?= htmlspecialchars($row['nome_celula']) ?></span></td>
                                    <td><?= htmlspecialchars($row['lider']) ?></td>
                                    <td><?= htmlspecialchars($row['geracao_nome'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['bairro']) ?></td>
                                    <td><?= htmlspecialchars($row['contato']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $row['status'] === 'pendente' ? 'pending' : ($row['status'] === 'aprovado' ? 'approved' : 'rejected') ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <div class="actions">
                                            <?php if ($row['status'] !== 'aprovado'): ?>
                                            <form method="POST" class="status-form">
                                                <input type="hidden" name="action" value="change_status">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="new_status" value="aprovado">
                                                <button type="submit" class="btn btn-sm btn-approve" title="Aprovar">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                            <?php if ($row['status'] !== 'rejeitado'): ?>
                                            <form method="POST" class="status-form">
                                                <input type="hidden" name="action" value="change_status">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="new_status" value="rejeitado">
                                                <button type="submit" class="btn btn-sm btn-reject" title="Rejeitar">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                            <?php if ($row['status'] !== 'pendente'): ?>
                                            <form method="POST" class="status-form">
                                                <input type="hidden" name="action" value="change_status">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="new_status" value="pendente">
                                                <button type="submit" class="btn btn-sm btn-pending" title="Voltar para Pendente">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                            <a href="?edit=<?= $row['id'] ?><?= $filterStatus ? "&status=$filterStatus" : '' ?><?= $filterGeracao ? "&geracao=$filterGeracao" : '' ?><?= $search ? "&search=$search" : '' ?>" class="btn btn-sm btn-secondary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" class="status-form" onsubmit="return confirm('Tem certeza que deseja excluir este registro?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Script do Mapa Dashboard -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Dados das células para o mapa
        const celulasData = <?= json_encode(array_values($celulasComCoordenadas)) ?>;
        const coresGeracoes = <?= json_encode($coresGeracoes) ?>;
        const geojsonData = <?= $geojsonData ?: 'null' ?>;
        
        // Inicializar mapa dashboard
        const dashboardMap = L.map('dashboardMap').setView([-12.6996, -38.3263], 11);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(dashboardMap);

        // Adicionar GeoJSON dos bairros
        if (geojsonData) {
            const bairrosComCelulas = [...new Set(celulasData.map(c => c.bairro.toUpperCase()))];
            
            L.geoJSON(geojsonData, {
                style: function(feature) {
                    const bairroNome = feature.properties.nm_bairro?.toUpperCase();
                    const temCelula = bairrosComCelulas.some(b => 
                        bairroNome?.includes(b) || b?.includes(bairroNome)
                    );
                    
                    return {
                        fillColor: temCelula ? '#D4AF37' : '#2c3e50',
                        weight: 1,
                        opacity: 0.8,
                        color: '#D4AF37',
                        fillOpacity: temCelula ? 0.3 : 0.1
                    };
                },
                onEachFeature: function(feature, layer) {
                    if (feature.properties && feature.properties.nm_bairro) {
                        layer.bindTooltip(feature.properties.nm_bairro, {
                            permanent: false,
                            direction: 'center',
                            className: 'bairro-tooltip'
                        });
                    }
                }
            }).addTo(dashboardMap);
        }

        // Criar marcadores para cada célula
        const geracoesNoMapa = new Set();
        
        celulasData.forEach(celula => {
            const cor = coresGeracoes[celula.geracao_id]?.cor || '#D4AF37';
            const geracaoNome = coresGeracoes[celula.geracao_id]?.nome || 'Sem geração';
            
            geracoesNoMapa.add(celula.geracao_id);
            
            // Criar ícone personalizado
            const markerIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div style="
                    background: ${cor};
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    border: 3px solid #fff;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.4);
                "></div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });
            
            const marker = L.marker([celula.latitude, celula.longitude], { icon: markerIcon })
                .addTo(dashboardMap);
            
            // Popup com informações
            const statusBadge = celula.status === 'aprovado' 
                ? '<span style="color:#27ae60;">✓ Aprovado</span>'
                : celula.status === 'pendente'
                    ? '<span style="color:#f39c12;">◷ Pendente</span>'
                    : '<span style="color:#e74c3c;">✗ Rejeitado</span>';
            
            marker.bindPopup(`
                <div style="min-width: 200px;">
                    <strong style="font-size: 14px; color: #D4AF37;">${celula.nome_celula}</strong><br>
                    <small style="color: #666;">
                        <b>Líder:</b> ${celula.lider}<br>
                        <b>Geração:</b> <span style="color:${cor};">●</span> ${geracaoNome}<br>
                        <b>Bairro:</b> ${celula.bairro}<br>
                        ${celula.rua ? `<b>Endereço:</b> ${celula.rua}${celula.numero ? ', ' + celula.numero : ''}<br>` : ''}
                        ${celula.ponto_referencia ? `<b>Ref:</b> ${celula.ponto_referencia}<br>` : ''}
                        <b>Contato:</b> ${celula.contato}<br>
                        <b>Status:</b> ${statusBadge}
                    </small>
                </div>
            `);
        });

        // Criar legenda com as gerações presentes no mapa
        const legendContainer = document.getElementById('mapLegend');
        geracoesNoMapa.forEach(geracaoId => {
            if (coresGeracoes[geracaoId]) {
                const item = document.createElement('div');
                item.className = 'legend-item';
                item.innerHTML = `
                    <span class="color-dot" style="background: ${coresGeracoes[geracaoId].cor}"></span>
                    ${coresGeracoes[geracaoId].nome}
                `;
                legendContainer.appendChild(item);
            }
        });

        // Ajustar zoom para mostrar todas as células
        if (celulasData.length > 0) {
            const bounds = L.latLngBounds(celulasData.map(c => [c.latitude, c.longitude]));
            dashboardMap.fitBounds(bounds, { padding: [30, 30], maxZoom: 14 });
        }
    </script>
    
    <style>
        .bairro-tooltip {
            background: rgba(26, 26, 46, 0.9);
            border: 1px solid #D4AF37;
            color: #fff;
            font-family: 'Exo', sans-serif;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .leaflet-popup-content-wrapper {
            background: #1a1a2e;
            color: #fff;
            border-radius: 10px;
        }
        .leaflet-popup-tip {
            background: #1a1a2e;
        }
    </style>

    <!-- Modal de Edição -->
    <?php if ($editData): ?>
    <div class="modal-overlay show" id="editModal">
        <div class="modal">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Editar Registro</h2>
                <a href="admin.php<?= $filterStatus ? "?status=$filterStatus" : '' ?><?= $filterGeracao ? ($filterStatus ? '&' : '?') . "geracao=$filterGeracao" : '' ?><?= $search ? (($filterStatus || $filterGeracao) ? '&' : '?') . "search=$search" : '' ?>" class="modal-close">&times;</a>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nome da Célula</label>
                            <input type="text" name="nome_celula" class="form-control" value="<?= htmlspecialchars($editData['nome_celula']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Líder(es)</label>
                            <input type="text" name="lider" class="form-control" value="<?= htmlspecialchars($editData['lider']) ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Geração</label>
                            <select name="geracao_id" class="form-control" required>
                                <?php foreach ($geracoes as $id => $nome): ?>
                                    <option value="<?= $id ?>" <?= $editData['geracao_id'] == $id ? 'selected' : '' ?>><?= htmlspecialchars($nome) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Contato</label>
                            <input type="text" name="contato" class="form-control" value="<?= htmlspecialchars($editData['contato']) ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Contato Alternativo - Nome</label>
                            <input type="text" name="contato2_nome" class="form-control" value="<?= htmlspecialchars($editData['contato2_nome'] ?? '') ?>" placeholder="Nome do contato alternativo">
                        </div>
                        <div class="form-group">
                            <label>Contato Alternativo - WhatsApp</label>
                            <input type="text" name="contato2_whatsapp" class="form-control" value="<?= htmlspecialchars($editData['contato2_whatsapp'] ?? '') ?>" placeholder="(00) 00000-0000">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Bairro</label>
                        <select name="bairro" class="form-control" required>
                            <?php foreach ($bairros as $b): ?>
                                <option value="<?= htmlspecialchars($b) ?>" <?= $editData['bairro'] == $b ? 'selected' : '' ?>><?= htmlspecialchars($b) ?></option>
                            <?php endforeach; ?>
                            <?php if (!in_array($editData['bairro'], $bairros)): ?>
                                <option value="<?= htmlspecialchars($editData['bairro']) ?>" selected><?= htmlspecialchars($editData['bairro']) ?></option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-row three">
                        <div class="form-group">
                            <label>Rua</label>
                            <input type="text" name="rua" class="form-control" value="<?= htmlspecialchars($editData['rua'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Número</label>
                            <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($editData['numero'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento" class="form-control" value="<?= htmlspecialchars($editData['complemento'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ponto de Referência</label>
                        <input type="text" name="ponto_referencia" class="form-control" value="<?= htmlspecialchars($editData['ponto_referencia'] ?? '') ?>" placeholder="Ex: Próximo ao mercado, em frente à praça...">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pendente" <?= $editData['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="aprovado" <?= $editData['status'] === 'aprovado' ? 'selected' : '' ?>>Aprovado</option>
                            <option value="rejeitado" <?= $editData['status'] === 'rejeitado' ? 'selected' : '' ?>>Rejeitado</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" name="latitude" id="editLat" class="form-control" value="<?= $editData['latitude'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="longitude" id="editLng" class="form-control" value="<?= $editData['longitude'] ?>">
                        </div>
                    </div>

                    <div id="editMap"></div>
                </div>
                <div class="modal-footer">
                    <a href="admin.php<?= $filterStatus ? "?status=$filterStatus" : '' ?><?= $filterGeracao ? ($filterStatus ? '&' : '?') . "geracao=$filterGeracao" : '' ?><?= $search ? (($filterStatus || $filterGeracao) ? '&' : '?') . "search=$search" : '' ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const editLat = <?= $editData['latitude'] ?: -12.6996 ?>;
        const editLng = <?= $editData['longitude'] ?: -38.3263 ?>;
        
        const editMap = L.map('editMap').setView([editLat, editLng], <?= $editData['latitude'] ? 16 : 13 ?>);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(editMap);
        
        let editMarker = L.marker([editLat, editLng], { draggable: true }).addTo(editMap);
        
        editMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            document.getElementById('editLat').value = pos.lat.toFixed(7);
            document.getElementById('editLng').value = pos.lng.toFixed(7);
        });
        
        editMap.on('click', function(e) {
            editMarker.setLatLng(e.latlng);
            document.getElementById('editLat').value = e.latlng.lat.toFixed(7);
            document.getElementById('editLng').value = e.latlng.lng.toFixed(7);
        });

        // Fix map size issue in modal
        setTimeout(() => editMap.invalidateSize(), 100);
    </script>
    <?php endif; ?>
</body>
</html>
