<?php
namespace App\Core;

// Respuestas JSON, vistas y redirecciones
class Response
{
    public static function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public static function view(string $view, array $data = []): void
    {
        extract($data);
        $path = __DIR__ . '/../../views/' . $view . '.php';
        if (!file_exists($path)) {
            http_response_code(404);
            echo 'View not found';
            exit;
        }
        require $path;
        exit;
    }
}
