<?php
declare(strict_types=1);

// Configurações de sessão seguras, compatíveis com HTTP em ambiente local.
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function exigirLogin(): void
{
    if (empty($_SESSION['usuario_id'])) {
        header('Location: login.php', true, 302);
        exit;
    }
}

function validarTokenCsrf(): bool
{
    $recebido = (string) ($_POST['csrf_token'] ?? '');
    $sessao = (string) ($_SESSION['csrf_token'] ?? '');
    return $sessao !== '' && hash_equals($sessao, $recebido);
}
