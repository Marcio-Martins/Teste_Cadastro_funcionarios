<?php
require 'config.php';

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
        $sql = "INSERT INTO funcionarios (prontuario, nome, idade, data_nascimento, data_admissao, email, telefone, endereco) 
                VALUES (:prontuario, :nome, :idade, :data_nascimento, :data_admissao, :email, :telefone, :endereco)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':prontuario' => $prontuario,
            ':nome' => $nome,
            ':idade' => $idade,
            ':data_nascimento' => $data_nascimento,
            ':data_admissao' => $data_admissao,
            ':email' => $email,
            ':telefone' => $telefone,
            ':endereco' => $endereco
        ]);

        echo "Funcionário cadastrado com sucesso!";
    } else {
        echo "Por favor, preencha todos os campos corretamente.";
    }
}
?>
