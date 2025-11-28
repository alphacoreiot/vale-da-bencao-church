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
    
    // Atualizar
    if ($action === 'update' && isset($_POST['id'])) {
        $sql = "UPDATE form_celulas_recadastramento SET 
                nome_celula = ?, lider = ?, geracao_id = ?, bairro = ?, 
                rua = ?, numero = ?, complemento = ?, contato = ?, 
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
            $_POST['contato'],
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
        </form>

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
                                            <a href="?edit=<?= $row['id'] ?><?= $filterStatus ? "&status=$filterStatus" : '' ?><?= $filterGeracao ? "&geracao=$filterGeracao" : '' ?><?= $search ? "&search=$search" : '' ?>" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" class="delete-form" onsubmit="return confirm('Tem certeza que deseja excluir este registro?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
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
                            <label>Líder</label>
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

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const lat = <?= $editData['latitude'] ?: -12.6996 ?>;
        const lng = <?= $editData['longitude'] ?: -38.3263 ?>;
        
        const map = L.map('editMap').setView([lat, lng], <?= $editData['latitude'] ? 16 : 13 ?>);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        let marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        
        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            document.getElementById('editLat').value = pos.lat.toFixed(7);
            document.getElementById('editLng').value = pos.lng.toFixed(7);
        });
        
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('editLat').value = e.latlng.lat.toFixed(7);
            document.getElementById('editLng').value = e.latlng.lng.toFixed(7);
        });

        // Fix map size issue in modal
        setTimeout(() => map.invalidateSize(), 100);
    </script>
    <?php endif; ?>
</body>
</html>
