#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/app/Http/Controllers/Frontend/CelulasController.php"

new_content = '''<?php

namespace App\\Http\\Controllers\\Frontend;

use App\\Http\\Controllers\\Controller;
use App\\Models\\Geracao;
use App\\Models\\Celula;
use Illuminate\\Http\\Request;

class CelulasController extends Controller
{
    /**
     * Exibe a página de células com mapa
     */
    public function index()
    {
        // Buscar todas as gerações ativas com suas células
        $geracoes = Geracao::with(['celulas' => function ($query) {
            $query->where('ativo', true)->orderBy('bairro');
        }])
        ->where('ativo', true)
        ->orderBy('nome')
        ->get();

        // Total de células
        $totalCelulas = Celula::where('ativo', true)->count();

        // Mapeamento não é mais necessário - bairros já estão padronizados
        $mapeamentoBairros = [];

        // Preparar dados das células para JSON
        $celulasJson = [];
        foreach ($geracoes as $geracao) {
            foreach ($geracao->celulas as $celula) {
                $celulasJson[] = [
                    'geracao' => $geracao->nome,
                    'lider' => $celula->lider,
                    'bairro' => $celula->bairro,
                    'ponto_referencia' => $celula->ponto_referencia,
                    'contato' => $celula->contato,
                ];
            }
        }

        return view('frontend.celulas', compact('geracoes', 'totalCelulas', 'mapeamentoBairros', 'celulasJson'));
    }
}
'''

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Controller simplificado - mapeamento não é mais necessário!")
