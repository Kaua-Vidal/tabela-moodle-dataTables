<?php
require_once('../../config.php');
require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/amdbasico/index.php'));
$PAGE->set_title('Relatório de Alunos');
$PAGE->set_heading('Relatório de Alunos');
$PAGE->requires->css('/local/amdbasico/styles.css');

// --- A MÁGICA DOS FILTROS ---
// Vamos buscar os Cursos e Polos únicos no banco de dados para criar as opções
global $DB;
$cursos_db = $DB->get_records_sql("SELECT DISTINCT curso AS id, curso FROM {local_amdbasico_alunos} ORDER BY curso");
$polos_db  = $DB->get_records_sql("SELECT DISTINCT polo AS id, polo FROM {local_amdbasico_alunos} ORDER BY polo");

echo $OUTPUT->header();
?>

<!-- AS BIBLIOTECAS QUE FALTAVAM -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<div id="painel-filtros" class="card p-3 mb-3">
    <div class="row">
        <div class="col-md-4">
            <label>Curso</label>
            <select class="form-control" data-filter-col="1">
                <option value="">Todos os Cursos</option>
                <?php
                // Desenha as opções de cursos dinamicamente
                if ($cursos_db) {
                    foreach ($cursos_db as $c) {
                        echo '<option value="' . s($c->curso) . '">' . s($c->curso) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label>Polo</label>
            <select class="form-control" data-filter-col="2">
                <option value="">Todos os Polos</option>
                <?php
                // Desenha as opções de polos dinamicamente
                if ($polos_db) {
                    foreach ($polos_db as $p) {
                        echo '<option value="' . s($p->polo) . '">' . s($p->polo) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="text-right mt-3">
        <button type="button" id="btn-imprimir" class="btn btn-danger px-4 mr-2"><i class="fa fa-print"></i> Salvar PDF / Imprimir</button>
        <button type="button" id="btn-aplicar-filtros" class="btn btn-primary px-4"><i class="fa fa-search"></i> Filtrar Registros</button>
    </div>
</div>

<table id="tabela-alunos" class="table table-striped table-bordered" style="width:100%" data-ajax-url="carregar_alunos.php">
    <thead>
        <tr>
            <th>Nome do Aluno</th><th>Curso</th><th>Polo</th><th>Ano</th><th>Período</th><th>Situação</th><th>Ações</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<?php
// Mapeia o DataTables para o Moodle
$PAGE->requires->js_amd_inline("
    require.config({
        paths: {
            'datatables.net': 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min',
            'datatables.net-bs4': 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min'
        }
    });
");

// Inicia o nosso script JS
$PAGE->requires->js_call_amd('local_amdbasico/tabela_init', 'init', [
    ['tabela' => '#tabela-alunos', 'filtros' => '#painel-filtros']
]);
echo $OUTPUT->footer();