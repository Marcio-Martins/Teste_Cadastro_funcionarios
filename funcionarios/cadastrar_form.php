<?php
require_once __DIR__ . '/auth.php';
exigirLogin();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Funcionário</title>
    <link rel="stylesheet" href="styles.css">
    <script>
    function validarFormulario() {
        const prontuario = document.forms["cadastroForm"]["prontuario"].value;
        const email = document.forms["cadastroForm"]["email"].value;
        const telefone = document.forms["cadastroForm"]["telefone"].value;

        if (prontuario.trim() === "") {
            alert("Prontuário é obrigatório.");
            return false;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            alert("Por favor, insira um email válido.");
            return false;
        }

        const telefonePattern = /^[0-9]{10,11}$/;
        if (!telefonePattern.test(telefone)) {
            alert("Por favor, insira um número de telefone válido.");
            return false;
        }

        return true;
    }
    </script>
</head>
<body>
    <h1>Cadastrar Funcionário</h1>
    <form name="cadastroForm" action="cadastrar.php" method="post" onsubmit="return validarFormulario()">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">

        <label for="prontuario">Prontuário:</label>
        <input id="prontuario" type="text" name="prontuario" required><br>

        <label for="nome">Nome:</label>
        <input id="nome" type="text" name="nome" required><br>

        <label for="idade">Idade:</label>
        <input id="idade" type="number" name="idade" min="1" required><br>

        <label for="data_nascimento">Data de Nascimento:</label>
        <input id="data_nascimento" type="date" name="data_nascimento" required><br>

        <label for="data_admissao">Data de Admissão:</label>
        <input id="data_admissao" type="date" name="data_admissao" required><br>

        <label for="email">Email:</label>
        <input id="email" type="email" name="email" required><br>

        <label for="telefone">Telefone:</label>
        <input id="telefone" type="text" name="telefone" pattern="[0-9]{10,11}" required><br>

        <label for="endereco">Endereço:</label>
        <input id="endereco" type="text" name="endereco" required><br>

        <button type="submit">Cadastrar</button>
    </form>

    <a href="index.php">Voltar ao Início</a>
</body>
</html>
