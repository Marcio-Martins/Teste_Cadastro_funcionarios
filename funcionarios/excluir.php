<?php
require 'config.php';

$id = $_GET['id'];

$sql = "DELETE FROM funcionarios WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);

header("Location: listar.php");
exit;
?>
