@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total de Seções</h6>
                        <h2 class="mb-0">{{ $totalSections }}</h2>
                    </div>
                    <div class="text-primary" style="font-size: 3rem; opacity: 0.3;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total de Conteúdos</h6>
                        <h2 class="mb-0">{{ $totalContents }}</h2>
                    </div>
                    <div class="text-success" style="font-size: 3rem; opacity: 0.3;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total de Mídias</h6>
                        <h2 class="mb-0">{{ $totalMedia }}</h2>
                    </div>
                    <div class="text-warning" style="font-size: 3rem; opacity: 0.3;">
                        <i class="fas fa-images"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Seções Recentes</h5>
            </div>
            <div class="card-body">
                @if($recentSections->isEmpty())
                    <p class="text-muted">Nenhuma seção cadastrada ainda.</p>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($recentSections as $section)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $section->name }}</h6>
                                <small class="text-muted">{{ $section->created_at->diffForHumans() }}</small>
                            </div>
                            <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Conteúdos Recentes</h5>
            </div>
            <div class="card-body">
                @if($recentContents->isEmpty())
                    <p class="text-muted">Nenhum conteúdo cadastrado ainda.</p>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($recentContents as $content)
                        <div class="list-group-item">
                            <h6 class="mb-1">{{ $content->title }}</h6>
                            <small class="text-muted">
                                {{ $content->section->name }} • {{ $content->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
