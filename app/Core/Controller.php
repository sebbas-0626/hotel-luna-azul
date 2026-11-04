<?php
namespace App\Core;

// Controlador base: autenticación y validación
class Controller
{
    protected function user()
    {
        return $_SESSION['user'] ?? null;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            Response::json(['error' => 'Unauthenticated'], 401);
        }
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $ruleList = explode('|', $rule);
            foreach ($ruleList as $r) {
                if ($r === 'required' && (!isset($data[$field]) || $data[$field] === '' || $data[$field] === null)) {
                    $errors[$field][] = 'El campo es obligatorio';
                }
                if (strpos($r, 'min:') === 0) {
                    $min = (int) substr($r, 4);
                    if (isset($data[$field]) && strlen((string)$data[$field]) < $min) {
                        $errors[$field][] = "Mínimo $min caracteres";
                    }
                }
                if ($r === 'email' && isset($data[$field]) && $data[$field] !== '' && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Email inválido';
                }
                if (strpos($r, 'numeric') === 0 && isset($data[$field]) && $data[$field] !== '' && !is_numeric($data[$field])) {
                    $errors[$field][] = 'Debe ser numérico';
                }
            }
        }
        if ($errors) {
            Response::json(['errors' => $errors], 422);
        }
        return $data;
    }
}
