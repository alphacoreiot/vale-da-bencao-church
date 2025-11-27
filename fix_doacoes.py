#!/usr/bin/env python3
# Script para adicionar seção de doações na home

file_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/home.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Seção de doações a ser adicionada antes da Localização
doacoes_section = '''<!-- Seção Doações -->
<section class="doacoes-section" style="padding: 80px 0; background: linear-gradient(180deg, #000 0%, #0a0a0a 100%);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="section-header" style="text-align: center; margin-bottom: 60px;">
            <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 8px 20px; border-radius: 20px; font-size: clamp(12px, 2vw, 14px); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Contribua</span>
            <h2 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Seja um Abençoador</h2>
            <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">Sua contribuição faz a diferença no Reino de Deus</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; max-width: 900px; margin: 0 auto;">
            <!-- Card Primícias -->
            <div class="doacao-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 20px; padding: 40px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 15px 40px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                <lord-icon
                    src="https://cdn.lordicon.com/yeallgsa.json"
                    trigger="loop"
                    delay="2000"
                    colors="primary:#D4AF37,secondary:#ffffff"
                    style="width:60px;height:60px;margin-bottom:15px">
                </lord-icon>
                <h3 style="color: #fff; font-size: clamp(1.3rem, 2.5vw, 1.6rem); font-weight: 700; margin-bottom: 15px;">Primícias</h3>
                <div style="background: #fff; padding: 15px; border-radius: 12px; margin-bottom: 15px; display: inline-block; cursor: pointer;" onclick="openQrModal('{{ asset('assets/primicias.jpeg') }}', 'QR Code Primícias')">
                    <img src="{{ asset('assets/primicias.jpeg') }}" alt="QR Code Primícias" style="width: 100%; max-width: 180px; height: auto; display: block; border-radius: 8px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <p style="color: rgba(255,255,255,0.7); font-size: clamp(0.85rem, 1.8vw, 0.95rem); line-height: 1.5; margin-bottom: 10px; font-style: italic;">
                    "Honra ao SENHOR com os teus bens e com as primícias de toda a tua renda"
                </p>
                <p style="color: #D4AF37; font-size: clamp(0.75rem, 1.5vw, 0.85rem); font-weight: 600; margin-bottom: 15px;">Provérbios 3:9</p>
                <div style="background: rgba(212, 175, 55, 0.15); padding: 12px; border-radius: 10px; border: 1px solid rgba(212, 175, 55, 0.3);">
                    <p style="color: rgba(255,255,255,0.6); font-size: clamp(0.7rem, 1.4vw, 0.8rem); margin-bottom: 5px;">Chave PIX:</p>
                    <p style="color: #fff; font-size: clamp(0.9rem, 1.8vw, 1rem); font-weight: 600;">(71) 99229-1423</p>
                </div>
            </div>

            <!-- Card Dízimos / Ofertas -->
            <div class="doacao-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 20px; padding: 40px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 15px 40px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                <lord-icon
                    src="https://cdn.lordicon.com/qhviklyi.json"
                    trigger="loop"
                    delay="2000"
                    colors="primary:#D4AF37,secondary:#ffffff"
                    style="width:60px;height:60px;margin-bottom:15px">
                </lord-icon>
                <h3 style="color: #fff; font-size: clamp(1.3rem, 2.5vw, 1.6rem); font-weight: 700; margin-bottom: 15px;">Dízimos / Ofertas</h3>
                <div style="background: #fff; padding: 15px; border-radius: 12px; margin-bottom: 15px; display: inline-block; cursor: pointer;" onclick="openQrModal('{{ asset('assets/dizimos.jpeg') }}', 'QR Code Dízimos e Ofertas')">
                    <img src="{{ asset('assets/dizimos.jpeg') }}" alt="QR Code Dízimos e Ofertas" style="width: 100%; max-width: 180px; height: auto; display: block; border-radius: 8px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <p style="color: rgba(255,255,255,0.7); font-size: clamp(0.85rem, 1.8vw, 0.95rem); line-height: 1.5; margin-bottom: 10px; font-style: italic;">
                    "Trazei todos os dízimos à casa do tesouro, para que haja mantimento na minha casa"
                </p>
                <p style="color: #D4AF37; font-size: clamp(0.75rem, 1.5vw, 0.85rem); font-weight: 600; margin-bottom: 15px;">Malaquias 3:10</p>
                <div style="background: rgba(212, 175, 55, 0.15); padding: 12px; border-radius: 10px; border: 1px solid rgba(212, 175, 55, 0.3);">
                    <p style="color: rgba(255,255,255,0.6); font-size: clamp(0.7rem, 1.4vw, 0.8rem); margin-bottom: 5px;">Chave PIX:</p>
                    <p style="color: #fff; font-size: clamp(0.9rem, 1.8vw, 1rem); font-weight: 600;">18.160.448/0001-38</p>
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

    document.getElementById('qrModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeQrModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQrModal();
        }
    });
</script>

'''

# Inserir antes da seção de Localização
old_text = '<!-- Seção Localização -->'
new_text = doacoes_section + '<!-- Seção Localização -->'

content = content.replace(old_text, new_text)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Seção de doações adicionada na home!")
