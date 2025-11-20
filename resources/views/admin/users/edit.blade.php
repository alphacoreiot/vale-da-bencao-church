@extends('admin.layouts.app')

@section('title', 'Editar Usuário')
@section('page-title', 'Editar Usuário')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editar Usuário: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Deixe em branco para manter a senha atual</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="user_group_id" class="form-label">Grupo de Usuário</label>
                        <select class="form-select @error('user_group_id') is-invalid @enderror" 
                                id="user_group_id" name="user_group_id">
                            <option value="">Sem grupo</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('user_group_id', $user->user_group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_group_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Usuário Ativo
                        </label>
                        @if($user->id === auth()->id())
                            <small class="d-block text-muted">Você não pode desativar seu próprio usuário</small>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Atualizar Usuário
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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
                <h5 class="mb-0">Informações</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Cadastrado em</small>
                    <p class="mb-0">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Última atualização</small>
                    <p class="mb-0">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Último login</small>
                    <p class="mb-0">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
                @if($user->id === auth()->id())
                    <div class="alert alert-info mb-0" style="background: rgba(0, 123, 255, 0.1); border-color: #0d6efd;">
                        <i class="fas fa-info-circle me-2"></i>Este é o seu usuário
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
