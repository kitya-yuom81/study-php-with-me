<?php
namespace App;

final class Router {
    private array $get = [];
    private array $post = [];

    public function get(string $path, callable|array $handler): void { $this->get[$path] = $handler; }
    public function post(string $path, callable|array $handler): void { $this->post[$path] = $handler; }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        $table = $method === 'POST' ? $this->post : $this->get;
        $handler = $table[$path] ?? null;

        if (!$handler) { http_response_code(404); echo "404 Not Found"; return; }

        if (is_array($handler)) {
            [$class, $func] = $handler;
            (new $class)->{$func}();
        } else {
            $handler();
        }
    }
}
