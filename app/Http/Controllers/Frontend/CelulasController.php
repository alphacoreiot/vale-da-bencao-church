<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Geracao;
use App\Models\Celula;
use Illuminate\Http\Request;

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

        // Mapeamento de bairros das células para bairros do GeoJSON
        $mapeamentoBairros = $this->getMapeamentoBairros();

        // Preparar dados das células para JSON (evita problema com arrow functions no Blade)
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
        
        $celulas = Celula::with('geracao')
            ->where('ativo', true)
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
            'Alphaville' => 'ALPHAVILLE',
            'Limoeiro' => 'JARDIM LIMOEIRO',
            'Jardim Limoeiro' => 'JARDIM LIMOEIRO',
            'Jardim Limoeiro 2' => 'JARDIM LIMOEIRO',
            'Piaçaveira' => 'PIACAVEIRA',
            'Santo Antônio 1' => 'SANTO ANTONIO',
            'Santo Antônio' => 'SANTO ANTONIO',
            'Verdes Horizonte' => 'VERDES HORIZONTES',
            'Verdes Horizontes' => 'VERDES HORIZONTES',
            'Gleba B' => 'GLEBA B',
            'Gleba C' => 'GLEBA C',
            'Gleba H' => 'GLEBA H',
            'Parq das Palmeiras' => 'PARQUE DAS PALMEIRAS',
            'Parque das Palmeiras' => 'PARQUE DAS PALMEIRAS',
            'Phoc 3' => 'TANCREDO NEVES - PHOC III',
            'Phoc 1' => 'NOVA ALIANCA - PHOC I',
            'Phoc I' => 'NOVA ALIANCA - PHOC I',
            'Phoc 2' => 'RENASCER - PHOC II',
            'Ponto Certo' => 'PONTO CERTO',
            'Novo Horizonte' => 'NOVO HORIZONTE',
            'Bairro Novo' => 'NOVO HORIZONTE',
            'Parque Satélite' => 'PARQUE SATELITE',
            'Santa Maria' => 'SANTA MARIA',
            'Lama preta' => 'LAMA PRETA',
            'Lama Preta' => 'LAMA PRETA',
            'Paque verde 2' => 'PARQUE VERDE II',
            'Parque verde 2' => 'PARQUE VERDE II',
            'Parque Verde' => 'PARQUE VERDE II',
            'Av Camaçari' => 'CENTRO',
            'Centro' => 'CENTRO',
            'Cond Villa Bella' => 'JARDIM LIMOEIRO',
            'Caji' => 'CATU DE ABRANTES (ABRANTES)',
            'Vila de Abrantes' => 'VILA DE ABRANTES (ABRANTES)',
            'Arembepe' => 'AREMBEPE (ABRANTES)',
            'Alto da Cruz' => 'ALTO DA CRUZ',
            'Gravatá' => 'GRAVATA',
            'Alto do Gravatá' => 'GRAVATA',
            'Parque das Mangabeiras' => 'PARQUE DAS MANGABAS',
            'Parque das Mangabas' => 'PARQUE DAS MANGABAS',
            'Alto do Coqueirinho' => 'ALTO DA CRUZ',
            'Nova Vitória' => 'NOVA VITORIA',
        ];
    }
}
