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
    exit('Solicitação inválida. Atualize a página e tente novamente.');
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('Identificador de funcionário inválido.');
}

$stmt = $pdo->prepare('DELETE FROM funcionarios WHERE id = :id');
$stmt->execute([':id' => $id]);

header('Location: listar.php', true, 303);
exit;
