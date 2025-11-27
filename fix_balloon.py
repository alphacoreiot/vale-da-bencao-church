#!/usr/bin/env python3
# Script para mudar estilo dos baloes - fundo branco com borda dourada

file_path = '/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/components/radio.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Estilo antigo do balão
old_style = '''.tip-balloon {
    position: fixed;
    bottom: 110px;
    right: 100px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%);
    color: #000;
    padding: 12px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
    z-index: 1000;
    animation: tipPulse 2s ease-in-out infinite;
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 200px;
}

.tip-balloon::after {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 30px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #B8941F;
}'''

# Novo estilo - fundo branco com borda dourada fina
new_style = '''.tip-balloon {
    position: fixed;
    bottom: 110px;
    right: 100px;
    background: #fff;
    color: #333;
    padding: 12px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    border: 2px solid #D4AF37;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    animation: tipPulse 2s ease-in-out infinite;
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 200px;
}

.tip-balloon::after {
    content: '';
    position: absolute;
    bottom: -10px;
    right: 30px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid #D4AF37;
}

.tip-balloon::before {
    content: '';
    position: absolute;
    bottom: -6px;
    right: 32px;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid #fff;
    z-index: 1;
}'''

content = content.replace(old_style, new_style)

# Também atualizar o botão de fechar para combinar
old_close = '''.tip-close {
    background: rgba(0,0,0,0.2);
    border: none;
    color: #000;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.tip-close:hover {
    background: rgba(0,0,0,0.3);
}'''

new_close = '''.tip-close {
    background: #D4AF37;
    border: none;
    color: #fff;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.tip-close:hover {
    background: #B8941F;
}'''

content = content.replace(old_close, new_close)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Baloes atualizados - fundo branco com borda dourada!")
