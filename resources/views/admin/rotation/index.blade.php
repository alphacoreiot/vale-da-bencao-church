@extends('admin.layouts.app')

@section('title', 'Rotação de Destaques')
@section('page-title', 'Rotação de Destaques')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Total de Seções</h6>
                        <h3 class="mb-0">{{ $statistics['total_sections'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-layer-group fa-2x" style="color: #C0C0C0;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Seções Ativas</h6>
                        <h3 class="mb-0">{{ $statistics['active_sections'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Próximo Destaque</h6>
                        <h3 class="mb-0">{{ $statistics['next_highlight'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Última Troca</h6>
                        <h3 class="mb-0">{{ $statistics['last_rotation'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-history fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Configuração da Rotação</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.rotation.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="rotation_type" class="form-label">Tipo de Rotação</label>
                        <select class="form-select @error('rotation_type') is-invalid @enderror" 
                                id="rotation_type" name="rotation_type" required>
                            @foreach($rotationTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('rotation_type', $config->rotation_type ?? '') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('rotation_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="interval_minutes" class="form-label">Intervalo (em minutos)</label>
                        <input type="number" class="form-control @error('interval_minutes') is-invalid @enderror" 
                               id="interval_minutes" name="interval_minutes" 
                               value="{{ old('interval_minutes', $config->interval_minutes ?? 60) }}" 
                               min="1" required>
                        @error('interval_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tempo entre cada troca de destaque</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                               value="1" {{ old('is_active', $config->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Rotação Ativa
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Salvar Configuração
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Destaque Atual</h5>
            </div>
            <div class="card-body">
                @if($currentHighlight)
                    <div class="text-center mb-3">
                        <div class="bg-dark rounded p-3 mb-3" style="border: 2px solid #C0C0C0;">
                            <i class="fas fa-star fa-3x" style="color: #C0C0C0;"></i>
                        </div>
                        <h5>{{ $currentHighlight->name }}</h5>
                        <p class="text-muted mb-2">{{ $currentHighlight->description }}</p>
                        
                        @if($currentHighlight->last_highlighted_at)
                            <small class="text-muted d-block">
                                <i class="fas fa-clock me-1"></i>
                                Destacado: {{ $currentHighlight->last_highlighted_at->diffForHumans() }}
                            </small>
                        @endif
                        
                        @if($currentHighlight->next_highlight_at)
                            <small class="text-muted d-block">
                                <i class="fas fa-forward me-1"></i>
                                Próxima: {{ $currentHighlight->next_highlight_at->diffForHumans() }}
                            </small>
                        @endif
                    </div>

                    <a href="{{ route('admin.sections.edit', $currentHighlight) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-edit me-2"></i>Editar Seção
                    </a>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Nenhuma seção destacada no momento</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações</h5>
            </div>
            <div class="card-body">
                <p class="text-white">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    O sistema de rotação alterna automaticamente qual seção aparece em destaque na página inicial.
                </p>
                <p class="text-white">
                    <i class="fas fa-sync text-success me-2"></i>
                    Configure o tipo de rotação e o intervalo entre as trocas.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
