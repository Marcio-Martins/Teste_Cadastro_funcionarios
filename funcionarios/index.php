<?php
require_once __DIR__ . '/auth.php';
exigirLogin();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Cadastro de Funcionários</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Sistema de Cadastro de Funcionários</h1>
    <nav>
        <ul>
            <li><a href="cadastrar_form.php">Cadastrar Funcionário</a></li>
            <li><a href="listar.php">Listar Funcionários</a></li>
        </ul>
    </nav>
    <p class="session-info">Sessão iniciada como <?php echo htmlspecialchars((string) $_SESSION['usuario_email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>.</p>
    <form action="logout.php" method="post" class="logout-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
        <button type="submit">Sair do sistema</button>
    </form>
</body>
</html>
