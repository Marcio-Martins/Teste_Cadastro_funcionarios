<?php
require_once __DIR__ . '/auth.php';
exigirLogin();
require_once __DIR__ . '/config.php';

function escapar($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function dataValida(string $valor): bool
{
    $data = DateTime::createFromFormat('!Y-m-d', $valor);
    return $data !== false && $data->format('Y-m-d') === $valor;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('Identificador de funcionário inválido.');
}

$stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE id = :id');
$stmt->execute([':id' => $id]);
$funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$funcionario) {
    http_response_code(404);
    exit('Funcionário não encontrado.');
}

$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenRecebido = (string) ($_POST['csrf_token'] ?? '');
    $tokenSessao = (string) ($_SESSION['csrf_token'] ?? '');

    if ($tokenSessao === '' || !hash_equals($tokenSessao, $tokenRecebido)) {
        http_response_code(403);
        exit('Solicitação inválida. Atualize a página e tente novamente.');
    }

    $prontuario = trim((string) ($_POST['prontuario'] ?? ''));
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $idade = filter_var($_POST['idade'] ?? null, FILTER_VALIDATE_INT);
    $data_nascimento = (string) ($_POST['data_nascimento'] ?? '');
    $data_admissao = (string) ($_POST['data_admissao'] ?? '');
    $emailEntrada = (string) ($_POST['email'] ?? '');
    $email = filter_var($emailEntrada, FILTER_VALIDATE_EMAIL);
    $telefoneEntrada = (string) ($_POST['telefone'] ?? '');
    $telefone = preg_match('/^[0-9]{10,11}$/', $telefoneEntrada) ? $telefoneEntrada : false;
    $endereco = trim((string) ($_POST['endereco'] ?? ''));

    if (
        $prontuario !== '' &&
        $nome !== '' &&
        $idade !== false && $idade !== null && $idade > 0 &&
        dataValida($data_nascimento) &&
        dataValida($data_admissao) &&
        $email !== false &&
        $telefone !== false &&
        $endereco !== ''
    ) {
        $sql = 'UPDATE funcionarios
                SET prontuario = :prontuario, nome = :nome, idade = :idade,
                    data_nascimento = :data_nascimento, data_admissao = :data_admissao,
                    email = :email, telefone = :telefone, endereco = :endereco
                WHERE id = :id';
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
            ':id' => $id,
        ]);

        header('Location: listar.php', true, 303);
        exit;
    }

    $mensagemErro = 'Confira os campos e informe valores válidos.';
    $funcionario = array_merge($funcionario, [
        'prontuario' => $prontuario,
        'nome' => $nome,
        'idade' => $idade === false || $idade === null ? '' : $idade,
        'data_nascimento' => $data_nascimento,
        'data_admissao' => $data_admissao,
        'email' => $emailEntrada,
        'telefone' => $telefoneEntrada,
        'endereco' => $endereco,
    ]);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Editar Funcionário</h1>
    <?php if ($mensagemErro !== ''): ?>
        <p role="alert"><?php echo escapar($mensagemErro); ?></p>
    <?php endif; ?>

    <form action="editar.php?id=<?php echo (int) $id; ?>" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo escapar($_SESSION['csrf_token']); ?>">

        <label for="prontuario">Prontuário:</label>
        <input id="prontuario" type="text" name="prontuario" value="<?php echo escapar($funcionario['prontuario']); ?>" required><br>

        <label for="nome">Nome:</label>
        <input id="nome" type="text" name="nome" value="<?php echo escapar($funcionario['nome']); ?>" required><br>

        <label for="idade">Idade:</label>
        <input id="idade" type="number" name="idade" min="1" value="<?php echo escapar($funcionario['idade']); ?>" required><br>

        <label for="data_nascimento">Data de Nascimento:</label>
        <input id="data_nascimento" type="date" name="data_nascimento" value="<?php echo escapar($funcionario['data_nascimento']); ?>" required><br>

        <label for="data_admissao">Data de Admissão:</label>
        <input id="data_admissao" type="date" name="data_admissao" value="<?php echo escapar($funcionario['data_admissao']); ?>" required><br>

        <label for="email">Email:</label>
        <input id="email" type="email" name="email" value="<?php echo escapar($funcionario['email']); ?>" required><br>

        <label for="telefone">Telefone:</label>
        <input id="telefone" type="text" name="telefone" value="<?php echo escapar($funcionario['telefone']); ?>" pattern="[0-9]{10,11}" required><br>

        <label for="endereco">Endereço:</label>
        <input id="endereco" type="text" name="endereco" value="<?php echo escapar($funcionario['endereco']); ?>" required><br>

        <button type="submit">Atualizar</button>
    </form>

    <a href="listar.php">Voltar à Lista</a>
</body>
</html>
