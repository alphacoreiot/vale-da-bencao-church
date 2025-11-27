#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/check_celulas.php"

content = '''<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

use App\\Models\\Geracao;
use App\\Models\\Celula;

echo "=== RESUMO ===" . PHP_EOL;
echo "Gerações: " . Geracao::count() . PHP_EOL;
echo "Células: " . Celula::count() . PHP_EOL;

echo PHP_EOL . "=== AMOSTRA DE GERAÇÕES ===" . PHP_EOL;
foreach(Geracao::take(10)->get() as $g) {
    echo "- " . $g->nome . " | Resp: " . ($g->responsaveis ?? 'N/A') . PHP_EOL;
}

echo PHP_EOL . "=== AMOSTRA DE CÉLULAS ===" . PHP_EOL;
foreach(Celula::with('geracao')->take(10)->get() as $c) {
    echo "- " . $c->geracao->nome . " | Líder: " . $c->lider . " | Bairro: " . ($c->bairro ?? 'N/A') . PHP_EOL;
}
'''

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Script de verificação criado!")
