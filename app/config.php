<?php
namespace App;

const BASE_PATH = __DIR__ . '/..';
const STORAGE   = BASE_PATH . '/storage/tasks.json';
const ASSETS    = '/assets';

if (!file_exists(STORAGE)) {
    @mkdir(dirname(STORAGE), 0777, true);
    file_put_contents(STORAGE, json_encode([], JSON_PRETTY_PRINT));
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}
function csrf_ok(): bool {
    return isset($_POST['_csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['_csrf']);
}

function render(string $view, array $data = []): void {
    extract($data);
    $viewFile = BASE_PATH . '/views/' . $view . '.php';
    require BASE_PATH . '/views/layout.php';
}

function redirect(string $to = '/'): void {
    header('Location: ' . $to);
    exit;
}
