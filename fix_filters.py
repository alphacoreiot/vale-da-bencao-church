#!/usr/bin/env python3
file_path = "/home/u817008098/domains/valedabencao.com.br/public_html/resources/views/frontend/celulas.blade.php"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Substituir a lógica dos filtros para serem encadeados
old_js = '''    // Filtros
    const filtroBairro = document.getElementById('filtro-bairro');
    const filtroGeracao = document.getElementById('filtro-geracao');
    const totalFiltrado = document.getElementById('total-filtrado');
    const semResultados = document.getElementById('sem-resultados');
    const celulasItems = document.querySelectorAll('.celula-item');

    function aplicarFiltros() {
        const bairroSelecionado = filtroBairro.value;
        const geracaoSelecionada = filtroGeracao.value;
        let visiveisCount = 0;

        celulasItems.forEach(item => {
            const bairro = item.dataset.bairro || '';
            const geracao = item.dataset.geracao || '';

            const matchBairro = !bairroSelecionado || bairro === bairroSelecionado;
            const matchGeracao = !geracaoSelecionada || geracao === geracaoSelecionada;

            if (matchBairro && matchGeracao) {
                item.classList.remove('hidden');
                item.style.display = 'block';
                visiveisCount++;
            } else {
                item.classList.add('hidden');
                item.style.display = 'none';
            }
        });

        totalFiltrado.textContent = visiveisCount;
        semResultados.style.display = visiveisCount === 0 ? 'block' : 'none';
    }

    filtroBairro.addEventListener('change', aplicarFiltros);
    filtroGeracao.addEventListener('change', aplicarFiltros);'''

new_js = '''    // Filtros encadeados
    const filtroBairro = document.getElementById('filtro-bairro');
    const filtroGeracao = document.getElementById('filtro-geracao');
    const totalFiltrado = document.getElementById('total-filtrado');
    const semResultados = document.getElementById('sem-resultados');
    const celulasItems = document.querySelectorAll('.celula-item');

    // Dados originais para os selects
    const todosOsBairros = [...new Set([...celulasItems].map(i => i.dataset.bairro).filter(b => b))].sort();
    const todasAsGeracoes = [...new Set([...celulasItems].map(i => i.dataset.geracao).filter(g => g))].sort();

    function atualizarSelectBairros(geracaoFiltro) {
        const bairroAtual = filtroBairro.value;
        filtroBairro.innerHTML = '<option value="">Todos os bairros</option>';
        
        let bairrosDisponiveis = todosOsBairros;
        if (geracaoFiltro) {
            bairrosDisponiveis = [...new Set(
                [...celulasItems]
                    .filter(i => i.dataset.geracao === geracaoFiltro)
                    .map(i => i.dataset.bairro)
                    .filter(b => b)
            )].sort();
        }
        
        bairrosDisponiveis.forEach(bairro => {
            const option = document.createElement('option');
            option.value = bairro;
            option.textContent = bairro;
            if (bairro === bairroAtual) option.selected = true;
            filtroBairro.appendChild(option);
        });
        
        // Se o bairro atual não está mais disponível, resetar
        if (bairroAtual && !bairrosDisponiveis.includes(bairroAtual)) {
            filtroBairro.value = '';
        }
    }

    function atualizarSelectGeracoes(bairroFiltro) {
        const geracaoAtual = filtroGeracao.value;
        filtroGeracao.innerHTML = '<option value="">Todas as gerações</option>';
        
        let geracoesDisponiveis = todasAsGeracoes;
        if (bairroFiltro) {
            geracoesDisponiveis = [...new Set(
                [...celulasItems]
                    .filter(i => i.dataset.bairro === bairroFiltro)
                    .map(i => i.dataset.geracao)
                    .filter(g => g)
            )].sort();
        }
        
        geracoesDisponiveis.forEach(geracao => {
            const option = document.createElement('option');
            option.value = geracao;
            option.textContent = geracao;
            if (geracao === geracaoAtual) option.selected = true;
            filtroGeracao.appendChild(option);
        });
        
        // Se a geração atual não está mais disponível, resetar
        if (geracaoAtual && !geracoesDisponiveis.includes(geracaoAtual)) {
            filtroGeracao.value = '';
        }
    }

    function aplicarFiltros() {
        const bairroSelecionado = filtroBairro.value;
        const geracaoSelecionada = filtroGeracao.value;
        let visiveisCount = 0;

        celulasItems.forEach(item => {
            const bairro = item.dataset.bairro || '';
            const geracao = item.dataset.geracao || '';

            const matchBairro = !bairroSelecionado || bairro === bairroSelecionado;
            const matchGeracao = !geracaoSelecionada || geracao === geracaoSelecionada;

            if (matchBairro && matchGeracao) {
                item.classList.remove('hidden');
                item.style.display = 'block';
                visiveisCount++;
            } else {
                item.classList.add('hidden');
                item.style.display = 'none';
            }
        });

        totalFiltrado.textContent = visiveisCount;
        semResultados.style.display = visiveisCount === 0 ? 'block' : 'none';
    }

    filtroBairro.addEventListener('change', function() {
        atualizarSelectGeracoes(this.value);
        aplicarFiltros();
    });

    filtroGeracao.addEventListener('change', function() {
        atualizarSelectBairros(this.value);
        aplicarFiltros();
    });'''

content = content.replace(old_js, new_js)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Filtros encadeados implementados!")
