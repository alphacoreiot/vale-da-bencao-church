<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SectionContent;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of contents for a section.
     */
    public function index(Section $section)
    {
        $contents = $section->contents()->latest()->paginate(20);

        return view('admin.contents.index', compact('section', 'contents'));
    }

    /**
     * Show the form for creating new content.
     */
    public function create(Section $section)
    {
        return view('admin.contents.create', compact('section'));
    }

    /**
     * Store a newly created content.
     */
    public function store(Request $request, Section $section)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:text,video,audio,gallery',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $content = $section->contents()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'is_published' => $validated['is_published'] ?? false,
            'published_at' => $validated['published_at'] ?? ($validated['is_published'] ?? false ? now() : null),
        ]);

        return redirect()
            ->route('admin.contents.index', $section)
            ->with('success', 'Conteúdo criado com sucesso!');
    }

    /**
     * Display the specified content.
     */
    public function show(Section $section, SectionContent $content)
    {
        $content->load('media');

        return view('admin.contents.show', compact('section', 'content'));
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Section $section, SectionContent $content)
    {
        return view('admin.contents.edit', compact('section', 'content'));
    }

    /**
     * Update the specified content.
     */
    public function update(Request $request, Section $section, SectionContent $content)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:text,video,audio,gallery',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $content->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'is_published' => $validated['is_published'] ?? $content->is_published,
            'published_at' => $validated['published_at'] ?? $content->published_at,
        ]);

        return redirect()
            ->route('admin.contents.index', $section)
            ->with('success', 'Conteúdo atualizado com sucesso!');
    }

    /**
     * Remove the specified content.
     */
    public function destroy(Section $section, SectionContent $content)
    {
        $content->delete();

        return redirect()
            ->route('admin.contents.index', $section)
            ->with('success', 'Conteúdo excluído com sucesso!');
    }

    /**
     * Publish the content.
     */
    public function publish(Section $section, SectionContent $content)
    {
        $content->publish();

        return back()->with('success', 'Conteúdo publicado com sucesso!');
    }

    /**
     * Unpublish the content.
     */
    public function unpublish(Section $section, SectionContent $content)
    {
        $content->unpublish();

        return back()->with('success', 'Conteúdo despublicado com sucesso!');
    }
}
