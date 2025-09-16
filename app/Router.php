<?php
namespace App;

final class Router {
    private array $get = [];
    private array $post = [];

    public function get(string $path, callable|array $h): void { $this->get[$path] = $h; }
    public function post(string $path, callable|array $h): void { $this->post[$path] = $h; }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        $h = $method === 'POST' ? $this->post[$path] ?? null : $this->get[$path] ?? null;
        if ($h === null) {
            if (($method === 'POST' && isset($this->get[$path])) ||
                ($method === 'GET'  && isset($this->post[$path]))) {
                http_response_code(405); echo "405 Method Not Allowed"; return;
            }
            http_response_code(404); echo "404 Not Found"; return;
        }

        if (is_array($h)) { [$class, $func] = $h; (new $class)->{$func}(); }
        else { $h(); }
    }
}
