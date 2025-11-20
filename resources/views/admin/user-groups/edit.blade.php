@extends('admin.layouts.app')

@section('title', 'Editar Grupo')
@section('page-title', 'Editar Grupo de Usuários')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editar Grupo: {{ $userGroup->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.user-groups.update', $userGroup) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do Grupo *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $userGroup->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $userGroup->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $userGroup->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Grupo Ativo
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Atualizar Grupo
                        </button>
                        <a href="{{ route('admin.user-groups.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estatísticas</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Usuários neste grupo</small>
                    <h3 class="mb-0">{{ $userGroup->users()->count() }}</h3>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Criado em</small>
                    <p class="mb-0">{{ $userGroup->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <small class="text-muted">Última atualização</small>
                    <p class="mb-0">{{ $userGroup->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
