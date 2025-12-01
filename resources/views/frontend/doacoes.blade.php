@extends('layouts.app')

@section('title', 'Doações - Vale da Benção Church')

@section('content')
<!-- Conteúdo Principal -->
<section class="section-content-area" style="padding: 120px 0 80px 0; background: #000;">
    <div class="container">
        <!-- Título da Seção -->
        <div class="section-header" style="text-align: center; margin-bottom: 60px; padding: 0 20px;">
            <div style="margin-bottom: 15px;">
                <img src="{{ asset('assets/doacoes.gif') }}" alt="Doações" style="width: 120px; height: 120px; object-fit: contain;">
            </div>
            <h1 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Doações</h1>
            <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">Seja um abençoador dessa obra. Sua contribuição faz a diferença no Reino de Deus.</p>
        </div>

        <!-- Cards de Doação -->
        <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
                <!-- Card Primícias -->
                <div class="doacao-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 20px; padding: 40px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 15px 40px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                    <h3 style="color: #fff; font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 700; margin-bottom: 20px;">Primícias</h3>
                    <div style="background: #fff; padding: 20px; border-radius: 15px; margin-bottom: 20px; display: inline-block; cursor: pointer;" onclick="openQrModal('{{ asset('assets/primicias.jpeg') }}', 'QR Code Primícias')">
                        <img src="{{ asset('assets/primicias.jpeg') }}" alt="QR Code Primícias" style="width: 100%; max-width: 250px; height: auto; display: block; border-radius: 10px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <p style="color: rgba(255,255,255,0.8); font-size: clamp(0.9rem, 2vw, 1rem); line-height: 1.6; margin-bottom: 15px;">
                        "Honra ao SENHOR com os teus bens e com as primícias de toda a tua renda"
                    </p>
                    <p style="color: #D4AF37; font-size: clamp(0.8rem, 1.5vw, 0.9rem); font-weight: 600; margin-bottom: 15px;">Provérbios 3:9</p>
                    <div style="background: rgba(212, 175, 55, 0.15); padding: 15px; border-radius: 10px; border: 1px solid rgba(212, 175, 55, 0.3);">
                        <p style="color: rgba(255,255,255,0.6); font-size: clamp(0.75rem, 1.5vw, 0.85rem); margin-bottom: 8px;">Chave PIX:</p>
                        <p style="color: #fff; font-size: clamp(1rem, 2vw, 1.1rem); font-weight: 600; letter-spacing: 1px;">(71) 99229-1423</p>
                    </div>
                </div>

                <!-- Card Dízimos / Ofertas -->
                <div class="doacao-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 20px; padding: 40px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 15px 40px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                    <h3 style="color: #fff; font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 700; margin-bottom: 30px;">Dízimos / Ofertas</h3>
                    <div style="background: #fff; padding: 20px; border-radius: 15px; margin-bottom: 20px; display: inline-block; cursor: pointer;" onclick="openQrModal('{{ asset('assets/dizimos.jpeg') }}', 'QR Code Dízimos e Ofertas')">
                        <img src="{{ asset('assets/dizimos.jpeg') }}" alt="QR Code Dízimos e Ofertas" style="width: 100%; max-width: 250px; height: auto; display: block; border-radius: 10px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <p style="color: rgba(255,255,255,0.8); font-size: clamp(0.9rem, 2vw, 1rem); line-height: 1.6; margin-bottom: 15px;">
                        "Trazei todos os dízimos à casa do tesouro, para que haja mantimento na minha casa"
                    </p>
                    <p style="color: #D4AF37; font-size: clamp(0.8rem, 1.5vw, 0.9rem); font-weight: 600; margin-bottom: 15px;">Malaquias 3:10</p>
                    <div style="background: rgba(212, 175, 55, 0.15); padding: 15px; border-radius: 10px; border: 1px solid rgba(212, 175, 55, 0.3);">
                        <p style="color: rgba(255,255,255,0.6); font-size: clamp(0.75rem, 1.5vw, 0.85rem); margin-bottom: 8px;">Chave PIX:</p>
                        <p style="color: #fff; font-size: clamp(1rem, 2vw, 1.1rem); font-weight: 600; letter-spacing: 1px;">18.160.448/0001-38</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Zoom do QR Code -->
<div id="qrModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
    <div style="position: relative; max-width: 90%; max-height: 90%; text-align: center;">
        <button onclick="closeQrModal()" style="position: absolute; top: -50px; right: 0; background: rgba(212, 175, 55, 0.2); color: #fff; border: 2px solid #D4AF37; width: 50px; height: 50px; border-radius: 50%; font-size: 24px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='#D4AF37'; this.style.color='#000';" onmouseout="this.style.background='rgba(212, 175, 55, 0.2)'; this.style.color='#fff';">×</button>
        <img id="qrModalImage" style="max-width: 90%; max-height: 90vh; border-radius: 15px; box-shadow: 0 20px 80px rgba(212, 175, 55, 0.3);">
        <p id="qrModalTitle" style="color: #fff; font-size: clamp(1.2rem, 2.5vw, 1.5rem); margin-top: 20px; font-weight: 600;"></p>
    </div>
</div>

@push('scripts')
<script>
    function openQrModal(imageSrc, title) {
        const modal = document.getElementById('qrModal');
        const img = document.getElementById('qrModalImage');
        const titleEl = document.getElementById('qrModalTitle');
        
        img.src = imageSrc;
        titleEl.textContent = title;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Fechar modal ao clicar fora da imagem
    document.getElementById('qrModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeQrModal();
        }
    });

    // Fechar modal com tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQrModal();
        }
    });
</script>
@endpush
@endsection
