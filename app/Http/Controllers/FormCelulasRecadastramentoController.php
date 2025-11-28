<?php

namespace App\Http\Controllers;

use App\Models\FormCelulasRecadastramento;
use App\Models\Geracao;
use Illuminate\Http\Request;

class FormCelulasRecadastramentoController extends Controller
{
    public function index()
    {
        $geracoes = Geracao::orderBy('nome')->pluck('nome', 'id');
        $geojsonPath = public_path('geojson/Camacari.geojson');
        $bairros = [];
        
        if (file_exists($geojsonPath)) {
            $geojson = json_decode(file_get_contents($geojsonPath));
            foreach ($geojson->features as $feature) {
                if (isset($feature->properties->nm_bairro)) {
                    $bairros[] = $feature->properties->nm_bairro;
                }
            }
            $bairros = array_unique($bairros);
            sort($bairros);
        }
        
        return view('formularios.celulas.recadastramento', compact('geracoes', 'bairros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_celula' => 'required|string|max:255',
            'lider' => 'required|string|max:255',
            'geracao_id' => 'required|exists:geracoes,id',
            'bairro' => 'required|string|max:255',
            'rua' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:50',
            'complemento' => 'nullable|string|max:255',
            'contato' => 'required|string|max:50',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        FormCelulasRecadastramento::create($validated);

        return redirect()
            ->route('formularios.celulas.recadastramento')
            ->with('success', 'Recadastramento enviado com sucesso! Aguarde a aprovação.');
    }
}
