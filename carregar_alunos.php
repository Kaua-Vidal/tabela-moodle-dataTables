<?php
require_once('../../config.php');
global $DB;
require_login();

// 1. Recebendo as variáveis de paginação 
$draw   = optional_param('draw', 1, PARAM_INT);
$start  = optional_param('start', 0, PARAM_INT);
$length = optional_param('length', 10, PARAM_INT);

// 2. Recebendo os dados usando as regras estritas do Moodle
$search_array = optional_param_array('search', null, PARAM_RAW);
$search_value = '';
if (is_array($search_array) && isset($search_array['value'])) {
    $search_value = clean_param($search_array['value'], PARAM_TEXT);
}

$filtro_curso = optional_param('filtro_curso', '', PARAM_TEXT);
$filtro_polo  = optional_param('filtro_polo', '', PARAM_TEXT);

// 3. Montando as condições do Banco de Dados
$condicoes = [];
$params = [];

// Filtro Global (Barra de busca)
if (!empty($search_value)) {
    // A CORREÇÃO ESTÁ AQUI: sql_like (sem o 'i') com 'false' no final!
    $condicoes[] = "(" . $DB->sql_like('nome', ':buscanome', false, false) . " OR " . 
                         $DB->sql_like('curso', ':buscacurso', false, false) . ")";
    $params['buscanome'] = '%' . $search_value . '%';
    $params['buscacurso'] = '%' . $search_value . '%';
}

// Filtros do Painel
if (!empty($filtro_curso)) {
    $condicoes[] = "curso = :f_curso";
    $params['f_curso'] = $filtro_curso;
}
if (!empty($filtro_polo)) {
    $condicoes[] = "polo = :f_polo";
    $params['f_polo'] = $filtro_polo;
}

$sql_condicao = "";
if (!empty($condicoes)) {
    $sql_condicao = implode(" AND ", $condicoes);
}

// 4. Contagem de Registros
$total_records = $DB->count_records('local_amdbasico_alunos');

$total_filtered = $total_records;
if (!empty($sql_condicao)) {
    $total_filtered = $DB->count_records_select('local_amdbasico_alunos', $sql_condicao, $params);
}

// 5. A Busca Definitiva
$sql = "SELECT * FROM {local_amdbasico_alunos} ";
if (!empty($sql_condicao)) {
    $sql .= " WHERE " . $sql_condicao;
}
$sql .= " ORDER BY id ASC";

$registros = $DB->get_records_sql($sql, $params, $start, $length);

// 6. Formata para o JavaScript
// 6. Formata para o JavaScript
$dados_formatados = [];
if ($registros) {
    foreach ($registros as $reg) {
        
        // Vamos formatar a Situação com as classes CSS (Badges) do Moodle/Bootstrap
        $sit = $reg->situacao;
        
        // Só aplicamos o HTML se a string já não contiver HTML (para não quebrar os alunos antigos)
        if (strpos($sit, '<') === false) {
            switch(strtolower(trim($sit))) {
                case 'ativo':
                    $sit = '<span class="badge badge-success bg-success text-white" style="padding: 5px 10px; border-radius: 20px;">Ativo</span>';
                    break;
                case 'formado':
                    $sit = '<span class="badge badge-primary bg-primary text-white" style="padding: 5px 10px; border-radius: 20px;">Formado</span>';
                    break;
                case 'trancado':
                    $sit = '<span class="badge badge-warning bg-warning text-dark" style="padding: 5px 10px; border-radius: 20px;">Trancado</span>';
                    break;
                case 'cancelado':
                    $sit = '<span class="badge badge-danger bg-danger text-white" style="padding: 5px 10px; border-radius: 20px;">Cancelado</span>';
                    break;
                default:
                    $sit = '<span class="badge badge-secondary bg-secondary text-white" style="padding: 5px 10px; border-radius: 20px;">' . $sit . '</span>';
            }
        }
        
        $dados_formatados[] = [
            'nome'     => $reg->nome,
            'curso'    => $reg->curso,
            'polo'     => $reg->polo,
            'ano'      => $reg->ano,
            'periodo'  => $reg->periodo,
            'situacao' => $sit
        ];
    }
}

// 7. Devolve o JSON Limpo
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    "draw"            => intval($draw),
    "recordsTotal"    => intval($total_records),
    "recordsFiltered" => intval($total_filtered),
    "data"            => $dados_formatados
]);