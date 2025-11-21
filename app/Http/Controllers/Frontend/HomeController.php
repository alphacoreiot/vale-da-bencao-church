<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Devocional;
use App\Services\SectionRotationService;
use App\Services\YouTubeService;
use League\CommonMark\CommonMarkConverter;

class HomeController extends Controller
{
    public function __construct(
        private SectionRotationService $rotationService,
        private YouTubeService $youtubeService
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

        // Get Eventos section media for Vale News carousel
        $eventoSection = Section::where('slug', 'eventos')->where('is_active', true)->first();
        $eventosMedia = [];
        
        if ($eventoSection) {
            $eventosMedia = $eventoSection->media()
                ->whereIn('type', ['image', 'video'])
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Get devocional ativo (do dia ou mais recente)
        $devocional = Devocional::ativoDoDia()->first() ?? Devocional::ativoRecente()->first();
        
        // Converter markdown para HTML se houver devocional
        if ($devocional) {
            $converter = new CommonMarkConverter([
                'html_input' => 'escape',
                'allow_unsafe_links' => false,
            ]);
            $devocional->titulo_html = $converter->convert($devocional->titulo);
            $devocional->descricao_html = $converter->convert($devocional->descricao);
            $devocional->texto_html = $converter->convert($devocional->texto);
            
            // Converter links do YouTube em iframes após processamento markdown
            $devocional->texto_html = $this->convertYoutubeLinks($devocional->texto_html);
        }

        // Get último vídeo do YouTube para Culto Online
        $latestVideo = $this->youtubeService->getLatestVideo();

        // Se for requisição AJAX, retornar apenas o conteúdo
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('frontend.home', compact(
                'sections',
                'highlightedSection',
                'featuredContent',
                'eventosMedia',
                'devocional',
                'latestVideo'
            ));
        }

        return view('frontend.home', compact(
            'sections',
            'highlightedSection',
            'featuredContent',
            'eventosMedia',
            'devocional',
            'latestVideo'
        ));
    }
    
    /**
     * Converte links do YouTube em iframes embed
     */
    private function convertYoutubeLinks($html)
    {
        // Padrões para identificar URLs do YouTube em links <a> ou texto puro
        $patterns = [
            // Link <a> com href do YouTube
            '/<a[^>]+href=["\']https?:\/\/(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)[^"\']*["\'][^>]*>.*?<\/a>/i',
            '/<a[^>]+href=["\']https?:\/\/(?:www\.)?youtu\.be\/([a-zA-Z0-9_-]+)[^"\']*["\'][^>]*>.*?<\/a>/i',
            // URLs puras do YouTube (não em links)
            '/(?<!href=["\'])https?:\/\/(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)(?:[^\s<]*)?/i',
            '/(?<!href=["\'])https?:\/\/(?:www\.)?youtu\.be\/([a-zA-Z0-9_-]+)(?:[^\s<]*)?/i',
        ];
        
        foreach ($patterns as $pattern) {
            $html = preg_replace_callback($pattern, function($matches) {
                $videoId = $matches[1];
                return '<div class="devocional-video-wrapper"><iframe src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
            }, $html);
        }
        
        return $html;
    }
}
