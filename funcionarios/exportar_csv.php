<?php
require_once __DIR__ . '/auth.php';
exigirLogin();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/csv_utils.php';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="funcionarios.csv"');
header('X-Content-Type-Options: nosniff');

$output = fopen('php://output', 'w');
if ($output === false) {
    http_response_code(500);
    exit('Não foi possível gerar o arquivo CSV.');
}

// BOM UTF-8 para melhor compatibilidade com planilhas comuns.
fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, [
    'Prontuário',
    'Nome',
    'Idade',
    'Data de Nascimento',
    'Data de Admissão',
    'Email',
    'Telefone',
    'Endereço',
]);

$stmt = $pdo->query(
    'SELECT prontuario, nome, idade, data_nascimento, data_admissao,
            email, telefone, endereco
     FROM funcionarios
     ORDER BY id'
);

while ($funcionario = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $linha = array_map('protegerFormulaCsv', array_values($funcionario));
    fputcsv($output, $linha);
}

fclose($output);
exit;
