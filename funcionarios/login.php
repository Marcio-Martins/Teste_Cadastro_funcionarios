<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php', true, 302);
    exit;
}

$erro = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $senha = (string) ($_POST['senha'] ?? '');

    if (!validarTokenCsrf()) {
        http_response_code(403);
        $erro = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $senha === '') {
        $erro = 'E-mail ou senha inválidos.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, email, senha_hash FROM usuarios WHERE email = :email AND ativo = 1 LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int) $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: index.php', true, 303);
            exit;
        }

        // Mensagem genérica evita revelar se o e-mail está cadastrado.
        $erro = 'E-mail ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar — Cadastro de Funcionários</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="login-card">
        <h1>Entrar no sistema</h1>
        <p>Informe suas credenciais para acessar o cadastro de funcionários.</p>
        <?php if ($erro !== ''): ?>
            <p role="alert" class="error-message"><?php echo htmlspecialchars($erro, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" autocomplete="username" value="<?php echo htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" required>
            <label for="senha">Senha</label>
            <input id="senha" type="password" name="senha" autocomplete="current-password" required>
            <button type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>
