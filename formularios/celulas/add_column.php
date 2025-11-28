<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    DB::statement("ALTER TABLE form_celulas_recadastramento ADD COLUMN ponto_referencia VARCHAR(255) NULL AFTER complemento");
    echo "Coluna ponto_referencia adicionada com sucesso!";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "Coluna já existe.";
    } else {
        echo "Erro: " . $e->getMessage();
    }
}
