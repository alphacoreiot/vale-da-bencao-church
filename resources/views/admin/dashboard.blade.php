@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4">Dashboard</h2>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Total de Seções</h6>
                <h2 class="mb-0">{{ $totalSections }}</h2>
                <small>{{ $activeSections }} ativas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Conteúdos</h6>
                <h2 class="mb-0">{{ $totalContents }}</h2>
                <small>{{ $publishedContents }} publicados</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6 class="card-title">Mídias</h6>
                <h2 class="mb-0">{{ $totalMedia }}</h2>
                <small>Total de arquivos</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background-color: #9C0505;">
            <div class="card-body">
                <h6 class="card-title">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.sections.create') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-plus"></i> Nova Seção
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <strong>Seções Recentes</strong>
            </div>
            <div class="card-body">
                @if($recentSections->isEmpty())
                    <p class="text-muted mb-0">Nenhuma seção criada ainda.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($recentSections as $section)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <strong>{{ $section->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $section->created_at->diffForHumans() }}</small>
                                </span>
                                <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <strong>Conteúdos Recentes</strong>
            </div>
            <div class="card-body">
                @if($recentContents->isEmpty())
                    <p class="text-muted mb-0">Nenhum conteúdo criado ainda.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($recentContents as $content)
                            <li class="list-group-item">
                                <strong>{{ $content->title }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $content->section->name }} • {{ $content->created_at->diffForHumans() }}
                                </small>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
