import pandas as pd
import re

# Ler o arquivo Excel
df = pd.read_excel(r"d:\DEV\IGREJA\vale-da-bencao-church\Atualizada 27-08-2025-1.xlsx")

# Renomear colunas
df.columns = ['vazio', 'geracao_info', 'lider', 'celula', 'bairro', 'ponto_referencia', 'contato']
df = df.drop(columns=['vazio'])

# Pular as primeiras 2 linhas (título e cabeçalho)
df = df.iloc[2:].reset_index(drop=True)

# Processar os dados
geracoes_dict = {}
celulas_list = []

current_geracao = None
current_responsaveis = None

def clean_str(val):
    if pd.isna(val) or str(val).strip() == 'nan':
        return None
    s = str(val).strip()
    # Remove caracteres invisíveis
    s = re.sub(r'[\u2060\u200b\u200c\u200d\ufeff]', '', s)
    # Substitui * por null
    if s == '*':
        return None
    return s

def escape_php(s):
    if s is None:
        return 'null'
    # Escape aspas simples
    s = s.replace("'", "\\'")
    return f"'{s}'"

for i, row in df.iterrows():
    geracao_info = clean_str(row['geracao_info'])
    lider = clean_str(row['lider'])
    
    if not geracao_info:
        continue
    
    # Se não tem líder, é uma linha de cabeçalho de geração
    if not lider:
        # Extrair geração e responsáveis do formato "Geração Nome - Responsáveis"
        if ' - ' in geracao_info:
            parts = geracao_info.split(' - ', 1)
            current_geracao = parts[0].strip()
            # Normalizar espaços duplos
            current_geracao = re.sub(r'\s+', ' ', current_geracao)
            current_responsaveis = parts[1].strip()
        else:
            current_geracao = re.sub(r'\s+', ' ', geracao_info)
            current_responsaveis = None
        
        if current_geracao not in geracoes_dict:
            geracoes_dict[current_geracao] = current_responsaveis
    else:
        # É uma célula
        if current_geracao:
            celulas_list.append({
                'geracao': current_geracao,
                'lider': lider,
                'nome': clean_str(row['celula']),
                'bairro': clean_str(row['bairro']),
                'ponto_referencia': clean_str(row['ponto_referencia']),
                'contato': clean_str(row['contato'])
            })

# Gerar o seeder PHP
seeder_content = '''<?php

namespace Database\Seeders;

use App\Models\Geracao;
use App\Models\Celula;
use Illuminate\Database\Seeder;

class CelulasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dados das gerações extraídos do Excel (27/11/2025)
        $geracoesData = [
'''

for nome, resp in geracoes_dict.items():
    resp_str = escape_php(resp)
    seeder_content += f"            ['nome' => {escape_php(nome)}, 'responsaveis' => {resp_str}],\n"

seeder_content += '''        ];

        // Criar gerações
        $geracoes = [];
        foreach ($geracoesData as $data) {
            $geracao = Geracao::updateOrCreate(
                ['nome' => $data['nome']],
                [
                    'responsaveis' => $data['responsaveis'],
                    'ativo' => true,
                ]
            );
            $geracoes[$data['nome']] = $geracao;
        }

        // Dados das células extraídos do Excel
        $celulasData = [
'''

for c in celulas_list:
    seeder_content += f"            ['geracao' => {escape_php(c['geracao'])}, 'lider' => {escape_php(c['lider'])}, 'nome' => {escape_php(c['nome'])}, 'bairro' => {escape_php(c['bairro'])}, 'ponto_referencia' => {escape_php(c['ponto_referencia'])}, 'contato' => {escape_php(c['contato'])}],\n"

seeder_content += '''        ];

        // Criar células
        foreach ($celulasData as $data) {
            $geracaoNome = $data['geracao'];
            
            // Encontrar a geração
            $geracao = $geracoes[$geracaoNome] ?? null;
            
            if ($geracao) {
                Celula::updateOrCreate(
                    [
                        'geracao_id' => $geracao->id,
                        'lider' => $data['lider'],
                        'bairro' => $data['bairro'],
                    ],
                    [
                        'nome' => $data['nome'],
                        'ponto_referencia' => $data['ponto_referencia'],
                        'contato' => $data['contato'],
                        'ativo' => true,
                    ]
                );
            }
        }

        $this->command->info('Gerações e células importadas com sucesso!');
        $this->command->info('Total de gerações: ' . Geracao::count());
        $this->command->info('Total de células: ' . Celula::count());
    }
}
'''

# Salvar o arquivo
with open(r'd:\DEV\IGREJA\vale-da-bencao-church\database\seeders\CelulasSeeder.php', 'w', encoding='utf-8') as f:
    f.write(seeder_content)

print(f"Seeder gerado com sucesso!")
print(f"Total de gerações: {len(geracoes_dict)}")
print(f"Total de células: {len(celulas_list)}")
