<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Content;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sections = Section::where('is_active', true)->get();
        $contents = Content::where('is_published', true)
            ->with('section')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Home
        $xml .= $this->createUrl(
            url('/'),
            now()->toAtomString(),
            'daily',
            '1.0'
        );

        // Células
        $xml .= $this->createUrl(
            route('celulas'),
            now()->toAtomString(),
            'weekly',
            '0.8'
        );

        // Seções
        foreach ($sections as $section) {
            $xml .= $this->createUrl(
                route('section.show', $section->slug),
                $section->updated_at->toAtomString(),
                'weekly',
                '0.8'
            );
        }

        // Conteúdos
        foreach ($contents as $content) {
            if ($content->section) {
                $xml .= $this->createUrl(
                    route('section.content', [
                        'sectionSlug' => $content->section->slug,
                        'contentId' => $content->id
                    ]),
                    $content->updated_at->toAtomString(),
                    'monthly',
                    '0.6'
                );
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    private function createUrl($loc, $lastmod, $changefreq, $priority)
    {
        return sprintf(
            '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%s</priority></url>',
            htmlspecialchars($loc),
            $lastmod,
            $changefreq,
            $priority
        );
    }
}
