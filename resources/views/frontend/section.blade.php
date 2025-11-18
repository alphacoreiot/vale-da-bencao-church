@extends('layouts.app')

@section('title', $section->name . ' - Igreja Vale da Bênção')

@section('content')
<!-- Conteúdo Principal -->
<section class="section-content-area" style="padding: 120px 0 80px 0; background: #000;">
    <div class="container">
        <!-- Título da Seção -->
        <div class="section-header" style="text-align: center; margin-bottom: 60px;">
            <div style="font-size: 3rem; margin-bottom: 15px;">
                @switch($section->slug)
                    @case('eventos') 📅 @break
                    @case('ministerios') 🙏 @break
                    @case('estudos') 📖 @break
                    @case('galeria') 📸 @break
                    @case('testemunhos') ⭐ @break
                    @case('contato') 📞 @break
                    @case('boas-vindas') 👋 @break
                    @default 📄
                @endswitch
            </div>
            <h1 class="section-main-title" style="font-size: 2.5rem; color: #fff; font-weight: 700; margin-bottom: 15px;">{{ $section->name }}</h1>
            @if($section->description)
                <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: 1.1rem; max-width: 700px; margin: 0 auto;">{{ $section->description }}</p>
            @endif
        </div>

        @if($section->slug === 'galeria')
            <!-- Feed do Instagram para Galeria -->
            <div class="instagram-feed-section">
                <div class="section-header" style="text-align: center; margin-bottom: 60px;">
                    <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 8px 20px; border-radius: 20px; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Instagram</span>
                    <h2 class="section-main-title" style="font-size: 2.5rem; color: #fff; font-weight: 700; margin-bottom: 15px;">Acompanhe Nossos Momentos</h2>
                    <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: 1.1rem; max-width: 700px; margin: 0 auto;">Veja as fotos e vídeos dos nossos cultos e eventos</p>
                </div>
                
                <!-- Embed do Instagram -->
                <div style="max-width: 1200px; margin: 0 auto;">
                    <!-- Elfsight Instagram Feed | Untitled Instagram Feed -->
                    <script src="https://elfsightcdn.com/platform.js" async></script>
                    <div class="elfsight-app-f416f43e-684e-444e-9add-9ebdc7e53f18" data-elfsight-app-lazy></div>
                </div>
            </div>
        @elseif($section->publishedContents->isEmpty())
            <div class="empty-content" style="text-align: center; padding: 80px 20px; background: #1a1a1a; border-radius: 15px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 20px rgba(255,255,255,0.05);">
                <div class="empty-icon" style="font-size: 5rem; margin-bottom: 20px;">📝</div>
                <h3 style="font-size: 2rem; color: #fff; font-weight: 700; margin-bottom: 15px;">Conteúdo em breve</h3>
                <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem; margin-bottom: 30px;">Estamos preparando conteúdos especiais para esta seção.</p>
                <a href="{{ route('home') }}" class="btn-primary" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">← Voltar ao Início</a>
            </div>
        @else
            <div class="content-grid-full" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
                @foreach($section->publishedContents as $content)
                    <article class="content-card" style="background: #1a1a1a; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(255,255,255,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255,255,255,0.05)';">
                        <h3 class="content-card-title" style="font-size: 1.5rem; color: #fff; font-weight: 700; margin-bottom: 15px;">{{ $content->title }}</h3>
                        
                        <div class="content-meta" style="display: flex; gap: 20px; margin-bottom: 15px; color: rgba(255,255,255,0.6); font-size: 14px;">
                            <span>📅 {{ $content->published_at->format('d/m/Y') }}</span>
                            @if($content->author)
                                <span>👤 {{ $content->author }}</span>
                            @endif
                        </div>
                        
                        <div class="content-excerpt" style="color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 20px;">
                            {!! Str::limit(strip_tags($content->content), 300) !!}
                        </div>
                        
                        @if($content->media->isNotEmpty())
                            <div class="content-gallery">
                                @foreach($content->media->take(3) as $media)
                                    @if($media->isImage())
                                        <img src="{{ $media->getUrl() }}" 
                                             alt="{{ $media->alt_text }}" 
                                             class="gallery-thumb">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        
                        <a href="{{ route('section.content', [$section->slug, $content->id]) }}" 
                           class="btn-read-more" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 15px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            Ler Mais →
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
