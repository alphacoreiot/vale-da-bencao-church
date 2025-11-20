@extends('admin.layouts.app')

@section('title', 'Grupos de Usuários')
@section('page-title', 'Grupos de Usuários')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Gerenciar Grupos</h2>
    <a href="{{ route('admin.user-groups.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Novo Grupo
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($groups->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-users-cog fa-3x text-muted mb-3"></i>
                <p class="text-muted">Nenhum grupo cadastrado ainda.</p>
                <a href="{{ route('admin.user-groups.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Criar Primeiro Grupo
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Usuários</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groups as $group)
                        <tr>
                            <td><strong>{{ $group->name }}</strong></td>
                            <td>{{ Str::limit($group->description, 50) }}</td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="fas fa-users me-1"></i>{{ $group->users_count }}
                                </span>
                            </td>
                            <td>
                                @if($group->is_active)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-secondary">Inativo</span>
                                @endif
                            </td>
                            <td>{{ $group->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.user-groups.edit', $group) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.user-groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este grupo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" {{ $group->users_count > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
