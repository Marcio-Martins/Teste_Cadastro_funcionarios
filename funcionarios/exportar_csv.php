<?php
require 'config.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="funcionarios.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Prontuário', 'Nome', 'Idade', 'Data de Nascimento', 'Data de Admissão', 'Email', 'Telefone', 'Endereço']);

$sql = "SELECT * FROM funcionarios";
$stmt = $pdo->query($sql);
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($funcionarios as $funcionario) {
    fputcsv($output, $funcionario);
}

fclose($output);
exit;
?>
