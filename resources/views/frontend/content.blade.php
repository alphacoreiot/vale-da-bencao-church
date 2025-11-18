@extends('layouts.app')

@section('title', $content->title . ' - Igreja Vale da Bênção')

@section('content')
<!-- Hero Section do Artigo -->
<section class="hero-section-page" style="min-height: 50vh; background: linear-gradient(135deg, #000 0%, #1a1a1a 100%); display: flex; align-items: center;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" style="color: #D4AF37; text-decoration: none;">
                                <i class="fas fa-home me-1"></i>Início
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('section.show', $section->slug) }}" style="color: #D4AF37; text-decoration: none;">
                                {{ $section->name }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white">{{ $content->title }}</li>
                    </ol>
                </nav>
                
                <h1 class="display-5 fw-bold text-white mb-3">{{ $content->title }}</h1>
                
                <div class="text-white-50 mb-3">
                    <i class="fas fa-calendar me-2"></i>
                    {{ $content->published_at->format('d/m/Y') }}
                    @if($content->author)
                        <span class="ms-4">
                            <i class="fas fa-user me-2"></i>{{ $content->author }}
                        </span>
                    @endif
                </div>
                
                @if($content->excerpt)
                    <p class="lead text-white-50">{{ $content->excerpt }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Conteúdo do Artigo -->
<section style="padding: 80px 0; background: #fff;">
    <div class="container">
        <div class="row">
            <!-- Artigo -->
            <div class="col-lg-8">
                <article class="content-article">
                    <!-- Imagem Destacada -->
                    @if($content->media->where('is_featured', true)->first())
                        <div class="mb-4">
                            <img src="{{ $content->media->where('is_featured', true)->first()->getUrl() }}" 
                                 alt="{{ $content->title }}" 
                                 class="img-fluid rounded shadow"
                                 style="width: 100%; height: 400px; object-fit: cover;">
                        </div>
                    @endif
                    
                    <!-- Conteúdo -->
                    <div class="article-content" style="font-size: 1.1rem; line-height: 1.8; color: #333;">
                        {!! $content->content !!}
                    </div>
                    
                    <!-- Galeria de Mídia -->
                    @if($content->media->where('is_featured', false)->count() > 0)
                        <div class="mt-5">
                            <h4 class="mb-4" style="color: #D4AF37;">Galeria</h4>
                            <div class="row g-3">
                                @foreach($content->media->where('is_featured', false) as $media)
                                    <div class="col-md-4">
                                        @if($media->isImage())
                                            <img src="{{ $media->getUrl() }}" 
                                                 alt="{{ $media->alt_text }}" 
                                                 class="img-fluid rounded shadow-sm"
                                                 style="height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                                 onclick="openImageModal(this.src)">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Compartilhar -->
                    <div class="mt-5 p-4 rounded" style="background: #f8f9fa;">
                        <h5 class="mb-3">Compartilhar</h5>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                               target="_blank" class="btn btn-primary btn-sm">
                                <i class="fab fa-facebook-f me-1"></i>Facebook
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ $content->title }} - {{ url()->current() }}" 
                               target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                            </a>
                            <button class="btn btn-secondary btn-sm" onclick="copyLink()">
                                <i class="fas fa-link me-1"></i>Copiar Link
                            </button>
                        </div>
                    </div>
                    
                    <!-- Voltar -->
                    <div class="mt-4">
                        <a href="{{ route('section.show', $section->slug) }}" class="btn btn-dark">
                            <i class="fas fa-arrow-left me-2"></i>Voltar para {{ $section->name }}
                        </a>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Artigos Relacionados -->
                @if($relatedContents->count() > 0)
                <div class="card shadow-sm mb-4" style="border: none; border-radius: 15px;">
                    <div class="card-header border-0" style="background: #000; color: white; border-radius: 15px 15px 0 0;">
                        <i class="fas fa-newspaper me-2"></i><strong>Artigos Relacionados</strong>
                    </div>
                    <div class="card-body p-0">
                        @foreach($relatedContents as $related)
                            <a href="{{ route('section.content', [$section->slug, $related->id]) }}" 
                               class="d-block p-3 text-decoration-none border-bottom {{ $loop->last ? '' : 'border-bottom' }}"
                               style="color: #333; transition: all 0.3s ease;"
                               onmouseover="this.style.backgroundColor='#f8f9fa'"
                               onmouseout="this.style.backgroundColor='transparent'">
                                <h6 class="mb-1 fw-bold">{{ $related->title }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $related->published_at->format('d/m/Y') }}
                                </small>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Chat IA -->
                <div class="card shadow-sm mb-4" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%);">
                    <div class="card-body text-white">
                        <h5 class="card-title fw-bold">
                            <i class="fas fa-robot me-2"></i>Assistente IA
                        </h5>
                        <p class="small opacity-75 mb-3">
                            Tire suas dúvidas sobre este conteúdo
                        </p>
                        <button class="btn btn-dark btn-sm w-100" onclick="openAIChat()">
                            <i class="fas fa-comments me-2"></i>Iniciar Conversa
                        </button>
                    </div>
                </div>

                <!-- Informações da Seção -->
                <div class="card shadow-sm" style="border: none; border-radius: 15px;">
                    <div class="card-body text-center">
                        <div class="mb-3" style="font-size: 3rem;">
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
                        <h5 class="fw-bold">{{ $section->name }}</h5>
                        @if($section->description)
                            <p class="text-muted small">{{ $section->description }}</p>
                        @endif
                        <a href="{{ route('section.show', $section->slug) }}" class="btn btn-outline-dark btn-sm">
                            Ver Todos os Conteúdos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para Zoom de Imagem -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" 
                        data-bs-dismiss="modal" style="z-index: 1000;"></button>
                <img id="modalImage" src="" class="img-fluid w-100" style="border-radius: 15px;">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openImageModal(src) {
    if (typeof openImageZoom === 'function') {
        openImageZoom(src, 'Imagem');
    }
}

function openAIChat() {
    const chatContainer = document.getElementById('aiChatContainer');
    if (chatContainer) {
        chatContainer.classList.remove('minimized');
        chatContainer.classList.add('front');
    }
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Link copiado para a área de transferência!');
    });
}
</script>
@endpush
@endsection