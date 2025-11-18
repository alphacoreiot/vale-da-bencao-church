@extends('layouts.app')

@section('title', $section->name . ' - Igreja Vale da Bênção')

@section('content')
<!-- Hero Section da Página -->
<section class="section-hero-page">
    <div class="container">
        <div class="section-hero-content">
            <div class="breadcrumb-nav">
                <a href="{{ route('home') }}" class="breadcrumb-link">← Início</a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">{{ $section->name }}</span>
            </div>
            
            <div class="section-icon-large">
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
            
            <h1 class="section-page-title">{{ $section->name }}</h1>
            @if($section->description)
                <p class="section-page-description">{{ $section->description }}</p>
            @endif
        </div>
    </div>
</section>

<!-- Conteúdo Principal -->
<section class="section-content-area">
    <div class="container">
        <div class="content-grid">
            <!-- Conteúdo Principal -->
            <div class="content-main">
                @if($section->publishedContents->isEmpty())
                    <div class="empty-content">
                        <div class="empty-icon">📝</div>
                        <h3>Conteúdo em breve</h3>
                        <p>Estamos preparando conteúdos especiais para esta seção.</p>
                        <a href="{{ route('home') }}" class="btn-primary">← Voltar ao Início</a>
                    </div>
                @else
                    @foreach($section->publishedContents as $content)
                        <article class="content-card">
                            <h3 class="content-card-title">{{ $content->title }}</h3>
                            
                            <div class="content-meta">
                                <span>📅 {{ $content->published_at->format('d/m/Y') }}</span>
                                @if($content->author)
                                    <span>👤 {{ $content->author }}</span>
                                @endif
                            </div>
                            
                            <div class="content-excerpt">
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
                               class="btn-read-more">
                                Ler Mais →
                            </a>
                        </article>
                    @endforeach
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="content-sidebar">
                <!-- Chat IA -->
                <div class="sidebar-card card-highlight">
                    <div class="sidebar-card-header">
                        <h4>🤖 Assistente IA</h4>
                    </div>
                    <div class="sidebar-card-body">
                        <p>Tire suas dúvidas sobre {{ strtolower($section->name) }}</p>
                        <button class="btn-secondary" onclick="openAIChat()">💬 Iniciar Conversa</button>
                    </div>
                </div>

                <!-- Outras Seções -->
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h4>📋 Outras Seções</h4>
                    </div>
                    <div class="sidebar-links">
                        @foreach(\App\Models\Section::where('is_active', true)->where('id', '!=', $section->id)->get() as $otherSection)
                            <a href="{{ route('section.show', $otherSection->slug) }}" class="sidebar-link">
                                → {{ $otherSection->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@push('scripts')
<script>
function openAIChat() {
    // Abrir chat AI fixo
    const chatContainer = document.getElementById('aiChatContainer');
    if (chatContainer) {
        chatContainer.classList.remove('minimized');
        chatContainer.classList.add('front');
    }
}
</script>
@endpush
@endsection
