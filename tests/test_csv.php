<?php
require_once __DIR__ . '/../funcionarios/csv_utils.php';

$casos = [
    ['=1+1', "'=1+1"],
    ['+SUM(A1:A2)', "'+SUM(A1:A2)"],
    ['-2+3', "'-2+3"],
    ['@SUM(A1)', "'@SUM(A1)"],
    ["  =1+1", "'  =1+1"],
    ['Maria Silva', 'Maria Silva'],
    ['123456', '123456'],
    ['', ''],
];

foreach ($casos as [$entrada, $esperado]) {
    $obtido = protegerFormulaCsv($entrada);
    if ($obtido !== $esperado) {
        fwrite(STDERR, "Falha: entrada " . var_export($entrada, true)
            . "; esperado " . var_export($esperado, true)
            . "; obtido " . var_export($obtido, true) . PHP_EOL);
        exit(1);
    }
}

echo "Todos os testes de proteção CSV passaram." . PHP_EOL;
