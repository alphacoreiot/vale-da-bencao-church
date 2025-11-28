<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Geracao;
use App\Models\CelulaCadastro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CelulasController extends Controller
{
    /**
     * Exibe a página de células com mapa
     */
    public function index()
    {
        // Buscar todas as gerações ativas
        $geracoes = Geracao::where('ativo', true)
            ->orderBy('nome')
            ->get();

        // Buscar células aprovadas do formulário de cadastro
        $celulas = CelulaCadastro::with('geracao')
            ->aprovadas()
            ->orderBy('bairro')
            ->get();

        // Total de células aprovadas
        $totalCelulas = $celulas->count();

        // Contar bairros únicos
        $totalBairros = $celulas->pluck('bairro')->unique()->count();

        // Mapeamento de bairros das células para bairros do GeoJSON
        $mapeamentoBairros = $this->getMapeamentoBairros();

        // Preparar dados das células para JSON
        $celulasJson = $celulas->map(function ($celula) {
            return [
                'id' => $celula->id,
                'nome' => $celula->nome_celula,
                'geracao' => $celula->geracao->nome ?? 'Sem geração',
                'geracao_id' => $celula->geracao_id,
                'lider' => $celula->lider,
                'bairro' => $celula->bairro,
                'endereco' => $celula->endereco_completo,
                'ponto_referencia' => $celula->ponto_referencia,
                'contato' => $celula->contato,
                'contato2_nome' => $celula->contato2_nome,
                'contato2_whatsapp' => $celula->contato2_whatsapp,
                'whatsapp_link' => $celula->whatsapp_link,
                'latitude' => $celula->latitude,
                'longitude' => $celula->longitude,
                'tem_coordenadas' => $celula->tem_coordenadas,
            ];
        })->values()->toArray();

        // Agrupar células por geração para estatísticas
        $celulasPorGeracao = $celulas->groupBy('geracao_id');

        return view('frontend.celulas-v2', compact(
            'geracoes', 
            'celulas',
            'totalCelulas', 
            'totalBairros',
            'mapeamentoBairros', 
            'celulasJson',
            'celulasPorGeracao'
        ));
    }

    /**
     * Retorna o GeoJSON dos bairros de Camaçari
     */
    public function geojson()
    {
        $geojsonPath = public_path('geojson/Camacari.geojson');
        
        if (!file_exists($geojsonPath)) {
            return response()->json(['error' => 'GeoJSON não encontrado'], 404);
        }

        $geojson = json_decode(file_get_contents($geojsonPath), true);
        
        return response()->json($geojson);
    }

    /**
     * API para buscar células por bairro
     */
    public function porBairro(Request $request)
    {
        $bairro = $request->get('bairro');
        
        $celulas = CelulaCadastro::with('geracao')
            ->aprovadas()
            ->where('bairro', 'like', '%' . $bairro . '%')
            ->get();

        return response()->json($celulas);
    }

    /**
     * Mapeamento de nomes de bairros das células para nomes do GeoJSON
     */
    private function getMapeamentoBairros(): array
    {
        return [
            // Bairro da Célula => Bairro do GeoJSON (nm_bairro)
            'ALPHAVILLE' => 'ALPHAVILLE',
            'JARDIM LIMOEIRO' => 'JARDIM LIMOEIRO',
            'LIMOEIRO' => 'JARDIM LIMOEIRO',
            'PIACAVEIRA' => 'PIACAVEIRA',
            'SANTO ANTONIO' => 'SANTO ANTONIO',
            'SANTO ANTÔNIO' => 'SANTO ANTONIO',
            'VERDES HORIZONTES' => 'VERDES HORIZONTES',
            'GLEBA A' => 'GLEBA A',
            'GLEBA B' => 'GLEBA B',
            'GLEBA C' => 'GLEBA C',
            'GLEBA E' => 'GLEBA E',
            'GLEBA H' => 'GLEBA H',
            'PARQUE DAS PALMEIRAS' => 'PARQUE DAS PALMEIRAS',
            'PHOC III' => 'TANCREDO NEVES - PHOC III',
            'PHOC I' => 'NOVA ALIANCA - PHOC I',
            'PHOC II' => 'RENASCER - PHOC II',
            'PONTO CERTO' => 'PONTO CERTO',
            'NOVO HORIZONTE' => 'NOVO HORIZONTE',
            'PARQUE SATELITE' => 'PARQUE SATELITE',
            'SANTA MARIA' => 'SANTA MARIA',
            'LAMA PRETA' => 'LAMA PRETA',
            'PARQUE VERDE II' => 'PARQUE VERDE II',
            'PARQUE VERDE I' => 'PARQUE VERDE I',
            'CENTRO' => 'CENTRO',
            'CATU DE ABRANTES' => 'CATU DE ABRANTES (ABRANTES)',
            'VILA DE ABRANTES' => 'VILA DE ABRANTES (ABRANTES)',
            'AREMBEPE' => 'AREMBEPE (ABRANTES)',
            'ALTO DA CRUZ' => 'ALTO DA CRUZ',
            'GRAVATA' => 'GRAVATA',
            'GRAVATÁ' => 'GRAVATA',
            'PARQUE DAS MANGABAS' => 'PARQUE DAS MANGABAS',
            'NOVA VITORIA' => 'NOVA VITORIA',
            'NOVA VITÓRIA' => 'NOVA VITORIA',
            'DIAS DAVILA' => 'DIAS DAVILA',
            'DIAS DÁVILA' => 'DIAS DAVILA',
        ];
    }
}
