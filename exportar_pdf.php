<?php
// 1. Carrega o núcleo do Moodle e a biblioteca de PDF
require_once('../../config.php');
require_once($CFG->libdir . '/pdflib.php');

// 2. Proteção
require_login();
$context = context_system::instance();
$PAGE->set_context($context);

// 3. Conecta ao PostgreSQL
global $DB;

// O Moodle busca todos os registros da nossa nova tabela e ordena pelo ID
$alunos = $DB->get_records('local_amdbasico_alunos', null, 'id ASC');

// 4. Inicializa o motor de PDF
$pdf = new \pdf();
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// 5. Desenha o HTML
$html = '<h2 style="text-align:center; color:#0f6cbf;">Relatório de Alunos</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0">';
$html .= '<thead>
            <tr style="background-color:#0f6cbf; color:#ffffff; font-weight:bold;">
                <th width="35%">Nome do Aluno</th>
                <th width="25%">Curso</th>
                <th width="20%">Polo</th>
                <th width="10%">Ano</th>
                <th width="10%">Período</th>
            </tr>
          </thead>';
$html .= '<tbody>';

// Percorre os alunos vindos do banco de dados (Note que agora usamos $aluno->nome com setinha, pois é um Objeto e não um Array)
if ($alunos) {
    foreach ($alunos as $aluno) {
        $html .= '<tr>';
        $html .= '<td>' . $aluno->nome . '</td>';
        $html .= '<td>' . $aluno->curso . '</td>';
        $html .= '<td>' . $aluno->polo . '</td>';
        $html .= '<td>' . $aluno->ano . '</td>';
        $html .= '<td>' . $aluno->periodo . '</td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="5">Nenhum aluno encontrado.</td></tr>';
}

$html .= '</tbody></table>';

// 6. Gera o Download
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('relatorio_alunos_bd.pdf', 'D');