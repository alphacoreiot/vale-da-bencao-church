@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Devocional Diário</h1>
            <p class="text-muted">Gerencie os devocionais que aparecem na homepage</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#devocionalModal" onclick="resetForm()">
            <i class="fas fa-plus me-2"></i>Novo Devocional
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            @if($devocionais->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhum devocional cadastrado. Crie um novo devocional.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Título</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th width="150">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devocionais as $dev)
                                <tr>
                                    <td>{{ $dev->data->format('d/m/Y') }}</td>
                                    <td>{{ $dev->titulo }}</td>
                                    <td>{{ Str::limit($dev->descricao, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $dev->ativo ? 'success' : 'secondary' }}">
                                            {{ $dev->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="editDevocional({{ $dev->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteDevocional({{ $dev->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $devocionais->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Criar/Editar -->
<div class="modal fade" id="devocionalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #000;" id="modalTitle">Novo Devocional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="devocionalForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="devocionalId" name="id">
                <input type="hidden" id="formMethod" value="POST">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data" class="form-label">Data</label>
                            <input type="date" class="form-control" id="data" name="data" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ativo" class="form-label">Status</label>
                            <select class="form-control" id="ativo" name="ativo">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="255">
                    </div>
                    
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required maxlength="255">
                    </div>
                    
                    <div class="mb-3">
                        <label for="texto" class="form-label">Texto Completo</label>
                        <small class="text-muted d-block mb-2">Inclua versículo, reflexão e oração</small>
                        <textarea class="form-control" id="texto" name="texto" rows="10" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem de Fundo</label>
                        <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                        <small class="text-muted">JPG, PNG (Máx: 10MB)</small>
                    </div>
                    
                    <div id="previewImage" class="mb-3" style="display: none;">
                        <label class="form-label">Pré-visualização</label>
                        <div id="previewImageContent"></div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.table {
    color: #fff;
}

.table thead th {
    border-color: rgba(192, 192, 192, 0.3);
    color: #C0C0C0;
    font-weight: 600;
}

.table tbody td {
    border-color: rgba(192, 192, 192, 0.2);
}

.table-hover tbody tr:hover {
    background-color: rgba(192, 192, 192, 0.1);
}

#previewImageContent img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 5px;
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
</style>

<script>
// Dados dos devocionais
const devocionaisData = @json($devocionais->items());

// Preview da imagem
document.getElementById('imagem').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const preview = document.getElementById('previewImage');
    const previewContent = document.getElementById('previewImageContent');
    
    preview.style.display = 'block';
    
    const reader = new FileReader();
    reader.onload = function(e) {
        previewContent.innerHTML = `<img src="${e.target.result}">`;
    };
    reader.readAsDataURL(file);
});

// Reset form
function resetForm() {
    document.getElementById('devocionalForm').reset();
    document.getElementById('devocionalId').value = '';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('modalTitle').textContent = 'Novo Devocional';
    document.getElementById('previewImage').style.display = 'none';
    document.getElementById('data').value = new Date().toISOString().split('T')[0];
}

// Edit devocional
function editDevocional(id) {
    const devocional = devocionaisData.find(d => d.id === id);
    if (!devocional) return;
    
    document.getElementById('devocionalId').value = devocional.id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('modalTitle').textContent = 'Editar Devocional';
    document.getElementById('titulo').value = devocional.titulo;
    document.getElementById('descricao').value = devocional.descricao;
    document.getElementById('texto').value = devocional.texto;
    document.getElementById('data').value = devocional.data;
    document.getElementById('ativo').value = devocional.ativo ? '1' : '0';
    
    if (devocional.imagem) {
        document.getElementById('previewImage').style.display = 'block';
        document.getElementById('previewImageContent').innerHTML = 
            `<img src="{{ asset('storage/') }}/${devocional.imagem}">`;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('devocionalModal'));
    modal.show();
}

// Submit form
document.getElementById('devocionalForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const method = document.getElementById('formMethod').value;
    const id = document.getElementById('devocionalId').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Salvando...';
    
    let url = '{{ route("admin.content.devocional.store") }}';
    if (method === 'PUT' && id) {
        url = `{{ url('admin/content/devocional') }}/${id}`;
        formData.append('_method', 'PUT');
    }
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('devocionalModal'));
            modal.hide();
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Erro ao salvar devocional', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showToast('Erro ao salvar devocional. Tente novamente.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Salvar';
    }
});

// Delete devocional
async function deleteDevocional(id) {
    if (!confirm('Tem certeza que deseja remover este devocional?')) return;
    
    try {
        const response = await fetch(`{{ url('admin/content/devocional') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showToast(data.message || 'Erro ao remover devocional', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showToast('Erro ao remover devocional. Tente novamente.', 'error');
    }
}
</script>
@endsection
