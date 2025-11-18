<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RotationConfig;
use App\Services\SectionRotationService;
use Illuminate\Http\Request;

class RotationController extends Controller
{
    public function __construct(
        private SectionRotationService $rotationService
    ) {}

    /**
     * Display rotation configuration.
     */
    public function index()
    {
        $config = RotationConfig::current();
        $statistics = $this->rotationService->getStatistics();
        $currentHighlight = $this->rotationService->getCurrentHighlight();
        $rotationTypes = RotationConfig::rotationTypes();

        return view('admin.rotation.index', compact(
            'config',
            'statistics',
            'currentHighlight',
            'rotationTypes'
        ));
    }

    /**
     * Update rotation configuration.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'rotation_type' => 'required|in:circular,priority,scheduled,random',
            'interval_minutes' => 'required|integer|min:15|max:1440',
            'is_active' => 'boolean',
        ]);

        $config = RotationConfig::current();

        if ($config) {
            $config->update($validated);
        } else {
            RotationConfig::create($validated);
        }

        return back()->with('success', 'Configuração de rotação atualizada com sucesso!');
    }

    /**
     * Manually trigger rotation.
     */
    public function rotate()
    {
        $section = $this->rotationService->rotate();

        if ($section) {
            return back()->with('success', "Rotação executada! Seção destacada: {$section->name}");
        }

        return back()->with('error', 'Não foi possível executar a rotação.');
    }

    /**
     * Toggle rotation on/off.
     */
    public function toggle()
    {
        $config = RotationConfig::current();

        if ($config) {
            $config->update(['is_active' => !$config->is_active]);
            $status = $config->is_active ? 'ativada' : 'desativada';
            return back()->with('success', "Rotação automática {$status}!");
        }

        return back()->with('error', 'Configuração de rotação não encontrada.');
    }
}
