<?php
require_once __DIR__ . '/auth.php';
exigirLogin();
require_once __DIR__ . '/config.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$busca = trim((string) ($_GET['busca'] ?? ''));
$porPagina = 10;
$paginaAtual = filter_var($_GET['pagina'] ?? 1, FILTER_VALIDATE_INT);
$paginaAtual = ($paginaAtual !== false && $paginaAtual > 0) ? $paginaAtual : 1;
$termoBusca = '%' . $busca . '%';

$stmtTotal = $pdo->prepare(
    'SELECT COUNT(*) FROM funcionarios
     WHERE nome LIKE :busca_nome OR prontuario LIKE :busca_prontuario'
);
$stmtTotal->execute([
    ':busca_nome' => $termoBusca,
    ':busca_prontuario' => $termoBusca,
]);
$totalFuncionarios = (int) $stmtTotal->fetchColumn();
$totalPaginas = max(1, (int) ceil($totalFuncionarios / $porPagina));
$paginaAtual = min($paginaAtual, $totalPaginas);
$offset = ($paginaAtual - 1) * $porPagina;

$stmt = $pdo->prepare(
    'SELECT * FROM funcionarios
     WHERE nome LIKE :busca_nome OR prontuario LIKE :busca_prontuario
     ORDER BY id DESC
     LIMIT :offset, :porPagina'
);
$stmt->bindValue(':busca_nome', $termoBusca, PDO::PARAM_STR);
$stmt->bindValue(':busca_prontuario', $termoBusca, PDO::PARAM_STR);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':porPagina', $porPagina, PDO::PARAM_INT);
$stmt->execute();
$funcionarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listar Funcionários</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Lista de Funcionários</h1>

    <form method="get" action="listar.php">
        <label for="busca">Buscar por nome ou prontuário</label>
        <input id="busca" type="text" name="busca" value="<?php echo escapar($busca); ?>"
               placeholder="Buscar por nome ou prontuário">
        <button type="submit">Buscar</button>
    </form>

    <table>
        <thead>
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
        </thead>
        <tbody>
        <?php if (!$funcionarios): ?>
            <tr><td colspan="9">Nenhum funcionário encontrado.</td></tr>
        <?php endif; ?>
        <?php foreach ($funcionarios as $funcionario): ?>
            <tr>
                <td><?php echo escapar($funcionario['prontuario']); ?></td>
                <td><?php echo escapar($funcionario['nome']); ?></td>
                <td><?php echo escapar($funcionario['idade']); ?></td>
                <td><?php echo escapar($funcionario['data_nascimento']); ?></td>
                <td><?php echo escapar($funcionario['data_admissao']); ?></td>
                <td><?php echo escapar($funcionario['email']); ?></td>
                <td><?php echo escapar($funcionario['telefone']); ?></td>
                <td><?php echo escapar($funcionario['endereco']); ?></td>
                <td>
                    <a href="editar.php?id=<?php echo (int) $funcionario['id']; ?>">Editar</a>
                    <form action="excluir.php" method="post"
                          onsubmit="return confirm('Tem certeza que deseja excluir este funcionário?');">
                        <input type="hidden" name="id" value="<?php echo (int) $funcionario['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo escapar($_SESSION['csrf_token']); ?>">
                        <button type="submit">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <nav class="pagination" aria-label="Paginação">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="listar.php?pagina=<?php echo $i; ?>&amp;busca=<?php echo rawurlencode($busca); ?>"
               <?php echo $i === $paginaAtual ? 'aria-current="page"' : ''; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </nav>

    <a href="exportar_csv.php" class="button">Exportar para CSV</a>
    <a href="index.php" class="button">Voltar ao Início</a>
</body>
</html>
