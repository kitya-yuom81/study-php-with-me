<?php
namespace App;

if (!defined('BASE_PATH')) define('BASE_PATH', __DIR__ . '/..');
if (!defined('PUBLIC_PATH')) define('PUBLIC_PATH', BASE_PATH . '/public');
if (!defined('STORAGE')) define('STORAGE', BASE_PATH . '/storage');
if (!defined('ASSETS')) define('ASSETS', '/assets');

@mkdir(STORAGE, 0777, true);
$dbPath = STORAGE . '/todo.sqlite';

/** @return \PDO */
function db(): \PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $pdo = new \PDO('sqlite:' . (STORAGE . '/todo.sqlite'));
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    // init schema
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tasks (
            id TEXT PRIMARY KEY,
            text TEXT NOT NULL,
            done INTEGER NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL DEFAULT (datetime('now'))
        );
    ");
    return $pdo;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_ok(): bool {
    return isset($_POST['_csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['_csrf']);
}

function flash(string $type, string $msg): void {
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}
function flashes(): array {
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

function render(string $view, array $data = []): void {
    extract($data);
    $viewFile = BASE_PATH . '/views/' . $view . '.php';
    require BASE_PATH . '/views/layout.php';
}
function redirect(string $to = '/'): void {
    header('Location: ' . $to); exit;
}
