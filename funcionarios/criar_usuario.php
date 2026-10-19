<?php
declare(strict_types=1);

// Este script deve ser executado somente pela linha de comando.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/config.php';

$email = strtolower(trim((string) getenv('ADMIN_EMAIL')));
$senha = (string) getenv('ADMIN_PASSWORD');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Defina ADMIN_EMAIL com um endereço de e-mail válido.\n");
    exit(1);
}
if (strlen($senha) < 12) {
    fwrite(STDERR, "ADMIN_PASSWORD deve ter pelo menos 12 caracteres.\n");
    exit(1);
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (email, senha_hash, ativo) VALUES (:email, :senha_hash, 1)'
    );
    $stmt->execute([
        ':email' => $email,
        ':senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
    ]);
    fwrite(STDOUT, "Usuário criado com sucesso: {$email}\n");
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        fwrite(STDERR, "Já existe um usuário com esse e-mail.\n");
        exit(1);
    }
    error_log('Falha ao criar usuário: ' . $e->getMessage());
    fwrite(STDERR, "Não foi possível criar o usuário. Confira a conexão e o esquema do banco.\n");
    exit(1);
}
