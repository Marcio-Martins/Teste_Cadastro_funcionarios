<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Funcionário</title>
    <link rel="stylesheet" href="styles.css">
    <script>
    function validarFormulario() {
        const prontuario = document.forms["cadastroForm"]["prontuario"].value;
        const email = document.forms["cadastroForm"]["email"].value;
        const telefone = document.forms["cadastroForm"]["telefone"].value;

        if (prontuario === "") {
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
        <label>Prontuário:</label>
        <input type="text" name="prontuario" required><br>
        
        <label>Nome:</label>
        <input type="text" name="nome" required><br>
        
        <label>Idade:</label>
        <input type="number" name="idade" required><br>
        
        <label>Data de Nascimento:</label>
        <input type="date" name="data_nascimento" required><br>
        
        <label>Data de Admissão:</label>
        <input type="date" name="data_admissao" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br>
        
        <label>Telefone:</label>
        <input type="text" name="telefone" required><br>
        
        <label>Endereço:</label>
        <input type="text" name="endereco" required><br>
        
        <input type="submit" value="Cadastrar">
    </form>

    <a href="index.php">Voltar ao Início</a>
</body>
</html>
