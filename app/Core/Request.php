<?php
namespace App\Core;

// Captura y parsea la petición HTTP
class Request
{
    public string $method;
    public string $uri;
    public array $params = [];
    public array $query;
    public array $body;
    public array $files;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptDir !== '/' && $scriptDir !== '.' && strpos($uri, $scriptDir) === 0) {
            $uri = substr($uri, strlen($scriptDir));
        }
        $this->uri = '/' . trim($uri, '/');
        if ($this->uri === '//') $this->uri = '/';
        $this->query = $_GET;
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            $this->body = json_decode($raw, true) ?: [];
        } else {
            $this->body = array_merge($_POST, $_GET);
        }
        $this->files = $_FILES;
    }

    public static function capture(): self
    {
        return new self();
    }

    public function input(string $key, $default = null)
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }
}
