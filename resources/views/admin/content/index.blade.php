@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gerenciador de Conteúdo</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="content-tree">
                <!-- Home -->
                <div class="tree-item">
                    <div class="tree-node" data-bs-toggle="collapse" data-bs-target="#homeNode">
                        <i class="fas fa-chevron-down tree-icon"></i>
                        <i class="fas fa-home text-primary me-2"></i>
                        <span class="tree-label">Home</span>
                    </div>
                    <div class="collapse show" id="homeNode">
                        <div class="tree-children">
                            <!-- Eventos -->
                            <div class="tree-item">
                                <a href="{{ route('admin.content.eventos') }}" class="tree-node tree-link">
                                    <i class="fas fa-calendar-alt text-success me-2 ms-3"></i>
                                    <span class="tree-label">Eventos</span>
                                </a>
                            </div>
                            <!-- Devocional -->
                            <div class="tree-item">
                                <a href="{{ route('admin.content.devocional') }}" class="tree-node tree-link">
                                    <i class="fas fa-book-open text-info me-2 ms-3"></i>
                                    <span class="tree-label">Devocional</span>
                                </a>
                            </div>
                            <!-- Culto Online -->
                            <div class="tree-item">
                                <div class="tree-node">
                                    <i class="fas fa-video text-danger me-2 ms-3"></i>
                                    <span class="tree-label">Culto Online</span>
                                    <small class="ms-2 text-muted">(em breve)</small>
                                </div>
                            </div>
                            <!-- Localização -->
                            <div class="tree-item">
                                <div class="tree-node">
                                    <i class="fas fa-map-marker-alt text-warning me-2 ms-3"></i>
                                    <span class="tree-label">Localização</span>
                                    <small class="ms-2 text-muted">(em breve)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-tree {
    font-size: 16px;
}

.tree-item {
    margin: 5px 0;
}

.tree-node {
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.2s;
    display: flex;
    align-items: center;
}

.tree-node:hover {
    background-color: rgba(192, 192, 192, 0.1);
}

.tree-link {
    text-decoration: none;
    color: inherit;
}

.tree-icon {
    margin-right: 8px;
    transition: transform 0.2s;
    color: #A8A8A8;
}

.tree-node[aria-expanded="false"] .tree-icon {
    transform: rotate(-90deg);
}

.tree-label {
    font-weight: 500;
    color: #fff;
}

.tree-children {
    margin-left: 20px;
    padding-left: 15px;
    border-left: 2px solid rgba(192, 192, 192, 0.3);
}
</style>
@endsection
