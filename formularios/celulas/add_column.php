<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Adicionar colunas de contato alternativo
$columns = [
    "ALTER TABLE form_celulas_recadastramento ADD COLUMN contato2_nome VARCHAR(255) NULL AFTER contato",
    "ALTER TABLE form_celulas_recadastramento ADD COLUMN contato2_whatsapp VARCHAR(50) NULL AFTER contato2_nome",
];

foreach ($columns as $sql) {
    try {
        DB::statement($sql);
        echo "Coluna adicionada com sucesso!\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "Coluna já existe.\n";
        } else {
            echo "Erro: " . $e->getMessage() . "\n";
        }
    }
}
