<?php
require 'config.php';

$id = $_GET['id'];

$sql = "SELECT * FROM funcionarios WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$funcionario) {
    echo "Funcionário não encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prontuario = trim($_POST['prontuario']);
    $nome = trim($_POST['nome']);
    $idade = filter_var($_POST['idade'], FILTER_VALIDATE_INT);
    $data_nascimento = $_POST['data_nascimento'];
    $data_admissao = $_POST['data_admissao'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $telefone = preg_match('/^[0-9]{10,11}$/', $_POST['telefone']) ? $_POST['telefone'] : false;
    $endereco = trim($_POST['endereco']);

    if ($prontuario && $nome && $idade && $data_nascimento && $data_admissao && $email && $telefone && $endereco) {
        $sql = "UPDATE funcionarios SET prontuario = :prontuario, nome = :nome, idade = :idade, 
                data_nascimento = :data_nascimento, data_admissao = :data_admissao, email = :email, 
                telefone = :telefone, endereco = :endereco WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':prontuario' => $prontuario,
            ':nome' => $nome,
            ':idade' => $idade,
            ':data_nascimento' => $data_nascimento,
            ':data_admissao' => $data_admissao,
            ':email' => $email,
            ':telefone' => $telefone,
            ':endereco' => $endereco,
            ':id' => $id
        ]);

        echo "Funcionário atualizado com sucesso!";
        header("Location: listar.php");
        exit;
    } else {
        echo "Por favor, preencha todos os campos corretamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Funcionário</h1>
    <form action="editar.php?id=<?php echo $id; ?>" method="post" onsubmit="return validarFormulario()">
        <label>Prontuário:</label>
        <input type="text" name="prontuario" value="<?php echo $funcionario['prontuario']; ?>" required><br>
        
        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $funcionario['nome']; ?>" required><br>
        
        <label>Idade:</label>
        <input type="number" name="idade" value="<?php echo $funcionario['idade']; ?>" required><br>
        
        <label>Data de Nascimento:</label>
        <input type="date" name="data_nascimento" value="<?php echo $funcionario['data_nascimento']; ?>" required><br>
        
        <label>Data de Admissão:</label>
        <input type="date" name="data_admissao" value="<?php echo $funcionario['data_admissao']; ?>" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $funcionario['email']; ?>" required><br>
        
        <label>Telefone:</label>
        <input type="text" name="telefone" value="<?php echo $funcionario['telefone']; ?>" required><br>
        
        <label>Endereço:</label>
        <input type="text" name="endereco" value="<?php echo $funcionario['endereco']; ?>" required><br>
        
        <input type="submit" value="Atualizar">
    </form>

    <a href="listar.php">Voltar à Lista</a>
</body>
</html>
