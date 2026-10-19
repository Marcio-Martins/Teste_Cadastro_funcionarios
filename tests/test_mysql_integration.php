<?php
declare(strict_types=1);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'cadastro_funcionarios';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

$pdo = new PDO(
    "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

function verificar(bool $condicao, string $mensagem): void
{
    if (!$condicao) {
        throw new RuntimeException($mensagem);
    }
}

$pdo->beginTransaction();

try {
    $prontuario = 'TESTE-' . bin2hex(random_bytes(6));

    $insert = $pdo->prepare(
        'INSERT INTO funcionarios
         (prontuario, nome, idade, data_nascimento, data_admissao, email, telefone, endereco)
         VALUES (:prontuario, :nome, :idade, :nascimento, :admissao, :email, :telefone, :endereco)'
    );
    $insert->execute([
        ':prontuario' => $prontuario,
        ':nome' => 'Funcionario de Teste',
        ':idade' => 30,
        ':nascimento' => '1996-01-15',
        ':admissao' => '2024-02-01',
        ':email' => 'teste@example.com',
        ':telefone' => '11987654321',
        ':endereco' => 'Endereco de teste',
    ]);
    $id = (int) $pdo->lastInsertId();
    verificar($id > 0, 'O INSERT não retornou um ID válido.');

    $select = $pdo->prepare('SELECT nome, idade FROM funcionarios WHERE id = :id');
    $select->execute([':id' => $id]);
    $registro = $select->fetch();
    verificar($registro !== false, 'O registro inserido não foi encontrado.');
    verificar($registro['nome'] === 'Funcionario de Teste', 'O nome retornado não corresponde ao inserido.');
    verificar((int) $registro['idade'] === 30, 'A idade retornada não corresponde à inserida.');

    $update = $pdo->prepare('UPDATE funcionarios SET nome = :nome WHERE id = :id');
    $update->execute([':nome' => 'Funcionario Atualizado', ':id' => $id]);

    $select->execute([':id' => $id]);
    $registroAtualizado = $select->fetch();
    verificar($registroAtualizado['nome'] === 'Funcionario Atualizado', 'O UPDATE não foi aplicado.');

    $delete = $pdo->prepare('DELETE FROM funcionarios WHERE id = :id');
    $delete->execute([':id' => $id]);

    $select->execute([':id' => $id]);
    verificar($select->fetch() === false, 'O DELETE não removeu o registro.');

    echo "Testes de integração MySQL (INSERT, SELECT, UPDATE e DELETE) passaram.\n";
} finally {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
}
