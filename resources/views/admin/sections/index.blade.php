@extends('layouts.admin')

@section('page-title', 'Gerenciar Seções')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Seções</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nova Seção
        </a>
    </div>
</div>

<!-- Current Highlight -->
@if($currentHighlight)
<div class="alert alert-info mb-4">
    <i class="fas fa-star me-2"></i>
    <strong>Seção em Destaque:</strong> {{ $currentHighlight->name }}
    <small class="ms-2">(até {{ $currentHighlight->next_highlight_at->format('d/m/Y H:i') }})</small>
</div>
@endif

<!-- Statistics -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <h4>{{ $statistics['total_sections'] }}</h4>
                <small class="text-muted">Total de Seções</small>
            </div>
            <div class="col-md-3">
                <h4>{{ $statistics['active_sections'] }}</h4>
                <small class="text-muted">Seções Ativas</small>
            </div>
            <div class="col-md-3">
                <h4>{{ $statistics['rotation_type'] ?? 'N/A' }}</h4>
                <small class="text-muted">Tipo de Rotação</small>
            </div>
            <div class="col-md-3">
                <h4>{{ $statistics['interval_minutes'] ?? 'N/A' }}min</h4>
                <small class="text-muted">Intervalo</small>
            </div>
        </div>
    </div>
</div>

<!-- Sections Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Slug</th>
                        <th class="text-center">Prioridade</th>
                        <th class="text-center">Ordem</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Conteúdos</th>
                        <th class="text-center">Mídias</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr>
                            <td>
                                <strong>{{ $section->name }}</strong>
                                @if($section->isHighlighted())
                                    <span class="badge bg-warning ms-2">
                                        <i class="fas fa-star"></i> Destaque
                                    </span>
                                @endif
                            </td>
                            <td><code>{{ $section->slug }}</code></td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $section->priority }}</span>
                            </td>
                            <td class="text-center">{{ $section->display_order }}</td>
                            <td class="text-center">
                                @if($section->is_active)
                                    <span class="badge bg-success">Ativa</span>
                                @else
                                    <span class="badge bg-danger">Inativa</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $section->contents_count }}</td>
                            <td class="text-center">{{ $section->media_count }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.contents.index', $section) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Conteúdos">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                    <a href="{{ route('admin.media.index', $section) }}" 
                                       class="btn btn-outline-info" 
                                       title="Mídias">
                                        <i class="fas fa-images"></i>
                                    </a>
                                    <a href="{{ route('admin.sections.edit', $section) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.sections.highlight', $section) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-outline-secondary" 
                                                title="Destacar">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Nenhuma seção cadastrada ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
