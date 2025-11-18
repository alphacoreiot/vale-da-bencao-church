<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SectionContent;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(
        private MediaService $mediaService
    ) {}

    /**
     * Display media for a section.
     */
    public function index(Section $section)
    {
        $media = $section->media()->latest()->paginate(24);
        $statistics = $this->mediaService->getStatistics();

        return view('admin.media.index', compact('section', 'media', 'statistics'));
    }

    /**
     * Show the upload form.
     */
    public function create(Section $section, Request $request)
    {
        $contentId = $request->query('content_id');
        $content = $contentId ? SectionContent::find($contentId) : null;

        return view('admin.media.create', compact('section', 'content'));
    }

    /**
     * Store uploaded media.
     */
    public function store(Request $request, Section $section)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
            'content_id' => 'nullable|exists:section_contents,id',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $content = $validated['content_id'] 
            ? SectionContent::find($validated['content_id']) 
            : null;

        $media = $this->mediaService->upload(
            $request->file('file'),
            $section,
            $content,
            $validated['alt_text'] ?? null
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'media' => $media,
                'url' => $media->getUrl(),
            ]);
        }

        return redirect()
            ->route('admin.media.index', $section)
            ->with('success', 'Mídia enviada com sucesso!');
    }

    /**
     * Show the form for editing media.
     */
    public function edit(Section $section, Media $media)
    {
        return view('admin.media.edit', compact('section', 'media'));
    }

    /**
     * Update media metadata.
     */
    public function update(Request $request, Section $section, Media $media)
    {
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
        ]);

        $this->mediaService->updateMetadata($media, $validated);

        return redirect()
            ->route('admin.media.index', $section)
            ->with('success', 'Mídia atualizada com sucesso!');
    }

    /**
     * Delete media.
     */
    public function destroy(Section $section, Media $media)
    {
        $this->mediaService->delete($media);

        return redirect()
            ->route('admin.media.index', $section)
            ->with('success', 'Mídia excluída com sucesso!');
    }

    /**
     * Gallery view for selecting media.
     */
    public function gallery(Section $section, Request $request)
    {
        $type = $request->query('type', 'image');
        $media = $section->media()
            ->where('type', $type)
            ->latest()
            ->paginate(24);

        return view('admin.media.gallery', compact('section', 'media', 'type'));
    }
}
