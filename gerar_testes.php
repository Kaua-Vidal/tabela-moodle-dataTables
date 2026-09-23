<?php
require_once('../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance()); // Só administradores podem rodar isso

global $DB;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/amdbasico/gerar_testes.php'));
$PAGE->set_title('Gerador de Alunos');
$PAGE->set_heading('Gerando 5000 Alunos de Teste');

echo $OUTPUT->header();

echo "<h3>Iniciando a geração de dados...</h3>";

// Nossas opções para sortear
$cursos = ['Engenharia de Software', 'Direito', 'Medicina', 'Administração', 'Pedagogia', 'Análise de Sistemas', 'Arquitetura'];
$polos = ['São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Curitiba', 'Porto Alegre', 'Salvador', 'Fortaleza'];
$situacoes = ['Ativo', 'Trancado', 'Formado', 'Cancelado'];

$registros_para_inserir = [];

// Vamos gerar 5000 alunos
for ($i = 1; $i <= 5000; $i++) {
    $aluno = new stdClass();
    $aluno->nome = "Aluno Teste Moodle nº " . rand(10000, 99999) . " - " . $i;
    $aluno->curso = $cursos[array_rand($cursos)];
    $aluno->polo = $polos[array_rand($polos)];
    $aluno->ano = rand(2018, 2026);
    $aluno->periodo = rand(1, 10);
    $aluno->situacao = $situacoes[array_rand($situacoes)];

    $registros_para_inserir[] = $aluno;

    // A cada 500 alunos, nós salvamos no banco para não sobrecarregar a memória
    if (count($registros_para_inserir) == 500) {
        $DB->insert_records('local_amdbasico_alunos', $registros_para_inserir);
        $registros_para_inserir = []; // Limpa a lista para os próximos 500
    }
}

// Insere qualquer sobra que tenha ficado
if (!empty($registros_para_inserir)) {
    $DB->insert_records('local_amdbasico_alunos', $registros_para_inserir);
}

echo '<div class="alert alert-success"><strong>Sucesso!</strong> 5000 alunos foram inseridos no banco de dados.</div>';
echo '<a href="index.php" class="btn btn-primary">Voltar para a Tabela</a>';

echo $OUTPUT->footer();