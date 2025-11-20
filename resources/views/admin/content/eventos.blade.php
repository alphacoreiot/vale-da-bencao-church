@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Eventos - Vale News</h1>
            <p class="text-muted">Gerencie as imagens e vídeos do carrossel da homepage</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-plus me-2"></i>Adicionar Mídia
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            @if($media->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhuma mídia cadastrada. Adicione imagens ou vídeos para o carrossel.</p>
                </div>
            @else
                <div class="row g-4" id="mediaGrid">
                    @foreach($media as $item)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 media-item" data-id="{{ $item->id }}">
                            <div class="card media-card h-100">
                                <div class="drag-handle">
                                    <i class="fas fa-grip-vertical"></i>
                                </div>
                                <div class="media-preview">
                                    @if($item->type === 'image')
                                        <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->alt_text }}" class="img-fluid">
                                    @else
                                        <video class="w-100" controls>
                                            <source src="{{ asset('storage/' . $item->path) }}" type="{{ $item->mime_type }}">
                                        </video>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="badge bg-{{ $item->type === 'image' ? 'primary' : 'success' }}">
                                            <i class="fas fa-{{ $item->type === 'image' ? 'image' : 'video' }} me-1"></i>
                                            {{ $item->type === 'image' ? 'Imagem' : 'Vídeo' }}
                                        </span>
                                    </div>
                                    <p class="small mb-2 flex-grow-1">{{ $item->alt_text ?? 'Sem descrição' }}</p>
                                    <p class="small text-muted mb-3">{{ number_format($item->size / 1024 / 1024, 2) }} MB</p>
                                    <button class="btn btn-sm btn-danger w-100" onclick="deleteMedia({{ $item->id }})">
                                        <i class="fas fa-trash me-1"></i>Remover
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $media->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #000;">Adicionar Mídia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Arquivo</label>
                        <input type="file" class="form-control" id="file" name="file" accept="image/*,video/*" required>
                        <small class="text-muted">Imagens: JPG, PNG, GIF | Vídeos: MP4, MOV, AVI (Máx: 50MB)</small>
                    </div>
                    <div class="mb-3">
                        <label for="alt_text" class="form-label">Descrição (opcional)</label>
                        <input type="text" class="form-control" id="alt_text" name="alt_text" placeholder="Descrição da mídia">
                    </div>
                    <div id="preview" class="mb-3" style="display: none;">
                        <label class="form-label">Pré-visualização</label>
                        <div id="previewContent"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.media-item {
    position: relative;
}

.media-card {
    border: 1px solid rgba(192, 192, 192, 0.3);
    transition: all 0.3s ease;
    background: #1a1a1a;
    overflow: hidden;
    position: relative;
}

.media-card.sortable-ghost {
    opacity: 0.4;
}

.media-card.sortable-drag {
    opacity: 0.8;
    transform: rotate(5deg);
}

.drag-handle {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
    background: rgba(0, 0, 0, 0.7);
    color: #C0C0C0;
    width: 32px;
    height: 32px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: move;
    transition: all 0.3s ease;
    border: 1px solid rgba(192, 192, 192, 0.3);
}

.drag-handle:hover {
    background: rgba(192, 192, 192, 0.9);
    color: #000;
    transform: scale(1.1);
}

.media-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(192, 192, 192, 0.2);
    border-color: rgba(192, 192, 192, 0.6);
}

.media-preview {
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.media-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-card .card-body {
    background: #1a1a1a;
    border-top: 1px solid rgba(192, 192, 192, 0.2);
    min-height: 160px;
}

.media-card .badge {
    font-size: 0.75rem;
    font-weight: 500;
}

#previewContent img,
#previewContent video {
    max-width: 100%;
    max-height: 300px;
    border-radius: 5px;
    background: #000;
}

/* Pagination styling */
.pagination {
    margin: 0;
}

.pagination .page-link {
    background-color: #1a1a1a;
    border-color: rgba(192, 192, 192, 0.3);
    color: #C0C0C0;
}

.pagination .page-link:hover {
    background-color: #2a2a2a;
    border-color: rgba(192, 192, 192, 0.5);
    color: #fff;
}

.pagination .page-item.active .page-link {
    background-color: #C0C0C0;
    border-color: #C0C0C0;
    color: #000;
}

.pagination .page-item.disabled .page-link {
    background-color: #0a0a0a;
    border-color: rgba(192, 192, 192, 0.1);
    color: #666;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Initialize Sortable for drag and drop
document.addEventListener('DOMContentLoaded', function() {
    const mediaGrid = document.getElementById('mediaGrid');
    
    if (mediaGrid) {
        const sortable = Sortable.create(mediaGrid, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Get all media items in new order
                const items = [];
                const mediaItems = mediaGrid.querySelectorAll('.media-item');
                
                mediaItems.forEach((item, index) => {
                    items.push({
                        id: parseInt(item.dataset.id),
                        order: index
                    });
                });
                
                // Send to server
                updateOrder(items);
            }
        });
    }
});

// Update order via AJAX
async function updateOrder(items) {
    try {
        const response = await fetch('{{ route("admin.content.eventos.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ items })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message || 'Ordem atualizada!', 'success');
        } else {
            showToast(data.message || 'Erro ao atualizar ordem', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showToast('Erro ao atualizar ordem', 'error');
    }
}

// Preview do arquivo antes de enviar
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const preview = document.getElementById('preview');
    const previewContent = document.getElementById('previewContent');
    
    preview.style.display = 'block';
    
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContent.innerHTML = `<img src="${e.target.result}" style="max-height: 200px;">`;
        };
        reader.readAsDataURL(file);
    } else if (file.type.startsWith('video/')) {
        const url = URL.createObjectURL(file);
        previewContent.innerHTML = `<video src="${url}" controls style="max-height: 200px; width: 100%;"></video>`;
    }
});

// Upload form
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enviando...';
    
    try {
        const response = await fetch('{{ route("admin.content.eventos.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Mostrar toast de sucesso
            showToast(data.message || 'Mídia adicionada com sucesso!', 'success');
            
            // Fechar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
            modal.hide();
            
            // Reset form
            this.reset();
            document.getElementById('preview').style.display = 'none';
            
            // Recarregar página após um delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Erro ao enviar mídia', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showToast('Erro ao enviar mídia. Tente novamente.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-upload me-1"></i>Enviar';
    }
});

// Delete media
async function deleteMedia(mediaId) {
    if (!confirm('Tem certeza que deseja remover esta mídia?')) return;
    
    try {
        const response = await fetch(`{{ url('admin/content/eventos') }}/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Mostrar toast de sucesso
            showToast(data.message || 'Mídia removida com sucesso!', 'success');
            
            // Recarregar página após um delay
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showToast(data.message || 'Erro ao remover mídia', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showToast('Erro ao remover mídia. Tente novamente.', 'error');
    }
}
</script>
@endsection
