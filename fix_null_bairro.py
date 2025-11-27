#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Corrigir o forEach para verificar se bairro existe
old_code = """    // Criar conjunto de bairros com células (normalizado)
    const bairrosComCelulas = new Set();
    celulasJson.forEach(celula => {
        const bairroNorm = celula.bairro.toUpperCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');
        bairrosComCelulas.add(bairroNorm);

        // Adicionar mapeamentos
        Object.entries(mapeamentoBairros).forEach(([key, value]) => {
            if (key === bairroNorm) {
                bairrosComCelulas.add(value);
            }
        });
    });"""

new_code = """    // Criar conjunto de bairros com células (normalizado)
    const bairrosComCelulas = new Set();
    celulasJson.forEach(celula => {
        if (!celula.bairro) return; // Pular células sem bairro
        const bairroNorm = celula.bairro.toUpperCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');
        bairrosComCelulas.add(bairroNorm);

        // Adicionar mapeamentos
        Object.entries(mapeamentoBairros).forEach(([key, value]) => {
            if (key === bairroNorm) {
                bairrosComCelulas.add(value);
            }
        });
    });"""

content = content.replace(old_code, new_code)

# Também corrigir o filter dentro do onEachFeature
old_filter = """                    // Encontrar células neste bairro
                    const celulasNoBairro = celulasJson.filter(c => {
                        const cBairroNorm = c.bairro.toUpperCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');
                        return cBairroNorm === nmBairroNorm || mapeamentoBairros[cBairroNorm] === nmBairroNorm;
                    });"""

new_filter = """                    // Encontrar células neste bairro
                    const celulasNoBairro = celulasJson.filter(c => {
                        if (!c.bairro) return false;
                        const cBairroNorm = c.bairro.toUpperCase().normalize('NFD').replace(/[\\u0300-\\u036f]/g, '');
                        return cBairroNorm === nmBairroNorm || mapeamentoBairros[cBairroNorm] === nmBairroNorm;
                    });"""

content = content.replace(old_filter, new_filter)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Verificação de bairro nulo adicionada!")
