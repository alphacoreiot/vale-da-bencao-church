<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Services\SectionRotationService;

class HomeController extends Controller
{
    public function __construct(
        private SectionRotationService $rotationService
    ) {}

    /**
     * Display the home page.
     */
    public function index()
    {
        // Get currently highlighted section
        $highlightedSection = $this->rotationService->getCurrentHighlight();

        // Get all active sections with their published content
        $sections = Section::active()
            ->with(['publishedContents' => function ($query) {
                $query->latest()->limit(3);
            }])
            ->ordered()
            ->get();

        // Get featured content from highlighted section
        $featuredContent = $highlightedSection
            ? $highlightedSection->publishedContents()->first()
            : null;

        // Se for requisição AJAX, retornar apenas o conteúdo
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('frontend.home', compact(
                'sections',
                'highlightedSection',
                'featuredContent'
            ));
        }

        return view('frontend.home', compact(
            'sections',
            'highlightedSection',
            'featuredContent'
        ));
    }
}
