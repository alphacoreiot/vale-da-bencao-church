<?php

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
            ['nome' => 'Geração Água Viva', 'responsaveis' => null],
            ['nome' => 'Geração Azul Celeste', 'responsaveis' => 'Nílton e Sara'],
            ['nome' => 'Geração B e D', 'responsaveis' => 'Jr e Michelle'],
            ['nome' => 'Geração Bege', 'responsaveis' => 'Ramos e Reijane'],
            ['nome' => 'Geração Branca', 'responsaveis' => 'Biel e Duda'],
            ['nome' => 'Geração Branca e Azul', 'responsaveis' => 'Dalmar'],
            ['nome' => 'Geração Cinza', 'responsaveis' => 'Eric e Carine'],
            ['nome' => 'Geração Coral', 'responsaveis' => 'Andrea'],
            ['nome' => 'Geração Dourada', 'responsaveis' => 'Jefinho e Jaqueline'],
            ['nome' => 'Geração Gaditas', 'responsaveis' => 'Jhones e Laís'],
            ['nome' => 'Geração Israel', 'responsaveis' => 'Jailton e Helo'],
            ['nome' => 'Geração Jeová Makadech', 'responsaveis' => 'Almir e Cilene'],
            ['nome' => 'Geração Laranja', 'responsaveis' => 'Messias e Sandra'],
            ['nome' => 'Geração Mostarda', 'responsaveis' => 'Wandirley e Suely'],
            ['nome' => 'Geração Marrom', 'responsaveis' => 'Ricardo e Jasiane'],
            ['nome' => 'Geração Neon', 'responsaveis' => 'PV e Camila'],
            ['nome' => 'Geração Ouro', 'responsaveis' => 'Hermerson e Carolina'],
            ['nome' => 'Geração Pink', 'responsaveis' => 'Ivan e Moni'],
            ['nome' => 'Porta do Secreto', 'responsaveis' => 'Jamile e Silvaninho'],
            ['nome' => 'Geração Prata', 'responsaveis' => 'Dimas e Aline'],
            ['nome' => 'Geração Preta', 'responsaveis' => 'Edvaldo e Elizama'],
            ['nome' => 'Geração Preta e Branca', 'responsaveis' => 'Val e Di'],
            ['nome' => 'Geração Resgate', 'responsaveis' => 'Uilton e Noélia'],
            ['nome' => 'Geração Rosinha', 'responsaveis' => 'Diana e Eber'],
            ['nome' => 'Geração Roxa', 'responsaveis' => 'Nataline'],
            ['nome' => 'Geração Verde Bandeira', 'responsaveis' => 'Jacson e María'],
            ['nome' => 'Geração Verde Tifanes', 'responsaveis' => 'Jacira e Alice'],
            ['nome' => 'Geração Verde e Vinho', 'responsaveis' => 'Joilson e Márcia'],
        ];

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
            ['geracao' => 'Geração Água Viva', 'lider' => 'Daniel e Jeane', 'nome' => 'Oliveira Verdadeira', 'bairro' => 'Alphaville', 'ponto_referencia' => 'Terras Alphaville Q W lote 26', 'contato' => null],
            ['geracao' => 'Geração Azul Celeste', 'lider' => 'Nílton e Sara', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Pardais II, Final de linha', 'contato' => '71983821240/71983015594'],
            ['geracao' => 'Geração B e D', 'lider' => 'Jr e Michelle', 'nome' => null, 'bairro' => 'Piaçaveira', 'ponto_referencia' => 'Caminho Atalaia, 85', 'contato' => '71983847641'],
            ['geracao' => 'Geração B e D', 'lider' => 'Erivaldo e Evelin', 'nome' => null, 'bairro' => 'Santo Antônio 1', 'ponto_referencia' => 'Rua Emanuel, 72', 'contato' => '71981264861'],
            ['geracao' => 'Geração B e D', 'lider' => 'Erivaldo e Evelin', 'nome' => null, 'bairro' => 'Verdes Horizonte', 'ponto_referencia' => 'Rua Praia do Sul, 37', 'contato' => '71981264861'],
            ['geracao' => 'Geração B e D', 'lider' => 'Alexandra e May - Bomba', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => 'Rua do Telégrafo, 612', 'contato' => '71984007927/ 11996396076'],
            ['geracao' => 'Geração B e D', 'lider' => 'Sandra Luz', 'nome' => null, 'bairro' => 'Parq das Palmeiras', 'ponto_referencia' => 'Rua Cajueiro, 28 - prox. a igreja', 'contato' => '75983367420'],
            ['geracao' => 'Geração B e D', 'lider' => 'Robert', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Pardais 1 , bloco 05 ,apt 01', 'contato' => '71996261590'],
            ['geracao' => 'Geração B e D', 'lider' => 'Nailan', 'nome' => 'Tempo Novo', 'bairro' => 'Phoc 3', 'ponto_referencia' => 'Rua Carajas n°270', 'contato' => '71993185691'],
            ['geracao' => 'Geração B e D', 'lider' => 'Edinalva', 'nome' => 'Chamada da Fé', 'bairro' => 'Ponto Certo', 'ponto_referencia' => 'Condomínio praia do mutá meu endereço, Blo 02 apt 204', 'contato' => '71 98396-6293'],
            ['geracao' => 'Geração Bege', 'lider' => 'Ramos e Reijane', 'nome' => 'Nascido de Deus', 'bairro' => 'Jardim Limoeiro', 'ponto_referencia' => 'Cond. Morada dos Sábias, em frente ao Unimar, segunda esquerda, bloco 13 casa 07.', 'contato' => '71999985133 / 71983485537'],
            ['geracao' => 'Geração Bege', 'lider' => 'Rogério e Jaci', 'nome' => 'Nascido de Deus', 'bairro' => 'Novo Horizonte', 'ponto_referencia' => 'Caminho Ponta Negra n°27, próximo a academia Horizonte Fitness', 'contato' => '7198100-5921'],
            ['geracao' => 'Geração Bege', 'lider' => 'Luciano e Angélica', 'nome' => 'Nascido de Deus', 'bairro' => 'Bairro Novo', 'ponto_referencia' => 'Condomínio Praia de Itacimirim', 'contato' => '7199645-7018/7199133-9205'],
            ['geracao' => 'Geração Bege', 'lider' => 'Nara e Lucas', 'nome' => 'Nascido de Deus', 'bairro' => 'Parque Satélite', 'ponto_referencia' => 'Rua São Francisco N56 próximo a assembleia de Deus', 'contato' => '7198753-4723 /  7198762-2848'],
            ['geracao' => 'Geração Bege', 'lider' => 'Bruno e Gleice', 'nome' => 'Nascido de Deus', 'bairro' => 'Jardim Limoeiro', 'ponto_referencia' => 'Condomínio Morada dos Sábias Rua F , Casa 04', 'contato' => '7198314-7865 / 7198386-3771'],
            ['geracao' => 'Geração Branca', 'lider' => 'Marcos e Alessandra', 'nome' => null, 'bairro' => 'Santa Maria', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Branca', 'lider' => 'José e Adailde', 'nome' => null, 'bairro' => 'Gleba H', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Branca', 'lider' => 'Igor e Paulinha', 'nome' => null, 'bairro' => 'Gleba H', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Branca', 'lider' => 'Elinha', 'nome' => null, 'bairro' => 'Lama preta', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Branca', 'lider' => 'Pim e Adalberto', 'nome' => null, 'bairro' => 'Lama preta', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Branca', 'lider' => 'José e Luciana', 'nome' => null, 'bairro' => 'Paque verde 2', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Branca', 'lider' => 'Dudu e Geise', 'nome' => null, 'bairro' => 'Av Camaçari', 'ponto_referencia' => 'Dudu veiculos', 'contato' => null],
            ['geracao' => 'Geração Branca e Azul', 'lider' => 'Dalmar', 'nome' => null, 'bairro' => null, 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Branca e Azul', 'lider' => 'Vânia', 'nome' => null, 'bairro' => 'Piaçaveira', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Cinza', 'lider' => 'Márcia e Jailson', 'nome' => null, 'bairro' => 'Lama Preta', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Cinza', 'lider' => 'Cezar e Jessica', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Cinza', 'lider' => 'Fábio e Dani', 'nome' => null, 'bairro' => 'Cond Villa Bella', 'ponto_referencia' => 'Cond Villa Bella', 'contato' => null],
            ['geracao' => 'Geração Cinza', 'lider' => 'Fred e Lalesca', 'nome' => null, 'bairro' => 'Piaçaveira', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Cinza', 'lider' => 'Henrique e Talita Lisboa', 'nome' => null, 'bairro' => 'Bairro Gleba B', 'ponto_referencia' => null, 'contato' => '7199155-3885'],
            ['geracao' => 'Geração Coral', 'lider' => 'Andrea', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Coral', 'lider' => 'Wanessa', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Coral', 'lider' => 'Jaquison', 'nome' => null, 'bairro' => 'Gleba A', 'ponto_referencia' => 'Rua oito', 'contato' => null],
            ['geracao' => 'Geração Coral', 'lider' => 'Eliana e Jonas', 'nome' => null, 'bairro' => 'Gleba H', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Coral', 'lider' => 'Felipe', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Dourada', 'lider' => 'Jefinho e Jaqueline', 'nome' => null, 'bairro' => 'Novo Horizonte', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Dourada', 'lider' => 'Denilson e Larissa', 'nome' => null, 'bairro' => 'Novo Horizonte', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Gaditas', 'lider' => 'Jhones e Laís', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Gaditas', 'lider' => 'Débora', 'nome' => null, 'bairro' => 'Gleba C', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Gaditas', 'lider' => 'Luciara', 'nome' => null, 'bairro' => 'Bairro novo', 'ponto_referencia' => 'Cond. Itacimirim', 'contato' => null],
            ['geracao' => 'Geração Gaditas', 'lider' => 'Lívia', 'nome' => null, 'bairro' => 'Bairro novo', 'ponto_referencia' => 'Cond. Itacimirim', 'contato' => null],
            ['geracao' => 'Geração Gaditas', 'lider' => 'Wanderson', 'nome' => null, 'bairro' => 'Camaçari de dentro', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Israel', 'lider' => 'Jailton e Helo', 'nome' => null, 'bairro' => 'Piaçaveira', 'ponto_referencia' => 'Caminho Fortaleza n°07 Antigo Caminho A 11', 'contato' => null],
            ['geracao' => 'Geração Israel', 'lider' => 'Josinaide', 'nome' => null, 'bairro' => 'Verde Horizonte', 'ponto_referencia' => 'Rua Petrolina n° 339 , ao lado da escola Virgínia Reis Tide, casa portão cinza', 'contato' => null],
            ['geracao' => 'Geração Jeová Makadech', 'lider' => 'Almir e Cilene', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Condomínio moradas dos sabias  rua  N blo. 38 ap, 6', 'contato' => null],
            ['geracao' => 'Geração Laranja', 'lider' => 'Paty e Bad', 'nome' => null, 'bairro' => 'Fican 2', 'ponto_referencia' => null, 'contato' => '71985541439 / 71991229626'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Jai e Van', 'nome' => null, 'bairro' => 'Pq. das Palmeiras', 'ponto_referencia' => null, 'contato' => '71999337996 / 71981867570'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Karol e Cristian', 'nome' => null, 'bairro' => 'Bairro novo', 'ponto_referencia' => 'Cond. Duo alto da colina', 'contato' => '71992341477 / 71992299336'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Mara e Carlos', 'nome' => null, 'bairro' => 'Pq Verde 1', 'ponto_referencia' => null, 'contato' => '71992110385 / 71992244165'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Arilson e Juliana', 'nome' => null, 'bairro' => 'Alto da Cruz', 'ponto_referencia' => 'Avenida Radial C', 'contato' => '71993788476 / 71992433201'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Josimar e Aline', 'nome' => null, 'bairro' => 'Arembepe', 'ponto_referencia' => 'Arembepe', 'contato' => '/ 71996046713'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Carol e Elcson', 'nome' => null, 'bairro' => 'Ponto Certo', 'ponto_referencia' => 'Jardim Atlantico Life', 'contato' => '71984462687 / 71981276124'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Moisés e Leyla', 'nome' => null, 'bairro' => 'Alpha Ville', 'ponto_referencia' => 'Alpha Ville', 'contato' => '71992339623'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Tailanderson e Diana', 'nome' => null, 'bairro' => 'Bairro Novo', 'ponto_referencia' => 'Cond. Jaua', 'contato' => '71992230642 / 71993343047'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Ewerton e Mirele', 'nome' => null, 'bairro' => 'Bairro Novo', 'ponto_referencia' => 'Condominio Viena', 'contato' => '7199189-2916 /7199111-3515'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Sávio e Ewelin', 'nome' => null, 'bairro' => 'Inocoop', 'ponto_referencia' => 'Rua Arembepe - Edf Flor de Cactus', 'contato' => '71992924724 / 71993547722'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Florisvaldo e Liviane', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Pardais IV', 'contato' => '71992586884 /'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Gabriel e Raely', 'nome' => null, 'bairro' => 'Pq. das Palmeiras', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Laranja', 'lider' => 'Azevedo e Andresa', 'nome' => 'Guerreiro de Cristo', 'bairro' => 'Bairro Phoc 2', 'ponto_referencia' => 'Rua Ilhéus n° 39', 'contato' => '7199258-7277 - 7199169-7429'],
            ['geracao' => 'Geração Laranja', 'lider' => 'Daniel', 'nome' => 'Servos', 'bairro' => 'Bairro Terras Alphaville', 'ponto_referencia' => 'Terras de Alphaville', 'contato' => '71987283293'],
            ['geracao' => 'Geração Mostarda', 'lider' => 'Wanderley e Suely', 'nome' => null, 'bairro' => 'Camaçari de Dentro', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Marrom', 'lider' => 'Ricardo e Jasiane', 'nome' => null, 'bairro' => 'Bairro Novo', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Marrom', 'lider' => 'Luciane Meirelles', 'nome' => null, 'bairro' => 'Algarobas 3', 'ponto_referencia' => 'Algarobas 3', 'contato' => '7198422-4396'],
            ['geracao' => 'Geração Marrom', 'lider' => 'Neia e Dasio', 'nome' => null, 'bairro' => '2 De Julho', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Marrom', 'lider' => 'Uoshington e Elane', 'nome' => null, 'bairro' => 'Ponto certo', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Neon', 'lider' => 'Yasmim e Diotagnan', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Estrada 24', 'contato' => null],
            ['geracao' => 'Geração Neon', 'lider' => 'Deive e Jaque', 'nome' => null, 'bairro' => 'Gravatá', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Neon', 'lider' => 'Maiko', 'nome' => null, 'bairro' => 'Piaçaveira', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Neon', 'lider' => 'Ícaro', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Neon', 'lider' => 'Joabe e Bruna', 'nome' => null, 'bairro' => 'Vivendas', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Neon', 'lider' => 'Natan e Fabia', 'nome' => null, 'bairro' => 'Bairro novo', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Neon', 'lider' => 'Mateus e Rafaela', 'nome' => null, 'bairro' => 'Parque das Mangabas', 'ponto_referencia' => 'Rua Getúlio Vargas, n°32, 1°portão', 'contato' => '7198191-9676 / 7199286-9912'],
            ['geracao' => 'Geração Neon', 'lider' => 'Neianderson e Elisandra', 'nome' => null, 'bairro' => 'Gleba H', 'ponto_referencia' => 'Primeira travessa do Jasmim número,n° 83', 'contato' => '7198116-1302/7199624-8897'],
            ['geracao' => 'Geração Neon', 'lider' => 'Karen e Virgílio', 'nome' => null, 'bairro' => 'Dias D\' Villa', 'ponto_referencia' => 'Rua Santos Titara , Condomínio Residencial Imbassay n°200 Casa 13', 'contato' => '7198896-0137 / 71 98641-0332'],
            ['geracao' => 'Geração Neon', 'lider' => 'Ana Gabriele', 'nome' => null, 'bairro' => 'Phoc 2', 'ponto_referencia' => null, 'contato' => '71 99395-9703'],
            ['geracao' => 'Geração Ouro', 'lider' => 'Hermerson e Carolina', 'nome' => null, 'bairro' => 'Camaçari de Dentro', 'ponto_referencia' => 'Rua Canário', 'contato' => '7199256-5307'],
            ['geracao' => 'Geração Pink', 'lider' => 'Ivan e Moni', 'nome' => null, 'bairro' => 'Gleba H', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Porta do Secreto', 'lider' => 'Jamile e Silvaninho', 'nome' => null, 'bairro' => 'Novo horizonte', 'ponto_referencia' => 'Travessa Av Leste, nº60', 'contato' => '7198198-1723'],
            ['geracao' => 'Geração Prata', 'lider' => 'Dimas e Aline', 'nome' => null, 'bairro' => 'Parque Verde', 'ponto_referencia' => 'Proximo ao posto de gasolina sentido gleba E', 'contato' => null],
            ['geracao' => 'Geração Preta', 'lider' => 'Edvaldo e Elizama', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Proximo a maternida de Camaçari, rua da pousada limoeiro', 'contato' => '7199336-2093 / 7199607-9841'],
            ['geracao' => 'Geração Preta', 'lider' => 'Raphael e Vanesca', 'nome' => 'Manancial', 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Condomínio Bela Morada', 'contato' => '71 99403-0661 / 71 99377-3473'],
            ['geracao' => 'Geração Preta', 'lider' => 'Tiago e Maria', 'nome' => null, 'bairro' => 'Ficam 2', 'ponto_referencia' => 'Rua Blumenau número 07', 'contato' => '71 99201-9125/71 98240-9594'],
            ['geracao' => 'Geração Preta e Branca', 'lider' => 'Val e Di', 'nome' => null, 'bairro' => 'Ponto certo', 'ponto_referencia' => 'Cond. Jardim Atlântico Life', 'contato' => '71996096229'],
            ['geracao' => 'Geração Preta e Branca', 'lider' => 'Lima', 'nome' => null, 'bairro' => 'Parafuso', 'ponto_referencia' => 'Parafuso', 'contato' => null],
            ['geracao' => 'Geração Preta e Branca', 'lider' => 'Nilo e Cleide', 'nome' => 'Maravilha Jesus', 'bairro' => 'Parque Verde 1', 'ponto_referencia' => 'Proximo ao Sitio do Vovô', 'contato' => '7199742-7214/7198747-9099'],
            ['geracao' => 'Geração Preta e Branca', 'lider' => 'Almir e Michele', 'nome' => 'Arca de Deus', 'bairro' => 'Gleba A', 'ponto_referencia' => 'Rua Cristo Redentor n°09', 'contato' => '1199284-6546'],
            ['geracao' => 'Geração Preta e Branca', 'lider' => 'Ludmila', 'nome' => 'Oásis', 'bairro' => 'Verde Horizonte', 'ponto_referencia' => 'Rua Amaralina n°21, próximo a praça Lirio', 'contato' => '71 99677-7824/71 98171-0090'],
            ['geracao' => 'Geração Resgate', 'lider' => 'Uilton e Noélia', 'nome' => null, 'bairro' => 'Parque das Palmeiras', 'ponto_referencia' => 'N°7, quarta rua', 'contato' => null],
            ['geracao' => 'Geração Rosinha', 'lider' => 'Diana e Eber', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Condomínio Cardeias Rua I', 'contato' => null],
            ['geracao' => 'Geração Rosinha', 'lider' => 'Michele', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Condomínio Sábias, Rua N', 'contato' => null],
            ['geracao' => 'Geração Rosinha', 'lider' => 'Angela e Sidney', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Condomínio Sábias, Rua N', 'contato' => null],
            ['geracao' => 'Geração Rosinha', 'lider' => 'Ana Paula e Gilberto', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Condomínio Sábias, Rua N', 'contato' => null],
            ['geracao' => 'Geração Rosinha', 'lider' => 'Joelma e Jilenildo', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Sábias, Rua P', 'contato' => null],
            ['geracao' => 'Geração Roxa', 'lider' => 'Nataline', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => 'Proximo a Ferrerinha', 'contato' => '71983035373'],
            ['geracao' => 'Geração Roxa', 'lider' => 'Heloize', 'nome' => null, 'bairro' => 'Natal', 'ponto_referencia' => 'Cond. Pq Florestal', 'contato' => '71997173906'],
            ['geracao' => 'Geração Roxa', 'lider' => 'Júlio e Carla', 'nome' => null, 'bairro' => 'Gleba B', 'ponto_referencia' => 'Proximo ao crepe 10', 'contato' => '71997073496'],
            ['geracao' => 'Geração Roxa', 'lider' => 'Cauã', 'nome' => null, 'bairro' => 'Lama Preta', 'ponto_referencia' => 'Cond. Morada dos Pinheiros', 'contato' => '71993657614'],
            ['geracao' => 'Geração Roxa', 'lider' => 'Emily', 'nome' => 'Jardim Particular', 'bairro' => 'Jardim Limoeiro', 'ponto_referencia' => 'Condomínio Alpha 2', 'contato' => '71 98192-9575'],
            ['geracao' => 'Geração Verde Bandeira', 'lider' => 'Jacson e María', 'nome' => null, 'bairro' => 'Parque satélite', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Verde Bandeira', 'lider' => 'Daiana', 'nome' => null, 'bairro' => 'Gravata', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Verde Bandeira', 'lider' => 'Fabíola', 'nome' => null, 'bairro' => 'Gravata', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Verde Bandeira', 'lider' => 'Israel dos Santos', 'nome' => 'Adonai Rafah', 'bairro' => 'Gravata / Vila Goiânia', 'ponto_referencia' => 'Endereço rua Goiânia n°06, próximo ao mercado leopan', 'contato' => '7198340-6635/71 92003-0492'],
            ['geracao' => 'Geração Verde Tifanes', 'lider' => 'Jacira e Alice', 'nome' => null, 'bairro' => 'Pq. das Palmeiras', 'ponto_referencia' => 'Sétima rua', 'contato' => null],
            ['geracao' => 'Geração Verde e Vinho', 'lider' => 'Joilson e Márcia', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Condomínio Muta', 'contato' => null],
            ['geracao' => 'Geração Verde e Vinho', 'lider' => 'Fabiano e Iracema', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => null, 'contato' => null],
            ['geracao' => 'Geração Verde e Vinho', 'lider' => 'Vinícius e Emile', 'nome' => null, 'bairro' => 'Limoeiro', 'ponto_referencia' => 'Praça Sol Nascente', 'contato' => null],
        ];

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
