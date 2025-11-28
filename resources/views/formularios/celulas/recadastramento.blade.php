@extends('layouts.app')

@section('title', 'Recadastramento de Células - Vale da Bênção')

@section('content')
<section class="form-celulas-section">
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-users"></i> Recadastramento de Células</h1>
            <p>Preencha os dados abaixo para recadastrar sua célula</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('formularios.celulas.recadastramento.store') }}" method="POST" class="form-recadastramento" id="formRecadastramento">
            @csrf

            <div class="form-group">
                <label for="nome_celula">
                    <i class="fas fa-home"></i> Nome da Célula *
                </label>
                <input type="text" 
                       name="nome_celula" 
                       id="nome_celula" 
                       class="form-control" 
                       value="{{ old('nome_celula') }}" 
                       placeholder="Ex: Célula Amor de Deus"
                       required>
            </div>

            <div class="form-group">
                <label for="lider">
                    <i class="fas fa-user-tie"></i> Nome do Líder *
                </label>
                <input type="text" 
                       name="lider" 
                       id="lider" 
                       class="form-control" 
                       value="{{ old('lider') }}" 
                       placeholder="Nome completo do líder"
                       required>
            </div>

            <div class="form-group">
                <label for="geracao_id">
                    <i class="fas fa-layer-group"></i> Geração *
                </label>
                <select name="geracao_id" id="geracao_id" class="form-control" required>
                    <option value="">Selecione a Geração</option>
                    @foreach($geracoes as $id => $nome)
                        <option value="{{ $id }}" {{ old('geracao_id') == $id ? 'selected' : '' }}>
                            {{ $nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="contato">
                    <i class="fas fa-phone"></i> Contato (WhatsApp) *
                </label>
                <input type="tel" 
                       name="contato" 
                       id="contato" 
                       class="form-control" 
                       value="{{ old('contato') }}" 
                       placeholder="(71) 99999-9999"
                       required>
            </div>

            <div class="form-section-title">
                <i class="fas fa-map-marker-alt"></i> Endereço da Célula
            </div>

            <div class="form-group">
                <label for="bairro">
                    <i class="fas fa-map"></i> Bairro *
                </label>
                <select name="bairro" id="bairro" class="form-control" required>
                    <option value="">Selecione o Bairro</option>
                    @foreach($bairros as $bairro)
                        <option value="{{ $bairro }}" {{ old('bairro') == $bairro ? 'selected' : '' }}>
                            {{ $bairro }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group col-8">
                    <label for="rua">
                        <i class="fas fa-road"></i> Rua
                    </label>
                    <input type="text" 
                           name="rua" 
                           id="rua" 
                           class="form-control" 
                           value="{{ old('rua') }}" 
                           placeholder="Nome da rua">
                </div>
                <div class="form-group col-4">
                    <label for="numero">
                        <i class="fas fa-hashtag"></i> Número
                    </label>
                    <input type="text" 
                           name="numero" 
                           id="numero" 
                           class="form-control" 
                           value="{{ old('numero') }}" 
                           placeholder="Nº">
                </div>
            </div>

            <div class="form-group">
                <label for="complemento">
                    <i class="fas fa-building"></i> Complemento
                </label>
                <input type="text" 
                       name="complemento" 
                       id="complemento" 
                       class="form-control" 
                       value="{{ old('complemento') }}" 
                       placeholder="Apto, Bloco, Casa, etc.">
            </div>

            <div class="form-section-title">
                <i class="fas fa-map-pin"></i> Localização no Mapa
            </div>

            <div class="form-group">
                <div id="map" class="form-map"></div>
                <p class="map-help">
                    <i class="fas fa-info-circle"></i> 
                    Clique no mapa para marcar a localização da célula ou use o botão abaixo para usar sua localização atual
                </p>
                <button type="button" class="btn btn-secondary" onclick="getMyLocation()">
                    <i class="fas fa-location-crosshairs"></i> Usar minha localização
                </button>
            </div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Enviar Recadastramento
                </button>
            </div>
        </form>
    </div>
</section>

<style>
.form-celulas-section {
    padding: 40px 20px;
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
}

.form-celulas-section .container {
    max-width: 600px;
    margin: 0 auto;
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
}

.form-header h1 {
    color: #D4AF37;
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.form-header h1 i {
    margin-right: 10px;
}

.form-header p {
    color: rgba(255,255,255,0.7);
    font-size: 1rem;
}

.form-recadastramento {
    background: rgba(255,255,255,0.05);
    border-radius: 16px;
    padding: 30px;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.form-section-title {
    color: #D4AF37;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 25px 0 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(212, 175, 55, 0.3);
}

.form-section-title i {
    margin-right: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    color: #fff;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-group label i {
    color: #D4AF37;
    margin-right: 8px;
    width: 20px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 8px;
    background: rgba(0,0,0,0.3);
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #D4AF37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
}

.form-control::placeholder {
    color: rgba(255,255,255,0.4);
}

select.form-control {
    cursor: pointer;
}

select.form-control option {
    background: #1a1a2e;
    color: #fff;
}

.form-row {
    display: flex;
    gap: 15px;
}

.form-row .col-8 {
    flex: 2;
}

.form-row .col-4 {
    flex: 1;
}

.form-map {
    width: 100%;
    height: 300px;
    border-radius: 8px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    margin-bottom: 10px;
}

.map-help {
    color: rgba(255,255,255,0.6);
    font-size: 0.85rem;
    margin-bottom: 10px;
}

.map-help i {
    color: #D4AF37;
    margin-right: 5px;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #D4AF37 0%, #b8972e 100%);
    color: #000;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
}

.btn-secondary {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.btn-secondary:hover {
    background: rgba(212, 175, 55, 0.2);
}

.btn-block {
    width: 100%;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.alert i {
    font-size: 1.2rem;
    margin-top: 2px;
}

.alert-success {
    background: rgba(40, 167, 69, 0.2);
    border: 1px solid rgba(40, 167, 69, 0.5);
    color: #28a745;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.2);
    border: 1px solid rgba(220, 53, 69, 0.5);
    color: #dc3545;
}

.alert-danger ul {
    margin: 0;
    padding-left: 20px;
}

@media (max-width: 768px) {
    .form-celulas-section {
        padding: 20px 15px;
    }
    
    .form-recadastramento {
        padding: 20px;
    }
    
    .form-header h1 {
        font-size: 1.5rem;
    }
    
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .form-map {
        height: 250px;
    }
}
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const defaultLat = -12.6976;
    const defaultLng = -38.3244;
    
    const map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    let marker = null;
    
    // Check if there's saved location
    const savedLat = document.getElementById('latitude').value;
    const savedLng = document.getElementById('longitude').value;
    
    if (savedLat && savedLng) {
        marker = L.marker([parseFloat(savedLat), parseFloat(savedLng)]).addTo(map);
        map.setView([parseFloat(savedLat), parseFloat(savedLng)], 15);
    }
    
    // Click on map to set marker
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
        
        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
    });
    
    // Phone mask
    const contatoInput = document.getElementById('contato');
    contatoInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        
        if (value.length > 6) {
            value = `(${value.slice(0,2)}) ${value.slice(2,7)}-${value.slice(7)}`;
        } else if (value.length > 2) {
            value = `(${value.slice(0,2)}) ${value.slice(2)}`;
        } else if (value.length > 0) {
            value = `(${value}`;
        }
        
        e.target.value = value;
    });
    
    // Get my location function
    window.getMyLocation = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
                
                map.setView([lat, lng], 16);
                
                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
            }, function(error) {
                alert('Erro ao obter localização: ' + error.message);
            });
        } else {
            alert('Geolocalização não suportada pelo navegador.');
        }
    };
});
</script>
@endsection
