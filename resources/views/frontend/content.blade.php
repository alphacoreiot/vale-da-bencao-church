@extends('layouts.app')

@section('title', $content->title . ' - Igreja Vale da Bênção')

@section('content')
<!-- Conteúdo do Artigo -->
<section style="padding: 120px 0 80px 0; background: #000;">
    <div class="container">
        <div class="row">
            <!-- Artigo -->
            <div class="col-lg-8" style="margin: 0 auto;">
                <article class="content-article" style="background: #1a1a1a; padding: 40px; border-radius: 15px; box-shadow: 0 4px 20px rgba(255,255,255,0.05);">
                    <!-- Título do Artigo -->
                    <h1 style="font-size: 2.5rem; font-weight: 700; color: #fff; margin-bottom: 20px; line-height: 1.2;">{{ $content->title }}</h1>
                    
                    <!-- Meta informações -->
                    <div style="color: rgba(255,255,255,0.6); margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #333; font-size: 14px;">
                        📅 {{ $content->published_at->format('d/m/Y') }}
                        @if($content->author)
                            <span style="margin-left: 20px;">👤 {{ $content->author }}</span>
                        @endif
                    </div>
                    
                    @if($content->excerpt)
                        <div style="font-size: 1.2rem; color: rgba(255,255,255,0.8); font-style: italic; margin-bottom: 30px; padding: 20px; background: #0a0a0a; border-left: 4px solid #D4AF37; border-radius: 5px;">
                            {{ $content->excerpt }}
                        </div>
                    @endif
                    
                    <!-- Imagem Destacada -->
                    @if($content->media->where('is_featured', true)->first())
                        <div class="mb-4">
                            <img src="{{ $content->media->where('is_featured', true)->first()->getUrl() }}" 
                                 alt="{{ $content->title }}" 
                                 class="img-fluid rounded shadow"
                                 style="width: 100%; height: 400px; object-fit: cover; border-radius: 10px;">
                        </div>
                    @endif
                    
                    <!-- Conteúdo -->
                    <div class="article-content" style="font-size: 1.1rem; line-height: 1.8; color: rgba(255,255,255,0.9);">
                        {!! $content->content !!}
                    </div>
                    
                    <!-- Galeria de Mídia -->
                    @if($content->media->where('is_featured', false)->count() > 0)
                        <div class="mt-5">
                            <div class="section-header" style="margin-bottom: 30px;">
                                <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Galeria</span>
                            </div>
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
                    <div class="mt-5 p-4 rounded" style="background: #0a0a0a; border-radius: 15px; border: 1px solid #333;">
                        <div class="section-header" style="margin-bottom: 20px;">
                            <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Compartilhar</span>
                        </div>
                        <div class="d-flex gap-2" style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                               target="_blank" style="display: inline-block; background: #1877F2; color: #fff; padding: 10px 20px; border-radius: 20px; text-decoration: none; font-weight: 600; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                                📘 Facebook
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ $content->title }} - {{ url()->current() }}" 
                               target="_blank" style="display: inline-block; background: #25D366; color: #fff; padding: 10px 20px; border-radius: 20px; text-decoration: none; font-weight: 600; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                                💬 WhatsApp
                            </a>
                            <button onclick="copyLink()" style="background: #666; color: #fff; padding: 10px 20px; border-radius: 20px; border: none; font-weight: 600; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                                🔗 Copiar Link
                            </button>
                        </div>
                    </div>
                    
                    <!-- Voltar -->
                    <div class="mt-4" style="text-align: center;">
                        <a href="{{ route('section.show', $section->slug) }}" style="display: inline-block; background: #000; color: #fff; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.background='#333';" onmouseout="this.style.background='#000';">
                            ← Voltar para {{ $section->name }}
                        </a>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4" style="display: none;">
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