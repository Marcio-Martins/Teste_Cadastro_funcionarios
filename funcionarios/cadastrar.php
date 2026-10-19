<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método não permitido.');
}

$tokenRecebido = (string) ($_POST['csrf_token'] ?? '');
$tokenSessao = (string) ($_SESSION['csrf_token'] ?? '');
if ($tokenSessao === '' || !hash_equals($tokenSessao, $tokenRecebido)) {
    http_response_code(403);
    exit('Solicitação inválida. Atualize o formulário e tente novamente.');
}

$prontuario = trim((string) ($_POST['prontuario'] ?? ''));
$nome = trim((string) ($_POST['nome'] ?? ''));
$idade = filter_var($_POST['idade'] ?? null, FILTER_VALIDATE_INT);
$dataNascimento = (string) ($_POST['data_nascimento'] ?? '');
$dataAdmissao = (string) ($_POST['data_admissao'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$telefoneEntrada = (string) ($_POST['telefone'] ?? '');
$telefone = preg_match('/^[0-9]{10,11}$/', $telefoneEntrada) ? $telefoneEntrada : false;
$endereco = trim((string) ($_POST['endereco'] ?? ''));

function dataValida(string $valor): bool
{
    $data = DateTime::createFromFormat('!Y-m-d', $valor);
    return $data !== false && $data->format('Y-m-d') === $valor;
}

if (
    $prontuario === '' ||
    $nome === '' ||
    $idade === false || $idade === null || $idade < 1 ||
    !dataValida($dataNascimento) ||
    !dataValida($dataAdmissao) ||
    $email === false ||
    $telefone === false ||
    $endereco === ''
) {
    http_response_code(422);
    exit('Por favor, preencha todos os campos com valores válidos.');
}

$sql = 'INSERT INTO funcionarios
        (prontuario, nome, idade, data_nascimento, data_admissao, email, telefone, endereco)
        VALUES (:prontuario, :nome, :idade, :data_nascimento, :data_admissao, :email, :telefone, :endereco)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':prontuario' => $prontuario,
    ':nome' => $nome,
    ':idade' => $idade,
    ':data_nascimento' => $dataNascimento,
    ':data_admissao' => $dataAdmissao,
    ':email' => $email,
    ':telefone' => $telefone,
    ':endereco' => $endereco,
]);

header('Location: listar.php', true, 303);
exit;
