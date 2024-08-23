<?php
require 'config.php';

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$porPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $porPagina;

$sql = "SELECT * FROM funcionarios WHERE nome LIKE :busca OR prontuario LIKE :busca LIMIT :offset, :porPagina";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':busca', $busca);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':porPagina', $porPagina, PDO::PARAM_INT);
$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalFuncionarios = $pdo->query("SELECT COUNT(*) FROM funcionarios WHERE nome LIKE '%$busca%' OR prontuario LIKE '%$busca%'")->fetchColumn();
$totalPaginas = ceil($totalFuncionarios / $porPagina);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Funcionários</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Lista de Funcionários</h1>

    <form method="get" action="listar.php">
        <input type="text" name="busca" placeholder="Buscar por nome ou prontuário">
        <button type="submit">Buscar</button>
    </form>

    <table>
        <tr>
            <th>Prontuário</th>
            <th>Nome</th>
            <th>Idade</th>
            <th>Data de Nascimento</th>
            <th>Data de Admissão</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Endereço</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($funcionarios as $funcionario): ?>
        <tr>
            <td><?php echo $funcionario['prontuario']; ?></td>
            <td><?php echo $funcionario['nome']; ?></td>
            <td><?php echo $funcionario['idade']; ?></td>
            <td><?php echo $funcionario['data_nascimento']; ?></td>
            <td><?php echo $funcionario['data_admissao']; ?></td>
            <td><?php echo $funcionario['email']; ?></td>
            <td><?php echo $funcionario['telefone']; ?></td>
            <td><?php echo $funcionario['endereco']; ?></td>
            <td>
                <a href="editar.php?id=<?php echo $funcionario['id']; ?>">Editar</a> |
                <a href="excluir.php?id=<?php echo $funcionario['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="listar.php?pagina=<?php echo $i; ?>&busca=<?php echo $busca; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

    <a href="exportar_csv.php" class="button">Exportar para CSV</a>
    <a href="index.php" class="button">Voltar ao Início</a>
</body>
</html>
