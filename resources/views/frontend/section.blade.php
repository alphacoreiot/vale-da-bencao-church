@extends('layouts.app')

@section('title', $section->name . ' - Igreja Vale da Bênção')

@section('content')
<!-- Section Header -->
<section class="py-5" style="background-color: #000000; color: #FFFFFF;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #D0FBF9;">Início</a></li>
                <li class="breadcrumb-item active" style="color: #FFFFFF;">{{ $section->name }}</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold">{{ $section->name }}</h1>
        @if($section->description)
            <p class="lead" style="color: #D0FBF9;">{{ $section->description }}</p>
        @endif
    </div>
</section>

<!-- Section Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @if($section->publishedContents->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Nenhum conteúdo disponível no momento.
                    </div>
                @else
                    @foreach($section->publishedContents as $content)
                        <article class="mb-4 pb-4 border-bottom">
                            <h3 class="mb-3" style="color: #9C0505;">{{ $content->title }}</h3>
                            <div class="text-muted small mb-3">
                                <i class="fas fa-calendar me-2"></i>
                                {{ $content->published_at->format('d/m/Y') }}
                            </div>
                            <div class="content">
                                {!! $content->content !!}
                            </div>
                            @if($content->media->isNotEmpty())
                                <div class="row mt-3 g-3">
                                    @foreach($content->media->take(3) as $media)
                                        <div class="col-md-4">
                                            @if($media->isImage())
                                                <img src="{{ $media->getUrl() }}" 
                                                     alt="{{ $media->alt_text }}" 
                                                     class="img-fluid rounded">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ route('section.content', [$section->slug, $content->id]) }}" 
                               class="btn btn-sm mt-3"
                               style="background-color: #9C0505; color: #FFFFFF;">
                                Ler Mais
                            </a>
                        </article>
                    @endforeach
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- AI Chat Widget -->
                @if($section->getAiAgentConfig()['enabled'])
                <div class="card mb-4 shadow-sm" style="border-left: 4px solid #FF3700 !important;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #9C0505;">
                            <i class="fas fa-robot me-2"></i>
                            {{ $section->getAiAgentConfig()['name'] }}
                        </h5>
                        <p class="small text-muted">
                            Tire suas dúvidas sobre {{ strtolower($section->name) }}
                        </p>
                        <button class="btn btn-sm w-100" 
                                style="background-color: #FF3700; color: #FFFFFF;"
                                onclick="openAIChat('{{ $section->slug }}')">
                            <i class="fas fa-comments me-2"></i> Iniciar Conversa
                        </button>
                    </div>
                </div>
                @endif

                <!-- Other Sections -->
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #9C0505; color: #FFFFFF;">
                        <strong>Outras Seções</strong>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach(\App\Models\Section::active()->where('id', '!=', $section->id)->ordered()->get() as $otherSection)
                            <a href="{{ route('section.show', $otherSection->slug) }}" 
                               class="list-group-item list-group-item-action">
                                {{ $otherSection->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function openAIChat(sectionSlug) {
    // Simple alert for now - will be enhanced with modal
    alert('Chat IA será implementado em breve! Seção: ' + sectionSlug);
    // TODO: Implement AI chat modal
}
</script>
@endpush
@endsection
