<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SectionContent;

class SectionController extends Controller
{
    /**
     * Display a section page.
     */
    public function show(string $slug)
    {
        $section = Section::where('slug', $slug)
            ->where('is_active', true)
            ->with(['publishedContents' => function ($query) {
                $query->latest();
            }, 'media'])
            ->firstOrFail();

        // Se for requisição AJAX, retornar apenas o conteúdo
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('frontend.section', compact('section'));
        }

        return view('frontend.section', compact('section'));
    }

    /**
     * Display a specific content item.
     */
    public function content(string $sectionSlug, int $contentId)
    {
        $section = Section::where('slug', $sectionSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $content = $section->publishedContents()
            ->with('media')
            ->findOrFail($contentId);

        $relatedContents = $section->publishedContents()
            ->where('id', '!=', $contentId)
            ->limit(3)
            ->get();

        // Se for requisição AJAX, retornar apenas o conteúdo
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('frontend.content', compact('section', 'content', 'relatedContents'));
        }

        return view('frontend.content', compact('section', 'content', 'relatedContents'));
    }
}
