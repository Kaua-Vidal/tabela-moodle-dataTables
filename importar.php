<?php
require_once('../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance()); 
global $DB;

// O JSON completo embutido direto no código!
$json_puro = '[
  { "nome": "Aline Albuquerque Aline Albuquerque Aline Albuquerque Aline ALine Aline", "curso": "Ciência da Computação", "polo": "Polo João Pessoa", "ano": "2024", "periodo": "2024.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Bruno Costa", "curso": "Sistemas de Informação", "polo": "Polo Abreu e Lima", "ano": "2023", "periodo": "2023.2", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Carlos Eduardo", "curso": "Direito", "polo": "Polo Campina Grande", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-warning\">Trancado</span>" },
  { "nome": "Daniela Mendes", "curso": "Engenharia de Software", "polo": "Polo João Pessoa", "ano": "2022", "periodo": "2022.1", "situacao": "<span class=\"badge badge-secondary\">Formado</span>" },
  { "nome": "Eduardo Silva", "curso": "Ciência da Computação", "polo": "Polo Abreu e Lima", "ano": "2024", "periodo": "2024.2", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Fernanda Lima", "curso": "Design Gráfico", "polo": "Polo Guarabira", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Gabriel Ribeiro", "curso": "Ciência da Computação", "polo": "Polo Campina Grande", "ano": "2023", "periodo": "2023.1", "situacao": "<span class=\"badge badge-danger\">Cancelado</span>" },
  { "nome": "Heloísa Santos", "curso": "Direito", "polo": "Polo João Pessoa", "ano": "2024", "periodo": "2024.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Igor Farias", "curso": "Sistemas de Informação", "polo": "Polo Abreu e Lima", "ano": "2022", "periodo": "2022.2", "situacao": "<span class=\"badge badge-secondary\">Formado</span>" },
  { "nome": "Juliana Castro", "curso": "Engenharia de Software", "polo": "Polo Guarabira", "ano": "2026", "periodo": "2026.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Lucas Martins", "curso": "Ciência da Computação", "polo": "Polo João Pessoa", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Mariana Oliveira", "curso": "Design Gráfico", "polo": "Polo Campina Grande", "ano": "2023", "periodo": "2023.2", "situacao": "<span class=\"badge badge-warning\">Trancado</span>" },
  { "nome": "Nícolas Pereira", "curso": "Direito", "polo": "Polo Abreu e Lima", "ano": "2024", "periodo": "2024.2", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Olívia Rocha", "curso": "Ciência da Computação", "polo": "Polo Guarabira", "ano": "2022", "periodo": "2022.1", "situacao": "<span class=\"badge badge-secondary\">Formado</span>" },
  { "nome": "Pedro Henrique", "curso": "Sistemas de Informação", "polo": "Polo João Pessoa", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Quintino Alves", "curso": "Engenharia de Software", "polo": "Polo Campina Grande", "ano": "2024", "periodo": "2024.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Rafael Nogueira", "curso": "Direito", "polo": "Polo João Pessoa", "ano": "2026", "periodo": "2026.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Sofia Gouveia", "curso": "Ciência da Computação", "polo": "Polo Abreu e Lima", "ano": "2023", "periodo": "2023.1", "situacao": "<span class=\"badge badge-warning\">Trancado</span>" },
  { "nome": "Thiago Moraes", "curso": "Design Gráfico", "polo": "Polo João Pessoa", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Úrsula Viana", "curso": "Sistemas de Informação", "polo": "Polo Guarabira", "ano": "2024", "periodo": "2024.2", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Vinícius Barros", "curso": "Ciência da Computação", "polo": "Polo Campina Grande", "ano": "2022", "periodo": "2022.2", "situacao": "<span class=\"badge badge-secondary\">Formado</span>" },
  { "nome": "Wagner Sousa", "curso": "Engenharia de Software", "polo": "Polo Abreu e Lima", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Xavier Lopes", "curso": "Direito", "polo": "Polo Guarabira", "ano": "2023", "periodo": "2023.2", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Yasmin Carvalho", "curso": "Ciência da Computação", "polo": "Polo João Pessoa", "ano": "2026", "periodo": "2026.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Zeca Teixeira", "curso": "Design Gráfico", "polo": "Polo Abreu e Lima", "ano": "2024", "periodo": "2024.1", "situacao": "<span class=\"badge badge-danger\">Cancelado</span>" },
  { "nome": "Amanda Correia", "curso": "Sistemas de Informação", "polo": "Polo Campina Grande", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Breno Batista", "curso": "Ciência da Computação", "polo": "Polo João Pessoa", "ano": "2023", "periodo": "2023.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Camila Fernandes", "curso": "Engenharia de Software", "polo": "Polo Abreu e Lima", "ano": "2024", "periodo": "2024.2", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Diogo Macedo", "curso": "Direito", "polo": "Polo João Pessoa", "ano": "2022", "periodo": "2022.1", "situacao": "<span class=\"badge badge-secondary\">Formado</span>" },
  { "nome": "Elaine Duarte", "curso": "Ciência da Computação", "polo": "Polo Guarabira", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-warning\">Trancado</span>" },
  { "nome": "Fábio Monteiro", "curso": "Design Gráfico", "polo": "Polo Campina Grande", "ano": "2024", "periodo": "2024.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Giovanna Freitas", "curso": "Sistemas de Informação", "polo": "Polo Abreu e Lima", "ano": "2026", "periodo": "2026.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Henrique Pires", "curso": "Ciência da Computação", "polo": "Polo João Pessoa", "ano": "2023", "periodo": "2023.2", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Isabela Moura", "curso": "Direito", "polo": "Polo Guarabira", "ano": "2024", "periodo": "2024.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" },
  { "nome": "Jorge Machado", "curso": "Engenharia de Software", "polo": "Polo Campina Grande", "ano": "2025", "periodo": "2025.1", "situacao": "<span class=\"badge badge-success\">Ativo</span>" }
]';

$alunos = json_decode($json_puro, true);

// Limpa a tabela para evitar alunos duplicados se você recarregar a página sem querer
$DB->delete_records('local_amdbasico_alunos');

$contador = 0;
if (is_array($alunos)) {
    foreach ($alunos as $aluno) {
        $novo_registro = new stdClass();
        $novo_registro->nome = $aluno['nome'];
        $novo_registro->curso = $aluno['curso'];
        $novo_registro->polo = $aluno['polo'];
        $novo_registro->ano = $aluno['ano'];
        $novo_registro->periodo = $aluno['periodo'];
        $novo_registro->situacao = $aluno['situacao'];
        
        // Insere no banco
        $DB->insert_record('local_amdbasico_alunos', $novo_registro);
        $contador++;
    }
}

echo "Sucesso! Inseridos {$contador} alunos no PostgreSQL.";