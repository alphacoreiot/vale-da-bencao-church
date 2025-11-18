<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Services\SectionRotationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function __construct(
        private SectionRotationService $rotationService
    ) {}

    /**
     * Display a listing of sections.
     */
    public function index()
    {
        $sections = Section::with(['contents', 'media'])
            ->withCount(['contents', 'media'])
            ->ordered()
            ->get();

        $currentHighlight = $this->rotationService->getCurrentHighlight();
        $statistics = $this->rotationService->getStatistics();

        return view('admin.sections.index', compact('sections', 'currentHighlight', 'statistics'));
    }

    /**
     * Show the form for creating a new section.
     */
    public function create()
    {
        return view('admin.sections.create');
    }

    /**
     * Store a newly created section.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:sections,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'priority' => 'required|integer|min:1|max:10',
            'display_order' => 'required|integer|min:0',
            'highlight_duration' => 'required|integer|min:15',
            'ai_enabled' => 'boolean',
            'ai_name' => 'nullable|string|max:255',
            'ai_personality' => 'nullable|string',
            'ai_system_prompt' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Build AI agent config
        $aiConfig = [
            'enabled' => $validated['ai_enabled'] ?? false,
            'name' => $validated['ai_name'] ?? 'Assistente',
            'personality' => $validated['ai_personality'] ?? 'amigável e prestativo',
            'prompts' => [
                'system' => $validated['ai_system_prompt'] ?? 'Você é um assistente da igreja.',
                'context' => 'Use apenas informações do sistema.',
            ],
        ];

        $section = Section::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'priority' => $validated['priority'],
            'display_order' => $validated['display_order'],
            'highlight_duration' => $validated['highlight_duration'],
            'ai_agent_config' => $aiConfig,
        ]);

        return redirect()
            ->route('admin.sections.index')
            ->with('success', 'Seção criada com sucesso!');
    }

    /**
     * Display the specified section.
     */
    public function show(Section $section)
    {
        $section->load(['contents' => function ($query) {
            $query->latest();
        }, 'media', 'highlightLogs' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.sections.show', compact('section'));
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    /**
     * Update the specified section.
     */
    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sections,slug,' . $section->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'priority' => 'required|integer|min:1|max:10',
            'display_order' => 'required|integer|min:0',
            'highlight_duration' => 'required|integer|min:15',
            'ai_enabled' => 'boolean',
            'ai_name' => 'nullable|string|max:255',
            'ai_personality' => 'nullable|string',
            'ai_system_prompt' => 'nullable|string',
        ]);

        // Build AI agent config
        $aiConfig = [
            'enabled' => $validated['ai_enabled'] ?? false,
            'name' => $validated['ai_name'] ?? 'Assistente',
            'personality' => $validated['ai_personality'] ?? 'amigável e prestativo',
            'prompts' => [
                'system' => $validated['ai_system_prompt'] ?? 'Você é um assistente da igreja.',
                'context' => 'Use apenas informações do sistema.',
            ],
        ];

        $section->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? $section->is_active,
            'priority' => $validated['priority'],
            'display_order' => $validated['display_order'],
            'highlight_duration' => $validated['highlight_duration'],
            'ai_agent_config' => $aiConfig,
        ]);

        return redirect()
            ->route('admin.sections.index')
            ->with('success', 'Seção atualizada com sucesso!');
    }

    /**
     * Remove the specified section.
     */
    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()
            ->route('admin.sections.index')
            ->with('success', 'Seção excluída com sucesso!');
    }

    /**
     * Toggle section active status.
     */
    public function toggle(Section $section)
    {
        $section->update(['is_active' => !$section->is_active]);

        $status = $section->is_active ? 'ativada' : 'desativada';
        return back()->with('success', "Seção {$status} com sucesso!");
    }

    /**
     * Force highlight a section.
     */
    public function highlight(Section $section)
    {
        $this->rotationService->forceHighlight($section);

        return back()->with('success', 'Seção destacada com sucesso!');
    }
}
