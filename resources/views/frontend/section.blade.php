@extends('layouts.app')

@section('title', $section->name . ' - Igreja Vale da Bênção')

@section('content')
<!-- Conteúdo Principal -->
<section class="section-content-area" style="padding: 120px 0 80px 0; background: #000;">
    <div class="container">
        <!-- Título da Seção -->
        <div class="section-header" style="text-align: center; margin-bottom: 60px; padding: 0 20px;">
            <div style="margin-bottom: 15px;">
                @switch($section->slug)
                    @case('eventos') <lord-icon src="https://cdn.lordicon.com/abfverha.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:80px;height:80px"></lord-icon> @break
                    @case('ministerios') <lord-icon src="https://cdn.lordicon.com/jjoolpwc.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:80px;height:80px"></lord-icon> @break
                    @case('estudos') 📖 @break
                    @case('galeria') <lord-icon src="https://cdn.lordicon.com/vixtkkbk.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:80px;height:80px"></lord-icon> @break
                    @case('testemunhos') ⭐ @break
                    @case('contato') <lord-icon src="https://cdn.lordicon.com/srsgifqc.json" trigger="loop" delay="1500" colors="primary:#d4af37,secondary:#ffffff" style="width:80px;height:80px"></lord-icon> @break
                    @case('boas-vindas') 👋 @break
                    @default 📄
                @endswitch
            </div>
            <h1 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">{{ $section->name }}</h1>
            @if($section->description)
                <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">{{ $section->description }}</p>
            @endif
        </div>

        @if($section->slug === 'eventos')
            <!-- Imagem dos Eventos do Mês -->
            <div class="eventos-section" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <div style="position: relative; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 30px rgba(212, 175, 55, 0.3);">
                    <img src="{{ asset('assets/imagem 0.jpeg') }}" 
                         alt="Eventos do Mês" 
                         style="width: 100%; height: auto; display: block;">
                    
                    <!-- Overlay com destaque -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.7) 100%); pointer-events: none;"></div>
                </div>
            </div>
        @elseif($section->slug === 'ministerios')
            <!-- Grid de Ministérios -->
            <div class="ministerios-grid" style="max-width: 1200px; margin: 0 auto 60px auto; padding: 0 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                    <!-- Professores -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">📚</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Professores</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Ensinando a Palavra</p>
                    </div>

                    <!-- Intercessão -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🙏</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Intercessão</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Orando pela Igreja</p>
                    </div>

                    <!-- Obreiros -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">✝️</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Obreiros</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Servindo com Dedicação</p>
                    </div>

                    <!-- Consolidação -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🤝</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Consolidação</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Cuidando das Almas</p>
                    </div>

                    <!-- Sonorização -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🔊</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Sonorização</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Excelência no Som</p>
                    </div>

                    <!-- Staff Apóstolo -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">👔</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Staff Apóstolo</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Apoio à Liderança</p>
                    </div>

                    <!-- Produção -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🎬</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Produção</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Teatro, Música e Eventos</p>
                    </div>

                    <!-- Introdução -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🚪</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Introdução</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Recepcionando com Amor</p>
                    </div>

                    <!-- Mídia -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">📱</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Mídia</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Comunicação Digital</p>
                    </div>

                    <!-- Multimídia -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🎥</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Multimídia</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Transmissão ao Vivo</p>
                    </div>

                    <!-- Libras -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">👐</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Libras</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Inclusão e Acessibilidade</p>
                    </div>

                    <!-- Músicos -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🎸</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Músicos</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Adoração e Louvor</p>
                    </div>

                    <!-- Hadash -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">💃</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Hadash</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Ministério de Dança</p>
                    </div>

                    <!-- Limpeza -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🧹</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Limpeza</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Mantendo a Casa de Deus</p>
                    </div>

                    <!-- Casais -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">💑</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Casais</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Fortalecendo Matrimônios</p>
                    </div>

                    <!-- Batismo -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">💧</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Batismo</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Celebrando a Nova Vida</p>
                    </div>

                    <!-- Mulheres -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">👩</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Mulheres</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Empoderadas em Cristo</p>
                    </div>

                    <!-- Homens -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">👨</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Homens</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Guerreiros de Fé</p>
                    </div>

                    <!-- Teatro -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🎭</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Teatro</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Arte que Transforma</p>
                    </div>

                    <!-- Jump -->
                    <div class="ministerio-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 15px; padding: 25px; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#D4AF37'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.boxShadow='none';">
                        <div style="font-size: 3rem; margin-bottom: 15px;">🧑‍🤝‍🧑</div>
                        <h3 style="color: #D4AF37; font-size: 1.3rem; font-weight: 700; margin-bottom: 10px;">Jump</h3>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Ministério de Adolescentes</p>
                    </div>
                </div>
            </div>

            <!-- Vídeo dos Ministérios -->
            <div class="ministerios-section" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <div class="section-header" style="text-align: center; margin-bottom: 30px;">
                    <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 8px 20px; border-radius: 20px; font-size: clamp(12px, 2vw, 14px); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Vídeo Institucional</span>
                    <h2 style="font-size: clamp(1.5rem, 3vw, 2rem); color: #fff; font-weight: 700; margin-bottom: 10px;">Conheça Nossos Ministérios</h2>
                </div>
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 15px; box-shadow: 0 8px 30px rgba(212, 175, 55, 0.3);">
                    <iframe 
                        src="https://www.youtube.com/embed/fhB35BCk--M?autoplay=1&mute=1" 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        @elseif($section->slug === 'galeria')
            <!-- Feed do Instagram para Galeria -->
            <div class="instagram-feed-section">
                <div class="section-header" style="text-align: center; margin-bottom: 60px; padding: 0 20px;">
                    <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 8px 20px; border-radius: 20px; font-size: clamp(12px, 2vw, 14px); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Instagram</span>
                    <h2 class="section-main-title" style="font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Acompanhe Nossos Momentos</h2>
                    <p class="section-description" style="color: rgba(255,255,255,0.7); font-size: clamp(1rem, 2vw, 1.1rem); max-width: 700px; margin: 0 auto;">Veja as fotos e vídeos dos nossos cultos e eventos</p>
                </div>
                
                <!-- Embed do Instagram -->
                <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; overflow-x: hidden;">
                    <!-- Elfsight Instagram Feed | Untitled Instagram Feed -->
                    <script src="https://elfsightcdn.com/platform.js" async></script>
                    <div class="elfsight-app-f416f43e-684e-444e-9add-9ebdc7e53f18" data-elfsight-app-lazy style="max-width: 100%; width: 100%;"></div>
                </div>
            </div>
        @elseif($section->slug === 'contato')
            <!-- Página de Contato -->
            <div class="contato-section" style="max-width: 1200px; margin: 0 auto; min-height: 60vh; display: flex; align-items: center; justify-content: center;">
                <div style="width: 100%;">
                
                <!-- Versículo sobre Localização Celestial -->
                <div style="text-align: center; margin-bottom: 60px; padding: 0 20px;">
                    <div style="max-width: 800px; margin: 0 auto; padding: 40px 30px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.05) 100%); border-left: 4px solid #D4AF37; border-radius: 10px;">
                        <p style="font-size: clamp(1.1rem, 2.5vw, 1.3rem); color: #fff; font-weight: 300; line-height: 1.8; font-style: italic; margin-bottom: 15px;">
                            "Mas a nossa cidade está nos céus, de onde também esperamos o Salvador, o Senhor Jesus Cristo."
                        </p>
                        <p style="font-size: clamp(0.9rem, 2vw, 1rem); color: #D4AF37; font-weight: 600; letter-spacing: 1px;">
                            Filipenses 3:20
                        </p>
                    </div>
                </div>
                
                <!-- Redes Sociais -->
                <div class="section-header" style="text-align: center; margin-bottom: 40px; padding: 0 20px;">
                    <span class="section-label" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 8px 20px; border-radius: 20px; font-size: clamp(12px, 2vw, 14px); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Redes Sociais</span>
                    <h3 style="font-size: clamp(1.5rem, 3vw, 2rem); color: #fff; font-weight: 700; margin-bottom: 15px; line-height: 1.2;">Conecte-se Conosco</h3>
                </div>
                
                <div style="display: flex; justify-content: center; gap: clamp(15px, 2vw, 20px); flex-wrap: wrap; margin-bottom: 60px; padding: 0 20px;">
                    <!-- Instagram Button -->
                    <a href="https://www.instagram.com/igvaledabencao/" target="_blank" class="social-button" style="display: flex; align-items: center; gap: 12px; padding: 15px 25px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease; cursor: pointer; backdrop-filter: blur(10px);" onmouseover="this.style.background='linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)'; this.style.borderColor='#D4AF37'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;" viewBox="0 0 24 24" fill="#fff">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="color: #fff; font-weight: 700; font-size: clamp(0.95rem, 2vw, 1.05rem); line-height: 1.2;">Siga-nos no Instagram</span>
                            <span style="color: rgba(255, 255, 255, 0.8); font-size: clamp(0.75rem, 1.5vw, 0.85rem); font-weight: 400;">Clique para acessar @igvaledabencao</span>
                        </div>
                    </a>
                    
                    <!-- Facebook Button -->
                    <a href="https://www.facebook.com/igvaledabencao" target="_blank" class="social-button" style="display: flex; align-items: center; gap: 12px; padding: 15px 25px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease; cursor: pointer; backdrop-filter: blur(10px);" onmouseover="this.style.background='linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)'; this.style.borderColor='#D4AF37'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;" viewBox="0 0 24 24" fill="#fff">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="color: #fff; font-weight: 700; font-size: clamp(0.95rem, 2vw, 1.05rem); line-height: 1.2;">Curta nossa Página</span>
                            <span style="color: rgba(255, 255, 255, 0.8); font-size: clamp(0.75rem, 1.5vw, 0.85rem); font-weight: 400;">Clique para ver novidades no Facebook</span>
                        </div>
                    </a>
                    
                    <!-- YouTube Button -->
                    <a href="https://www.youtube.com/@valedabencaochurch" target="_blank" class="social-button" style="display: flex; align-items: center; gap: 12px; padding: 15px 25px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease; cursor: pointer; backdrop-filter: blur(10px);" onmouseover="this.style.background='linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)'; this.style.borderColor='#D4AF37'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;" viewBox="0 0 24 24" fill="#fff">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="color: #fff; font-weight: 700; font-size: clamp(0.95rem, 2vw, 1.05rem); line-height: 1.2;">Inscreva-se no YouTube</span>
                            <span style="color: rgba(255, 255, 255, 0.8); font-size: clamp(0.75rem, 1.5vw, 0.85rem); font-weight: 400;">Clique para assistir nossos cultos</span>
                        </div>
                    </a>
                    
                    <!-- Localização Button -->
                    <a href="https://www.google.com/maps/search/?api=1&query=Rua+Dos+Buritis+07+Parque+Das+Palmeiras+Camaçari+BA" target="_blank" class="social-button" style="display: flex; align-items: center; gap: 12px; padding: 15px 25px; background: rgba(255, 255, 255, 0.1); border: 2px solid rgba(212, 175, 55, 0.3); border-radius: 12px; text-decoration: none; transition: all 0.3s ease; cursor: pointer; backdrop-filter: blur(10px);" onmouseover="this.style.background='linear-gradient(135deg, #D4AF37 0%, #B8941F 100%)'; this.style.borderColor='#D4AF37'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.borderColor='rgba(212, 175, 55, 0.3)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;" viewBox="0 0 24 24" fill="#fff">
                                <path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="color: #fff; font-weight: 700; font-size: clamp(0.95rem, 2vw, 1.05rem); line-height: 1.2;">Venha nos Visitar</span>
                            <span style="color: rgba(255, 255, 255, 0.8); font-size: clamp(0.75rem, 1.5vw, 0.85rem); font-weight: 400;">Clique para ver nossa localização</span>
                        </div>
                    </a>
                    </a>
                </div>
                </div>
            </div>
        @elseif($section->publishedContents->isEmpty())
            <div class="empty-content" style="text-align: center; padding: 80px 20px; background: #1a1a1a; border-radius: 15px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 20px rgba(255,255,255,0.05);">
                <div class="empty-icon" style="font-size: 5rem; margin-bottom: 20px;">📝</div>
                <h3 style="font-size: 2rem; color: #fff; font-weight: 700; margin-bottom: 15px;">Conteúdo em breve</h3>
                <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem; margin-bottom: 30px;">Estamos preparando conteúdos especiais para esta seção.</p>
                <a href="{{ route('home') }}" class="btn-primary" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">← Voltar ao Início</a>
            </div>
        @else
            <div class="content-grid-full" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
                @foreach($section->publishedContents as $content)
                    <article class="content-card" style="background: #1a1a1a; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(255,255,255,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(212, 175, 55, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(255,255,255,0.05)';">
                        <h3 class="content-card-title" style="font-size: 1.5rem; color: #fff; font-weight: 700; margin-bottom: 15px;">{{ $content->title }}</h3>
                        
                        <div class="content-meta" style="display: flex; gap: 20px; margin-bottom: 15px; color: rgba(255,255,255,0.6); font-size: 14px;">
                            <span>📅 {{ $content->published_at->format('d/m/Y') }}</span>
                            @if($content->author)
                                <span>👤 {{ $content->author }}</span>
                            @endif
                        </div>
                        
                        <div class="content-excerpt" style="color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 20px;">
                            {!! Str::limit(strip_tags($content->content), 300) !!}
                        </div>
                        
                        @if($content->media->isNotEmpty())
                            <div class="content-gallery">
                                @foreach($content->media->take(3) as $media)
                                    @if($media->isImage())
                                        <img src="{{ $media->getUrl() }}" 
                                             alt="{{ $media->alt_text }}" 
                                             class="gallery-thumb">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        
                        <a href="{{ route('section.content', [$section->slug, $content->id]) }}" 
                           class="btn-read-more" style="display: inline-block; background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: #000; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 15px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            Ler Mais →
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
